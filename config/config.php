<?php
// Simple .env loader for local development
$envFile = __DIR__ . '/../.env';
if (is_file($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if (str_starts_with(trim($line), '#') || !str_contains($line, '=')) {
            continue;
        }
        [$key, $value] = explode('=', $line, 2);
        $value = trim($value);
        $value = trim($value, "\"' ");
        putenv($key . '=' . $value);
        $_ENV[$key] = $value;
    }
}

if (!function_exists('env')) {
    function env(string $key, mixed $default = null): mixed
    {
        $value = getenv($key);
        return $value === false ? $default : $value;
    }
}

return [
    'db' => [
        'host' => env('DB_HOST', 'localhost'),
        'port' => (int)env('DB_PORT', 3306),
        'name' => env('DB_NAME', 'fast_link'),
        'user' => env('DB_USER', 'root'),
        'pass' => env('DB_PASS', ''),
        'charset' => 'utf8mb4',
    ],
    'mail' => [
        'provider' => strtolower(env('MAIL_PROVIDER', 'resend')),
        'from_email' => env('FROM_EMAIL', 'piriyevtural00@gmail.com'),
        'from_name' => env('FROM_NAME', 'FASTLINK Support'),
        'resend_api_key' => env('RESEND_API_KEY', 're_3Pi9Prgy_KUQyT8cWi6uhJ7d562VCHaJP'),
        'brevo_api_key' => env('BREVO_API_KEY', ''),
        'http_verify_ssl' => filter_var(env('HTTP_VERIFY_SSL', 'true'), FILTER_VALIDATE_BOOL),
    ],
    'app' => [
        'base_url' => env('APP_URL', 'http://localhost/FASTLINK'),
        'otp_expiry_minutes' => 5,
        'otp_resend_cooldown_seconds' => 60,
    ],
];
