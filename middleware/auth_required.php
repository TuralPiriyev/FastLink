<?php
require_once __DIR__ . '/../helpers/redirect.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (empty($_SESSION['user_id'])) {
    redirect('/public/auth/auth-login.php');
}
