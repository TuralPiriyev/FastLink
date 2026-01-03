<?php
$config = require __DIR__ . '/../config/config.php';
$baseUrl = rtrim($config['app']['base_url'] ?? '', '/');
$loginUrl = $baseUrl . '/public/auth/auth-login.php';
$registerUrl = $baseUrl . '/public/auth/auth-register.php';
$contactAction = $baseUrl . '/public/contact_submit.php';
?>
<!DOCTYPE html>
<html lang="az">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>FASTLINK | Biznes idarə paneli</title>
  <link rel="stylesheet" href="<?php echo $baseUrl; ?>/public/assets/css/landing.css" />
</head>
<body>
  <nav class="navbar">
    <div class="navbar-inner">
      <div class="brand">FASTLINK</div>
      <div class="nav-links" id="navLinks">
        <a href="#hero">Əsas</a>
        <a href="#features">Bu xidmət nə üçündür?</a>
        <a href="#about">Haqqımızda</a>
        <a href="#video">Video dərslik</a>
        <a href="#contact">Əlaqə</a>
      </div>
      <div class="nav-actions">
        <button class="btn ghost" onclick="window.location.href='<?php echo $loginUrl; ?>'">Giriş edin</button>
        <button class="btn primary" onclick="window.location.href='<?php echo $registerUrl; ?>'">Qeydiyyat</button>
        <button class="burger" id="burger" aria-label="Menu">☰</button>
      </div>
    </div>
  </nav>

  <section class="hero" id="hero">
    <div class="wrapper hero-grid">
      <div>
        <h1>FASTLINK ilə məhsul, satış və müştərilərinizi tək paneldən idarə edin.</h1>
        <p class="lead">Biznes sahibləri üçün hazırlanmış müasir idarə paneli: məhsul yükləmə, filter/variantlar, stok izləmə, sifariş və müştəri idarəçiliyi – hamısı bir yerdə.</p>
        <div class="cta">
          <button class="btn primary" onclick="window.location.href='<?php echo $loginUrl; ?>'">Giriş edin</button>
          <button class="btn ghost" onclick="document.getElementById('video').scrollIntoView({behavior:'smooth'});">Demo / Video</button>
        </div>
      </div>
      <div class="hero-card">
        <div class="feature-title">Niyə FASTLINK?</div>
        <div class="hero-bullets">
          <span>• Məhsullarınızı, filter və variantlarını saniyələrə əlavə edin</span>
          <span>• Satışları izləyin, hesabatları görün və komandanızla paylaşın</span>
          <span>• Müştəri məlumatlarını qoruyun və əlaqələri gücləndirin</span>
        </div>
      </div>
    </div>
  </section>

  <section class="section" id="features">
    <div class="wrapper">
      <h2>Bu xidmət nə üçündür?</h2>
      <p class="lead">FASTLINK, fiziki və onlayn satış edən bizneslərin məhsul, stok və müştəri idarəçiliyini sadələşdirir.</p>
      <div class="features-grid">
        <?php
          $features = [
            ['title' => 'Məhsul idarəetməsi', 'text' => 'Məhsulları tez yükləyin, redaktə edin, aktiv/passiv edin.'],
            ['title' => 'Filter & Variantlar', 'text' => 'Filterlər, ölçü/rəng kimi variantlarla dəqiq kataloq qurun.'],
            ['title' => 'Stok izləmə', 'text' => 'Anbar səviyyələrinə nəzarət, xəbərdarlıqlar və sürətli yeniləmə.'],
            ['title' => 'Satış & Hesabat', 'text' => 'Satış trendlərini izləyin, ödənişləri və kargonu görün.'],
            ['title' => 'Müştəri idarəçiliyi', 'text' => 'Müştəri məlumatlarını saxlayın, əlaqə və dəstəyi mərkəzləşdirin.'],
            ['title' => 'Bildirişlər', 'text' => 'Əməliyyat bildirişləri ilə komandanız xəbərdar qalsın.'],
          ];
          foreach ($features as $feat): ?>
            <div class="feature-card">
              <div class="feature-title"><?php echo $feat['title']; ?></div>
              <div class="feature-text"><?php echo $feat['text']; ?></div>
            </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="section" id="about">
    <div class="wrapper">
      <h2>Haqqımızda</h2>
      <p class="lead">FASTLINK, yerli bizneslərin rəqəmsal idarəetməsini sadələşdirmək üçün qurulub. Məqsədimiz: kiçik və orta bizneslərin vaxtına qənaət etmək, satışları şəffaf izləmək və komanda ilə işləməyi asanlaşdırmaq.</p>
    </div>
  </section>

  <section class="section" id="video">
    <div class="wrapper video-wrap">
      <div>
        <h2>Video dərslik</h2>
        <p class="lead">Qısa video ilə FASTLINK-in əsas imkanlarını izləyin.</p>
        <div class="video-frame">
          <iframe width="100%" height="100%" src="https://www.youtube.com/embed/dQw4w9WgXcQ" title="FASTLINK demo" frameborder="0" allowfullscreen></iframe>
        </div>
      </div>
      <div class="fm-card" style="gap:10px;">
        <div class="fm-title">Tutorial başlıqları</div>
        <ul class="tutorial-list">
          <li>Məhsul əlavə etmək və filterlər tətbiq etmək</li>
          <li>Sifarişləri izləmək və ödənişləri görmək</li>
          <li>Komanda və müştəri məlumatlarını idarə etmək</li>
        </ul>
      </div>
    </div>
  </section>

  <section class="section" id="contact">
    <div class="wrapper contact-grid">
      <div class="contact-card">
        <h2>Əlaqə</h2>
        <p class="lead">Bizə yazın: support@fastlink.az | +994 (00) 000 00 00</p>
        <form id="contactForm" class="contact-form" method="POST" action="<?php echo $contactAction; ?>">
          <input class="input" type="text" name="name" placeholder="Ad" required />
          <input class="input" type="email" name="email" placeholder="Email" required />
          <textarea class="input textarea" name="message" placeholder="Mesaj" required></textarea>
          <button class="btn primary" type="submit">Göndər</button>
          <div id="contactMsg" class="msg" style="min-height:18px;"></div>
        </form>
      </div>
      <div class="contact-card">
        <h2>Niyə indi?</h2>
        <p class="lead">FASTLINK ilə satış, stok və müştəri məlumatlarınızı itirmədən, komandanızla təhlükəsiz paylaşa bilərsiniz. Xidmətimiz mobil uyğun, sürətli və genişlənə biləndir.</p>
      </div>
    </div>
  </section>

  <footer class="footer">
    <div class="footer-inner">
      <div>© <?php echo date('Y'); ?> FASTLINK. Bütün hüquqlar qorunur.</div>
      <div class="footer-links">
        <a href="#about">Haqqımızda</a>
        <a href="#contact">Əlaqə</a>
        <a href="#features">Xidmət nə üçündür?</a>
      </div>
    </div>
  </footer>
  <script src="<?php echo $baseUrl; ?>/public/assets/js/landing.js"></script>
</body>
</html>
