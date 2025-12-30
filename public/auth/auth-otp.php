<?php
require_once __DIR__ . '/../../helpers/redirect.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$errors = $_SESSION['errors'] ?? [];
unset($_SESSION['errors']);

$allowedEmail = $_SESSION['otp_allowed_email'] ?? '';
$origin = $_SESSION['otp_origin'] ?? 'login';

if (empty($allowedEmail)) {
    $fallback = $origin === 'register' ? '/public/auth/auth-register.php' : '/public/auth/auth-login.php';
    redirect($fallback);
}

$email = $allowedEmail;
$emailDisplay = htmlentities($email, ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="az">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>FastLink - OTP Təsdiqi</title>
    <link rel="stylesheet" href="../../CSS/auth-otp.css"/>
</head>
<body>
    <main class="auth-shell">
        <div class="mobile-banner">
            <div class="auth-logo-mark"></div>
            <div>
                <div class="auth-logo-text">FastLink</div>
                <div class="auth-desc">Kod təsdiqi</div>
            </div>
        </div>
        <section class="otp-card">
            <header>
                <h1 class="auth-title">Email təsdiqi (RU: Подтвердите / EN: Verify)</h1>
                <p class="auth-desc">Kod göndərildi: <?php echo $emailDisplay ?: 'email'; ?></p>
            </header>

            <?php if (!empty($errors['general'])): ?>
                <div class="field error"><div class="error-text" style="color:#e5484d;font-size:13px;">&nbsp;<?php echo $errors['general']; ?></div></div>
            <?php endif; ?>

            <form method="POST" action="otp_post.php" class="otp-form" novalidate>
                <input type="hidden" name="email" value="<?php echo $emailDisplay; ?>">
                <input type="hidden" name="otp" id="otp-full" />
                <div class="otp-grid">
                    <?php for ($i = 0; $i < 6; $i++): ?>
                        <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]" class="otp-digit" autocomplete="one-time-code" aria-label="OTP digit <?php echo $i + 1; ?>" />
                    <?php endfor; ?>
                </div>
                <?php if (!empty($errors['otp'])): ?>
                    <div class="field error"><div class="error-text" style="color:#e5484d;font-size:13px;">&nbsp;<?php echo $errors['otp']; ?></div></div>
                <?php endif; ?>
                <div class="otp-meta">
                    <span>OTP 5 dəqiqə ərzində etibarlıdır.</span>
                    <a href="resend_otp.php?email=<?php echo urlencode($email); ?>">Kodu yenidən göndər (RU: Отправить / EN: Resend)</a>
                </div>
                <div class="auth-actions">
                    <button class="btn auth-primary" type="submit">Təsdiqlə (RU: Подтвердить / EN: Confirm)</button>
                    <a class="btn auth-ghost" href="auth-login.php">Geri (RU: Назад / EN: Back)</a>
                </div>
            </form>
        </section>
    </main>

    <script>
        const digits = Array.from(document.querySelectorAll('.otp-digit'));
        const full = document.getElementById('otp-full');
        const form = document.querySelector('.otp-form');

        digits.forEach((input, idx) => {
            input.addEventListener('input', () => {
                input.value = input.value.replace(/[^0-9]/g, '').slice(0, 1);
                if (input.value && digits[idx + 1]) {
                    digits[idx + 1].focus();
                }
                syncOtp();
            });
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && !input.value && digits[idx - 1]) {
                    digits[idx - 1].focus();
                }
            });
        });

        form.addEventListener('submit', () => {
            syncOtp();
        });

        function syncOtp() {
            full.value = digits.map(d => d.value).join('');
        }
    </script>
</body>
</html>
