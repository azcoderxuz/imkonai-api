<?php
header("Content-Type: application/json");

if (!isset($_GET['savol']) || trim($_GET['savol']) === '') {
    echo json_encode(["error" => "Iltimos, ?savol= parametrini yuboring"], JSON_UNESCAPED_UNICODE);
    exit;
}

$savol = trim($_GET['savol']);

$data = [
    "chat" => $savol,
    "model" => "imkonai",
    "temperature" => 0.7,
    "max_tokens" => 500
];

$ch = curl_init("https://api.imkonai.uz/v1/chat/completions");
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_TIMEOUT => 1000,
    CURLOPT_CONNECTTIMEOUT => 2,
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json",
        "Expect:",
        "Connection: keep-alive"
    ],
    CURLOPT_DNS_CACHE_TIMEOUT => 120,
    CURLOPT_POSTFIELDS => json_encode($data, JSON_UNESCAPED_UNICODE),
]);


$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

if ($response === false || $http_code >= 400) {
    echo json_encode(["error" => "API bilan aloqa bo‘lmadi", "details" => $error], JSON_UNESCAPED_UNICODE);
    exit;
}

echo $response;
