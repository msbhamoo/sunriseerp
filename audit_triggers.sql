-- Database Audit Triggers for Fee & Accounts Modules
-- Run this script in phpMyAdmin on your LIVE server.

CREATE TABLE IF NOT EXISTS `fee_acc_audit_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `table_name` varchar(100) NOT NULL,
  `record_id` varchar(255) DEFAULT NULL,
  `action` varchar(20) NOT NULL,
  `old_data` json DEFAULT NULL,
  `new_data` json DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TRIGGER IF EXISTS `audit_student_fees_deposite_insert`;
DELIMITER $$
CREATE TRIGGER `audit_student_fees_deposite_insert` AFTER INSERT ON `student_fees_deposite`
FOR EACH ROW
BEGIN
    INSERT INTO fee_acc_audit_logs (table_name, record_id, action, new_data, user_id)
    VALUES ('student_fees_deposite', NEW.`id`, 'INSERT', JSON_OBJECT('id', NEW.`id`, 'student_fees_master_id', NEW.`student_fees_master_id`, 'fee_groups_feetype_id', NEW.`fee_groups_feetype_id`, 'student_transport_fee_id', NEW.`student_transport_fee_id`, 'student_transport_yearly_fee_id', NEW.`student_transport_yearly_fee_id`, 'amount_detail', NEW.`amount_detail`, 'is_active', NEW.`is_active`, 'created_at', NEW.`created_at`, 'updated_at', NEW.`updated_at`), @current_audit_user_id);
END$$
DELIMITER ;

DROP TRIGGER IF EXISTS `audit_student_fees_deposite_update`;
DELIMITER $$
CREATE TRIGGER `audit_student_fees_deposite_update` AFTER UPDATE ON `student_fees_deposite`
FOR EACH ROW
BEGIN
    INSERT INTO fee_acc_audit_logs (table_name, record_id, action, old_data, new_data, user_id)
    VALUES ('student_fees_deposite', NEW.`id`, 'UPDATE', JSON_OBJECT('id', OLD.`id`, 'student_fees_master_id', OLD.`student_fees_master_id`, 'fee_groups_feetype_id', OLD.`fee_groups_feetype_id`, 'student_transport_fee_id', OLD.`student_transport_fee_id`, 'student_transport_yearly_fee_id', OLD.`student_transport_yearly_fee_id`, 'amount_detail', OLD.`amount_detail`, 'is_active', OLD.`is_active`, 'created_at', OLD.`created_at`, 'updated_at', OLD.`updated_at`), JSON_OBJECT('id', NEW.`id`, 'student_fees_master_id', NEW.`student_fees_master_id`, 'fee_groups_feetype_id', NEW.`fee_groups_feetype_id`, 'student_transport_fee_id', NEW.`student_transport_fee_id`, 'student_transport_yearly_fee_id', NEW.`student_transport_yearly_fee_id`, 'amount_detail', NEW.`amount_detail`, 'is_active', NEW.`is_active`, 'created_at', NEW.`created_at`, 'updated_at', NEW.`updated_at`), @current_audit_user_id);
END$$
DELIMITER ;

DROP TRIGGER IF EXISTS `audit_student_fees_deposite_delete`;
DELIMITER $$
CREATE TRIGGER `audit_student_fees_deposite_delete` AFTER DELETE ON `student_fees_deposite`
FOR EACH ROW
BEGIN
    INSERT INTO fee_acc_audit_logs (table_name, record_id, action, old_data, user_id)
    VALUES ('student_fees_deposite', OLD.`id`, 'DELETE', JSON_OBJECT('id', OLD.`id`, 'student_fees_master_id', OLD.`student_fees_master_id`, 'fee_groups_feetype_id', OLD.`fee_groups_feetype_id`, 'student_transport_fee_id', OLD.`student_transport_fee_id`, 'student_transport_yearly_fee_id', OLD.`student_transport_yearly_fee_id`, 'amount_detail', OLD.`amount_detail`, 'is_active', OLD.`is_active`, 'created_at', OLD.`created_at`, 'updated_at', OLD.`updated_at`), @current_audit_user_id);
END$$
DELIMITER ;

DROP TRIGGER IF EXISTS `audit_student_fees_master_insert`;
DELIMITER $$
CREATE TRIGGER `audit_student_fees_master_insert` AFTER INSERT ON `student_fees_master`
FOR EACH ROW
BEGIN
    INSERT INTO fee_acc_audit_logs (table_name, record_id, action, new_data, user_id)
    VALUES ('student_fees_master', NEW.`id`, 'INSERT', JSON_OBJECT('id', NEW.`id`, 'is_system', NEW.`is_system`, 'student_session_id', NEW.`student_session_id`, 'fee_session_group_id', NEW.`fee_session_group_id`, 'amount', NEW.`amount`, 'is_active', NEW.`is_active`, 'created_at', NEW.`created_at`, 'updated_at', NEW.`updated_at`), @current_audit_user_id);
END$$
DELIMITER ;

DROP TRIGGER IF EXISTS `audit_student_fees_master_update`;
DELIMITER $$
CREATE TRIGGER `audit_student_fees_master_update` AFTER UPDATE ON `student_fees_master`
FOR EACH ROW
BEGIN
    INSERT INTO fee_acc_audit_logs (table_name, record_id, action, old_data, new_data, user_id)
    VALUES ('student_fees_master', NEW.`id`, 'UPDATE', JSON_OBJECT('id', OLD.`id`, 'is_system', OLD.`is_system`, 'student_session_id', OLD.`student_session_id`, 'fee_session_group_id', OLD.`fee_session_group_id`, 'amount', OLD.`amount`, 'is_active', OLD.`is_active`, 'created_at', OLD.`created_at`, 'updated_at', OLD.`updated_at`), JSON_OBJECT('id', NEW.`id`, 'is_system', NEW.`is_system`, 'student_session_id', NEW.`student_session_id`, 'fee_session_group_id', NEW.`fee_session_group_id`, 'amount', NEW.`amount`, 'is_active', NEW.`is_active`, 'created_at', NEW.`created_at`, 'updated_at', NEW.`updated_at`), @current_audit_user_id);
END$$
DELIMITER ;

DROP TRIGGER IF EXISTS `audit_student_fees_master_delete`;
DELIMITER $$
CREATE TRIGGER `audit_student_fees_master_delete` AFTER DELETE ON `student_fees_master`
FOR EACH ROW
BEGIN
    INSERT INTO fee_acc_audit_logs (table_name, record_id, action, old_data, user_id)
    VALUES ('student_fees_master', OLD.`id`, 'DELETE', JSON_OBJECT('id', OLD.`id`, 'is_system', OLD.`is_system`, 'student_session_id', OLD.`student_session_id`, 'fee_session_group_id', OLD.`fee_session_group_id`, 'amount', OLD.`amount`, 'is_active', OLD.`is_active`, 'created_at', OLD.`created_at`, 'updated_at', OLD.`updated_at`), @current_audit_user_id);
END$$
DELIMITER ;

DROP TRIGGER IF EXISTS `audit_acc_vouchers_insert`;
DELIMITER $$
CREATE TRIGGER `audit_acc_vouchers_insert` AFTER INSERT ON `acc_vouchers`
FOR EACH ROW
BEGIN
    INSERT INTO fee_acc_audit_logs (table_name, record_id, action, new_data, user_id)
    VALUES ('acc_vouchers', NEW.`id`, 'INSERT', JSON_OBJECT('id', NEW.`id`, 'voucher_no', NEW.`voucher_no`, 'voucher_date', NEW.`voucher_date`, 'voucher_type', NEW.`voucher_type`, 'status', NEW.`status`, 'rejected_reason', NEW.`rejected_reason`, 'narration', NEW.`narration`, 'reference_module', NEW.`reference_module`, 'reference_id', NEW.`reference_id`, 'reversed_by_voucher_id', NEW.`reversed_by_voucher_id`, 'reversal_of_voucher_id', NEW.`reversal_of_voucher_id`, 'payment_method', NEW.`payment_method`, 'cheque_no', NEW.`cheque_no`, 'cheque_date', NEW.`cheque_date`, 'bank_name', NEW.`bank_name`, 'upi_transaction_id', NEW.`upi_transaction_id`, 'net_banking_ref', NEW.`net_banking_ref`, 'attachment', NEW.`attachment`, 'session_id', NEW.`session_id`, 'created_by', NEW.`created_by`, 'created_at', NEW.`created_at`, 'updated_at', NEW.`updated_at`), @current_audit_user_id);
END$$
DELIMITER ;

DROP TRIGGER IF EXISTS `audit_acc_vouchers_update`;
DELIMITER $$
CREATE TRIGGER `audit_acc_vouchers_update` AFTER UPDATE ON `acc_vouchers`
FOR EACH ROW
BEGIN
    INSERT INTO fee_acc_audit_logs (table_name, record_id, action, old_data, new_data, user_id)
    VALUES ('acc_vouchers', NEW.`id`, 'UPDATE', JSON_OBJECT('id', OLD.`id`, 'voucher_no', OLD.`voucher_no`, 'voucher_date', OLD.`voucher_date`, 'voucher_type', OLD.`voucher_type`, 'status', OLD.`status`, 'rejected_reason', OLD.`rejected_reason`, 'narration', OLD.`narration`, 'reference_module', OLD.`reference_module`, 'reference_id', OLD.`reference_id`, 'reversed_by_voucher_id', OLD.`reversed_by_voucher_id`, 'reversal_of_voucher_id', OLD.`reversal_of_voucher_id`, 'payment_method', OLD.`payment_method`, 'cheque_no', OLD.`cheque_no`, 'cheque_date', OLD.`cheque_date`, 'bank_name', OLD.`bank_name`, 'upi_transaction_id', OLD.`upi_transaction_id`, 'net_banking_ref', OLD.`net_banking_ref`, 'attachment', OLD.`attachment`, 'session_id', OLD.`session_id`, 'created_by', OLD.`created_by`, 'created_at', OLD.`created_at`, 'updated_at', OLD.`updated_at`), JSON_OBJECT('id', NEW.`id`, 'voucher_no', NEW.`voucher_no`, 'voucher_date', NEW.`voucher_date`, 'voucher_type', NEW.`voucher_type`, 'status', NEW.`status`, 'rejected_reason', NEW.`rejected_reason`, 'narration', NEW.`narration`, 'reference_module', NEW.`reference_module`, 'reference_id', NEW.`reference_id`, 'reversed_by_voucher_id', NEW.`reversed_by_voucher_id`, 'reversal_of_voucher_id', NEW.`reversal_of_voucher_id`, 'payment_method', NEW.`payment_method`, 'cheque_no', NEW.`cheque_no`, 'cheque_date', NEW.`cheque_date`, 'bank_name', NEW.`bank_name`, 'upi_transaction_id', NEW.`upi_transaction_id`, 'net_banking_ref', NEW.`net_banking_ref`, 'attachment', NEW.`attachment`, 'session_id', NEW.`session_id`, 'created_by', NEW.`created_by`, 'created_at', NEW.`created_at`, 'updated_at', NEW.`updated_at`), @current_audit_user_id);
END$$
DELIMITER ;

DROP TRIGGER IF EXISTS `audit_acc_vouchers_delete`;
DELIMITER $$
CREATE TRIGGER `audit_acc_vouchers_delete` AFTER DELETE ON `acc_vouchers`
FOR EACH ROW
BEGIN
    INSERT INTO fee_acc_audit_logs (table_name, record_id, action, old_data, user_id)
    VALUES ('acc_vouchers', OLD.`id`, 'DELETE', JSON_OBJECT('id', OLD.`id`, 'voucher_no', OLD.`voucher_no`, 'voucher_date', OLD.`voucher_date`, 'voucher_type', OLD.`voucher_type`, 'status', OLD.`status`, 'rejected_reason', OLD.`rejected_reason`, 'narration', OLD.`narration`, 'reference_module', OLD.`reference_module`, 'reference_id', OLD.`reference_id`, 'reversed_by_voucher_id', OLD.`reversed_by_voucher_id`, 'reversal_of_voucher_id', OLD.`reversal_of_voucher_id`, 'payment_method', OLD.`payment_method`, 'cheque_no', OLD.`cheque_no`, 'cheque_date', OLD.`cheque_date`, 'bank_name', OLD.`bank_name`, 'upi_transaction_id', OLD.`upi_transaction_id`, 'net_banking_ref', OLD.`net_banking_ref`, 'attachment', OLD.`attachment`, 'session_id', OLD.`session_id`, 'created_by', OLD.`created_by`, 'created_at', OLD.`created_at`, 'updated_at', OLD.`updated_at`), @current_audit_user_id);
END$$
DELIMITER ;

DROP TRIGGER IF EXISTS `audit_acc_voucher_items_insert`;
DELIMITER $$
CREATE TRIGGER `audit_acc_voucher_items_insert` AFTER INSERT ON `acc_voucher_items`
FOR EACH ROW
BEGIN
    INSERT INTO fee_acc_audit_logs (table_name, record_id, action, new_data, user_id)
    VALUES ('acc_voucher_items', NEW.`id`, 'INSERT', JSON_OBJECT('id', NEW.`id`, 'voucher_id', NEW.`voucher_id`, 'ledger_id', NEW.`ledger_id`, 'expense_type_id', NEW.`expense_type_id`, 'debit_amount', NEW.`debit_amount`, 'credit_amount', NEW.`credit_amount`, 'narration', NEW.`narration`), @current_audit_user_id);
END$$
DELIMITER ;

DROP TRIGGER IF EXISTS `audit_acc_voucher_items_update`;
DELIMITER $$
CREATE TRIGGER `audit_acc_voucher_items_update` AFTER UPDATE ON `acc_voucher_items`
FOR EACH ROW
BEGIN
    INSERT INTO fee_acc_audit_logs (table_name, record_id, action, old_data, new_data, user_id)
    VALUES ('acc_voucher_items', NEW.`id`, 'UPDATE', JSON_OBJECT('id', OLD.`id`, 'voucher_id', OLD.`voucher_id`, 'ledger_id', OLD.`ledger_id`, 'expense_type_id', OLD.`expense_type_id`, 'debit_amount', OLD.`debit_amount`, 'credit_amount', OLD.`credit_amount`, 'narration', OLD.`narration`), JSON_OBJECT('id', NEW.`id`, 'voucher_id', NEW.`voucher_id`, 'ledger_id', NEW.`ledger_id`, 'expense_type_id', NEW.`expense_type_id`, 'debit_amount', NEW.`debit_amount`, 'credit_amount', NEW.`credit_amount`, 'narration', NEW.`narration`), @current_audit_user_id);
END$$
DELIMITER ;

DROP TRIGGER IF EXISTS `audit_acc_voucher_items_delete`;
DELIMITER $$
CREATE TRIGGER `audit_acc_voucher_items_delete` AFTER DELETE ON `acc_voucher_items`
FOR EACH ROW
BEGIN
    INSERT INTO fee_acc_audit_logs (table_name, record_id, action, old_data, user_id)
    VALUES ('acc_voucher_items', OLD.`id`, 'DELETE', JSON_OBJECT('id', OLD.`id`, 'voucher_id', OLD.`voucher_id`, 'ledger_id', OLD.`ledger_id`, 'expense_type_id', OLD.`expense_type_id`, 'debit_amount', OLD.`debit_amount`, 'credit_amount', OLD.`credit_amount`, 'narration', OLD.`narration`), @current_audit_user_id);
END$$
DELIMITER ;

DROP TRIGGER IF EXISTS `audit_acc_ledgers_insert`;
DELIMITER $$
CREATE TRIGGER `audit_acc_ledgers_insert` AFTER INSERT ON `acc_ledgers`
FOR EACH ROW
BEGIN
    INSERT INTO fee_acc_audit_logs (table_name, record_id, action, new_data, user_id)
    VALUES ('acc_ledgers', NEW.`id`, 'INSERT', JSON_OBJECT('id', NEW.`id`, 'group_id', NEW.`group_id`, 'name', NEW.`name`, 'mobile', NEW.`mobile`, 'email', NEW.`email`, 'address', NEW.`address`, 'state', NEW.`state`, 'gst_no', NEW.`gst_no`, 'pan_no', NEW.`pan_no`, 'aadhar_no', NEW.`aadhar_no`, 'bank_id', NEW.`bank_id`, 'account_no', NEW.`account_no`, 'ifsc_code', NEW.`ifsc_code`, 'branch', NEW.`branch`, 'opening_type', NEW.`opening_type`, 'opening_balance', NEW.`opening_balance`, 'current_balance', NEW.`current_balance`, 'opening_date', NEW.`opening_date`, 'created_at', NEW.`created_at`, 'updated_at', NEW.`updated_at`), @current_audit_user_id);
END$$
DELIMITER ;

DROP TRIGGER IF EXISTS `audit_acc_ledgers_update`;
DELIMITER $$
CREATE TRIGGER `audit_acc_ledgers_update` AFTER UPDATE ON `acc_ledgers`
FOR EACH ROW
BEGIN
    INSERT INTO fee_acc_audit_logs (table_name, record_id, action, old_data, new_data, user_id)
    VALUES ('acc_ledgers', NEW.`id`, 'UPDATE', JSON_OBJECT('id', OLD.`id`, 'group_id', OLD.`group_id`, 'name', OLD.`name`, 'mobile', OLD.`mobile`, 'email', OLD.`email`, 'address', OLD.`address`, 'state', OLD.`state`, 'gst_no', OLD.`gst_no`, 'pan_no', OLD.`pan_no`, 'aadhar_no', OLD.`aadhar_no`, 'bank_id', OLD.`bank_id`, 'account_no', OLD.`account_no`, 'ifsc_code', OLD.`ifsc_code`, 'branch', OLD.`branch`, 'opening_type', OLD.`opening_type`, 'opening_balance', OLD.`opening_balance`, 'current_balance', OLD.`current_balance`, 'opening_date', OLD.`opening_date`, 'created_at', OLD.`created_at`, 'updated_at', OLD.`updated_at`), JSON_OBJECT('id', NEW.`id`, 'group_id', NEW.`group_id`, 'name', NEW.`name`, 'mobile', NEW.`mobile`, 'email', NEW.`email`, 'address', NEW.`address`, 'state', NEW.`state`, 'gst_no', NEW.`gst_no`, 'pan_no', NEW.`pan_no`, 'aadhar_no', NEW.`aadhar_no`, 'bank_id', NEW.`bank_id`, 'account_no', NEW.`account_no`, 'ifsc_code', NEW.`ifsc_code`, 'branch', NEW.`branch`, 'opening_type', NEW.`opening_type`, 'opening_balance', NEW.`opening_balance`, 'current_balance', NEW.`current_balance`, 'opening_date', NEW.`opening_date`, 'created_at', NEW.`created_at`, 'updated_at', NEW.`updated_at`), @current_audit_user_id);
END$$
DELIMITER ;

DROP TRIGGER IF EXISTS `audit_acc_ledgers_delete`;
DELIMITER $$
CREATE TRIGGER `audit_acc_ledgers_delete` AFTER DELETE ON `acc_ledgers`
FOR EACH ROW
BEGIN
    INSERT INTO fee_acc_audit_logs (table_name, record_id, action, old_data, user_id)
    VALUES ('acc_ledgers', OLD.`id`, 'DELETE', JSON_OBJECT('id', OLD.`id`, 'group_id', OLD.`group_id`, 'name', OLD.`name`, 'mobile', OLD.`mobile`, 'email', OLD.`email`, 'address', OLD.`address`, 'state', OLD.`state`, 'gst_no', OLD.`gst_no`, 'pan_no', OLD.`pan_no`, 'aadhar_no', OLD.`aadhar_no`, 'bank_id', OLD.`bank_id`, 'account_no', OLD.`account_no`, 'ifsc_code', OLD.`ifsc_code`, 'branch', OLD.`branch`, 'opening_type', OLD.`opening_type`, 'opening_balance', OLD.`opening_balance`, 'current_balance', OLD.`current_balance`, 'opening_date', OLD.`opening_date`, 'created_at', OLD.`created_at`, 'updated_at', OLD.`updated_at`), @current_audit_user_id);
END$$
DELIMITER ;

