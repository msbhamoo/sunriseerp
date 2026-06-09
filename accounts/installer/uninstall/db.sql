-- Smart School Accounts Module Uninstall DB
-- Version 1.0

DROP TABLE IF EXISTS `acc_voucher_items`;
DROP TABLE IF EXISTS `acc_vouchers`;
DROP TABLE IF EXISTS `acc_purchase_items`;
DROP TABLE IF EXISTS `acc_purchase_entries`;
DROP TABLE IF EXISTS `acc_ledgers`;
DROP TABLE IF EXISTS `acc_ledger_groups`;
DROP TABLE IF EXISTS `acc_expense_types`;
DROP TABLE IF EXISTS `acc_banks`;
DROP TABLE IF EXISTS `acc_settings`;

-- --------------------------------------------------------

DELETE FROM `permission_group` WHERE id=900;

-- Categories 9001 to 9014
DELETE FROM `permission_category` WHERE id BETWEEN 9001 AND 9014;

-- Roles permissions
DELETE FROM `roles_permissions` WHERE perm_cat_id BETWEEN 9001 AND 9014;

-- Sidebar parent 40
DELETE FROM `sidebar_menus` WHERE id=40;

-- Sidebar sub menus 240 to 257
DELETE FROM `sidebar_sub_menus` WHERE id BETWEEN 240 AND 257;

-- Reset addon version
UPDATE `addons` SET current_version = NULL, installation_by = NULL WHERE product_id = 99000001;
