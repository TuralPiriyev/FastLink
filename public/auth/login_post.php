<?php
require_once __DIR__ . '/../../services/AuthService.php';
require_once __DIR__ . '/../../helpers/redirect.php';

$authService = new AuthService();
$authService->startSession();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/public/auth/auth-login.php');
}

$input = [
    'email' => $_POST['email'] ?? '',
    'password' => $_POST['password'] ?? '',
];

$result = $authService->loginFlow($input);

if (isset($result['redirect_otp']) && $result['redirect_otp'] === true) {
    redirect('/public/auth/auth-otp.php?email=' . urlencode($input['email']));
}

if ($result['success']) {
    redirect('sidebar/overview.php');
}

$_SESSION['errors'] = $result['errors'] ?? ['general' => 'Bilinməyən xəta baş verdi.'];
$_SESSION['old'] = ['email' => $input['email']];
redirect('/public/auth/auth-login.php');
