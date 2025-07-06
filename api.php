<?php
header("Content-Type: application/json");

$joinLink = "https://t.me/imkonai";

if (!isset($_GET['savol']) || trim($_GET['savol']) === '') {
    echo json_encode([
        "Join" => $joinLink,
        "response" => "Iltimos, ?savol= parametrini yuboring",
        "status" => 400,
        "successful" => "failed"
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$savol = trim($_GET['savol']);

$data = [
    "chat" => $savol,
    "model" => "imkonai",
    "temperature" => 0.7,
    "max_tokens" => 500
];

$ch = curl_init("https://api.imkonai.workers.dev/v1/chat/completions");
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => ["Content-Type: application/json"],
    CURLOPT_POSTFIELDS => json_encode($data, JSON_UNESCAPED_UNICODE),
]);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

if ($response === false || $http_code >= 400) {
    echo json_encode([
        "Join" => $joinLink,
        "response" => $error ?: "API bilan aloqa bo‘lmadi",
        "status" => 500,
        "successful" => "failed"
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$decoded = json_decode($response, true);
if (!isset($decoded['response'])) {
    echo json_encode([
        "Join" => $joinLink,
        "response" => "Yaroqsiz API javobi",
        "status" => 500,
        "successful" => "failed"
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

echo json_encode([
    "Join" => $joinLink,
    "response" => $decoded['response'],
    "status" => 200,
    "successful" => "success"
], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
