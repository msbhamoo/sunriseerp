<?php $db = new mysqli('localhost', 'root', '', 'smserp'); $r = $db->query('DESCRIBE student_applied_discounts'); while($row = $r->fetch_assoc()) { echo implode(' | ', $row) . PHP_EOL; }
