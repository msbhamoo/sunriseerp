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

// Enable error reporting for debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
} catch (Exception $e) {
    die("Database Connection Exception: " . $e->getMessage());
}

// Target column mapping: Custom field name => DB column name
$field_mapping = [
    'apaar id' => 'apaar_id',
    'pen' => 'pen',
    'aadhaar id' => 'aadhaar_id',
    'admission type' => 'admission_type',
    'shrestha' => 'shrestha'
];

// Find custom field IDs
$custom_fields = [];
$sql = "SELECT id, name FROM custom_fields WHERE belong_to = 'students'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $name_lower = strtolower(trim($row['name']));
        if (array_key_exists($name_lower, $field_mapping)) {
            $custom_fields[$row['id']] = $field_mapping[$name_lower];
        }
    }
}

if (empty($custom_fields)) {
    echo "No matching custom fields found. Please verify the exact names of your custom fields (APAAR ID, PEN, Aadhaar ID, Admission Type, Shrestha).\n";
    exit;
}

$is_migrate = isset($_GET['migrate']) && $_GET['migrate'] == '1';

echo "<div style='font-family: sans-serif; padding: 20px;'>";
echo "<h2>Custom Field Migration Tool</h2>";

if (!$is_migrate) {
    echo "<div style='background-color: #e3f2fd; padding: 15px; border-left: 5px solid #2196f3; margin-bottom: 20px;'>";
    echo "<h3 style='margin-top:0;'>PREVIEW MODE</h3>";
    echo "<p>No data is being modified. Below is a preview of the custom field values that will be moved to the new native columns.</p>";
    echo "<p><a href='?migrate=1' style='display: inline-block; padding: 10px 20px; background: #4caf50; color: white; text-decoration: none; border-radius: 4px; font-weight: bold;'>Run Migration Now</a></p>";
    echo "</div>";
} else {
    echo "<div style='background-color: #e8f5e9; padding: 15px; border-left: 5px solid #4caf50; margin-bottom: 20px;'>";
    echo "<h3 style='margin-top:0;'>MIGRATION MODE</h3>";
    echo "<p>Data is being updated...</p>";
    echo "</div>";
}

$update_counts = [];

foreach ($custom_fields as $cf_id => $db_column) {
    $update_counts[$db_column] = 0;
    
    echo "<h3>Mapping: Custom Field ID $cf_id -> students.$db_column</h3>";
    
    $val_sql = "
        SELECT cv.belong_table_id, cv.field_value, s.firstname, s.lastname 
        FROM custom_field_values cv
        JOIN students s ON s.id = cv.belong_table_id
        WHERE cv.custom_field_id = " . intval($cf_id) . " 
        AND cv.field_value IS NOT NULL 
        AND cv.field_value != ''
    ";
    
    $val_result = $conn->query($val_sql);
    
    if ($val_result->num_rows > 0) {
        echo "<table border='1' cellpadding='8' style='border-collapse: collapse; margin-bottom: 30px; width: 100%; max-width: 800px; text-align: left;'>";
        echo "<tr style='background-color: #f5f5f5;'><th>Student ID</th><th>Student Name</th><th>Value to Migrate</th><th>Status</th></tr>";
        
        while($val_row = $val_result->fetch_assoc()) {
            $student_id = $val_row['belong_table_id'];
            $field_value = $conn->real_escape_string($val_row['field_value']);
            $student_name = $val_row['firstname'] . " " . $val_row['lastname'];
            
            if ($is_migrate) {
                // Update students table
                $update_sql = "UPDATE students SET $db_column = '$field_value' WHERE id = " . intval($student_id) . " AND ($db_column IS NULL OR $db_column = '' OR $db_column = 'New' OR $db_column = 'No')";
                
                if ($conn->query($update_sql) === TRUE) {
                    if ($conn->affected_rows > 0) {
                        $update_counts[$db_column]++;
                        echo "<tr><td>$student_id</td><td>$student_name</td><td>" . htmlspecialchars($val_row['field_value']) . "</td><td style='color:green; font-weight:bold;'>Migrated</td></tr>";
                    } else {
                        echo "<tr><td>$student_id</td><td>$student_name</td><td>" . htmlspecialchars($val_row['field_value']) . "</td><td style='color:orange;'>Skipped (Native column already holds data)</td></tr>";
                    }
                } else {
                    echo "<tr><td>$student_id</td><td>$student_name</td><td>" . htmlspecialchars($val_row['field_value']) . "</td><td style='color:red;'>Error: " . $conn->error . "</td></tr>";
                }
            } else {
                echo "<tr><td>$student_id</td><td>$student_name</td><td>" . htmlspecialchars($val_row['field_value']) . "</td><td style='color:blue;'>Ready to Migrate</td></tr>";
            }
        }
        echo "</table>";
    } else {
        echo "<p style='color: #777;'><i>No data found to migrate for this field.</i></p>";
    }
}

if ($is_migrate) {
    echo "<div style='margin-top: 30px; padding: 20px; background-color: #fff3e0; border: 1px solid #ffcc80; border-radius: 5px;'>";
    echo "<h3 style='margin-top:0;'>Migration Summary</h3><ul>";
    foreach ($update_counts as $col => $count) {
        echo "<li><b>$col</b>: $count student records updated.</li>";
    }
    echo "</ul>";
    echo "<p><b>Migration complete!</b> You may now safely delete these custom fields through the `/admin/customfield` UI.</p>";
    echo "</div>";
}

echo "</div>";
$conn->close();
?>
