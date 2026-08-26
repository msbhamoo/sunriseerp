<?php
$m = new mysqli('127.0.0.1', 'root', '', 'schoolsms', 3307);
$res = $m->query("SELECT ai_gemini_api_key FROM sch_settings LIMIT 1");
$row = $res->fetch_assoc();
$key = $row['ai_gemini_api_key'];

$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-3.6-flash:generateContent?key=" . urlencode($key);
$payload = [
    'contents' => [['parts' => [['text' => 'Output JSON: {"msg":"Gemini is online"}']]]],
    'generationConfig' => ['responseMimeType' => 'application/json']
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$res = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
echo "HTTP: $code\nResponse: $res\n";
exit;

// Test stealth/ox-alpha on OpenRouter directly
$url = "https://openrouter.ai/api/v1/chat/completions";
$models_to_test = ['stealth/ox-alpha', 'nvidia/nemotron-nano-12b-v2-vl:free'];

foreach ($models_to_test as $model) {
    echo "\nTesting: $model\n";
    $payload = [
        'model' => $model,
        'messages' => [
            ['role' => 'user', 'content' => 'Say hello in 3 words.']
        ],
        'response_format' => ['type' => 'json_object']
    ];
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
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $res = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    echo "HTTP Code: $code\nResponse: $res\n";
}
