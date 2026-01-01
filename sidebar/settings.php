<?php
require_once __DIR__ . '/../services/UserService.php';

$config = require __DIR__ . '/../config/config.php';
$baseUrl = rtrim($config['app']['base_url'] ?? '', '/');

$userService = new UserService();
$userId = (int)($_SESSION['user_id'] ?? 0);
$user = $userId > 0 ? $userService->findUserById($userId) : null;

$errors = $_SESSION['errors'] ?? [];
$success = $_SESSION['success'] ?? '';
$old = $_SESSION['old'] ?? [];
unset($_SESSION['errors'], $_SESSION['success'], $_SESSION['old']);

$formValues = [
  'first_name' => $old['first_name'] ?? ($user['first_name'] ?? ''),
  'last_name' => $old['last_name'] ?? ($user['last_name'] ?? ''),
  'email' => $old['email'] ?? ($user['email'] ?? ''),
  'phone' => $old['phone'] ?? ($user['phone'] ?? ''),
  'business_name' => $old['business_name'] ?? ($user['business_name'] ?? ''),
  'city' => $old['city'] ?? ($user['city'] ?? ''),
  'location' => $old['location'] ?? ($user['location'] ?? ''),
  'plan' => $old['plan'] ?? ($user['plan'] ?? ''),
];

$escape = fn ($value) => htmlentities((string)$value, ENT_QUOTES, 'UTF-8');
$displayOrDash = fn ($value) => strlen(trim((string)$value)) > 0 ? $escape($value) : '--';

$planLabels = [
  'az' => ['kicik' => 'Kiçik', 'orta' => 'Orta', 'boyuk' => 'Böyük'],
  'ru' => ['kicik' => 'Малый', 'orta' => 'Средний', 'boyuk' => 'Крупный'],
  'en' => ['kicik' => 'Small', 'orta' => 'Medium', 'boyuk' => 'Large'],
];
$planOptions = $planLabels[$lang] ?? $planLabels['az'];
$planReadable = $user ? ($planOptions[$user['plan'] ?? ''] ?? ($user['plan'] ?? $t('settings.plan_default'))) : $t('settings.plan_default');

// Build share link: https://fastlink/<business_name>/shop
$slugify = function (string $value): string {
  $value = strtolower($value);
  $value = preg_replace('/[^a-z0-9]+/i', '-', $value);
  $value = trim($value ?? '', '-');
  return $value;
};
$businessSlug = $user ? $slugify($user['business_name'] ?? '') : '';
$shareLink = $businessSlug !== '' ? "https://fastlink/{$businessSlug}/shop" : 'https://fastlink/shop';
?>

<section id="settings" class="page">
  <div class="page-head">
    <div>
      <div class="eyebrow"><?php echo $t('settings.eyebrow'); ?></div>
      <h1><?php echo $t('settings.title'); ?></h1>
    </div>
    <div class="pill status-pill"><?php echo $t('settings.profile_status'); ?></div>
  </div>

  <?php if (!empty($success)): ?>
    <div class="alert success">
      <span>&#10003;</span><div><?php echo $escape($success); ?></div>
    </div>
  <?php endif; ?>

  <?php if (!empty($errors['general'])): ?>
    <div class="alert error">
      <span>!</span><div><?php echo $escape($errors['general']); ?></div>
    </div>
  <?php endif; ?>

  <?php if (!$user): ?>
    <div class="card">
      <?php echo $t('settings.profile_missing'); ?>
    </div>
  <?php else: ?>
    <div class="settings-grid">
      <div class="card profile-card span-2">
        <div class="card-head">
          <div>
            <div class="eyebrow"><?php echo $t('settings.sections.business'); ?></div>
            <h3><?php echo $t('settings.profile.title'); ?></h3>
            <p class="muted"><?php echo $t('settings.profile.subtitle'); ?></p>
          </div>
          <button class="btn primary tiny" data-open-profile><?php echo $t('buttons.edit'); ?></button>
        </div>
        <div class="profile-grid">
          <div class="info-row">
            <div class="info-label"><?php echo $t('settings.fields.business_name'); ?></div>
            <div class="info-value"><?php echo $displayOrDash($user['business_name'] ?? ''); ?></div>
            <button class="edit-chip" data-open-profile data-focus="business_name" aria-label="Edit business name">&#9998;</button>
          </div>
          <div class="info-row">
            <div class="info-label"><?php echo $t('settings.fields.city'); ?></div>
            <div class="info-value"><?php echo $displayOrDash($user['city'] ?? ''); ?></div>
            <button class="edit-chip" data-open-profile data-focus="city" aria-label="Edit city">&#9998;</button>
          </div>
          <div class="info-row">
            <div class="info-label"><?php echo $t('settings.fields.location'); ?></div>
            <div class="info-value"><?php echo $displayOrDash($user['location'] ?? ''); ?></div>
            <button class="edit-chip" data-open-profile data-focus="location" aria-label="Edit location">&#9998;</button>
          </div>
          <div class="info-row">
            <div class="info-label"><?php echo $t('settings.fields.first_name'); ?></div>
            <div class="info-value"><?php echo $displayOrDash($user['first_name'] ?? ''); ?></div>
            <button class="edit-chip" data-open-profile data-focus="first_name" aria-label="Edit first name">&#9998;</button>
          </div>
          <div class="info-row">
            <div class="info-label"><?php echo $t('settings.fields.last_name'); ?></div>
            <div class="info-value"><?php echo $displayOrDash($user['last_name'] ?? ''); ?></div>
            <button class="edit-chip" data-open-profile data-focus="last_name" aria-label="Edit last name">&#9998;</button>
          </div>
          <div class="info-row">
            <div class="info-label"><?php echo $t('settings.fields.email'); ?></div>
            <div class="info-value"><?php echo $displayOrDash($user['email'] ?? ''); ?></div>
            <button class="edit-chip" data-open-profile data-focus="email" aria-label="Edit email">&#9998;</button>
          </div>
          <div class="info-row">
            <div class="info-label"><?php echo $t('settings.fields.phone'); ?></div>
            <div class="info-value"><?php echo $displayOrDash($user['phone'] ?? ''); ?></div>
            <button class="edit-chip" data-open-profile data-focus="phone" aria-label="Edit phone">&#9998;</button>
          </div>
          <div class="info-row">
            <div class="info-label"><?php echo $t('settings.fields.plan'); ?></div>
            <div class="info-value badge-plan"><?php echo $escape($planReadable); ?></div>
            <button class="edit-chip" data-open-profile data-focus="plan" aria-label="Edit plan" title="<?php echo $t('settings.plan_locked'); ?>">&#9998;</button>
          </div>
        </div>
      </div>

      <div class="card plan-card">
        <div class="card-head">
          <div>
            <div class="eyebrow"><?php echo $t('settings.sections.plan'); ?></div>
            <h3><?php echo $t('settings.plan_title'); ?></h3>
          </div>
          <button class="btn primary tiny"><?php echo $t('buttons.upgrade'); ?></button>
        </div>
        <div class="plan-line">
          <div>
            <div class="label"><?php echo $t('plan_label'); ?></div>
            <div class="muted"><?php echo $t('settings.plan_line'); ?></div>
          </div>
          <div class="pill soft"><?php echo $t('settings.plan_status'); ?></div>
        </div>
      </div>

      <div class="card share-card">
        <div class="card-head">
          <div>
            <div class="eyebrow"><?php echo $t('settings.sections.share'); ?></div>
            <h3><?php echo $t('settings.share_title'); ?></h3>
          </div>
          <button class="btn ghost tiny" id="copyShareBtn"><?php echo $t('buttons.copy'); ?></button>
        </div>
        <div class="share">
          <input id="shareLink" type="text" value="<?php echo $escape($shareLink); ?>" readonly />
          <div class="qr">
            <div class="qr-box"><?php echo $t('settings.qr_placeholder'); ?></div>
          </div>
        </div>
      </div>

      <div class="card language-card">
        <div class="card-head">
          <div class="eyebrow"><?php echo $t('settings.sections.language'); ?></div>
          <h3><?php echo $t('settings.language_title'); ?></h3>
        </div>
        <div class="btn-row">
          <?php foreach ($t('settings.language_options') as $opt): ?>
            <button class="pill <?php echo strtolower($opt) === $lang ? 'active' : ''; ?>" onclick="window.location='index.php?page=settings&lang=<?php echo strtolower($opt); ?>'" type="button"><?php echo $opt; ?></button>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="card zones-card">
        <div class="card-head">
          <div class="eyebrow"><?php echo $t('settings.sections.zones'); ?></div>
          <h3><?php echo $t('settings.zones_title'); ?></h3>
        </div>
        <div class="zones-list">
          <div class="zone-row header"><span><?php echo $t('settings.zones_headers')[0]; ?></span><span><?php echo $t('settings.zones_headers')[1]; ?></span><span><?php echo $t('settings.zones_headers')[2]; ?></span></div>
          <div class="zone-row"><span>Nizami</span><span>Metro</span><span>10:00-18:00</span></div>
          <div class="zone-row"><span>Yasamal</span><span>Metro</span><span>09:00-20:00</span></div>
        </div>
        <button class="btn ghost tiny" disabled><?php echo $t('buttons.add_zone') ?? 'Zona əlavə et'; ?></button>
      </div>

      <div class="card security-card span-2">
        <div class="card-head">
          <div class="eyebrow"><?php echo $t('settings.sections.security'); ?></div>
          <h3><?php echo $t('settings.security_title'); ?></h3>
        </div>
        <div class="form-grid">
          <label><?php echo $t('settings.password.old'); ?><input type="password" placeholder="••••••••" disabled /></label>
          <label><?php echo $t('settings.password.new'); ?><input type="password" placeholder="••••••••" disabled /></label>
          <label><?php echo $t('settings.password.confirm'); ?><input type="password" placeholder="••••••••" disabled /></label>
        </div>
        <p class="muted note"><?php echo $t('settings.security_note'); ?></p>
      </div>
    </div>

    <div class="modal overlay" id="profileModal">
      <div class="modal sheet">
        <div class="modal-head">
          <div>
            <div class="eyebrow"><?php echo $t('settings.profile.title'); ?></div>
            <h3><?php echo $t('settings.profile.edit_title'); ?></h3>
          </div>
          <button class="icon-btn close-btn" data-close-profile>✕</button>
        </div>
        <form class="form-grid" action="<?php echo $baseUrl; ?>/public/settings/update_profile.php" method="POST">
          <div class="form-row two">
            <label><?php echo $t('settings.fields.first_name'); ?>
              <input name="first_name" type="text" value="<?php echo $escape($formValues['first_name']); ?>" required />
              <?php if (!empty($errors['first_name'])): ?><div class="error-text"><?php echo $escape($errors['first_name']); ?></div><?php endif; ?>
            </label>
            <label><?php echo $t('settings.fields.last_name'); ?>
              <input name="last_name" type="text" value="<?php echo $escape($formValues['last_name']); ?>" required />
              <?php if (!empty($errors['last_name'])): ?><div class="error-text"><?php echo $escape($errors['last_name']); ?></div><?php endif; ?>
            </label>
          </div>
          <div class="form-row two">
            <label><?php echo $t('settings.fields.email'); ?>
              <input name="email" type="email" value="<?php echo $escape($formValues['email']); ?>" required />
              <?php if (!empty($errors['email'])): ?><div class="error-text"><?php echo $escape($errors['email']); ?></div><?php endif; ?>
            </label>
            <label><?php echo $t('settings.fields.phone'); ?>
              <input name="phone" type="tel" value="<?php echo $escape($formValues['phone']); ?>" placeholder="+994" required />
              <?php if (!empty($errors['phone'])): ?><div class="error-text"><?php echo $escape($errors['phone']); ?></div><?php endif; ?>
            </label>
          </div>
          <label><?php echo $t('settings.fields.business_name'); ?>
            <input name="business_name" type="text" value="<?php echo $escape($formValues['business_name']); ?>" required />
            <?php if (!empty($errors['business_name'])): ?><div class="error-text"><?php echo $escape($errors['business_name']); ?></div><?php endif; ?>
          </label>
          <label><?php echo $t('settings.fields.plan'); ?>
            <select name="plan" required>
              <?php foreach ($planOptions as $value => $label): ?>
                <option value="<?php echo $escape($value); ?>" <?php echo ($formValues['plan'] === $value ? 'selected' : ''); ?>><?php echo $escape($label); ?></option>
              <?php endforeach; ?>
            </select>
            <?php if (!empty($errors['plan'])): ?><div class="error-text"><?php echo $escape($errors['plan']); ?></div><?php endif; ?>
          </label>
          <div class="form-row two">
            <label><?php echo $t('settings.fields.city'); ?>
              <input name="city" type="text" value="<?php echo $escape($formValues['city']); ?>" required />
              <?php if (!empty($errors['city'])): ?><div class="error-text"><?php echo $escape($errors['city']); ?></div><?php endif; ?>
            </label>
            <label><?php echo $t('settings.fields.location'); ?>
              <input name="location" type="text" value="<?php echo $escape($formValues['location']); ?>" />
            </label>
          </div>
          <div class="form-actions">
            <button class="btn ghost" type="button" data-close-profile><?php echo $t('buttons.dismiss'); ?></button>
            <button class="btn primary" type="submit"><?php echo $t('buttons.save'); ?></button>
          </div>
        </form>
      </div>
    </div>
  <?php endif; ?>
</section>

<script>
  (function() {
    const modal = document.getElementById('profileModal');
    const openers = document.querySelectorAll('[data-open-profile]');
    const closers = document.querySelectorAll('[data-close-profile]');
    const copyBtn = document.getElementById('copyShareBtn');
    const shareInput = document.getElementById('shareLink');

    function openModal(focus) {
      if (!modal) return;
      modal.classList.add('active');
      if (focus) {
        const field = modal.querySelector('[name="' + focus + '"]');
        if (field) {
          setTimeout(() => field.focus(), 50);
        }
      }
    }

    function closeModal() {
      modal && modal.classList.remove('active');
    }

    openers.forEach((btn) => {
      btn.addEventListener('click', (e) => {
        e.preventDefault();
        openModal(btn.dataset.focus || '');
      });
    });

    closers.forEach((btn) => btn.addEventListener('click', (e) => { e.preventDefault(); closeModal(); }));

    modal && modal.addEventListener('click', (e) => {
      if (e.target === modal) closeModal();
    });

    if (copyBtn && shareInput) {
      copyBtn.addEventListener('click', (e) => {
        e.preventDefault();
        shareInput.select();
        shareInput.setSelectionRange(0, shareInput.value.length);
        try {
          document.execCommand('copy');
          copyBtn.textContent = '<?php echo $t('buttons.copy'); ?> OK';
          setTimeout(() => { copyBtn.textContent = '<?php echo $t('buttons.copy'); ?>'; }, 1600);
        } catch (err) {}
      });
    }
  })();
</script>
