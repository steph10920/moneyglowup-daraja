<?php
require_once '../config/mpesa.php';

function generateAccessToken()
{
    $credentials = base64_encode(MPESA_CONSUMER_KEY . ":" . MPESA_CONSUMER_SECRET);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, MPESA_ACCESS_TOKEN_URL);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Basic {$credentials}"]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    if ($response) {
        $data = json_decode($response, true);
        return $data['access_token'] ?? null;
    }

    return null;
}
