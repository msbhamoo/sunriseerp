<?php
$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'smserp';

$mysqli = new mysqli($hostname, $username, $password, $database);

if ($mysqli->connect_errno) {
    echo "Connection failed: " . $mysqli->connect_error;
    exit();
}

$queries = [
    "CREATE TABLE IF NOT EXISTS ptms (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        ptm_date DATE NOT NULL,
        time_from TIME NOT NULL,
        time_to TIME NOT NULL,
        venue VARCHAR(255) NOT NULL,
        description TEXT,
        is_active TINYINT(1) DEFAULT 1,
        created_by INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    "CREATE TABLE IF NOT EXISTS ptm_targets (
        id INT AUTO_INCREMENT PRIMARY KEY,
        ptm_id INT NOT NULL,
        class_id INT NOT NULL,
        section_id INT NOT NULL
    )",
    "CREATE TABLE IF NOT EXISTS ptm_attendances (
        id INT AUTO_INCREMENT PRIMARY KEY,
        ptm_id INT NOT NULL,
        student_session_id INT NOT NULL,
        status ENUM('present', 'absent') DEFAULT 'absent',
        attendee_type ENUM('father', 'mother', 'both', 'guardian', 'other') NULL,
        arrival_time TIME NULL,
        departure_time TIME NULL,
        discussion_points TEXT,
        parent_remarks TEXT,
        teacher_remarks TEXT,
        concerns_academics TEXT,
        concerns_attendance TEXT,
        concerns_behavior TEXT,
        concerns_discipline TEXT,
        action_items TEXT,
        followup_required TINYINT(1) DEFAULT 0,
        followup_assigned_to INT NULL,
        followup_date DATE NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )"
];

foreach ($queries as $query) {
    if (!$mysqli->query($query)) {
        echo "Error: " . $mysqli->error . "\n";
    } else {
        echo "Query executed successfully.\n";
    }
}

// Menu Insertions
$menu_queries = [
    // Ensure Academic menu exists or find its ID. Usually it is already there.
    "INSERT INTO sidebar_menus (menu, lang_key, `level`, is_active, access_permissions) SELECT 'Academic', 'academics', 1, 1, '' FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM sidebar_menus WHERE lang_key='academics')",
    
    // Add Submenu
    "INSERT INTO sidebar_sub_menus (sidebar_menu_id, url, permission_group_id, lang_key, activate_controller, activate_methods, is_active, access_permissions) 
     SELECT id, 'admin/ptm', NULL, 'ptm_parent_teacher_meeting', 'ptm', 'index,create,attendance,report', 1, 'ptm_parent_teacher_meeting,can_view' FROM sidebar_menus WHERE lang_key='academics' AND NOT EXISTS (SELECT 1 FROM sidebar_sub_menus WHERE url='admin/ptm')"
];

foreach ($menu_queries as $query) {
    if (!$mysqli->query($query)) {
        echo "Error: " . $mysqli->error . "\n";
    } else {
        echo "Menu Insert executed successfully.\n";
    }
}

// Add a permission category for PTM under Academics
$perm_group_query = "INSERT INTO permission_category (name, short_code, perm_group_id, enable_view, enable_add, enable_edit, enable_delete) 
SELECT 'PTM', 'ptm_parent_teacher_meeting', id, 1, 1, 1, 1 FROM permission_group WHERE short_code='academics' 
AND NOT EXISTS (SELECT 1 FROM permission_category WHERE short_code='ptm_parent_teacher_meeting')";
$mysqli->query($perm_group_query);

$mysqli->close();
echo "Done.";
?>
