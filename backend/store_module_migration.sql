-- =====================================================================
-- School Store & Inventory Management System (store.sunriseschool.in)
-- Database Migration Script - Fully Idempotent & Additive Only
-- Safe for Production Execution
-- =====================================================================

-- 1. SSO Exchange Tokens Table ----------------------------------------
CREATE TABLE IF NOT EXISTS `store_sso_tokens` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `token` VARCHAR(128) NOT NULL,
  `staff_id` INT(11) NOT NULL,
  `session_id` INT(11) DEFAULT NULL,
  `branch_id` INT(11) DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expires_at` DATETIME NOT NULL,
  `is_used` TINYINT(1) NOT NULL DEFAULT 0,
  `used_at` DATETIME DEFAULT NULL,
  `ip_address` VARCHAR(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_token` (`token`),
  KEY `idx_staff_valid` (`staff_id`, `expires_at`, `is_used`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Store Godowns / Locations Master -----------------------------------
CREATE TABLE IF NOT EXISTS `store_godowns` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `branch_id` INT(11) DEFAULT NULL,
  `godown_name` VARCHAR(150) NOT NULL,
  `code` VARCHAR(50) DEFAULT NULL,
  `description` TEXT DEFAULT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `store_godowns` (`id`, `godown_name`, `code`, `description`) VALUES
  (1, 'Main School Store', 'MAIN', 'Central warehouse for school supplies and uniforms'),
  (2, 'Sports Department Store', 'SPORTS', 'Sports equipment, kits, and accessories'),
  (3, 'Science Lab Godown', 'LAB', 'Physics, Chemistry, and Biology lab apparatus'),
  (4, 'Library Book Store', 'LIB', 'Textbooks and study material stock');

-- 3. Units of Measurement ----------------------------------------------
CREATE TABLE IF NOT EXISTS `store_units` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `unit_name` VARCHAR(50) NOT NULL,
  `short_code` VARCHAR(20) NOT NULL,
  `allow_decimal` TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_code` (`short_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `store_units` (`id`, `unit_name`, `short_code`, `allow_decimal`) VALUES
  (1, 'Pieces', 'Pcs', 0),
  (2, 'Box', 'Box', 0),
  (3, 'Set', 'Set', 0),
  (4, 'Pair', 'Pair', 0),
  (5, 'Kilogram', 'Kg', 1),
  (6, 'Meter', 'Mtr', 1),
  (7, 'Packet', 'Pkt', 0),
  (8, 'Dozen', 'Dzn', 0);

-- 4. Item Categories & Sub-Categories ----------------------------------
CREATE TABLE IF NOT EXISTS `store_categories` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `parent_id` INT(11) DEFAULT NULL,
  `name` VARCHAR(150) NOT NULL,
  `code` VARCHAR(50) DEFAULT NULL,
  `description` TEXT DEFAULT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_parent` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `store_categories` (`id`, `parent_id`, `name`, `code`) VALUES
  (1, NULL, 'School Uniforms', 'UNIFORM'),
  (2, NULL, 'Books & Textbooks', 'BOOKS'),
  (3, NULL, 'Stationery & Office Supplies', 'STATIONERY'),
  (4, NULL, 'Sports Equipment', 'SPORTS'),
  (5, NULL, 'Science & Lab Consumables', 'LAB'),
  (6, NULL, 'IT & Electronic Assets', 'IT'),
  (7, NULL, 'Furniture & Fixtures', 'FURNITURE');

-- 5. Vendor / Supplier Master ------------------------------------------
CREATE TABLE IF NOT EXISTS `store_vendors` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(200) NOT NULL,
  `company_name` VARCHAR(200) DEFAULT NULL,
  `contact_person` VARCHAR(150) DEFAULT NULL,
  `mobile` VARCHAR(20) DEFAULT NULL,
  `email` VARCHAR(150) DEFAULT NULL,
  `gstin` VARCHAR(30) DEFAULT NULL,
  `pan` VARCHAR(20) DEFAULT NULL,
  `address` TEXT DEFAULT NULL,
  `bank_name` VARCHAR(150) DEFAULT NULL,
  `account_no` VARCHAR(50) DEFAULT NULL,
  `ifsc` VARCHAR(30) DEFAULT NULL,
  `opening_balance` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Item Master (Products & Assets) -----------------------------------
CREATE TABLE IF NOT EXISTS `store_items` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `category_id` INT(11) NOT NULL,
  `unit_id` INT(11) NOT NULL,
  `item_name` VARCHAR(255) NOT NULL,
  `item_code` VARCHAR(100) NOT NULL,
  `barcode` VARCHAR(100) DEFAULT NULL,
  `hsn_code` VARCHAR(50) DEFAULT NULL,
  `description` TEXT DEFAULT NULL,
  `purchase_price` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `sale_price` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `gst_rate` DECIMAL(5,2) NOT NULL DEFAULT 0.00,
  `min_stock_alert` INT(11) NOT NULL DEFAULT 5,
  `rack_shelf` VARCHAR(100) DEFAULT NULL,
  `is_returnable_asset` TINYINT(1) NOT NULL DEFAULT 0,
  `is_saleable` TINYINT(1) NOT NULL DEFAULT 1,
  `image` VARCHAR(255) DEFAULT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_item_code` (`item_code`),
  KEY `idx_cat` (`category_id`),
  KEY `idx_barcode` (`barcode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. Real-Time Stock Register per Godown --------------------------------
CREATE TABLE IF NOT EXISTS `store_stock_balances` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `branch_id` INT(11) DEFAULT 1,
  `godown_id` INT(11) NOT NULL,
  `item_id` INT(11) NOT NULL,
  `quantity` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_godown_item` (`branch_id`, `godown_id`, `item_id`),
  KEY `idx_item` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. Complete Immutable Stock Ledger / Audit Trail ---------------------
CREATE TABLE IF NOT EXISTS `store_stock_ledger` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `branch_id` INT(11) DEFAULT 1,
  `godown_id` INT(11) NOT NULL,
  `item_id` INT(11) NOT NULL,
  `transaction_type` ENUM('OPENING', 'PURCHASE_GRN', 'PURCHASE_RETURN', 'SALE', 'SALE_RETURN', 'STAFF_ISSUE', 'STAFF_RETURN', 'LOAN_ISSUE', 'LOAN_RETURN', 'STOCK_TRANSFER_IN', 'STOCK_TRANSFER_OUT', 'ADJUSTMENT') NOT NULL,
  `reference_id` INT(11) DEFAULT NULL,
  `reference_no` VARCHAR(100) DEFAULT NULL,
  `qty_in` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `qty_out` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `unit_price` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `balance_after` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `remarks` TEXT DEFAULT NULL,
  `created_by_staff_id` INT(11) NOT NULL,
  `ip_address` VARCHAR(45) DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_item_date` (`item_id`, `created_at`),
  KEY `idx_ref` (`transaction_type`, `reference_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. Purchase Orders (PO) & GRN (Goods Received Note) ------------------
CREATE TABLE IF NOT EXISTS `store_purchase_orders` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `po_number` VARCHAR(50) NOT NULL,
  `vendor_id` INT(11) NOT NULL,
  `po_date` DATE NOT NULL,
  `delivery_expected_date` DATE DEFAULT NULL,
  `subtotal` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `tax_amount` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `total_amount` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `status` ENUM('Draft', 'Approved', 'Partially_Received', 'Completed', 'Cancelled') NOT NULL DEFAULT 'Draft',
  `remarks` TEXT DEFAULT NULL,
  `created_by_staff_id` INT(11) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_po_no` (`po_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `store_po_items` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `po_id` INT(11) NOT NULL,
  `item_id` INT(11) NOT NULL,
  `quantity` DECIMAL(12,2) NOT NULL,
  `received_quantity` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `unit_price` DECIMAL(12,2) NOT NULL,
  `tax_rate` DECIMAL(5,2) NOT NULL DEFAULT 0.00,
  `total_cost` DECIMAL(12,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_po` (`po_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `store_grn` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `grn_number` VARCHAR(50) NOT NULL,
  `po_id` INT(11) DEFAULT NULL,
  `vendor_id` INT(11) NOT NULL,
  `godown_id` INT(11) NOT NULL,
  `challan_no` VARCHAR(100) DEFAULT NULL,
  `invoice_no` VARCHAR(100) DEFAULT NULL,
  `grn_date` DATE NOT NULL,
  `total_amount` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `bill_file` VARCHAR(255) DEFAULT NULL,
  `created_by_staff_id` INT(11) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_grn_no` (`grn_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `store_grn_items` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `grn_id` INT(11) NOT NULL,
  `item_id` INT(11) NOT NULL,
  `received_qty` DECIMAL(12,2) NOT NULL,
  `unit_cost` DECIMAL(12,2) NOT NULL,
  `total_cost` DECIMAL(12,2) NOT NULL,
  `batch_no` VARCHAR(100) DEFAULT NULL,
  `expiry_date` DATE DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_grn` (`grn_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. Staff & Department Requisitions (Stock Out) ----------------------
CREATE TABLE IF NOT EXISTS `store_requisitions` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `requisition_no` VARCHAR(50) NOT NULL,
  `staff_id` INT(11) NOT NULL,
  `department` VARCHAR(150) DEFAULT NULL,
  `request_date` DATE NOT NULL,
  `purpose` TEXT DEFAULT NULL,
  `status` ENUM('Pending', 'Approved', 'Partially_Issued', 'Issued', 'Rejected') NOT NULL DEFAULT 'Pending',
  `approved_by_staff_id` INT(11) DEFAULT NULL,
  `issue_date` DATE DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_req_no` (`requisition_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `store_requisition_items` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `requisition_id` INT(11) NOT NULL,
  `item_id` INT(11) NOT NULL,
  `requested_qty` DECIMAL(12,2) NOT NULL,
  `approved_qty` DECIMAL(12,2) DEFAULT NULL,
  `issued_qty` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `remarks` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_req` (`requisition_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 11. Returnable Assets & Store Loan Facility --------------------------
CREATE TABLE IF NOT EXISTS `store_loans` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `loan_slip_no` VARCHAR(50) NOT NULL,
  `item_id` INT(11) NOT NULL,
  `godown_id` INT(11) NOT NULL,
  `issued_to_staff_id` INT(11) NOT NULL,
  `quantity` DECIMAL(12,2) NOT NULL DEFAULT 1.00,
  `issue_date` DATE NOT NULL,
  `due_return_date` DATE NOT NULL,
  `actual_return_date` DATE DEFAULT NULL,
  `condition_on_issue` VARCHAR(200) DEFAULT 'Good Working Condition',
  `condition_on_return` VARCHAR(200) DEFAULT NULL,
  `status` ENUM('Issued', 'Overdue', 'Returned', 'Damaged', 'Lost') NOT NULL DEFAULT 'Issued',
  `remarks` TEXT DEFAULT NULL,
  `penalty_amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `issued_by_staff_id` INT(11) NOT NULL,
  `received_by_staff_id` INT(11) DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_loan_slip` (`loan_slip_no`),
  KEY `idx_staff_status` (`issued_to_staff_id`, `status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 12. Point of Sale (POS) Counter Billing (Students / Parents) --------
CREATE TABLE IF NOT EXISTS `store_sales` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `invoice_no` VARCHAR(50) NOT NULL,
  `session_id` INT(11) DEFAULT NULL,
  `branch_id` INT(11) DEFAULT 1,
  `godown_id` INT(11) NOT NULL,
  `student_id` INT(11) DEFAULT NULL,
  `customer_name` VARCHAR(150) NOT NULL,
  `class_section` VARCHAR(100) DEFAULT NULL,
  `contact_no` VARCHAR(20) DEFAULT NULL,
  `sale_date` DATE NOT NULL,
  `subtotal` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `discount_amount` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `tax_amount` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `grand_total` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `payment_mode` ENUM('Cash', 'UPI', 'DebitCard', 'CreditCard', 'StudentLedger') NOT NULL DEFAULT 'Cash',
  `payment_reference` VARCHAR(100) DEFAULT NULL,
  `payment_status` ENUM('Paid', 'Pending', 'Partial') NOT NULL DEFAULT 'Paid',
  `billed_by_staff_id` INT(11) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_sale_inv` (`invoice_no`),
  KEY `idx_student` (`student_id`),
  KEY `idx_sale_date` (`sale_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `store_sale_items` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `sale_id` INT(11) NOT NULL,
  `item_id` INT(11) NOT NULL,
  `quantity` DECIMAL(12,2) NOT NULL,
  `unit_price` DECIMAL(12,2) NOT NULL,
  `discount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `tax_amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `net_amount` DECIMAL(12,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_sale` (`sale_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 13. Smart School RBAC Registration for Store Module -------------------
INSERT INTO `permission_category` 
  (`perm_group_id`, `name`, `short_code`, `enable_view`, `enable_add`, `enable_edit`, `enable_delete`)
SELECT pg.`id`, 'Store Module', 'store_module', 1, 1, 1, 1
FROM `permission_group` pg
WHERE pg.`short_code` = 'system_settings'
  AND NOT EXISTS (SELECT 1 FROM `permission_category` pc WHERE pc.`short_code` = 'store_module');

-- Grant view access to Admin Role (Role ID = 1) if not already set. Super Admin automatically bypasses.
INSERT INTO `roles_permissions` (`role_id`, `perm_cat_id`, `can_view`, `can_add`, `can_edit`, `can_delete`)
SELECT 1, pc.`id`, 1, 1, 1, 1
FROM `permission_category` pc
WHERE pc.`short_code` = 'store_module'
  AND NOT EXISTS (
    SELECT 1 FROM `roles_permissions` rp
    WHERE rp.`role_id` = 1 AND rp.`perm_cat_id` = pc.`id`
  );
