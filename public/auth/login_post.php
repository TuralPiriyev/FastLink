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
    $otpEmail = filter_var($input['email'], FILTER_VALIDATE_EMAIL) ? trim($input['email']) : '';
    $_SESSION['otp_allowed_email'] = $otpEmail;
    $_SESSION['otp_origin'] = 'login';
    redirect('/public/auth/auth-otp.php?email=' . urlencode($input['email']));
}

if ($result['success']) {
    redirect('index.php');
}

$_SESSION['errors'] = $result['errors'] ?? ['general' => 'Bilinməyən xəta baş verdi.'];
$_SESSION['old'] = ['email' => $input['email']];
redirect('/public/auth/auth-login.php');
