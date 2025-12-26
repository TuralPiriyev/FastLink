<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$autoload = __DIR__ . '/../vendor/autoload.php';
if (!file_exists($autoload)) {
    throw new RuntimeException('Composer autoload tapılmadı. Layihə kökündə `composer require phpmailer/phpmailer` çalışdırın.');
}
require_once $autoload;

class MailService
{
    private PHPMailer $mailer;
    private array $config;

    public function __construct()
    {
        $this->config = require __DIR__ . '/../config/config.php';
        $this->mailer = new PHPMailer(true);
        $this->configure();
    }

    private function configure(): void
    {
        $smtp = $this->config['smtp'];

        $this->mailer->isSMTP();
        $this->mailer->Host = $smtp['host'];
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = $smtp['username'];
        $this->mailer->Password = $smtp['password'];
        $this->mailer->SMTPSecure = $smtp['encryption'];
        $this->mailer->Port = $smtp['port'];
        $this->mailer->CharSet = 'UTF-8';
        $this->mailer->setFrom($smtp['from_email'], $smtp['from_name']);
        $this->mailer->isHTML(true);
    }

    public function sendOtpEmail(string $toEmail, string $otpCode): bool
    {
        try {
            $this->mailer->clearAddresses();
            $this->mailer->clearAttachments();
            $this->mailer->addAddress($toEmail);

            $this->mailer->Subject = 'FASTLINK - Email Doğrulama Kodu';
            $this->mailer->Body = '<p>Sizin doğrulama kodunuz: <strong>' . htmlentities($otpCode, ENT_QUOTES, 'UTF-8') . '</strong></p>' .
                '<p>Kod 5 dəqiqə ərzində etibarlıdır.</p>';
            $this->mailer->AltBody = 'Sizin doğrulama kodunuz: ' . $otpCode . ' (5 dəqiqə ərzində etibarlıdır).';

            return $this->mailer->send();
        } catch (Exception $e) {
            // Log in production
            return false;
        }
    }
}
