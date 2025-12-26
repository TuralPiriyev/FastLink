<?php
require_once __DIR__ . '/../helpers/validation.php';
require_once __DIR__ . '/../helpers/redirect.php';
require_once __DIR__ . '/UserService.php';
require_once __DIR__ . '/OtpService.php';
require_once __DIR__ . '/MailService.php';

class AuthService
{
    private UserService $userService;
    private OtpService $otpService;
    private MailService $mailService;
    private array $config;

    public function __construct()
    {
        $this->userService = new UserService();
        $this->otpService = new OtpService();
        $this->mailService = new MailService();
        $this->config = require __DIR__ . '/../config/config.php';
    }

    public function getMailService(): MailService
    {
        return $this->mailService;
    }

    public function registerFlow(array $input): array
    {
        $errors = $this->validateRegister($input);
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        $email = sanitize($input['email']);
        $phone = sanitize($input['phone']);

        if ($this->userService->findUserByEmail($email)) {
            $errors['email'] = 'Bu email artıq qeydiyyatdan keçib.';
        }

        if ($this->userService->findUserByPhone($phone)) {
            $errors['phone'] = 'Bu telefon artıq qeydiyyatdan keçib.';
        }

        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        $otpCode = $this->otpService->generateOtp();
        $otpHash = $this->otpService->hashOtp($otpCode);
        $expiry = $this->otpService->getExpiryDateTime($this->config['app']['otp_expiry_minutes']);

        $userData = [
            'first_name' => sanitize($input['first_name']),
            'last_name' => sanitize($input['last_name']),
            'phone' => $phone,
            'email' => $email,
            'password' => password_hash($input['password'], PASSWORD_DEFAULT),
            'business_name' => sanitize($input['business_name']),
            'city' => sanitize($input['city']),
            'location' => sanitize($input['location'] ?? ''),
            'plan' => $input['plan'],
            'terms_accepted' => 1,
            'otp_code_hash' => $otpHash,
            'otp_expires_at' => $expiry,
        ];

        $this->userService->createUserPendingVerify($userData);

        $sent = $this->mailService->sendOtpEmail($email, $otpCode);
        if (!$sent) {
            return ['success' => false, 'errors' => ['general' => 'OTP e-poçta göndərilə bilmədi. Zəhmət olmasa sonra yenidən cəhd edin.']];
        }

        return ['success' => true];
    }

    public function loginFlow(array $input): array
    {
        $errors = [];

        $email = sanitize($input['email'] ?? '');
        $password = $input['password'] ?? '';

        if (!isValidEmail($email)) {
            $errors['email'] = 'Email düzgün deyil.';
        }

        if (!isRequired($password)) {
            $errors['password'] = 'Şifrə tələb olunur.';
        }

        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        $user = $this->userService->findUserByEmail($email);
        if (!$user) {
            return ['success' => false, 'errors' => ['general' => 'Email və ya şifrə yanlışdır.']];
        }

        if (!password_verify($password, $user['password'])) {
            return ['success' => false, 'errors' => ['general' => 'Email və ya şifrə yanlışdır.']];
        }

        if ((int)$user['is_email_verified'] === 0) {
            return ['success' => false, 'redirect_otp' => true];
        }

        $this->startSession();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['business_name'] = $user['business_name'];

        return ['success' => true];
    }

    public function startSession(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    public function logout(): void
    {
        $this->startSession();
        session_unset();
        session_destroy();
    }

    private function validateRegister(array $input): array
    {
        $errors = [];

        $requiredFields = ['first_name', 'last_name', 'phone', 'email', 'password', 'confirm_password', 'business_name', 'city', 'plan', 'terms_accepted'];
        foreach ($requiredFields as $field) {
            if (!isset($input[$field]) || !isRequired($input[$field])) {
                $errors[$field] = 'Bu sahə tələb olunur.';
            }
        }

        if (isset($input['email']) && !isValidEmail($input['email'])) {
            $errors['email'] = 'Email formatı düzgün deyil.';
        }

        if (isset($input['phone']) && !isValidPhoneAz($input['phone'])) {
            $errors['phone'] = 'Telefon nömrəsi +994XXXXXXXXX formatında olmalıdır.';
        }

        if (isset($input['password']) && !isValidPassword($input['password'])) {
            $errors['password'] = 'Şifrə min 8 simvol, ən az 1 hərf və 1 rəqəm olmalıdır.';
        }

        if (isset($input['password'], $input['confirm_password']) && $input['password'] !== $input['confirm_password']) {
            $errors['confirm_password'] = 'Şifrələr uyğun deyil.';
        }

        if (isset($input['plan']) && !isValidPlan($input['plan'])) {
            $errors['plan'] = 'Plan yalnız: kicik, orta, boyuk.';
        }

        if (!isset($input['terms_accepted']) || !termsAccepted($input['terms_accepted'])) {
            $errors['terms_accepted'] = 'Qaydalar qəbul olunmalıdır.';
        }

        return $errors;
    }
}
