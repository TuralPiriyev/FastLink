<section id="settings" class="page">
  <div class="page-head">
    <div>
      <div class="eyebrow"><?php echo $t('settings.eyebrow'); ?></div>
      <h1><?php echo $t('settings.title'); ?></h1>
    </div>
  </div>
  <div class="grid-2">
    <div class="card form-card">
      <h3><?php echo $t('settings.sections.business'); ?></h3>
      <div class="form-grid">
        <label><?php echo $t('settings.fields.business_name'); ?><input type="text" placeholder="OrderLink" /></label>
        <label><?php echo $t('settings.fields.city'); ?><input type="text" placeholder="Bakı" /></label>
        <label><?php echo $t('settings.fields.location'); ?><input type="text" placeholder="Metro yaxınlığı" /></label>
        <label><?php echo $t('settings.fields.hours'); ?><input type="text" placeholder="09:00 - 22:00" /></label>
      </div>
    </div>
    <div class="card form-card">
      <h3><?php echo $t('settings.sections.zones'); ?></h3>
      <div class="list simple">
        <div class="row"><span><?php echo $t('settings.zones_headers')[0]; ?></span><span><?php echo $t('settings.zones_headers')[1]; ?></span><span><?php echo $t('settings.zones_headers')[2]; ?></span></div>
        <div class="row"><span>Nizami</span><span>Metro</span><span>10:00-18:00</span></div>
        <div class="row"><span>Yasamal</span><span>Metro</span><span>09:00-20:00</span></div>
        <button class="btn ghost tiny"><?php echo $t('buttons.add_zone') ?? 'Zona əlavə et'; ?></button>
      </div>
    </div>
    <div class="card form-card">
      <h3><?php echo $t('settings.sections.language'); ?></h3>
      <div class="btn-row">
        <?php foreach ($t('settings.language_options') as $opt): ?>
          <button class="pill <?php echo strtolower($opt) === $lang ? 'active' : ''; ?>" onclick="window.location='index.php?page=settings&lang=<?php echo strtolower($opt); ?>'" type="button"><?php echo $opt; ?></button>
        <?php endforeach; ?>
      </div>
    </div>
    <div class="card form-card">
      <h3><?php echo $t('settings.sections.plan'); ?></h3>
      <div class="plan-line">
        <div>
          <div class="label"><?php echo $t('plan_label'); ?></div>
          <div class="muted"><?php echo $t('settings.plan_line'); ?></div>
        </div>
        <button class="btn primary tiny"><?php echo $t('buttons.upgrade'); ?></button>
      </div>
    </div>
    <div class="card form-card">
      <h3><?php echo $t('settings.sections.share'); ?></h3>
      <div class="share">
        <input type="text" value="<?php echo $t('settings.share_link'); ?>" readonly />
        <button class="btn secondary"><?php echo $t('buttons.copy'); ?></button>
      </div>
      <div class="qr">QR placeholder</div>
    </div>
    <div class="card form-card">
      <h3><?php echo $t('settings.sections.security'); ?></h3>
      <div class="form-grid">
        <label><?php echo $t('settings.password.old'); ?><input type="password" /></label>
        <label><?php echo $t('settings.password.new'); ?><input type="password" /></label>
        <label><?php echo $t('settings.password.confirm'); ?><input type="password" /></label>
      </div>
      <button class="btn primary"><?php echo $t('buttons.save'); ?></button>
    </div>
  </div>
</section>
