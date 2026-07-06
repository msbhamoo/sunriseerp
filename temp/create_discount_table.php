<?php
$db = new mysqli('localhost', 'root', '', 'smserp');

$query = "
CREATE TABLE IF NOT EXISTS `fee_discount_requests` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `student_session_id` INT NOT NULL,
    `requested_by` INT NOT NULL COMMENT 'staff.id of requester',
    `discount_type` ENUM('fix', 'percentage') NOT NULL DEFAULT 'fix',
    `amount` DECIMAL(10,2) DEFAULT 0.00,
    `percentage` DECIMAL(5,2) DEFAULT NULL,
    `reason` TEXT NOT NULL,
    `status` ENUM('pending', 'provisional', 'approved', 'rejected') NOT NULL DEFAULT 'pending',
    
    `student_fees_deposite_id` INT DEFAULT NULL,
    `sub_invoice_id` INT DEFAULT NULL,
    `fee_groups_feetype_id` INT DEFAULT NULL,
    
    `approved_by` INT DEFAULT NULL COMMENT 'staff.id of admin',
    `admin_remark` TEXT DEFAULT NULL,
    `approved_at` DATETIME DEFAULT NULL,
    
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX `idx_student_session` (`student_session_id`),
    INDEX `idx_status` (`status`),
    INDEX `idx_requested_by` (`requested_by`),
    INDEX `idx_deposite` (`student_fees_deposite_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
";

if ($db->query($query) === TRUE) {
    echo "Table fee_discount_requests created successfully\n";
} else {
    echo "Error creating table: " . $db->error . "\n";
}
