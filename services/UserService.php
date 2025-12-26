<?php
require_once __DIR__ . '/../config/db.php';

class UserService
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = getPdo();
    }

    public function findUserByEmail(string $email): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public function findUserByPhone(string $phone): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE phone = :phone LIMIT 1');
        $stmt->execute(['phone' => $phone]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public function getUserByEmail(string $email): ?array
    {
        return $this->findUserByEmail($email);
    }

    public function createUserPendingVerify(array $data): int
    {
        $sql = 'INSERT INTO users (
            first_name, last_name, phone, email, password, business_name, city, location, plan, terms_accepted, is_email_verified, otp_code_hash, otp_expires_at, created_at, updated_at
        ) VALUES (
            :first_name, :last_name, :phone, :email, :password, :business_name, :city, :location, :plan, :terms_accepted, 0, :otp_code_hash, :otp_expires_at, NOW(), NOW()
        )';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'password' => $data['password'],
            'business_name' => $data['business_name'],
            'city' => $data['city'],
            'location' => $data['location'],
            'plan' => $data['plan'],
            'terms_accepted' => $data['terms_accepted'],
            'otp_code_hash' => $data['otp_code_hash'],
            'otp_expires_at' => $data['otp_expires_at'],
        ]);

        return (int)$this->pdo->lastInsertId();
    }

    public function setOtpForUser(string $email, string $otpHash, string $expiresAt): void
    {
        $stmt = $this->pdo->prepare('UPDATE users SET otp_code_hash = :otp_hash, otp_expires_at = :expires_at, updated_at = NOW() WHERE email = :email');
        $stmt->execute([
            'otp_hash' => $otpHash,
            'expires_at' => $expiresAt,
            'email' => $email,
        ]);
    }

    public function markEmailVerified(string $email): void
    {
        $stmt = $this->pdo->prepare('UPDATE users SET is_email_verified = 1, updated_at = NOW() WHERE email = :email');
        $stmt->execute(['email' => $email]);
    }

    public function clearOtp(string $email): void
    {
        $stmt = $this->pdo->prepare('UPDATE users SET otp_code_hash = NULL, otp_expires_at = NULL, updated_at = NOW() WHERE email = :email');
        $stmt->execute(['email' => $email]);
    }
}
