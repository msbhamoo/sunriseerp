<?php
$m = new mysqli('127.0.0.1', 'root', '', 'schoolsms', 3307);
$res = $m->query("SHOW COLUMNS FROM sch_settings LIKE '%api_key%'");
while ($row = $res->fetch_assoc()) {
    echo $row['Field'] . "\n";
}
$res2 = $m->query("SELECT * FROM sch_settings LIMIT 1");
$data = $res2->fetch_assoc();
foreach ($data as $k => $v) {
    if (stripos($k, 'key') !== false || stripos($k, 'ai_') !== false || stripos($k, 'nvidia') !== false) {
        echo "$k: " . (empty($v) ? '(empty)' : substr($v, 0, 10) . '...') . "\n";
    }
}
