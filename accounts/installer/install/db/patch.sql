
DROP PROCEDURE IF EXISTS AddColumnIfNotExist;
CREATE PROCEDURE AddColumnIfNotExist(
    IN tblName VARCHAR(255), 
    IN colName VARCHAR(255), 
    IN colDef TEXT
)
BEGIN
    IF NOT EXISTS (
        SELECT * FROM INFORMATION_SCHEMA.COLUMNS 
        WHERE TABLE_SCHEMA = DATABASE() 
        AND TABLE_NAME = tblName 
        AND COLUMN_NAME = colName
    ) THEN
        SET @sql = CONCAT('ALTER TABLE ', tblName, ' ADD COLUMN ', colName, ' ', colDef);
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
    END IF;
END;

-- Patch for Maker-Checker workflow

-- 1. Update acc_vouchers to support 'rejected' status and rejected_reason
ALTER TABLE `acc_vouchers` MODIFY COLUMN `status` ENUM('posted','reversed','draft','rejected') NOT NULL DEFAULT 'posted';
CALL AddColumnIfNotExist('acc_vouchers', 'rejected_reason', 'TEXT DEFAULT NULL AFTER `status`');

-- 2. Update acc_purchase_entries to support 'draft' and 'rejected' status (Maker-Checker)
CALL AddColumnIfNotExist('acc_purchase_entries', 'status', 'ENUM(''posted'',''reversed'',''draft'',''rejected'') NOT NULL DEFAULT ''posted'' AFTER `purchase_date`');
CALL AddColumnIfNotExist('acc_purchase_entries', 'rejected_reason', 'TEXT DEFAULT NULL AFTER `status`');

-- 3. Replace acc_voucher_items triggers to respect voucher status



DROP TRIGGER IF EXISTS `trg_voucher_item_insert`//
CREATE TRIGGER `trg_voucher_item_insert` AFTER INSERT ON `acc_voucher_items`
FOR EACH ROW
BEGIN
  DECLARE v_status VARCHAR(20);
  SELECT status INTO v_status FROM acc_vouchers WHERE id = NEW.voucher_id;
  
  IF v_status = 'posted' THEN
    UPDATE `acc_ledgers` l
    SET l.`current_balance` = l.`current_balance` + (
        SELECT COALESCE(SUM(debit_amount - credit_amount), 0)
        FROM acc_voucher_items
        WHERE voucher_id = NEW.voucher_id AND ledger_id = l.id
    )
    WHERE l.`id` = NEW.ledger_id;
  END IF;
END;

DROP TRIGGER IF EXISTS `trg_voucher_item_delete`//
CREATE TRIGGER `trg_voucher_item_delete` AFTER DELETE ON `acc_voucher_items`
FOR EACH ROW
BEGIN
  DECLARE v_status VARCHAR(20);
  SELECT status INTO v_status FROM acc_vouchers WHERE id = OLD.voucher_id;
  
  IF v_status = 'posted' THEN
    UPDATE `acc_ledgers` l
    SET l.`current_balance` = l.`current_balance` - (OLD.debit_amount - OLD.credit_amount)
    WHERE l.`id` = OLD.ledger_id;
  END IF;
END;

DROP TRIGGER IF EXISTS `trg_voucher_item_update`//
CREATE TRIGGER `trg_voucher_item_update` AFTER UPDATE ON `acc_voucher_items`
FOR EACH ROW
BEGIN
  DECLARE v_status VARCHAR(20);
  SELECT status INTO v_status FROM acc_vouchers WHERE id = NEW.voucher_id;
  
  IF v_status = 'posted' THEN
    -- First subtract the old amounts
    UPDATE `acc_ledgers`
    SET `current_balance` = `current_balance` - (OLD.debit_amount - OLD.credit_amount)
    WHERE `id` = OLD.ledger_id;
    
    -- Then add the new amounts
    UPDATE `acc_ledgers`
    SET `current_balance` = `current_balance` + (NEW.debit_amount - NEW.credit_amount)
    WHERE `id` = NEW.ledger_id;
  END IF;
END;

-- 4. Add trigger on acc_vouchers to handle status transitions
DROP TRIGGER IF EXISTS `trg_voucher_update`//
CREATE TRIGGER `trg_voucher_update` AFTER UPDATE ON `acc_vouchers`
FOR EACH ROW
BEGIN
    IF OLD.status != NEW.status THEN
        -- If moving TO posted (e.g. from draft or rejected), ADD to balances
        IF NEW.status = 'posted' THEN
            UPDATE acc_ledgers l
            SET l.current_balance = l.current_balance + (
                SELECT COALESCE(SUM(debit_amount - credit_amount), 0)
                FROM acc_voucher_items vi
                WHERE vi.voucher_id = NEW.id AND vi.ledger_id = l.id
            )
            WHERE l.id IN (SELECT ledger_id FROM acc_voucher_items WHERE voucher_id = NEW.id);
        END IF;

        -- If moving FROM posted (e.g. to reversed, draft, or rejected), SUBTRACT from balances
        IF OLD.status = 'posted' THEN
            UPDATE acc_ledgers l
            SET l.current_balance = l.current_balance - (
                SELECT COALESCE(SUM(debit_amount - credit_amount), 0)
                FROM acc_voucher_items vi
                WHERE vi.voucher_id = NEW.id AND vi.ledger_id = l.id
            )
            WHERE l.id IN (SELECT ledger_id FROM acc_voucher_items WHERE voucher_id = NEW.id);
        END IF;
    END IF;
END;



-- Insert approval privilege into RBAC (Maker-Checker)
INSERT IGNORE INTO `permission_category` (`id`, `perm_group_id`, `name`, `short_code`, `enable_view`, `enable_add`, `enable_edit`, `enable_delete`, `created_at`) 
VALUES (9015, 900, 'Approve Voucher', 'acc_approve_voucher', 1, 0, 1, 0, '2026-06-08 00:00:00');

-- Map approval privilege to Super Admin (Role 1), Admin (Role 4), Super Admin (Role 7)
INSERT IGNORE INTO `roles_permissions` (`role_id`, `perm_cat_id`, `can_view`, `can_add`, `can_edit`, `can_delete`, `created_at`)
SELECT r.id, 9015, 1, 0, 1, 0, '2026-06-08 00:00:00' FROM `roles` r WHERE r.id IN (1, 4, 7);

