<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
}
$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old'] ?? [];
unset($_SESSION['errors'], $_SESSION['old']);
?>
<!DOCTYPE html>
<html lang="az">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>FastLink - Giriş</title>
    <link rel="stylesheet" href="../../CSS/auth-login.css"/>

</head>
<body>
    <main class="auth-shell">
        <div class="mobile-banner">
            <div class="auth-logo-mark"></div>
            <div>
                <div class="auth-logo-text">FastLink</div>
                <div class="auth-desc">Sürətli sifariş izləmə (RU/EN)</div>
            </div>
        </div>
        <section class="auth-card">
            <aside class="auth-brand">
                <div class="auth-logo">
                    <div class="auth-logo-mark"></div>
                    <div class="auth-logo-text">FastLink</div>
                </div>
                <ul class="auth-benefits">
                    <li><span class="dot"></span><span>Sürətli sifariş izləmə</span></li>
                    <li><span class="dot"></span><span>Çoxdilli menyu</span></li>
                    <li><span class="dot"></span><span>Ani ödəniş bildirişləri</span></li>
                </ul>
                <div class="auth-note">Premium panel: təhlükəsiz, sürətli, sahibkarlar üçün optimallaşdırılıb.</div>
            </aside>
            <div class="auth-form">
                <header>
                    <h1 class="auth-title">Giriş (RU: Вход / EN: Login)</h1>
                    <p class="auth-desc">Sahibkar panelinə daxil olun</p>
                </header>
                <?php if (!empty($errors['general'])): ?>
                    <div class="field error"><div class="error-text"><?php echo $errors['general']; ?></div></div>
                <?php endif; ?>
                <form class="form-grid-auth" method="POST" action="login_post.php" novalidate>
                    <div class="field <?php echo isset($errors['email']) ? 'error' : ''; ?>">
                        <label>Telefon və ya Email (RU: Телефон или Email / EN: Phone or Email)</label>
                        <input type="text" name="email" placeholder="+994 XX... və ya name@mail.com" autocomplete="username" value="<?php echo htmlentities($old['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required />
                        <?php if (!empty($errors['email'])): ?><div class="error-text"><?php echo $errors['email']; ?></div><?php endif; ?>
                    </div>
                    <div class="field <?php echo isset($errors['password']) ? 'error' : ''; ?>">
                        <label>Şifrə (RU: Пароль / EN: Password)</label>
                        <input type="password" name="password" placeholder="••••••••" autocomplete="current-password" required />
                        <?php if (!empty($errors['password'])): ?><div class="error-text"><?php echo $errors['password']; ?></div><?php endif; ?>
                    </div>
                    <div class="options-row">
                        <div class="left">
                            <input type="checkbox" id="remember" disabled />
                            <label for="remember">Məni xatırla (RU: Запомнить / EN: Remember me)</label>
                        </div>
                        <span style="font-size:13px;color:#5a5a5a;">Şifrəni unutmusunuz? (hazırda deaktiv)</span>
                    </div>
                    <div class="auth-actions">
                        <button class="btn auth-primary" type="submit">Daxil ol (RU: Войти / EN: Sign in)</button>
                    </div>
                    <div class="auth-footer">Hesabınız yoxdur? <a href="auth-register.php">Qeydiyyat (RU: Регистрация / EN: Register)</a></div>
                </form>
            </div>
        </section>
    </main>
</body>
</html>
