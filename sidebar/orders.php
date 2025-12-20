<section id="orders" class="page">
  <div class="page-head">
    <div>
      <div class="eyebrow"><?php echo $t('orders.eyebrow'); ?></div>
      <h1><?php echo $t('orders.title'); ?></h1>
    </div>
    <div class="btn-row">
      <button class="btn ghost"><?php echo $t('buttons.filter'); ?></button>
      <button class="btn secondary">CSV</button>
    </div>
  </div>
  <div class="tabs">
    <button class="tab active"><?php echo $t('orders.tabs.new'); ?> (12)</button>
    <button class="tab"><?php echo $t('orders.tabs.preparing'); ?> (6)</button>
    <button class="tab"><?php echo $t('orders.tabs.delivered'); ?> (30)</button>
    <button class="tab"><?php echo $t('orders.tabs.cancelled'); ?> (2)</button>
  </div>
  <div class="toolbar">
    <div class="search">
      <span class="icon">⌕</span>
      <input type="search" placeholder="<?php echo $t('search_placeholder'); ?>" />
    </div>
    <div class="btn-row">
      <button class="btn ghost"><?php echo $t('buttons.date_range'); ?></button>
      <button class="btn ghost"><?php echo $t('buttons.zone'); ?></button>
    </div>
  </div>
  <div class="card panel">
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th><?php echo $t('table_headers.order_id'); ?></th>
            <th><?php echo $t('table_headers.customer'); ?></th>
            <th><?php echo $t('table_headers.phone1'); ?></th>
            <th><?php echo $t('table_headers.phone2'); ?></th>
            <th><?php echo $t('table_headers.zone'); ?></th>
            <th><?php echo $t('table_headers.time'); ?></th>
            <th><?php echo $t('table_headers.amount'); ?></th>
            <th><?php echo $t('table_headers.payment'); ?></th>
            <th><?php echo $t('table_headers.status'); ?></th>
            <th><?php echo $t('table_headers.actions'); ?></th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>#OL-2350</td>
            <td>Lalə S.</td>
            <td>+994 55 222 33 44</td>
            <td>+994 50 333 44 55</td>
            <td>Yasamal</td>
            <td>10:00-11:00</td>
            <td>72 ₼</td>
            <td><span class="badge outline yellow"><?php echo $t('statuses.pending'); ?></span></td>
            <td><span class="badge outline yellow"><?php echo $t('statuses.new'); ?></span></td>
            <td>
              <button class="btn tiny primary"><?php echo $t('buttons.accept'); ?></button>
            </td>
          </tr>
          <tr class="hoverable">
            <td>#OL-2348</td>
            <td>Rəhman A.</td>
            <td>+994 55 444 55 66</td>
            <td>—</td>
            <td>Nizami</td>
            <td>12:00-13:00</td>
            <td>38 ₼</td>
            <td><span class="badge dark"><?php echo $t('statuses.paid'); ?></span></td>
            <td><span class="badge dark"><?php echo $t('statuses.preparing'); ?></span></td>
            <td><button class="btn tiny ghost"><?php echo $t('buttons.view'); ?></button></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <aside class="drawer">
    <div class="drawer-head">
      <div>
        <div class="eyebrow"><?php echo $t('table_headers.order_id'); ?> #OL-2348</div>
        <h3>Rəhman A.</h3>
      </div>
      <div class="drawer-head-actions">
        <button class="icon-btn close-btn" aria-label="<?php echo $t('orders.drawer.close'); ?>">✕</button>
        <span class="badge dark"><?php echo $t('statuses.preparing'); ?></span>
      </div>
    </div>
    <div class="drawer-section">
      <div class="label"><?php echo $t('orders.drawer.customer'); ?></div>
      <div class="value">+994 55 444 55 66 / —</div>
    </div>
    <div class="drawer-section">
      <div class="label"><?php echo $t('orders.drawer.address'); ?></div>
      <div class="value">Nizami, metro yaxınlığı</div>
    </div>
    <div class="drawer-section">
      <div class="label"><?php echo $t('orders.drawer.time_window'); ?></div>
      <div class="value">12:00-13:00</div>
    </div>
    <div class="drawer-section">
      <div class="label"><?php echo $t('orders.drawer.notes'); ?></div>
      <div class="value">Şəkərsiz olsun</div>
    </div>
    <div class="drawer-section">
      <div class="label"><?php echo $t('orders.drawer.payment'); ?></div>
      <div class="value"><?php echo $t('statuses.paid'); ?> (kart)</div>
    </div>
    <div class="timeline">
      <?php foreach ($t('orders.drawer.timeline') as $i => $step): ?>
        <div class="step <?php echo $i < 2 ? 'done' : ($i === 2 ? 'current' : ''); ?>"><?php echo $step; ?></div>
      <?php endforeach; ?>
    </div>
    <div class="drawer-actions">
      <button class="btn primary wfull"><?php echo $t('buttons.mark_preparing'); ?></button>
      <button class="btn secondary wfull"><?php echo $t('buttons.mark_delivered'); ?></button>
      <button class="btn ghost wfull"><?php echo $t('buttons.cancel'); ?></button>
    </div>
  </aside>
</section>
