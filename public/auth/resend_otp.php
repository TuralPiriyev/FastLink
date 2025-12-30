<?php
require_once __DIR__ . '/../../services/AuthService.php';
require_once __DIR__ . '/../../services/UserService.php';
require_once __DIR__ . '/../../services/OtpService.php';
require_once __DIR__ . '/../../services/MailService.php';
require_once __DIR__ . '/../../helpers/validation.php';
require_once __DIR__ . '/../../helpers/redirect.php';

$authService = new AuthService();
$authService->startSession();
$userService = new UserService();
$otpService = new OtpService();
$mailService = new MailService();
$config = require __DIR__ . '/../../config/config.php';

$email = sanitize($_POST['email'] ?? $_GET['email'] ?? '');
$allowedEmail = $_SESSION['otp_allowed_email'] ?? '';
$origin = $_SESSION['otp_origin'] ?? 'login';

if (empty($allowedEmail) || strcasecmp($email, $allowedEmail) !== 0) {
    $_SESSION['errors'] = ['general' => 'OTP sessiyası tapılmadı.'];
    $fallback = $origin === 'register' ? '/public/auth/auth-register.php' : '/public/auth/auth-login.php';
    redirect($fallback);
}

if (!isValidEmail($email)) {
    $_SESSION['errors'] = ['general' => 'Email düzgün deyil.'];
    redirect('/public/auth/auth-otp.php');
}

$nowTs = time();
$cooldown = $config['app']['otp_resend_cooldown_seconds'];
$lastKey = 'otp_last_sent_' . $email;

if (isset($_SESSION[$lastKey]) && ($nowTs - $_SESSION[$lastKey]) < $cooldown) {
    $wait = $cooldown - ($nowTs - $_SESSION[$lastKey]);
    $_SESSION['errors'] = ['general' => 'OTP yenidən göndərmək üçün ' . $wait . ' saniyə gözləyin.'];
    redirect('/public/auth/auth-otp.php?email=' . urlencode($email));
}

$user = $userService->getUserByEmail($email);
if (!$user) {
    $_SESSION['errors'] = ['general' => 'İstifadəçi tapılmadı.'];
    redirect('/public/auth/auth-otp.php');
}

$otpCode = $otpService->generateOtp();
$otpHash = $otpService->hashOtp($otpCode);
$expiry = $otpService->getExpiryDateTime($config['app']['otp_expiry_minutes']);

$userService->setOtpForUser($email, $otpHash, $expiry);

$sent = $mailService->sendOtpEmail($email, $otpCode);
if (!$sent) {
    $_SESSION['errors'] = ['general' => 'OTP göndərilə bilmədi.'];
    redirect('/public/auth/auth-otp.php?email=' . urlencode($email));
}

$_SESSION[$lastKey] = $nowTs;
redirect('/public/auth/auth-otp.php?email=' . urlencode($email));
