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
        // Use PHPMailer constants for clarity and reduce misconfig risk
        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mailer->Port = (int)$smtp['port'];
        $this->mailer->CharSet = 'UTF-8';
        $this->mailer->setFrom($smtp['from_email'], $smtp['from_name']);
        $this->mailer->isHTML(true);

        // Allow local/self-signed during dev; adjust for production as needed
        $this->mailer->SMTPOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
            ],
        ];
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
