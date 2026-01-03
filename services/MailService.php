<?php

$autoload = __DIR__ . '/../vendor/autoload.php';
if (!file_exists($autoload)) {
    throw new RuntimeException('Composer autoload tapılmadı. Layihə kökündə `composer install` çalışdırın.');
}
require_once $autoload;

require_once __DIR__ . '/Email/EmailProviderInterface.php';
require_once __DIR__ . '/Email/ResendProvider.php';
require_once __DIR__ . '/Email/BrevoProvider.php';

class MailService
{
    private array $config;
    private EmailProviderInterface $provider;

    public function __construct()
    {
        $this->config = require __DIR__ . '/../config/config.php';
        $this->provider = $this->resolveProvider();
    }

    private function resolveProvider(): EmailProviderInterface
    {
        $mail = $this->config['mail'] ?? [];
        $provider = strtolower($mail['provider'] ?? 'resend');
        $fromEmail = $mail['from_email'] ?? 'no-reply@example.com';
        $fromName = $mail['from_name'] ?? 'FASTLINK Support';
        $verifySsl = (bool)($mail['http_verify_ssl'] ?? true);

        if ($provider === 'resend' && empty($mail['resend_api_key'])) {
            throw new RuntimeException('RESEND_API_KEY boşdur. .env faylını doldurun.');
        }
        if ($provider === 'brevo' && empty($mail['brevo_api_key'])) {
            throw new RuntimeException('BREVO_API_KEY boşdur. .env faylını doldurun.');
        }

        return match ($provider) {
            'brevo' => new BrevoProvider($mail['brevo_api_key'] ?? '', $fromEmail, $fromName, $verifySsl),
            default => new ResendProvider($mail['resend_api_key'] ?? '', $fromEmail, $fromName, $verifySsl),
        };
    }

    public function sendOtpEmail(string $toEmail, string $otpCode): bool
    {
        $subject = 'FASTLINK - Email Doğrulama Kodu';
        $textBody = 'Sizin doğrulama kodunuz: ' . $otpCode . ' (5 dəqiqə ərzində etibarlıdır).';
        $htmlBody = '<p>Sizin doğrulama kodunuz: <strong>' . htmlentities($otpCode, ENT_QUOTES, 'UTF-8') . '</strong></p>' .
            '<p>Kod 5 dəqiqə ərzində etibarlıdır.</p>';

        try {
            $this->provider->send($toEmail, $subject, $textBody, $htmlBody);
            return true;
        } catch (Throwable $e) {
            error_log('OTP email send failed: ' . $e->getMessage());
            return false;
        }
    }
}
