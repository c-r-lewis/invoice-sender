<?php

$secretKey = getenv('KEY');

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $parts = explode('|', base64_decode($token));

    if (count($parts) === 3) {
        
        list($invoiceNo, $timestamp, $hash) = $parts;
        $data = "$invoiceNo|$timestamp";
        $calculatedHash = hash_hmac('sha256', $data, $secretKey);

        if (hash_equals($calculatedHash, $hash)) {
            // Token is valid for 24 hours 
            if (time() - $timestamp < 86400) { 
                $file = __DIR__ . "/invoices/$invoiceNo.pdf";

                if (file_exists($file)) {
                    header('Content-Type: application/pdf');
                    header('Content-Disposition: attachment; filename="' . basename($file) . '"');
                    readfile($file);
                    exit;
                }
                else {
                    echo "File $invoiceNo doesn't exist.";
                }
            }

        }
    }
}

http_response_code(403);
echo "Invalid or expired token.";
