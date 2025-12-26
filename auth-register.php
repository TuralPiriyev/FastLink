<!DOCTYPE html>
<html lang="az">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <title>OrderLink - Qeydiyyat</title>
  <link rel = "stylesheet" href = "CSS/auth-register.css" />
</head>
<body>
  <main class="auth-shell">
    <div class="mobile-banner">
      <div class="auth-logo-mark"></div>
      <div>
        <div class="auth-logo-text">OrderLink</div>
        <div class="auth-desc">Biznesinizi onlayn idarə edin (RU/EN)</div>
      </div>
    </div>
    <section class="auth-card">
      <aside class="auth-brand">
        <div class="auth-logo">
          <div class="auth-logo-mark"></div>
          <div class="auth-logo-text">OrderLink</div>
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
        <div class="form-grid-auth">
          <div class="section-block">
            <h3 class="section-title">Sahibkar məlumatları (RU/EN)</h3>
            <div class="field">
              <label>Ad</label>
              <input type="text" placeholder="Ad" autocomplete="given-name" />
            </div>
            <div class="field">
              <label>Soyad</label>
              <input type="text" placeholder="Soyad" autocomplete="family-name" />
            </div>
            <div class="field">
              <label>Telefon</label>
              <input type="tel" placeholder="+994 XX XXX XX XX" autocomplete="tel" />
              <div class="helper">Format: +994 XX XXX XX XX</div>
            </div>
            <div class="field">
              <label>Email (opsional)</label>
              <input type="email" placeholder="name@mail.com" autocomplete="email" />
            </div>
            <div class="field">
              <label>Şifrə</label>
              <input type="password" placeholder="Minimum 8 simvol" autocomplete="new-password" />
              <div class="helper">Minimum 8 simvol, hərf və rəqəm</div>
            </div>
            <div class="field">
              <label>Şifrəni təsdiqlə</label>
              <input type="password" placeholder="Şifrəni təkrarla" autocomplete="new-password" />
            </div>
          </div>
          
          <div class="section-block">
            <h3 class="section-title">Biznes məlumatları (RU/EN)</h3>
            <div class="field">
              <label>Biznes adı</label>
              <input type="text" placeholder="OrderLink Cafe" />
            </div>
            <div class="field">
              <label>Şəhər</label>
              <input type="text" placeholder="Bakı" autocomplete="address-level2" />
            </div>
            <div class="field">
              <label>Lokasiya</label>
              <textarea placeholder="Metro yaxınlığı"></textarea>
            </div>
          </div>
          
          <div class="section-block">
            <h3 class="section-title">Paket seçimi (RU/EN)</h3>
            <div class="package-grid">
              <label class="package-card">
                <input type="radio" name="plan" checked />
                <div class="package-main">
                  <div class="pkg-name">Kiçik</div>
                  <div class="pkg-price">30 AZN/ay</div>
                  <div class="pkg-cap">Maksimum 20 məhsul</div>
                </div>
                <span class="chip">Seçildi</span>
              </label>
              <label class="package-card">
                <input type="radio" name="plan" />
                <div class="package-main">
                  <div class="pkg-name">Orta</div>
                  <div class="pkg-price">60 AZN/ay</div>
                  <div class="pkg-cap">Maksimum 60 məhsul</div>
                </div>
                <span class="chip">Seçildi</span>
              </label>
              <label class="package-card">
                <input type="radio" name="plan" />
                <div class="package-main">
                  <div class="pkg-name">Böyük</div>
                  <div class="pkg-price">100 AZN/ay</div>
                  <div class="pkg-cap">Limitsiz məhsul</div>
                </div>
                <span class="chip">Seçildi</span>
              </label>
            </div>
          </div>
          
          <div class="section-block">
            <div class="terms-row">
              <input type="checkbox" id="terms" />
              <label for="terms">"Şərtlər və Məxfilik" ilə razıyam (RU: Согласен / EN: I agree)</label>
            </div>
          </div>
          
          <div class="auth-actions">
            <button class="btn auth-primary" type="button">Hesab yarat (RU: Создать / EN: Create account)</button>
          </div>
          
          <div class="auth-footer">
            Artıq hesabınız var? <a href="auth-login.php">Giriş (RU: Вход / EN: Login)</a>
          </div>
        </div>
      </div>
    </section>
  </main>
</body>
</html>