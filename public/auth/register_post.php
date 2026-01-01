<?php
require_once __DIR__ . '/../../services/AuthService.php';
require_once __DIR__ . '/../../helpers/redirect.php';

$authService = new AuthService();
$authService->startSession();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/public/auth/auth-register.php');
}

$input = [
    'first_name' => $_POST['first_name'] ?? '',
    'last_name' => $_POST['last_name'] ?? '',
    'phone' => $_POST['phone'] ?? '',
    'email' => $_POST['email'] ?? '',
    'password' => $_POST['password'] ?? '',
    'confirm_password' => $_POST['confirm_password'] ?? '',
    'business_name' => $_POST['business_name'] ?? '',
    'city' => $_POST['city'] ?? '',
    'location' => $_POST['location'] ?? '',
    'plan' => $_POST['plan'] ?? '',
    'terms_accepted' => isset($_POST['terms_accepted']) ? 1 : 0,
];

$result = $authService->registerFlow($input);

if ($result['success']) {
    $otpEmail = filter_var($input['email'], FILTER_VALIDATE_EMAIL) ? trim($input['email']) : '';
    $_SESSION['otp_allowed_email'] = $otpEmail;
    $_SESSION['otp_origin'] = 'register';
    $_SESSION['pending_registration'] = [
        'user' => $result['pending_user'],
        'otp_hash' => $result['otp_hash'],
        'otp_expires_at' => $result['otp_expires_at'],
    ];
    redirect('/public/auth/auth-otp.php?email=' . urlencode($input['email']));
}

$_SESSION['errors'] = $result['errors'] ?? ['general' => 'Bilinməyən xəta baş verdi.'];
$_SESSION['old'] = $input;
redirect('/public/auth/auth-register.php');
