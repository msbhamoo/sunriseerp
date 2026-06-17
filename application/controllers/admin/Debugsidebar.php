<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Debugsidebar extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->load->database();
        
        echo "<html><body style='font-family:Arial; padding:20px'>";
        echo "<h2>CBSE Sidebar Debug</h2>";

        echo "<h3>1. Parent Menu</h3>";
        $query = $this->db->query("SELECT id, menu, lang_key, is_active, sidebar_display, access_permissions FROM sidebar_menus WHERE lang_key LIKE '%cbse%' OR menu LIKE '%cbse%' OR menu LIKE '%CBSE%'");
        echo "<table border='1' cellpadding='5'><tr><th>ID</th><th>menu</th><th>lang_key</th><th>active</th><th>display</th><th>access_permissions</th></tr>";
        foreach($query->result_array() as $row) {
            echo "<tr><td>{$row['id']}</td><td>{$row['menu']}</td><td>{$row['lang_key']}</td><td>{$row['is_active']}</td><td>{$row['sidebar_display']}</td><td style='max-width:400px;word-wrap:break-word'>{$row['access_permissions']}</td></tr>";
        }
        echo "</table>";

        echo "<h3>2. ALL Sub-Menus under CBSE</h3>";
        $parent = $this->db->query("SELECT id FROM sidebar_menus WHERE lang_key = 'cbse_exam' LIMIT 1")->row();
        $parent_id = $parent ? $parent->id : 0;
        
        $query = $this->db->query("SELECT id, menu, `key`, lang_key, url, access_permissions, permission_group_id, addon_permission, is_active, activate_controller, activate_methods FROM sidebar_sub_menus WHERE sidebar_menu_id = {$parent_id} ORDER BY id");
        echo "<table border='1' cellpadding='5'><tr><th>ID</th><th>menu</th><th>key</th><th>lang_key</th><th>url</th><th>access_perms</th><th>perm_grp_id</th><th>addon</th><th>active</th><th>controller</th><th>methods</th></tr>";
        foreach($query->result_array() as $row) {
            $bg = in_array($row['lang_key'], ['dashboard','generate_admit_card','marks_register','timetable_viewer']) ? " style='background:#ffffcc'" : "";
            echo "<tr{$bg}><td>{$row['id']}</td><td>{$row['menu']}</td><td>{$row['key']}</td><td>{$row['lang_key']}</td><td>{$row['url']}</td><td>{$row['access_permissions']}</td><td>{$row['permission_group_id']}</td><td>{$row['addon_permission']}</td><td>{$row['is_active']}</td><td>{$row['activate_controller']}</td><td>{$row['activate_methods']}</td></tr>";
        }
        echo "</table>";

        echo "<h3>3. Search dashboard/admit entries ANYWHERE</h3>";
        $query = $this->db->query("SELECT id, sidebar_menu_id, menu, lang_key, url, access_permissions, addon_permission FROM sidebar_sub_menus WHERE lang_key = 'dashboard' OR lang_key = 'generate_admit_card' OR url LIKE '%dashboard%' OR url LIKE '%admitcardbulk%'");
        if ($query->num_rows() > 0) {
            echo "<table border='1' cellpadding='5'><tr><th>ID</th><th>sidebar_menu_id</th><th>menu</th><th>lang_key</th><th>url</th><th>access_perms</th><th>addon</th></tr>";
            foreach($query->result_array() as $row) {
                echo "<tr><td>{$row['id']}</td><td>{$row['sidebar_menu_id']}</td><td>{$row['menu']}</td><td>{$row['lang_key']}</td><td>{$row['url']}</td><td>{$row['access_permissions']}</td><td>{$row['addon_permission']}</td></tr>";
            }
            echo "</table>";
        } else {
            echo "<p style='color:red;font-size:18px'><b>Dashboard and Generate Admit Card entries DO NOT EXIST in this database!</b></p>";
        }

        echo "<h3>4. Addons check</h3>";
        $query = $this->db->query("SELECT * FROM addons WHERE short_name LIKE '%cbse%'");
        if ($query->num_rows() > 0) {
            foreach($query->result_array() as $row) {
                print_r($row);
            }
        }

        echo "<p style='color:red'><b>DELETE this controller after debugging!</b></p>";
        echo "</body></html>";
    }
}
