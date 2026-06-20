<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "smserp";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "CREATE TABLE IF NOT EXISTS staff_substitutions (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    date DATE NOT NULL,
    absent_staff_id INT(11) NOT NULL,
    substitute_staff_id INT(11) NOT NULL,
    subject_timetable_id INT(11) NOT NULL,
    is_unplanned TINYINT(1) DEFAULT 0,
    override_conflict_timetable_id INT(11) NULL,
    session_id INT(11) NOT NULL,
    created_by INT(11) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Table staff_substitutions created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();
?>
