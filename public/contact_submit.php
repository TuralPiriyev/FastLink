<?php
// Simple public contact stub
header('Content-Type: text/plain; charset=UTF-8');
if (strtoupper($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405);
    echo 'Only POST';
    exit;
}

echo 'OK';
