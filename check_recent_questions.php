<?php
$m = new mysqli('127.0.0.1', 'root', '', 'schoolsms', 3307);
$res = $m->query("SELECT id, question_type, level, question, correct, opt_a FROM questions ORDER BY id DESC LIMIT 5");
while ($row = $res->fetch_assoc()) {
    echo "ID: " . $row['id'] . " | Type: " . $row['question_type'] . " | Correct: " . $row['correct'] . "\n";
    echo "Q: " . substr(strip_tags($row['question']), 0, 80) . "\n";
    echo "Opt A: " . $row['opt_a'] . "\n---\n";
}
