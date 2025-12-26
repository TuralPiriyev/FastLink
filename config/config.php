<?php
return [
    'db' => [
        'host' => 'localhost',
        'port' => 3306,
        'name' => 'fastlink',
        'user' => 'root',
        'pass' => '',
        'charset' => 'utf8mb4',
    ],
    'smtp' => [
        'host' => 'smtp.example.com',
        'port' => 587,
        'username' => 'no-reply@example.com',
        'password' => 'your_smtp_password',
        'from_email' => 'no-reply@example.com',
        'from_name' => 'FASTLINK Support',
        'encryption' => 'tls'
    ],
    'app' => [
        'base_url' => 'http://localhost/FASTLINK',
        'otp_expiry_minutes' => 5,
        'otp_resend_cooldown_seconds' => 60,
    ],
];
