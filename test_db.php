<?php
define('BASEPATH', '1');
require 'application/config/database.php';
$db = $db['default'];
$conn = new mysqli($db['hostname'], $db['username'], $db['password'], $db['database']);
$res = $conn->query("SELECT id, firstname, lastname, rte, admission_type FROM students LIMIT 5");
if(!$res) echo $conn->error;
while($row = $res->fetch_assoc()){
    print_r($row);
}
?>
