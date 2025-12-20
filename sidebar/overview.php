<section id="overview" class="page">
  <div class="page-head">
    <div>
      <div class="eyebrow"><?php echo $t('overview.eyebrow'); ?></div>
      <h1><?php echo $t('overview.title'); ?></h1>
    </div>
    <div class="btn-row">
      <button class="btn ghost"><?php echo $t('overview.csv'); ?></button>
      <button class="btn secondary"><?php echo $t('overview.share'); ?></button>
      <button class="btn primary"><?php echo $t('overview.add_product'); ?></button>
    </div>
  </div>

  <div class="kpi-grid">
    <article class="card kpi">
      <div class="label"><?php echo $t('overview.kpi_today'); ?></div>
      <div class="value">128</div>
      <div class="trend"><?php echo $t('overview.trend_today'); ?></div>
      <div class="spark"></div>
    </article>
    <article class="card kpi">
      <div class="label"><?php echo $t('overview.kpi_week'); ?></div>
      <div class="value">864</div>
      <div class="trend"><?php echo $t('overview.trend_week'); ?></div>
      <div class="spark"></div>
    </article>
    <article class="card kpi">
      <div class="label"><?php echo $t('overview.kpi_month'); ?></div>
      <div class="value">3,420</div>
      <div class="trend"><?php echo $t('overview.trend_month'); ?></div>
      <div class="spark"></div>
    </article>
    <article class="card kpi">
      <div class="label"><?php echo $t('overview.kpi_revenue'); ?></div>
      <div class="value">48,250 ₼</div>
      <div class="trend muted"><?php echo $t('overview.trend_revenue'); ?></div>
      <div class="spark bar"></div>
    </article>
  </div>

  <div class="layout-split">
    <div class="card panel">
      <div class="panel-head">
        <div>
          <div class="eyebrow"><?php echo $t('overview.table'); ?></div>
          <h2><?php echo $t('overview.recent_title'); ?></h2>
        </div>
        <div class="btn-row">
          <button class="btn ghost"><?php echo $t('overview.filter'); ?></button>
        </div>
      </div>
      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th><?php echo $t('table_headers.order_id'); ?></th>
              <th><?php echo $t('table_headers.customer'); ?></th>
              <th><?php echo $t('table_headers.phone'); ?></th>
              <th><?php echo $t('table_headers.area'); ?></th>
              <th><?php echo $t('table_headers.time'); ?></th>
              <th><?php echo $t('table_headers.amount'); ?></th>
              <th><?php echo $t('table_headers.payment'); ?></th>
              <th><?php echo $t('table_headers.status'); ?></th>
              <th><?php echo $t('table_headers.actions'); ?></th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>#OL-2341</td>
              <td>Aylin M.</td>
              <td>+994 55 000 00 00</td>
              <td>Nərimanov</td>
              <td>12:00-13:00</td>
              <td>45 ₼</td>
              <td><span class="badge outline yellow"><?php echo $t('statuses.pending'); ?></span></td>
              <td><span class="badge outline yellow"><?php echo $t('statuses.new'); ?></span></td>
              <td><button class="link"><?php echo $t('buttons.view'); ?></button></td>
            </tr>
            <tr>
              <td>#OL-2340</td>
              <td>Orxan R.</td>
              <td>+994 50 111 22 33</td>
              <td>Xətai</td>
              <td>14:00-15:00</td>
              <td>60 ₼</td>
              <td><span class="badge dark"><?php echo $t('statuses.paid'); ?></span></td>
              <td><span class="badge dark"><?php echo $t('statuses.delivered'); ?></span></td>
              <td><button class="link"><?php echo $t('buttons.view'); ?></button></td>
            </tr>
            <tr class="skeleton-row">
              <td colspan="9">
                <div class="skeleton-line"></div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="empty-state">
        <div class="icon">⬜</div>
        <div class="title"><?php echo $t('overview.empty_title'); ?></div>
        <div class="muted"><?php echo $t('overview.empty_desc'); ?></div>
        <button class="btn ghost"><?php echo $t('overview.refresh'); ?></button>
      </div>
    </div>

    <div class="card shortcuts">
      <div class="panel-head">
        <h3><?php echo $t('overview.quick_actions'); ?></h3>
      </div>
      <button class="btn primary wfull"><?php echo $t('overview.add_product'); ?></button>
      <button class="btn secondary wfull"><?php echo $t('overview.view_orders'); ?></button>
      <button class="btn ghost wfull"><?php echo $t('overview.share'); ?></button>
      <div class="tip"><?php echo $t('overview.tip'); ?></div>
    </div>
  </div>
</section>
