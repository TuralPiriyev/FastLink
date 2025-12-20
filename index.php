<?php
// Load translations
$translations = include __DIR__ . '/translations.php';
$lang = $_GET['lang'] ?? 'az';
if (!array_key_exists($lang, $translations)) {
  $lang = 'az';
}

// Helper for translation lookup
$t = function (string $key) use ($translations, $lang) {
  $parts = explode('.', $key);
  $value = $translations[$lang] ?? [];
  foreach ($parts as $part) {
    if (is_array($value) && array_key_exists($part, $value)) {
      $value = $value[$part];
    } else {
      return $key;
    }
  }
  return $value;
};

$pages = [
  'overview' => 'overview',
  'products' => 'products',
  'orders' => 'orders',
  'delivered' => 'delivered',
  'payments' => 'payments',
  'settings' => 'settings',
];

$page = $_GET['page'] ?? 'overview';
if (!array_key_exists($page, $pages)) {
  $page = 'overview';
}

$showSearch = in_array($page, ['products', 'orders', 'delivered', 'payments'], true);
?>
<!DOCTYPE html>
<html lang="az">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>OrderLink İdarə Paneli</title>
  <link rel="stylesheet" href="styles.css" />
</head>
<body class="app bg-white text-dark">
  <aside class="sidebar">
    <div class="brand">OrderLink</div>
    <nav class="nav">
      <?php foreach ($pages as $slug => $labelKey): ?>
        <a class="nav-item <?php echo $page === $slug ? 'active' : ''; ?>" href="index.php?page=<?php echo $slug; ?>&lang=<?php echo $lang; ?>"><?php echo $t('nav.' . $labelKey); ?></a>
      <?php endforeach; ?>
    </nav>
    <div class="sidebar-footer">
      <div class="plan-pill"><?php echo $t('plan_label'); ?></div>
      <a class="help-link" href="#help"><?php echo $t('help'); ?></a>
    </div>
  </aside>

  <main class="main">
    <header class="topbar<?php echo $showSearch ? '' : ' no-search'; ?>">
      <div class="topbar-title"><?php echo $t('panel_title'); ?></div>
      <?php if ($showSearch): ?>
        <div class="search">
          <span class="icon">⌕</span>
          <input type="search" placeholder="<?php echo $t('search_placeholder'); ?>" />
        </div>
      <?php endif; ?>
      <div class="top-actions">
        <button class="icon-btn" aria-label="Bildirişlər">🔔<span class="dot"></span></button>
        <div class="pill lang-pill">
          <?php foreach (['az','ru','en'] as $code): ?>
            <a class="lang-link <?php echo $lang === $code ? 'active' : ''; ?>" href="index.php?page=<?php echo $page; ?>&lang=<?php echo $code; ?>"><?php echo strtoupper($code); ?></a>
          <?php endforeach; ?>
        </div>
        <button class="pill">👤 Admin ▾</button>
      </div>
    </header>

    <?php include __DIR__ . '/sidebar/' . $page . '.php'; ?>
  </main>
</body>
</html>
