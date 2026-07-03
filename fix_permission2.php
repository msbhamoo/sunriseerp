<?php
// Database connection logic mirroring CodeIgniter's config
$is_localhost = isset($_SERVER['HTTP_HOST']) && ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === '127.0.0.1' || strpos($_SERVER['HTTP_HOST'], 'localhost:') === 0);

if ($is_localhost) {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "smserp";
} else {
    $servername = "localhost";
    $username = "u774654038_sunrise";
    $password = "Sunrise@2026";
    $dbname = "u774654038_sunrise";
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Find the ID of the 'Front Office' group
    $res = $conn->query("SELECT id FROM permission_group WHERE short_code = 'front_office' LIMIT 1");
    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $group_id = $row['id'];
        
        // Check if the permission feature actually exists
        $check = $conn->query("SELECT id FROM permission_category WHERE short_code = 'front_office_gate_pass'");
        
        if ($check && $check->num_rows > 0) {
            // Update it to belong to Front Office
            $conn->query("UPDATE permission_category SET perm_group_id = $group_id, name = 'Front Office Gate Pass' WHERE short_code = 'front_office_gate_pass'");
            echo "Restored and moved existing 'Front Office Gate Pass' feature.<br>";
        } else {
            // It got deleted or had a typo in the short_code. Let's create it fresh in the correct group!
            $insert = "INSERT INTO permission_category (perm_group_id, name, short_code, enable_view, enable_add, enable_edit, enable_delete, created_at, updated_at) VALUES ($group_id, 'Front Office Gate Pass', 'front_office_gate_pass', 1, 1, 1, 1, NOW(), NOW())";
            
            if ($conn->query($insert)) {
                echo "Successfully created the 'Front Office Gate Pass' feature inside the 'Front Office' group!<br>";
            } else {
                echo "Error inserting: " . $conn->error;
            }
        }
        
    } else {
        echo "Could not find Front Office permission group.";
    }
    $conn->close();
} catch (Exception $e) {
    die("Database Connection Exception: " . $e->getMessage());
}
?>
