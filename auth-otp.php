<!DOCTYPE html>
<html lang="az">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <title>OrderLink - Təsdiq</title>
  <link rel="stylesheet" href="CSS/auth-otp.css"/>
</head>
<body>
  <main class="auth-shell">
    <div class="mobile-banner">
      <div class="auth-logo-mark"></div>
      <div>
        <div class="auth-logo-text">OrderLink</div>
        <div class="auth-desc">Kod təsdiqi</div>
      </div>
    </div>
    <section class="otp-card">
      <header>
        <h1 class="auth-title">Nömrəni təsdiqlə (RU: Подтвердите / EN: Verify)</h1>
        <p class="auth-desc">Kod göndərildi: +994 XX XXX XX XX</p>
      </header>
      <div class="otp-grid">
        <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]" autocomplete="one-time-code" />
        <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]" autocomplete="one-time-code" />
        <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]" autocomplete="one-time-code" />
        <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]" autocomplete="one-time-code" />
        <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]" autocomplete="one-time-code" />
        <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]" autocomplete="one-time-code" />
      </div>
      <div class="otp-meta">
        <span>Yenidən göndər: 00:45</span>
        <a href="#">Kodu yenidən göndər (RU: Отправить / EN: Resend)</a>
      </div>
      <div class="auth-actions">
        <button class="btn auth-primary">Təsdiqlə (RU: Подтвердить / EN: Confirm)</button>
        <button class="btn auth-ghost">Geri (RU: Назад / EN: Back)</button>
      </div>
    </section>
  </main>
</body>
</html>