<?php
require_once '../config/mpesa.php';
require_once '../utils/response.php';
header("Access-Control-Allow-Origin: https://moneyglowup-by-brenda.vercel.app"); // Replace with your frontend domain
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

// Handle preflight requests (OPTIONS method)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}
// Check for POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse(405, "Method Not Allowed");
    exit;
}

// Get input data
$data = json_decode(file_get_contents("php://input"), true);
$phone = $data['phone'] ?? null;
$amount = $data['amount'] ?? null;
$item = $data['item'] ?? null;

if (!$phone || !$amount || !$item) {
    sendResponse(400, "Missing required parameters");
    exit;
}

// Generate MPESA access token
$accessToken = generateAccessToken();
if (!$accessToken) {
    sendResponse(500, "Failed to generate access token");
    exit;
}

// Create STK Push request
$timestamp = date("YmdHis");
$password = base64_encode(MPESA_SHORTCODE . MPESA_PASSKEY . $timestamp);

$stkPushData = [
    "BusinessShortCode" => MPESA_SHORTCODE,
    "Password" => $password,
    "Timestamp" => $timestamp,
    "TransactionType" => "CustomerPayBillOnline",
    "Amount" => $amount,
    "PartyA" => $phone,
    "PartyB" => MPESA_SHORTCODE,
    "PhoneNumber" => $phone,
    "CallBackURL" => MPESA_CALLBACK_URL,
    "AccountReference" => $item,
    "TransactionDesc" => "Payment for {$item}"
];

// Send STK Push request to Safaricom
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, MPESA_STK_PUSH_URL);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer {$accessToken}",
    "Content-Type: application/json",
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($stkPushData));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode === 200) {
    sendResponse(200, "Payment initiated successfully", json_decode($response, true));
} else {
    sendResponse(500, "Failed to initiate payment", json_decode($response, true));
}
