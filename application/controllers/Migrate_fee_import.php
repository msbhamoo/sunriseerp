<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Migrate_fee_import extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function index()
    {
        echo "Starting migration for Fee Import feature...<br>";

        // 1. Create table fee_import_batches
        $sql1 = "CREATE TABLE IF NOT EXISTS `fee_import_batches` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `batch_code` VARCHAR(50) NOT NULL UNIQUE,
            `description` TEXT,
            `total_rows` INT DEFAULT 0,
            `success_rows` INT DEFAULT 0,
            `failed_rows` INT DEFAULT 0,
            `skipped_rows` INT DEFAULT 0,
            `total_amount` DECIMAL(15,2) DEFAULT 0,
            `status` ENUM('draft','previewed','imported','reverted','partial_revert') DEFAULT 'draft',
            `imported_by` INT,
            `imported_at` DATETIME,
            `reverted_by` INT,
            `reverted_at` DATETIME,
            `session_id` INT NOT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

        if ($this->db->query($sql1)) {
            echo "Table 'fee_import_batches' created successfully.<br>";
        } else {
            echo "Failed to create 'fee_import_batches'.<br>";
        }

        // 2. Create table fee_import_rows
        $sql2 = "CREATE TABLE IF NOT EXISTS `fee_import_rows` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `batch_id` INT NOT NULL,
            `row_number` INT NOT NULL,
            `csv_data` TEXT COMMENT 'Original CSV row as JSON',
            `student_id` INT,
            `student_session_id` INT,
            `student_fees_master_id` INT,
            `fee_groups_feetype_id` INT,
            `student_transport_fee_id` INT,
            `student_transport_yearly_fee_id` INT,
            `student_fees_deposite_id` INT COMMENT 'Created deposit ID',
            `sub_invoice_id` INT COMMENT 'Sub-invoice number',
            `custom_receipt_log_id` INT COMMENT 'Created receipt log ID',
            `acc_voucher_ids` TEXT COMMENT 'JSON array of voucher IDs',
            `amount` DECIMAL(10,2) DEFAULT 0,
            `discount` DECIMAL(10,2) DEFAULT 0,
            `fine` DECIMAL(10,2) DEFAULT 0,
            `net_amount` DECIMAL(10,2) DEFAULT 0,
            `original_receipt_no` VARCHAR(100),
            `original_date` DATE,
            `payment_mode` VARCHAR(50),
            `status` ENUM('pending','matched','imported','failed','reverted','skipped') DEFAULT 'pending',
            `error_message` TEXT,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            KEY `idx_batch` (`batch_id`),
            KEY `idx_deposit` (`student_fees_deposite_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

        if ($this->db->query($sql2)) {
            echo "Table 'fee_import_rows' created successfully.<br>";
        } else {
            echo "Failed to create 'fee_import_rows'.<br>";
        }

        // 3. Add Permission Group
        $this->db->where('short_code', 'fee_import');
        $q = $this->db->get('permission_group');
        $perm_group_id = 0;
        if ($q->num_rows() == 0) {
            $this->db->insert('permission_group', [
                'name' => 'Historical Fee Import',
                'short_code' => 'fee_import',
                'is_active' => 1
            ]);
            $perm_group_id = $this->db->insert_id();
            echo "Inserted permission group: Historical Fee Import (ID: $perm_group_id)<br>";
        } else {
            $perm_group_id = $q->row()->id;
            echo "Permission group already exists: Historical Fee Import<br>";
        }

        // 4. Update sidebar_menus and sidebar_sub_menus for Fees Collection
        $this->db->where('menu', 'Fees Collection');
        $this->db->or_where('lang_key', 'fees_collection');
        $q_menu = $this->db->get('sidebar_menus');
        if ($q_menu->num_rows() > 0) {
            $menu = $q_menu->row();
            $menu_id = $menu->id;
            $current_permissions = $menu->access_permissions;
            
            // Add 'fee_import' to parent access_permissions if not exists
            if (strpos($current_permissions, "'fee_import'") === false) {
                $new_permissions = $current_permissions . " || ('fee_import', 'can_view')";
                $this->db->where('id', $menu_id);
                $this->db->update('sidebar_menus', ['access_permissions' => $new_permissions]);
                echo "Updated Fees Collection menu access permissions.<br>";
            }

            // Insert into sidebar_sub_menus
            $this->db->where('key', 'historical_fee_import');
            $this->db->where('sidebar_menu_id', $menu_id);
            $q_submenu = $this->db->get('sidebar_sub_menus');
            if ($q_submenu->num_rows() == 0) {
                $this->db->insert('sidebar_sub_menus', [
                    'sidebar_menu_id' => $menu_id,
                    'menu' => 'Historical Fee Import',
                    'key' => 'historical_fee_import',
                    'lang_key' => 'historical_fee_import',
                    'url' => 'feeimport',
                    'level' => 1,
                    'access_permissions' => 'fee_import,can_view',
                    'is_active' => 1,
                    'permission_group_id' => $perm_group_id
                ]);
                echo "Inserted sidebar sub-menu: Historical Fee Import.<br>";
            } else {
                echo "Sidebar sub-menu already exists.<br>";
            }
        } else {
            echo "Error: Fees Collection menu not found in sidebar_menus.<br>";
        }

        echo "Migration completed.";
    }
}
