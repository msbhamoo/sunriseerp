<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Temp_install extends CI_Controller {
    public function index() {
        // 1. Permission Group/Category
        // Check if permission category "student_dashboard" exists
        $q = $this->db->get_where('permission_category', ['short_code' => 'student_dashboard']);
        if ($q->num_rows() == 0) {
            $this->db->insert('permission_category', [
                'name' => 'Student Dashboard',
                'short_code' => 'student_dashboard',
                'enable_view' => 1,
                'enable_add' => 0,
                'enable_edit' => 0,
                'enable_delete' => 0,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            $perm_group_id = $this->db->insert_id();

            // Insert into roles_permissions for Super Admin (role_id = 7 usually, let's just grant to 7)
            $this->db->insert('roles_permissions', [
                'role_id' => 7,
                'perm_cat_id' => $perm_group_id,
                'can_view' => 1,
                'can_add' => 0,
                'can_edit' => 0,
                'can_delete' => 0,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }

        // 2. Sidebar sub menu
        // Find parent sidebar menu ID for 'Student Information'
        $parent = $this->db->get_where('sidebar_menus', ['key' => 'student_information'])->row();
        if ($parent) {
            $sub_menu = $this->db->get_where('sidebar_sub_menus', ['key' => 'student_dashboard']);
            if ($sub_menu->num_rows() == 0) {
                $this->db->insert('sidebar_sub_menus', [
                    'sidebar_menu_id' => $parent->id,
                    'menu' => 'Student Dashboard',
                    'key' => 'student_dashboard',
                    'lang_key' => 'student_dashboard',
                    'url' => 'student/dashboard',
                    'level' => 1,
                    'access_permissions' => 'student_dashboard',
                    'activate_controller' => 'student',
                    'activate_methods' => 'dashboard',
                    'is_active' => 1
                ]);
            }
        }

        echo "Installation Complete!";
    }
}
