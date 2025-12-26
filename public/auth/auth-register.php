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
    <title>FastLink - Qeydiyyat</title>
    <link rel = "stylesheet" href = "../../CSS/auth-register.css" />
</head>
<body>
    <main class="auth-shell">
        <div class="mobile-banner">
            <div class="auth-logo-mark"></div>
            <div>
                <div class="auth-logo-text">FastLink</div>
                <div class="auth-desc">Biznesinizi onlayn idarə edin (RU/EN)</div>
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
                <div class="auth-note">Bir neçə dəqiqəyə qeydiyyat və satışa hazır panel.</div>
            </aside>
            <div class="auth-form">
                <header>
                    <h1 class="auth-title">Qeydiyyat (RU: Регистрация / EN: Register)</h1>
                    <p class="auth-desc">Sahibkar hesabı yaradın</p>
                </header>
                <?php if (!empty($errors['general'])): ?>
                    <div class="field error"><div class="error-text"><?php echo $errors['general']; ?></div></div>
                <?php endif; ?>
                <form class="form-grid-auth" method="POST" action="register_post.php" novalidate>
                    <div class="section-block">
                        <h3 class="section-title">Sahibkar məlumatları (RU/EN)</h3>
                        <div class="field <?php echo isset($errors['first_name']) ? 'error' : ''; ?>">
                            <label>Ad</label>
                            <input type="text" name="first_name" placeholder="Ad" autocomplete="given-name" value="<?php echo htmlentities($old['first_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required />
                            <?php if (!empty($errors['first_name'])): ?><div class="error-text"><?php echo $errors['first_name']; ?></div><?php endif; ?>
                        </div>
                        <div class="field <?php echo isset($errors['last_name']) ? 'error' : ''; ?>">
                            <label>Soyad</label>
                            <input type="text" name="last_name" placeholder="Soyad" autocomplete="family-name" value="<?php echo htmlentities($old['last_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required />
                            <?php if (!empty($errors['last_name'])): ?><div class="error-text"><?php echo $errors['last_name']; ?></div><?php endif; ?>
                        </div>
                        <div class="field <?php echo isset($errors['phone']) ? 'error' : ''; ?>">
                            <label>Telefon</label>
                            <input type="tel" name="phone" placeholder="+994 XX XXX XX XX" autocomplete="tel" value="<?php echo htmlentities($old['phone'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required />
                            <div class="helper">Format: +994 XX XXX XX XX</div>
                            <?php if (!empty($errors['phone'])): ?><div class="error-text"><?php echo $errors['phone']; ?></div><?php endif; ?>
                        </div>
                        <div class="field <?php echo isset($errors['email']) ? 'error' : ''; ?>">
                            <label>Email</label>
                            <input type="email" name="email" placeholder="name@mail.com" autocomplete="email" value="<?php echo htmlentities($old['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required />
                            <?php if (!empty($errors['email'])): ?><div class="error-text"><?php echo $errors['email']; ?></div><?php endif; ?>
                        </div>
                        <div class="field <?php echo isset($errors['password']) ? 'error' : ''; ?>">
                            <label>Şifrə</label>
                            <input type="password" name="password" placeholder="Minimum 8 simvol" autocomplete="new-password" required />
                            <div class="helper">Minimum 8 simvol, hərf və rəqəm</div>
                            <?php if (!empty($errors['password'])): ?><div class="error-text"><?php echo $errors['password']; ?></div><?php endif; ?>
                        </div>
                        <div class="field <?php echo isset($errors['confirm_password']) ? 'error' : ''; ?>">
                            <label>Şifrəni təsdiqlə</label>
                            <input type="password" name="confirm_password" placeholder="Şifrəni təkrarla" autocomplete="new-password" required />
                            <?php if (!empty($errors['confirm_password'])): ?><div class="error-text"><?php echo $errors['confirm_password']; ?></div><?php endif; ?>
                        </div>
                    </div>
          
                    <div class="section-block">
                        <h3 class="section-title">Biznes məlumatları (RU/EN)</h3>
                        <div class="field <?php echo isset($errors['business_name']) ? 'error' : ''; ?>">
                            <label>Biznes adı</label>
                            <input type="text" name="business_name" placeholder="FastLink Cafe" value="<?php echo htmlentities($old['business_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required />
                            <?php if (!empty($errors['business_name'])): ?><div class="error-text"><?php echo $errors['business_name']; ?></div><?php endif; ?>
                        </div>
                        <div class="field <?php echo isset($errors['city']) ? 'error' : ''; ?>">
                            <label>Şəhər</label>
                            <input type="text" name="city" placeholder="Bakı" autocomplete="address-level2" value="<?php echo htmlentities($old['city'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required />
                            <?php if (!empty($errors['city'])): ?><div class="error-text"><?php echo $errors['city']; ?></div><?php endif; ?>
                        </div>
                        <div class="field <?php echo isset($errors['location']) ? 'error' : ''; ?>">
                            <label>Lokasiya</label>
                            <textarea name="location" placeholder="Metro yaxınlığı" autocomplete="street-address"><?php echo htmlentities($old['location'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                            <?php if (!empty($errors['location'])): ?><div class="error-text"><?php echo $errors['location']; ?></div><?php endif; ?>
                        </div>
                    </div>
          
                    <div class="section-block">
                        <h3 class="section-title">Paket seçimi (RU/EN)</h3>
                        <div class="package-grid">
                            <label class="package-card">
                                <input type="radio" name="plan" value="kicik" <?php echo (!isset($old['plan']) || $old['plan'] === 'kicik') ? 'checked' : ''; ?> />
                                <div class="package-main">
                                    <div class="pkg-name">Kiçik</div>
                                    <div class="pkg-price">30 AZN/ay</div>
                                    <div class="pkg-cap">Maksimum 20 məhsul</div>
                                </div>
                                <span class="chip">Seçildi</span>
                            </label>
                            <label class="package-card">
                                <input type="radio" name="plan" value="orta" <?php echo (isset($old['plan']) && $old['plan'] === 'orta') ? 'checked' : ''; ?> />
                                <div class="package-main">
                                    <div class="pkg-name">Orta</div>
                                    <div class="pkg-price">60 AZN/ay</div>
                                    <div class="pkg-cap">Maksimum 60 məhsul</div>
                                </div>
                                <span class="chip">Seçildi</span>
                            </label>
                            <label class="package-card">
                                <input type="radio" name="plan" value="boyuk" <?php echo (isset($old['plan']) && $old['plan'] === 'boyuk') ? 'checked' : ''; ?> />
                                <div class="package-main">
                                    <div class="pkg-name">Böyük</div>
                                    <div class="pkg-price">100 AZN/ay</div>
                                    <div class="pkg-cap">Limitsiz məhsul</div>
                                </div>
                                <span class="chip">Seçildi</span>
                            </label>
                        </div>
                        <?php if (!empty($errors['plan'])): ?><div class="field error"><div class="error-text"><?php echo $errors['plan']; ?></div></div><?php endif; ?>
                    </div>
          
                    <div class="section-block">
                        <div class="terms-row">
                            <input type="checkbox" name="terms_accepted" id="terms" value="1" <?php echo isset($old['terms_accepted']) ? 'checked' : ''; ?> />
                            <label for="terms">"Şərtlər və Məxfilik" ilə razıyam (RU: Согласен / EN: I agree)</label>
                        </div>
                        <?php if (!empty($errors['terms_accepted'])): ?><div class="field error"><div class="error-text"><?php echo $errors['terms_accepted']; ?></div></div><?php endif; ?>
                    </div>
          
                    <div class="auth-actions">
                        <button class="btn auth-primary" type="submit">Hesab yarat (RU: Создать / EN: Create account)</button>
                    </div>
          
                    <div class="auth-footer">
                        Artıq hesabınız var? <a href="auth-login.php">Giriş (RU: Вход / EN: Login)</a>
                    </div>
                </form>
            </div>
        </section>
    </main>
</body>
</html>
