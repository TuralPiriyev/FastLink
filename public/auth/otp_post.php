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

$allowedEmail = $_SESSION['otp_allowed_email'] ?? '';
$origin = $_SESSION['otp_origin'] ?? 'login';

if (empty($allowedEmail)) {
    $_SESSION['errors'] = ['general' => 'OTP sessiyası tapılmadı.'];
    $fallback = $origin === 'register' ? '/public/auth/auth-register.php' : '/public/auth/auth-login.php';
    redirect($fallback);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/public/auth/auth-otp.php');
}

$email = sanitize($_POST['email'] ?? '');
$otp = trim($_POST['otp'] ?? '');
$errors = [];

if (strcasecmp($email, $allowedEmail) !== 0) {
    $errors['general'] = 'OTP sessiyası bu email üçün aktiv deyil.';
}

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

unset($_SESSION['otp_allowed_email'], $_SESSION['otp_origin']);

redirect('/public/auth/auth-login.php');
