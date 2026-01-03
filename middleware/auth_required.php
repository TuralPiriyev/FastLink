<?php
require_once __DIR__ . '/../helpers/redirect.php';

if (!function_exists('require_login')) {
    function require_login(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (empty($_SESSION['user_id'])) {
            redirect('/public/auth/auth-login.php');
        }
    }
}

// Maintain previous behavior for files that include this directly
require_login();
