<?php
return [
    'db' => [
        'host' => 'localhost',
        'port' => 3306,
        'name' => 'fast_link',
        'user' => 'root',
        'pass' => '',
        'charset' => 'utf8mb4',
    ],
    'smtp' => [
        'host' => 'smtp.gmail.com',
        'port' => 465,
        'username' => 'piriyevtural00@gmail.com',
        'password' => 'qprg vadq ntww adjf',
        'from_email' => 'piriyevtural00@gmail.com',
        'from_name' => 'FASTLINK Support',
        'encryption' => 'tls'
    ],
    'app' => [
        'base_url' => 'http://localhost/FASTLINK',
        'otp_expiry_minutes' => 5,
        'otp_resend_cooldown_seconds' => 60,
    ],
];
