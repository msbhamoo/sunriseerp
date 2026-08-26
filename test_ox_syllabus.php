<?php
$m = new mysqli('127.0.0.1', 'root', '', 'schoolsms', 3307);
$res = $m->query("SELECT ai_openrouter_api_key FROM sch_settings LIMIT 1");
$row = $res->fetch_assoc();
$key = $row['ai_openrouter_api_key'];

$prompt = <<<EOT
You are an expert CBSE, NCERT, and State Education Curriculum Director.
Provide the standard list of NCERT / CBSE curriculum chapters/units for:
Class: VI
Subject: REASONING

STRICT JSON OUTPUT FORMAT ONLY:
Output MUST be a single valid JSON object strictly matching this schema with NO markdown fences:
{
  "class_name": "VI",
  "subject_name": "REASONING",
  "chapters": [
    "Chapter 1 - Chapter Name",
    "Chapter 2 - Chapter Name"
  ]
}
EOT;

$url = "https://openrouter.ai/api/v1/chat/completions";
$t0 = microtime(true);
$payload = [
    'model' => 'stealth/ox-alpha',
    'messages' => [
        ['role' => 'system', 'content' => 'You are an expert CBSE curriculum director. Output only raw valid JSON.'],
        ['role' => 'user', 'content' => $prompt]
    ],
    'response_format' => ['type' => 'json_object'],
    'temperature' => 0.3,
    'max_tokens' => 3000
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
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$res = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$err = curl_error($ch);
curl_close($ch);
$t1 = microtime(true);

echo "Time taken: " . round($t1 - $t0, 2) . "s\n";
echo "HTTP: $code\n";
if ($err) echo "cURL Error: $err\n";
echo "Response: " . substr($res, 0, 500) . "...\n";
