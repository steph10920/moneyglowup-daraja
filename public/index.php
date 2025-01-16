<?php
// Enable CORS for frontend communication
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Route requests
$requestUri = $_SERVER['REQUEST_URI'];

if ($requestUri === '/api/pay') {
    require_once '../src/api/pay.php';
} else {
    http_response_code(404);
    echo json_encode(["message" => "Endpoint not found"]);
}
