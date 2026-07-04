<?php
require_once 'config/ai.php';
$ch = curl_init('https://api.x.ai/v1/models');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . GROK_API_KEY
]);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($ch);
curl_close($ch);
file_put_contents('xai_error.log', $response);
echo "Logged.";
?>