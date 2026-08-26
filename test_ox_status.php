<?php
$m = new mysqli('127.0.0.1', 'root', '', 'schoolsms', 3307);
$res = $m->query("SELECT ai_openrouter_api_key FROM sch_settings LIMIT 1");
$row = $res->fetch_assoc();
$key = $row['ai_openrouter_api_key'];

$url = "https://openrouter.ai/api/v1/chat/completions";
$model = "stealth/ox-alpha";

echo "Testing: $model\n";
$payload = [
    'model' => $model,
    'messages' => [
        ['role' => 'user', 'content' => 'Say hello in JSON format {"greeting": "Hello"}']
    ],
    'response_format' => ['type' => 'json_object']
];

$t0 = microtime(true);
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $key,
    'HTTP-Referer: https://sunriseschool.in',
    'X-Title: Sunrise ERP AI Studio'
]);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$res = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
$t1 = microtime(true);

echo "HTTP Code: $code (in " . round($t1-$t0, 2) . "s)\nResponse: $res\n";
