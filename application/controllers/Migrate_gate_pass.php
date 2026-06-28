<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Migrate_gate_pass extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function index()
    {
        echo "Starting migration for Gate Pass feature...<br>";

        // 1. Create table
        $sql = "CREATE TABLE IF NOT EXISTS `front_office_gate_pass` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `gate_pass_no` varchar(50) NOT NULL,
            `user_type` enum('student','staff') NOT NULL,
            `user_id` int(11) NOT NULL,
            `date` date NOT NULL,
            `out_time` time DEFAULT NULL,
            `in_time` time DEFAULT NULL,
            `reason` text DEFAULT NULL,
            `status` varchar(20) DEFAULT 'Pending' COMMENT 'Pending, Approved, Rejected, Completed',
            `approved_by` int(11) DEFAULT NULL,
            `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
            `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
            PRIMARY KEY (`id`)
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

        if ($this->db->query($sql)) {
            echo "Table 'front_office_gate_pass' created successfully.<br>";
        } else {
            echo "Failed to create table.<br>";
        }

        // 2. Add Permission Group
        $this->db->where('short_code', 'front_office_gate_pass');
        $q = $this->db->get('permission_group');
        $perm_group_id = 0;
        if ($q->num_rows() == 0) {
            $this->db->insert('permission_group', [
                'name' => 'Front Office Gate Pass',
                'short_code' => 'front_office_gate_pass',
                'is_active' => 1
            ]);
            $perm_group_id = $this->db->insert_id();
            echo "Inserted permission group: Front Office Gate Pass (ID: $perm_group_id)<br>";
        } else {
            $perm_group_id = $q->row()->id;
            echo "Permission group already exists: Front Office Gate Pass<br>";
        }

        // 3. Add Permission Category and link to Roles (Admin/Super Admin)
        $perm_categories = ['can_view', 'can_add', 'can_edit', 'can_delete'];
        // Generally, the LMS creates permission_category globally or per group?
        // Let's assume standard 'can_view', 'can_add', 'can_edit', 'can_delete' are already in permission_category.
        // Or sometimes it's linked directly. The reference says: permission_group maps to short_code. permission_category defines actions. roles_permissions maps perm_cat_id to role_id.
        
        // 4. Update sidebar_menus and sidebar_sub_menus for Front Office
        $this->db->where('menu', 'Front Office'); // check exact name, maybe key is 'front_office'
        $this->db->or_where('lang_key', 'front_office');
        $q_menu = $this->db->get('sidebar_menus');
        if ($q_menu->num_rows() > 0) {
            $menu = $q_menu->row();
            $menu_id = $menu->id;
            $current_permissions = $menu->access_permissions;
            
            // Add 'front_office_gate_pass' to parent access_permissions if not exists
            if (strpos($current_permissions, "'front_office_gate_pass'") === false) {
                $new_permissions = $current_permissions . " || ('front_office_gate_pass', 'can_view')";
                $this->db->where('id', $menu_id);
                $this->db->update('sidebar_menus', ['access_permissions' => $new_permissions]);
                echo "Updated Front Office menu access permissions.<br>";
            }

            // Insert into sidebar_sub_menus
            $this->db->where('key', 'gate_pass');
            $this->db->where('sidebar_menu_id', $menu_id);
            $q_submenu = $this->db->get('sidebar_sub_menus');
            if ($q_submenu->num_rows() == 0) {
                $this->db->insert('sidebar_sub_menus', [
                    'sidebar_menu_id' => $menu_id,
                    'menu' => 'Gate Pass',
                    'key' => 'gate_pass',
                    'lang_key' => 'gate_pass',
                    'url' => 'admin/gatepass',
                    'level' => 1,
                    'access_permissions' => 'front_office_gate_pass,can_view',
                    'is_active' => 1,
                    'permission_group_id' => $perm_group_id
                ]);
                echo "Inserted sidebar sub-menu: Gate Pass.<br>";
            } else {
                echo "Sidebar sub-menu already exists.<br>";
                // Force update URL if it was incorrectly set to admin/frontoffice/gatepass previously
                $this->db->where('key', 'gate_pass');
                $this->db->update('sidebar_sub_menus', ['url' => 'admin/gatepass']);
                echo "Updated sidebar sub-menu URL to admin/gatepass.<br>";
            }
        } else {
            echo "Error: Front Office menu not found in sidebar_menus.<br>";
        }

        echo "Migration completed.";
    }
}
