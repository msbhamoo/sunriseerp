<?php
$db = new mysqli('localhost', 'root', '', 'smserp');
echo "=== fees_discounts ===" . PHP_EOL;
$r1 = $db->query('DESCRIBE fees_discounts');
while($row = $r1->fetch_assoc()) {
    echo $row['Field'] . ' | ' . $row['Type'] . ' | ' . $row['Null'] . ' | ' . $row['Key'] . ' | ' . $row['Default'] . PHP_EOL;
}
echo PHP_EOL . "=== student_fees_discounts ===" . PHP_EOL;
$r2 = $db->query('DESCRIBE student_fees_discounts');
while($row2 = $r2->fetch_assoc()) {
    echo $row2['Field'] . ' | ' . $row2['Type'] . ' | ' . $row2['Null'] . ' | ' . $row2['Key'] . ' | ' . $row2['Default'] . PHP_EOL;
}
echo PHP_EOL . "=== student_fees_deposite ===" . PHP_EOL;
$r3 = $db->query('DESCRIBE student_fees_deposite');
if($r3) {
    while($row3 = $r3->fetch_assoc()) {
        echo $row3['Field'] . ' | ' . $row3['Type'] . ' | ' . $row3['Null'] . ' | ' . $row3['Key'] . ' | ' . $row3['Default'] . PHP_EOL;
    }
}
echo PHP_EOL . "=== Sample fees_discounts data ===" . PHP_EOL;
$r4 = $db->query('SELECT * FROM fees_discounts LIMIT 3');
while($row4 = $r4->fetch_assoc()) {
    echo json_encode($row4) . PHP_EOL;
}
echo PHP_EOL . "=== Sample student_fees_discounts data ===" . PHP_EOL;
$r5 = $db->query('SELECT * FROM student_fees_discounts LIMIT 5');
while($row5 = $r5->fetch_assoc()) {
    echo json_encode($row5) . PHP_EOL;
}
