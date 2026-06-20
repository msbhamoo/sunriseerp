<?php
$mysqli = new mysqli('localhost', 'root', '', 'smserp');

// Find Academics Menu ID
$res = $mysqli->query("SELECT id FROM sidebar_menus WHERE menu = 'academics'");
$row = $res->fetch_assoc();
$academics_id = $row['id'];

// Check if Substitution Planning exists
$res2 = $mysqli->query("SELECT id FROM sidebar_sub_menus WHERE sidebar_menu_id = $academics_id AND `key` = 'substitution_planning'");
if ($res2->num_rows == 0) {
    // Insert Substitution Planning
    $mysqli->query("INSERT INTO sidebar_sub_menus (sidebar_menu_id, menu, `key`, url, permission_group_id, level, is_active) VALUES ($academics_id, 'Substitute Planning', 'substitution_planning', 'admin/substitution', 1, 99, 1)");
    echo "Inserted Substitute Planning\n";
}

// Check if Substitution History exists
$res3 = $mysqli->query("SELECT id FROM sidebar_sub_menus WHERE sidebar_menu_id = $academics_id AND `key` = 'substitution_history'");
if ($res3->num_rows == 0) {
    // Insert Substitution History
    $mysqli->query("INSERT INTO sidebar_sub_menus (sidebar_menu_id, menu, `key`, url, permission_group_id, level, is_active) VALUES ($academics_id, 'Substitution History', 'substitution_history', 'admin/substitution/history', 1, 100, 1)");
    echo "Inserted Substitution History\n";
}

// Check if Today's Schedule exists
$res4 = $mysqli->query("SELECT id FROM sidebar_sub_menus WHERE sidebar_menu_id = $academics_id AND `key` = 'todays_schedule'");
if ($res4->num_rows == 0) {
    // Insert Today's Schedule
    $mysqli->query("INSERT INTO sidebar_sub_menus (sidebar_menu_id, menu, `key`, url, permission_group_id, level, is_active) VALUES ($academics_id, 'Today\'s Schedule', 'todays_schedule', 'admin/substitution/todays_schedule', 1, 101, 1)");
    echo "Inserted Today's Schedule\n";
}

echo "Done\n";
?>
