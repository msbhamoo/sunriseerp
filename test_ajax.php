<?php
$ch = curl_init('http://localhost/lms/admin/substitution/get_staff_timetable');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, ['staff_id' => 4, 'date' => '20 Jun 2026']);
$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
echo "HTTP Code: $httpcode\n";
echo "Response: $response\n";
if(curl_error($ch)) echo "Error: " . curl_error($ch);
curl_close($ch);
?>
