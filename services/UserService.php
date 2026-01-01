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

    public function findUserById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    /**
     * Find user by business slug (slug rules: lowercase, non-alphanumerics -> hyphen, trimmed).
     */
    public function findUserByBusinessSlug(string $slug): ?array
    {
        $slug = trim(strtolower($slug));
        if ($slug === '') {
            return null;
        }

        // Try matching common variants in SQL, then validate via PHP slugify to ensure correctness.
        $likeName = str_replace('-', ' ', $slug);

        $stmt = $this->pdo->prepare(
            'SELECT * FROM users
             WHERE LOWER(REPLACE(business_name, " ", "-")) = :slug
                OR LOWER(business_name) = :name
             LIMIT 1'
        );
        $stmt->execute(['slug' => $slug, 'name' => $likeName]);
        $user = $stmt->fetch();

        if (!$user) {
            return null;
        }

        $slugify = function (string $value): string {
            $value = strtolower($value);
            $value = preg_replace('/[^a-z0-9]+/i', '-', $value);
            $value = trim($value ?? '', '-');
            return $value;
        };

        $computed = $slugify($user['business_name'] ?? '');
        if ($computed !== $slug) {
            return null;
        }

        return $user ?: null;
    }

    public function getPdo(): PDO
    {
        return $this->pdo;
    }

    public function emailExistsForOther(string $email, int $excludeId): bool
    {
        $stmt = $this->pdo->prepare('SELECT id FROM users WHERE email = :email AND id <> :id LIMIT 1');
        $stmt->execute(['email' => $email, 'id' => $excludeId]);
        return (bool)$stmt->fetchColumn();
    }

    public function phoneExistsForOther(string $phone, int $excludeId): bool
    {
        $stmt = $this->pdo->prepare('SELECT id FROM users WHERE phone = :phone AND id <> :id LIMIT 1');
        $stmt->execute(['phone' => $phone, 'id' => $excludeId]);
        return (bool)$stmt->fetchColumn();
    }

    public function updateProfile(int $id, array $data): void
    {
        $stmt = $this->pdo->prepare('UPDATE users SET first_name = :first_name, last_name = :last_name, phone = :phone, email = :email, business_name = :business_name, city = :city, location = :location, plan = :plan, updated_at = NOW() WHERE id = :id');
        $stmt->execute([
            'id' => $id,
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'business_name' => $data['business_name'],
            'city' => $data['city'],
            'location' => $data['location'],
            'plan' => $data['plan'],
        ]);
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

    /**
     * Create a user marked as verified (used after OTP confirmation before insert).
     */
    public function createUserVerified(array $data): int
    {
        $sql = 'INSERT INTO users (
            first_name, last_name, phone, email, password, business_name, city, location, plan, terms_accepted, is_email_verified, created_at, updated_at
        ) VALUES (
            :first_name, :last_name, :phone, :email, :password, :business_name, :city, :location, :plan, :terms_accepted, 1, NOW(), NOW()
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
            'terms_accepted' => $data['terms_accepted'] ?? 1,
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
