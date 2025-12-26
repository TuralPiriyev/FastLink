<?php
require_once __DIR__ . '/../../services/AuthService.php';
require_once __DIR__ . '/../../helpers/redirect.php';

$authService = new AuthService();
$authService->logout();

redirect('/public/auth/auth-login.php');
