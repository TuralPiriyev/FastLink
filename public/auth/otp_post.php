<?php
require_once __DIR__ . '/../../services/AuthService.php';
require_once __DIR__ . '/../../services/UserService.php';
require_once __DIR__ . '/../../services/OtpService.php';
require_once __DIR__ . '/../../helpers/validation.php';
require_once __DIR__ . '/../../helpers/redirect.php';

$authService = new AuthService();
$authService->startSession();
$userService = new UserService();
$otpService = new OtpService();
$config = require __DIR__ . '/../../config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/public/auth/auth-otp.php');
}

$email = sanitize($_POST['email'] ?? '');
$otp = trim($_POST['otp'] ?? '');
$errors = [];

if (!isValidEmail($email)) {
    $errors['general'] = 'Email düzgün deyil.';
}

if (!preg_match('/^\d{6}$/', $otp)) {
    $errors['otp'] = 'OTP 6 rəqəmdən ibarət olmalıdır.';
}

$user = $userService->getUserByEmail($email);
if (!$user) {
    $errors['general'] = 'İstifadəçi tapılmadı.';
}

if (empty($errors)) {
    if (empty($user['otp_code_hash']) || empty($user['otp_expires_at'])) {
        $errors['general'] = 'OTP mövcud deyil. Zəhmət olmasa yenidən göndərin.';
    } else {
        $now = new DateTime('now');
        $expiry = new DateTime($user['otp_expires_at']);
        if ($now > $expiry) {
            $errors['general'] = 'OTP vaxtı bitib. Yenidən göndərin.';
        } elseif (!$otpService->verifyOtp($otp, $user['otp_code_hash'])) {
            $errors['otp'] = 'OTP düzgün deyil.';
        }
    }
}

if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    redirect('/public/auth/auth-otp.php?email=' . urlencode($email));
}

$userService->markEmailVerified($email);
$userService->clearOtp($email);

redirect('/public/auth/auth-login.php');
