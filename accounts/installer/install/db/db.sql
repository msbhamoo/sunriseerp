
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

-- Smart School Accounts Module Installer DB
-- Version 2.0 (Double-Entry Hardened)

CREATE TABLE IF NOT EXISTS `acc_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `payment_prefix` varchar(50) DEFAULT 'PAY-',
  `receipt_prefix` varchar(50) DEFAULT 'REC-',
  `contra_prefix` varchar(50) DEFAULT 'CON-',
  `journal_prefix` varchar(50) DEFAULT 'JOU-',
  `enable_fee_sync` tinyint(1) DEFAULT '1',
  `fee_receipt_ledger_id` int(11) DEFAULT NULL,
  `fee_bank_receipt_ledger_id` int(11) DEFAULT NULL,
  `fee_income_ledger_id` int(11) DEFAULT NULL,
  `enable_payroll_sync` tinyint(1) DEFAULT '1',
  `payroll_payment_ledger_id` int(11) DEFAULT NULL,
  `payroll_expense_ledger_id` int(11) DEFAULT NULL,
  `enable_expense_sync` tinyint(1) DEFAULT '0',
  `enable_income_sync` tinyint(1) DEFAULT '0',
  `expense_payment_ledger_id` int(11) DEFAULT NULL,
  `income_receipt_ledger_id` int(11) DEFAULT NULL,
  `gateway_clearing_ledger_id` int(11) DEFAULT NULL,
  `fee_discount_expense_ledger_id` int(11) DEFAULT NULL,
  `signature_1_label` varchar(255) DEFAULT 'Prepared By',
  `signature_2_label` varchar(255) DEFAULT 'Checked By',
  `signature_3_label` varchar(255) DEFAULT 'Authorized Signatory',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT IGNORE INTO `acc_settings` (`id`, `payment_prefix`, `receipt_prefix`, `contra_prefix`, `journal_prefix`, `enable_fee_sync`, `fee_receipt_ledger_id`, `fee_bank_receipt_ledger_id`, `fee_income_ledger_id`, `enable_payroll_sync`, `payroll_payment_ledger_id`, `payroll_expense_ledger_id`, `enable_expense_sync`, `enable_income_sync`, `expense_payment_ledger_id`, `income_receipt_ledger_id`, `gateway_clearing_ledger_id`, `fee_discount_expense_ledger_id`, `signature_1_label`, `signature_2_label`, `signature_3_label`) VALUES (1, 'PAY-', 'REC-', 'CON-', 'JOU-', 1, 2, NULL, 6, 1, 2, 10, 1, 1, 2, 2, 11, 12, 'Prepared By', 'Checked By', 'Authorized Signatory');

CREATE TABLE IF NOT EXISTS `acc_banks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT IGNORE INTO `acc_banks` (`id`, `name`, `is_active`) VALUES
(1, 'State Bank of India', 1),
(2, 'HDFC Bank', 1),
(3, 'ICICI Bank', 1),
(4, 'Axis Bank', 1),
(5, 'Bank of Baroda', 1),
(6, 'Punjab National Bank', 1),
(7, 'Canara Bank', 1),
(8, 'Union Bank of India', 1),
(9, 'HSBC Bank', 1),
(10, 'Citi Bank', 1);

CREATE TABLE IF NOT EXISTS `acc_expense_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `type` enum('expense','income') NOT NULL DEFAULT 'expense',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `acc_ledger_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `system_name` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `system_name` (`system_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT IGNORE INTO `acc_ledger_groups` (`id`, `name`, `system_name`) VALUES
(1,'Bank Account','bank'),(2,'Cash-in-Hand','cash'),(3,'Sundry Debtors','sundry_debtors'),
(4,'Sundry Creditors','sundry_creditors'),(8,'Indirect Expenses','indirect_expense'),
(9,'Direct Income','direct_income'),(10,'Indirect Income','indirect_income'),(14,'Current Assets','current_assets'),
(15,'Current Liabilities','current_liabilities');

CREATE TABLE IF NOT EXISTS `acc_ledgers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` text,
  `state` varchar(100) DEFAULT NULL,
  `gst_no` varchar(50) DEFAULT NULL,
  `pan_no` varchar(50) DEFAULT NULL,
  `aadhar_no` varchar(50) DEFAULT NULL,
  `bank_id` int(11) DEFAULT NULL,
  `account_no` varchar(50) DEFAULT NULL,
  `ifsc_code` varchar(20) DEFAULT NULL,
  `branch` varchar(100) DEFAULT NULL,
  `opening_type` enum('Dr','Cr') DEFAULT 'Dr',
  `opening_balance` decimal(15,2) DEFAULT '0.00',
  `current_balance` decimal(15,2) DEFAULT '0.00',
  `opening_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`group_id`) REFERENCES `acc_ledger_groups`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `acc_vouchers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `voucher_no` varchar(100) NOT NULL,
  `voucher_date` date NOT NULL,
  `voucher_type` enum('payment','receipt','contra','journal','purchase') NOT NULL,
  `status` enum('posted','reversed','draft','rejected') NOT NULL DEFAULT 'posted',
  `rejected_reason` text DEFAULT NULL,
  `narration` text,
  `reference_module` varchar(50) DEFAULT NULL,
  `reference_id` varchar(100) DEFAULT NULL,
  `reversed_by_voucher_id` int(11) DEFAULT NULL,
  `reversal_of_voucher_id` int(11) DEFAULT NULL,
  `payment_method` varchar(100) DEFAULT NULL,
  `cheque_no` varchar(100) DEFAULT NULL,
  `cheque_date` date DEFAULT NULL,
  `bank_name` varchar(200) DEFAULT NULL,
  `upi_transaction_id` varchar(200) DEFAULT NULL,
  `net_banking_ref` varchar(200) DEFAULT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `session_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `voucher_no_type` (`voucher_no`, `voucher_type`),
  INDEX `idx_ref_module_id` (`reference_module`, `reference_id`),
  INDEX `idx_date_session_type` (`voucher_date`, `session_id`, `voucher_type`),
  INDEX `idx_session_id` (`session_id`),
  INDEX `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `acc_voucher_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `voucher_id` int(11) NOT NULL,
  `ledger_id` int(11) NOT NULL,
  `expense_type_id` int(11) DEFAULT NULL,
  `debit_amount` decimal(15,2) DEFAULT '0.00',
  `credit_amount` decimal(15,2) DEFAULT '0.00',
  `narration` text,
  PRIMARY KEY (`id`),
  KEY `idx_ledger_id` (`ledger_id`),
  KEY `idx_ledger_debit_credit` (`ledger_id`, `debit_amount`, `credit_amount`),
  CONSTRAINT `fk_voucher_id` FOREIGN KEY (`voucher_id`) REFERENCES `acc_vouchers` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`ledger_id`) REFERENCES `acc_ledgers`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `acc_purchase_entries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `purchase_date` date NOT NULL,
  `status` enum('posted','reversed','draft','rejected') NOT NULL DEFAULT 'posted',
  `rejected_reason` text DEFAULT NULL,
  `supplier_ledger_id` int(11) NOT NULL,
  `invoice_no` varchar(100) DEFAULT NULL,
  `total_amount` decimal(15,2) DEFAULT '0.00',
  `gst_amount` decimal(15,2) DEFAULT '0.00',
  `discount` decimal(15,2) DEFAULT '0.00',
  `net_amount` decimal(15,2) DEFAULT '0.00',
  `narration` text,
  `attachment` varchar(255) DEFAULT NULL,
  `session_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`supplier_ledger_id`) REFERENCES `acc_ledgers`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `acc_purchase_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `purchase_id` int(11) NOT NULL,
  `item_description` varchar(255) NOT NULL,
  `expense_type_id` int(11) DEFAULT NULL,
  `qty` decimal(10,2) DEFAULT '1.00',
  `rate` decimal(15,2) DEFAULT '0.00',
  `amount` decimal(15,2) DEFAULT '0.00',
  PRIMARY KEY (`id`),
  FOREIGN KEY (`purchase_id`) REFERENCES `acc_purchase_entries`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Voucher Audit Log (immutability tracking)
CREATE TABLE IF NOT EXISTS `acc_voucher_audit_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `voucher_id` int(11) NOT NULL,
  `action` enum('create','edit','delete','reverse') NOT NULL,
  `old_data` text DEFAULT NULL,
  `new_data` text DEFAULT NULL,
  `performed_by` int(11) NOT NULL DEFAULT 0,
  `performed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_address` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_voucher_id` (`voucher_id`),
  INDEX `idx_performed_at` (`performed_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Sync Failure Queue (retry mechanism for failed auto-syncs)
CREATE TABLE IF NOT EXISTS `acc_sync_queue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `source_module` varchar(50) NOT NULL COMMENT 'fee_collection, payroll, ss_expense, ss_income',
  `source_id` varchar(100) NOT NULL,
  `action` enum('create','reverse') NOT NULL DEFAULT 'create',
  `status` enum('pending','processing','completed','failed') NOT NULL DEFAULT 'pending',
  `attempts` int(3) NOT NULL DEFAULT 0,
  `error_message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `processed_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_status` (`status`),
  UNIQUE KEY `uk_source` (`source_module`, `source_id`, `action`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Atomic Voucher Number Sequences (prevents race conditions)
CREATE TABLE IF NOT EXISTS `acc_voucher_sequences` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `voucher_type` varchar(20) NOT NULL,
  `last_number` int(11) NOT NULL DEFAULT 0,
  `prefix` varchar(50) NOT NULL DEFAULT '',
  `padding_length` int(11) NOT NULL DEFAULT 5,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_type` (`voucher_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT IGNORE INTO `acc_voucher_sequences` (`voucher_type`, `last_number`, `prefix`, `padding_length`) VALUES
('payment', 0, 'PAY-', 5),
('receipt', 0, 'REC-', 5),
('contra', 0, 'CON-', 5),
('journal', 0, 'JOU-', 5),
('purchase', 0, 'PUR-', 5);

-- MySQL Triggers to maintain current_balance on acc_ledgers


CREATE TRIGGER IF NOT EXISTS `trg_voucher_item_insert` AFTER INSERT ON `acc_voucher_items`
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

CREATE TRIGGER IF NOT EXISTS `trg_voucher_item_delete` AFTER DELETE ON `acc_voucher_items`
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

CREATE TRIGGER IF NOT EXISTS `trg_voucher_item_update` AFTER UPDATE ON `acc_voucher_items`
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

CREATE TRIGGER IF NOT EXISTS `trg_voucher_update` AFTER UPDATE ON `acc_vouchers`
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



-- Insert RBAC Groups & Categories
INSERT IGNORE INTO `permission_group` (`id`, `name`, `short_code`, `is_active`, `system`, `created_at`) VALUES (900, 'Accounts', 'accounts', 1, 0, '2026-05-19 00:00:00');

INSERT IGNORE INTO `permission_category` (`id`, `perm_group_id`, `name`, `short_code`, `enable_view`, `enable_add`, `enable_edit`, `enable_delete`, `created_at`) VALUES
(9001, 900, 'Expense Type', 'acc_expense_type', 1, 1, 1, 1, '2026-05-19 00:00:00'),
(9002, 900, 'Ledger Master', 'acc_ledger_master', 1, 1, 1, 1, '2026-05-19 00:00:00'),
(9003, 900, 'Purchase Entry', 'acc_purchase_entry', 1, 1, 1, 1, '2026-05-19 00:00:00'),
(9004, 900, 'Payment Voucher', 'acc_payment_voucher', 1, 1, 1, 1, '2026-05-19 00:00:00'),
(9005, 900, 'Receipt Voucher', 'acc_receipt_voucher', 1, 1, 1, 1, '2026-05-19 00:00:00'),
(9006, 900, 'Contra Voucher', 'acc_contra_voucher', 1, 1, 1, 1, '2026-05-19 00:00:00'),
(9007, 900, 'Journal Voucher', 'acc_journal_voucher', 1, 1, 1, 1, '2026-05-19 00:00:00'),
(9008, 900, 'Day Book Report', 'acc_daybook_report', 1, 0, 0, 0, '2026-05-19 00:00:00'),
(9009, 900, 'Cash Book Report', 'acc_cashbook_report', 1, 0, 0, 0, '2026-05-19 00:00:00'),
(9010, 900, 'Bank Book Report', 'acc_bankbook_report', 1, 0, 0, 0, '2026-05-19 00:00:00'),
(9011, 900, 'Statement', 'acc_statement', 1, 0, 0, 0, '2026-05-19 00:00:00'),
(9012, 900, 'Outstanding', 'acc_outstanding', 1, 0, 0, 0, '2026-05-19 00:00:00'),
(9013, 900, 'Exp-Income Type Report', 'acc_expincome_report', 1, 0, 0, 0, '2026-05-19 00:00:00'),
(9014, 900, 'Accounts Settings', 'acc_settings', 1, 0, 1, 0, '2026-05-19 00:00:00'),
(9015, 900, 'Approve Voucher', 'acc_approve_voucher', 1, 0, 1, 0, '2026-06-08 00:00:00');

-- Roles permissions mapping (Role 1=Super Admin, 2=Teacher, 3=Accountant, 4=Admin, 5=Receptionist, 6=Librarian, 7=Super Admin)
INSERT IGNORE INTO `roles_permissions` (`role_id`, `perm_cat_id`, `can_view`, `can_add`, `can_edit`, `can_delete`, `created_at`) 
SELECT r.id, c.id, 1, 1, 1, 1, '2026-05-19 00:00:00' FROM `roles` r CROSS JOIN `permission_category` c WHERE r.id IN (1, 3, 4, 7) AND c.id BETWEEN 9001 AND 9014;

-- Map approval privilege to Super Admin (Role 1), Admin (Role 4), Super Admin (Role 7)
INSERT IGNORE INTO `roles_permissions` (`role_id`, `perm_cat_id`, `can_view`, `can_add`, `can_edit`, `can_delete`, `created_at`)
SELECT r.id, 9015, 1, 0, 1, 0, '2026-06-08 00:00:00' FROM `roles` r WHERE r.id IN (1, 4, 7);

-- Add Accounts Sidebar menu
INSERT IGNORE INTO `sidebar_menus` (`id`, `product_name`, `permission_group_id`, `icon`, `menu`, `activate_menu`, `lang_key`, `system_level`, `level`, `sidebar_display`, `access_permissions`, `is_active`, `created_at`) VALUES
(40, 'accounts', 900, 'fa fa-calculator', 'Accounts', 'accounts', 'accounts', 0, 1, 1, '(\'acc_expense_type\', \'can_view\') || (\'acc_ledger_master\', \'can_view\') || (\'acc_purchase_entry\', \'can_view\') || (\'acc_payment_voucher\', \'can_view\') || (\'acc_receipt_voucher\', \'can_view\') || (\'acc_contra_voucher\', \'can_view\') || (\'acc_journal_voucher\', \'can_view\') || (\'acc_daybook_report\', \'can_view\') || (\'acc_cashbook_report\', \'can_view\') || (\'acc_bankbook_report\', \'can_view\') || (\'acc_statement\', \'can_view\') || (\'acc_outstanding\', \'can_view\') || (\'acc_expincome_report\', \'can_view\') || (\'acc_settings\', \'can_view\')', 1, '2026-05-19 00:00:00');

-- Add Accounts Sidebar Sub-menus
INSERT IGNORE INTO `sidebar_sub_menus` (`id`, `sidebar_menu_id`, `menu`, `key`, `lang_key`, `url`, `level`, `access_permissions`, `permission_group_id`, `activate_controller`, `activate_methods`, `addon_permission`, `is_active`, `created_at`) VALUES 
(236, 40, 'Dashboard', 'accounts_dashboard', 'accounts_dashboard', 'accounts/dashboard', 1, '(\'acc_ledger_master\', \'can_view\')', NULL, 'dashboard', 'index', 'accounts', 1, '2026-05-19 00:00:00'),
(237, 40, '--- 1. Configuration Masters ---', 'grp_config', '<b style=\"color:#1e3a8a;font-size:11px;\">1. CONFIGURATION MASTERS</b>', '#', 1, '(\'acc_ledger_master\', \'can_view\')', NULL, '', '', 'accounts', 1, '2026-05-19 00:00:00'),
(238, 40, 'Settings', 'settings', 'settings', 'accounts/settings', 1, '(\'acc_settings\', \'can_view\')', NULL, 'settings', 'index', 'accounts', 1, '2026-05-19 00:00:00'),
(239, 40, 'Ledger Master', 'ledger_master', 'ledger_master', 'accounts/ledgermaster', 1, '(\'acc_ledger_master\', \'can_view\')', NULL, 'ledgermaster', 'index', 'accounts', 1, '2026-05-19 00:00:00'),
(240, 40, 'Expense Type', 'expense_type', 'expense_type', 'accounts/expensetype', 1, '(\'acc_expense_type\', \'can_view\')', NULL, 'expensetype', 'index', 'accounts', 1, '2026-05-19 00:00:00'),
(241, 40, '--- 2. Daily Transactions ---', 'grp_txn', '<b style=\"color:#1e3a8a;font-size:11px;\">2. DAILY TRANSACTIONS</b>', '#', 1, '(\'acc_payment_voucher\', \'can_view\')', NULL, '', '', 'accounts', 1, '2026-05-19 00:00:00'),
(242, 40, 'Purchase Entry', 'purchase_entry', 'purchase_entry', 'accounts/purchaseentry', 1, '(\'acc_purchase_entry\', \'can_view\')', NULL, 'purchaseentry', 'index', 'accounts', 1, '2026-05-19 00:00:00'),
(243, 40, 'Payment Voucher', 'payment_voucher', 'payment_voucher', 'accounts/paymentvoucher', 1, '(\'acc_payment_voucher\', \'can_view\')', NULL, 'paymentvoucher', 'index', 'accounts', 1, '2026-05-19 00:00:00'),
(244, 40, 'Receipt Voucher', 'receipt_voucher', 'receipt_voucher', 'accounts/receiptvoucher', 1, '(\'acc_receipt_voucher\', \'can_view\')', NULL, 'receiptvoucher', 'index', 'accounts', 1, '2026-05-19 00:00:00'),
(245, 40, 'Contra Voucher', 'contra_voucher', 'contra_voucher', 'accounts/contravoucher', 1, '(\'acc_contra_voucher\', \'can_view\')', NULL, 'contravoucher', 'index', 'accounts', 1, '2026-05-19 00:00:00'),
(246, 40, 'Journal Voucher', 'journal_voucher', 'journal_voucher', 'accounts/journalvoucher', 1, '(\'acc_journal_voucher\', \'can_view\')', NULL, 'journalvoucher', 'index', 'accounts', 1, '2026-05-19 00:00:00'),
(247, 40, '--- 3. Registers & Ledger Reports ---', 'grp_reports', '<b style=\"color:#1e3a8a;font-size:11px;\">3. REGISTERS & REPORTS</b>', '#', 1, '(\'acc_daybook_report\', \'can_view\')', NULL, '', '', 'accounts', 1, '2026-05-19 00:00:00'),
(248, 40, 'Day Book Report', 'day_book_report', 'day_book_report', 'accounts/reports/daybook', 1, '(\'acc_daybook_report\', \'can_view\')', NULL, 'reports', 'daybook', 'accounts', 1, '2026-05-19 00:00:00'),
(249, 40, 'Cash Book Report', 'cash_book_report', 'cash_book_report', 'accounts/reports/cashbook', 1, '(\'acc_cashbook_report\', \'can_view\')', NULL, 'reports', 'cashbook', 'accounts', 1, '2026-05-19 00:00:00'),
(250, 40, 'Bank Book Report', 'bank_book_report', 'bank_book_report', 'accounts/reports/bankbook', 1, '(\'acc_bankbook_report\', \'can_view\')', NULL, 'reports', 'bankbook', 'accounts', 1, '2026-05-19 00:00:00'),
(251, 40, 'Statement', 'statement', 'statement', 'accounts/reports/statement', 1, '(\'acc_statement\', \'can_view\')', NULL, 'reports', 'statement', 'accounts', 1, '2026-05-19 00:00:00'),
(252, 40, 'Outstanding', 'outstanding', 'outstanding', 'accounts/reports/outstanding', 1, '(\'acc_outstanding\', \'can_view\')', NULL, 'reports', 'outstanding', 'accounts', 1, '2026-05-19 00:00:00'),
(253, 40, 'Exp-Income Type Report', 'exp_income_type_report', 'exp_income_type_report', 'accounts/reports/expincome_type', 1, '(\'acc_expincome_report\', \'can_view\')', NULL, 'reports', 'expincome_type', 'accounts', 1, '2026-05-19 00:00:00'),
(254, 40, '--- 4. Final Financial Closures ---', 'grp_closures', '<b style=\"color:#1e3a8a;font-size:11px;\">4. FINANCIAL CLOSURES</b>', '#', 1, '(\'acc_statement\', \'can_view\')', NULL, '', '', 'accounts', 1, '2026-05-19 00:00:00'),
(255, 40, 'Trial Balance', 'trial_balance', 'trial_balance', 'accounts/reports/trialbalance', 1, '(\'acc_statement\', \'can_view\')', NULL, 'reports', 'trialbalance', 'accounts', 1, '2026-05-19 00:00:00'),
(256, 40, 'Profit & Loss', 'profit_loss', 'profit_loss', 'accounts/reports/profitloss', 1, '(\'acc_statement\', \'can_view\')', NULL, 'reports', 'profitloss', 'accounts', 1, '2026-05-19 00:00:00'),
(257, 40, 'Balance Sheet', 'balance_sheet', 'balance_sheet', 'accounts/reports/balancesheet', 1, '(\'acc_statement\', \'can_view\')', NULL, 'reports', 'balancesheet', 'accounts', 1, '2026-05-19 00:00:00');

-- Default Expense and Income types for schools
INSERT IGNORE INTO `acc_expense_types` (`id`, `name`, `type`, `is_active`) VALUES
(1, 'Tuition Fees', 'income', 1),
(2, 'Admission Fees', 'income', 1),
(3, 'Transport Fees', 'income', 1),
(4, 'Bookstore Income', 'income', 1),
(5, 'Canteen Sales', 'income', 1),
(6, 'Hostel Fees', 'income', 1),
(7, 'Miscellaneous Income', 'income', 1),
(8, 'Teacher Salaries', 'expense', 1),
(9, 'Staff Salaries', 'expense', 1),
(10, 'Electricity & Water Utilities', 'expense', 1),
(11, 'Rent & Taxes', 'expense', 1),
(12, 'Printing & Stationery', 'expense', 1),
(13, 'Repairs & Maintenance', 'expense', 1),
(14, 'Canteen Expenses', 'expense', 1),
(15, 'Vehicle Fuel & Transport Maintenance', 'expense', 1),
(16, 'Library Books & Periodicals', 'expense', 1),
(17, 'Laboratory Expenses', 'expense', 1),
(18, 'Sports & Games Expenses', 'expense', 1);

-- Default Ledgers for schools
INSERT IGNORE INTO `acc_ledgers` (`id`,`group_id`,`name`,`opening_type`,`opening_balance`,`current_balance`,`opening_date`) VALUES
(1,2,'Cash Account','Dr',0,0,'2026-04-01'),
(2,1,'Main Bank Account','Dr',0,0,'2026-04-01'),
(3,3,'School Fees Receivable','Dr',0,0,'2026-04-01'),
(6,9,'Tuition Fees Collection','Cr',0,0,'2026-04-01'),
(10,8,'Staff Salaries Expense Account','Dr',0,0,'2026-04-01'),
(11,14,'Payment Gateway Clearing Account','Dr',0,0,'2026-04-01'),
(12,8,'Fee Discount / Concession Expense','Dr',0,0,'2026-04-01'),
(13,15,'Salary Payable Account','Cr',0,0,'2026-04-01'),
(14,14,'Staff Advance Account','Dr',0,0,'2026-04-01');



CALL AddColumnIfNotExist('staff_payslip', 'advance_recovered_amount', 'DECIMAL(15,2) DEFAULT ''0.00'' AFTER `leave_deduction`');
CALL AddColumnIfNotExist('staff_payslip', 'staff_advance_id', 'INT DEFAULT NULL AFTER `advance_recovered_amount`');
