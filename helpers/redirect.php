<?php
function redirect(string $path): void
{
    $isAbsolute = preg_match('/^https?:\/\//i', $path) === 1;
    if ($isAbsolute) {
        header('Location: ' . $path);
        exit;
    }

    $config = require __DIR__ . '/../config/config.php';
    $base = rtrim($config['app']['base_url'], '/');
    $normalizedPath = '/' . ltrim($path, '/');
    header('Location: ' . $base . $normalizedPath);
    exit;
}
