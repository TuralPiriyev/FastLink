<?php
class OtpService
{
    public function generateOtp(): string
    {
        return str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    public function hashOtp(string $otp): string
    {
        return password_hash($otp, PASSWORD_DEFAULT);
    }

    public function verifyOtp(string $otp, string $hash): bool
    {
        return password_verify($otp, $hash);
    }

    public function getExpiryDateTime(int $minutes): string
    {
        $expiry = new DateTime('+' . $minutes . ' minutes');
        return $expiry->format('Y-m-d H:i:s');
    }
}
