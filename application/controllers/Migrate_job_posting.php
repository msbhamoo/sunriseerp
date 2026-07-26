<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Migrate_job_posting extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function index()
    {
        echo "Starting migration for Job Posting Manager...<br>";

        // 1. Create table job_postings
        $sql1 = "CREATE TABLE IF NOT EXISTS `job_postings` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `title` VARCHAR(255) NOT NULL,
            `designation_id` INT(11) NOT NULL,
            `department` VARCHAR(255) DEFAULT NULL,
            `employment_type` VARCHAR(50) NOT NULL DEFAULT 'Full Time',
            `vacancies` INT(11) NOT NULL DEFAULT 1,
            `location` VARCHAR(255) DEFAULT NULL,
            `experience_required` VARCHAR(255) DEFAULT NULL,
            `educational_level` TEXT DEFAULT NULL,
            `role_overview` TEXT DEFAULT NULL,
            `job_description` LONGTEXT DEFAULT NULL,
            `benefits` LONGTEXT DEFAULT NULL,
            `certificates` TEXT DEFAULT NULL,
            `last_date` DATE DEFAULT NULL,
            `is_active` TINYINT(1) NOT NULL DEFAULT 1,
            `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `idx_designation` (`designation_id`),
            KEY `idx_is_active` (`is_active`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

        if ($this->db->query($sql1)) {
            echo "Table 'job_postings' created or verified successfully.<br>";
        } else {
            echo "Failed to create 'job_postings'.<br>";
        }

        // 2. Check and insert permission_category for job_posting
        $this->db->where('short_code', 'job_posting');
        $q_perm = $this->db->get('permission_category');
        if ($q_perm->num_rows() == 0) {
            // Find perm_group_id for Human Resource
            $this->db->where('short_code', 'human_resource');
            $q_grp = $this->db->get('permission_group');
            $perm_group_id = 5; // default fallback
            if ($q_grp->num_rows() > 0) {
                $perm_group_id = $q_grp->row()->id;
            }
            $this->db->insert('permission_category', [
                'name' => 'Job Posting',
                'short_code' => 'job_posting',
                'perm_group_id' => $perm_group_id,
                'enable_view' => 1,
                'enable_add' => 1,
                'enable_edit' => 1,
                'enable_delete' => 1,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            echo "Inserted 'job_posting' into permission_category.<br>";
        } else {
            echo "permission_category 'job_posting' already exists.<br>";
        }

        // 3. Update sidebar_menus and sidebar_sub_menus for Human Resource
        $this->db->where('menu', 'Human Resource');
        $this->db->or_where('lang_key', 'human_resource');
        $q_menu = $this->db->get('sidebar_menus');
        if ($q_menu->num_rows() > 0) {
            $menu = $q_menu->row();
            $menu_id = $menu->id;
            $current_permissions = $menu->access_permissions;
            
            // Add 'job_posting' to parent access_permissions if not exists
            if (strpos($current_permissions, "'job_posting'") === false) {
                $new_permissions = $current_permissions . " || ('job_posting', 'can_view')";
                $this->db->where('id', $menu_id);
                $this->db->update('sidebar_menus', ['access_permissions' => $new_permissions]);
                echo "Updated Human Resource menu access permissions.<br>";
            }

            // Insert into sidebar_sub_menus
            $this->db->where('key', 'job_posting');
            $this->db->where('sidebar_menu_id', $menu_id);
            $q_submenu = $this->db->get('sidebar_sub_menus');
            if ($q_submenu->num_rows() == 0) {
                $this->db->insert('sidebar_sub_menus', [
                    'sidebar_menu_id' => $menu_id,
                    'menu' => 'Job Posting',
                    'key' => 'job_posting',
                    'lang_key' => 'job_posting',
                    'url' => 'admin/jobposting',
                    'level' => 10,
                    'access_permissions' => 'job_posting,can_view',
                    'is_active' => 1,
                    'permission_group_id' => 0
                ]);
                echo "Inserted sidebar sub-menu: Job Posting.<br>";
            } else {
                echo "Sidebar sub-menu for Job Posting already exists.<br>";
            }
        } else {
            echo "Human Resource menu not found in sidebar_menus.<br>";
        }

        echo "Job Posting Manager Migration Completed Successfully.";
    }
}
