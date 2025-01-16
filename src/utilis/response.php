<?php
function sendResponse($statusCode, $message, $data = null)
{
    http_response_code($statusCode);
    echo json_encode([
        "success" => $statusCode === 200,
        "message" => $message,
        "data" => $data,
    ]);
}
