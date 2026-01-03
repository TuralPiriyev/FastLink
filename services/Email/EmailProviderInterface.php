<?php
interface EmailProviderInterface
{
    public function send(string $toEmail, string $subject, string $textBody, ?string $htmlBody = null): void;
}
