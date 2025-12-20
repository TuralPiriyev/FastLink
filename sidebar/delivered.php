<section id="delivered" class="page">
  <div class="page-head">
    <div>
      <div class="eyebrow"><?php echo $t('delivered.eyebrow'); ?></div>
      <h1><?php echo $t('delivered.title'); ?></h1>
    </div>
    <div class="btn-row">
      <button class="btn ghost">CSV</button>
      <button class="btn primary"><?php echo $t('buttons.export'); ?></button>
    </div>
  </div>
  <div class="toolbar">
    <div class="search">
      <span class="icon">⌕</span>
      <input type="search" placeholder="<?php echo $t('search_placeholder'); ?>" />
    </div>
    <div class="btn-row">
      <button class="btn ghost"><?php echo $t('buttons.date_range'); ?></button>
      <button class="btn ghost"><?php echo $t('buttons.filter'); ?></button>
    </div>
  </div>
  <div class="card panel">
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th><?php echo $t('table_headers.order_id'); ?></th>
            <th><?php echo $t('table_headers.customer'); ?></th>
            <th><?php echo $t('table_headers.zone'); ?></th>
            <th><?php echo $t('table_headers.delivered_at'); ?></th>
            <th><?php echo $t('table_headers.amount'); ?></th>
            <th><?php echo $t('table_headers.payment'); ?></th>
            <th><?php echo $t('table_headers.notes'); ?></th>
            <th><?php echo $t('table_headers.actions'); ?></th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>#OL-2201</td>
            <td>Tunar H.</td>
            <td>Sumqayıt</td>
            <td>21.12, 15:10</td>
            <td>52 ₼</td>
            <td><span class="badge dark"><?php echo $t('statuses.paid'); ?></span></td>
            <td>—</td>
            <td><button class="link"><?php echo $t('payments.receipt'); ?></button></td>
          </tr>
          <tr class="skeleton-row">
            <td colspan="8"><div class="skeleton-line"></div></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</section>
