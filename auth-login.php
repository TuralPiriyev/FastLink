<!DOCTYPE html>
<html lang="az">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <title>FastLink - Giriş</title>
  <link rel="stylesheet" href="CSS/auth-login.css"/>

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
        <div class="form-grid-auth">
          <div class="field">
            <label>Telefon və ya Email (RU: Телефон или Email / EN: Phone or Email)</label>
            <input type="text" placeholder="+994 XX... və ya name@mail.com" autocomplete="username" />
          </div>
          <div class="field error">
            <label>Şifrə (RU: Пароль / EN: Password)</label>
            <input type="password" placeholder="••••••••" autocomplete="current-password" />
            <div class="error-text">Xəta: məlumatları yenidən yoxlayın</div>
          </div>
          <div class="options-row">
            <div class="left">
              <input type="checkbox" id="remember" />
              <label for="remember">Məni xatırla (RU: Запомнить / EN: Remember me)</label>
            </div>
            <a href="#">Şifrəni unutmusunuz? (RU: Забыли пароль? / EN: Forgot password?)</a>
          </div>
          <div class="auth-actions">
            <button class="btn auth-primary" type="button">Daxil ol (RU: Войти / EN: Sign in)</button>
          </div>
          <div class="auth-footer">Hesabınız yoxdur? <a href="auth-register.php">Qeydiyyat (RU: Регистрация / EN: Register)</a></div>
        </div>
      </div>
    </section>
  </main>
</body>
</html>