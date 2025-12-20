<section id="payments" class="page">
  <div class="page-head">
    <div>
      <div class="eyebrow"><?php echo $t('payments.eyebrow'); ?></div>
      <h1><?php echo $t('payments.title'); ?></h1>
    </div>
    <div class="btn-row">
      <button class="btn ghost"><?php echo $t('buttons.filter'); ?></button>
    </div>
  </div>
  <div class="toolbar">
    <div class="search">
      <span class="icon">⌕</span>
      <input type="search" placeholder="<?php echo $t('search_placeholder'); ?>" />
    </div>
    <div class="btn-row">
      <button class="btn ghost"><?php echo $t('buttons.date_range'); ?></button>
    </div>
  </div>
  <div class="list">
    <div class="card payment">
      <div>
        <div class="label"><?php echo $t('table_headers.order_id'); ?> #OL-2201</div>
        <div class="value">52 ₼</div>
      </div>
      <div class="meta">
        <span class="badge outline yellow"><?php echo $t('demo'); ?></span>
        <span class="muted">21.12, 15:10</span>
      </div>
      <button class="btn ghost tiny"><?php echo $t('payments.receipt'); ?></button>
    </div>
    <div class="card payment">
      <div>
        <div class="label"><?php echo $t('table_headers.order_id'); ?> #OL-2202</div>
        <div class="value">18 ₼</div>
      </div>
      <div class="meta">
        <span class="badge dark"><?php echo $t('statuses.paid'); ?></span>
        <span class="muted">21.12, 16:40</span>
      </div>
      <button class="btn ghost tiny"><?php echo $t('payments.receipt'); ?></button>
    </div>
  </div>

  <div class="modal demo">
    <div class="modal-body">
      <div class="modal-head">
        <h3><?php echo $t('payments.receipt_modal.title'); ?></h3>
        <button class="icon-btn">✕</button>
      </div>
      <div class="receipt">
        <div class="row"><span><?php echo $t('payments.receipt_modal.order_id'); ?></span><span>#OL-2201</span></div>
        <div class="row"><span><?php echo $t('payments.receipt_modal.amount'); ?></span><span>52 ₼</span></div>
        <div class="row"><span><?php echo $t('payments.receipt_modal.paid'); ?></span><span>52 ₼</span></div>
        <div class="row"><span><?php echo $t('payments.receipt_modal.remaining'); ?></span><span>0 ₼</span></div>
        <div class="row"><span><?php echo $t('payments.receipt_modal.time'); ?></span><span>21.12, 15:10</span></div>
        <div class="row"><span><?php echo $t('payments.receipt_modal.type'); ?></span><span><?php echo $t('demo'); ?></span></div>
      </div>
      <div class="form-actions">
        <button class="btn ghost" type="button"><?php echo $t('buttons.print'); ?></button>
        <button class="btn primary" type="button"><?php echo $t('buttons.download'); ?></button>
      </div>
    </div>
  </div>
</section>
