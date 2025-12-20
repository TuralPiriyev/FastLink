<section id="products" class="page">
  <div class="page-head">
    <div>
      <div class="eyebrow"><?php echo $t('products.eyebrow'); ?></div>
      <h1><?php echo $t('products.title'); ?></h1>
    </div>
    <div class="btn-row">
      <button class="btn ghost"><?php echo $t('products.monitor'); ?></button>
      <button class="btn primary" onclick="document.getElementById('addProductModal').classList.add('active'); return false;">Məhsul əlavə et</button>
    </div>
  </div>
  <div class="toolbar">
    <div class="search">
      <span class="icon">⌕</span>
      <input type="search" placeholder="<?php echo $t('search_placeholder'); ?>" />
    </div>
    <div class="btn-row">
      <button class="pill active"><?php echo $t('buttons.grid'); ?></button>
      <button class="pill"><?php echo $t('buttons.table'); ?></button>
      <button class="btn ghost"><?php echo $t('buttons.status'); ?></button>
    </div>
  </div>

  <div class="product-grid">
    <article class="card product-card" data-name="Latte" data-price="6" data-desc="Qısa təsvir: yüngül qəhvə ləzzəti." data-status="new" data-images='["Latte","Latte 2","Latte 3"]'>
      <div class="product-thumb">
        <div class="product-slider" data-images='["Latte","Latte 2","Latte 3"]'>
          <img class="product-image" src="" alt="Product" />
          <div class="slider-arrows"><button class="slider-prev">‹</button><button class="slider-next">›</button></div>
          <div class="product-slider-dots"></div>
        </div>
      </div>
      <div class="product-meta">
        <div class="product-name">Latte</div>
        <div class="product-price">6 ₼</div>
      </div>
      <div class="product-desc">Qısa təsvir: yüngül qəhvə ləzzəti.</div>
      <div class="product-status">
        <label class="toggle"><input type="checkbox" checked /><span></span></label>
        <span><?php echo $t('statuses.new'); ?></span>
      </div>
      <div class="product-actions">
        <button class="link edit-btn"><?php echo $t('buttons.edit'); ?></button>
        <button class="link muted"><?php echo $t('buttons.delete'); ?></button>
      </div>
    </article>

    <article class="card product-card" data-name="Espresso" data-price="5" data-desc="Daha güclü espresso." data-status="pending" data-images='["Espresso","Espresso 2"]'>
      <div class="product-thumb">
        <div class="product-slider" data-images='["Espresso","Espresso 2"]'>
          <img class="product-image" src="" alt="Product" />
          <div class="slider-arrows"><button class="slider-prev">‹</button><button class="slider-next">›</button></div>
          <div class="product-slider-dots"></div>
        </div>
      </div>
      <div class="product-meta">
        <div class="product-name">Espresso</div>
        <div class="product-price">5 ₼</div>
      </div>
      <div class="product-desc">Daha güclü espresso.</div>
      <div class="product-status">
        <label class="toggle"><input type="checkbox" /><span></span></label>
        <span><?php echo $t('statuses.pending'); ?></span>
      </div>
      <div class="product-actions">
        <button class="link edit-btn"><?php echo $t('buttons.edit'); ?></button>
        <button class="link muted"><?php echo $t('buttons.delete'); ?></button>
      </div>
    </article>
  </div>

  <div class="card panel" style="margin-top:16px;">
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th><?php echo $t('table_headers.product'); ?></th>
            <th><?php echo $t('table_headers.price'); ?></th>
            <th><?php echo $t('table_headers.status'); ?></th>
            <th><?php echo $t('table_headers.updated'); ?></th>
            <th><?php echo $t('table_headers.actions'); ?></th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Latte</td>
            <td>6 ₼</td>
            <td><label class="toggle"><input type="checkbox" checked /><span></span></label></td>
            <td>12.01</td>
            <td>
              <button class="link"><?php echo $t('buttons.edit'); ?></button>
              <button class="link muted"><?php echo $t('buttons.delete'); ?></button>
            </td>
          </tr>
          <tr class="empty">
            <td colspan="5">
              <div class="empty-row"><?php echo $t('products.empty'); ?></div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <div class="modal overlay" id="addProductModal">
    <div class="modal sheet">
      <div class="modal-head">
        <h3><?php echo $t('products.modal_title'); ?></h3>
        <button class="icon-btn close-btn" onclick="document.getElementById('addProductModal').classList.remove('active'); return false;">✕</button>
      </div>
      <form class="form-grid">
        <label><?php echo $t('products.name'); ?>
          <input type="text" placeholder="Məs: Latte" />
        </label>
        <label><?php echo $t('products.price_azn'); ?>
          <input type="number" placeholder="0" />
        </label>
        <label><?php echo $t('products.description'); ?>
          <textarea placeholder="Qısa təsvir"></textarea>
        </label>
        <label><?php echo $t('products.upload'); ?> (multi)
          <div class="upload multi">
            <div class="thumbs">
              <div class="thumb">+</div>
              <div class="thumb">+</div>
              <div class="thumb">+</div>
            </div>
          </div>
        </label>
        <label class="toggle-row"><?php echo $t('products.active'); ?>
          <label class="toggle"><input type="checkbox" checked /><span></span></label>
        </label>
        <div class="form-actions">
          <button class="btn ghost" type="button" onclick="document.getElementById('addProductModal').classList.remove('active'); return false; "><?php echo $t('buttons.dismiss'); ?></button>
          <button class="btn primary" type="submit"><?php echo $t('buttons.save'); ?></button>
        </div>
      </form>
    </div>
  </div>

  <div class="modal overlay" id="editProductModal">
    <div class="modal sheet">
      <div class="modal-head">
        <h3><?php echo $t('buttons.edit'); ?></h3>
        <button class="icon-btn close-btn" onclick="document.getElementById('editProductModal').classList.remove('active'); return false;">✕</button>
      </div>
      <form class="form-grid">
        <label><?php echo $t('products.name'); ?>
          <input id="editName" type="text" />
        </label>
        <label><?php echo $t('products.price_azn'); ?>
          <input id="editPrice" type="number" />
        </label>
        <label><?php echo $t('products.description'); ?>
          <textarea id="editDesc"></textarea>
        </label>
        <label><?php echo $t('products.upload'); ?>
          <div class="product-thumb" style="height: 200px;">
            <div class="product-slider" id="editSlider" data-images="[]">
              <img class="product-image" src="" alt="Product" />
              <div class="slider-arrows"><button class="slider-prev">‹</button><button class="slider-next">›</button></div>
              <div class="product-slider-dots"></div>
            </div>
          </div>
        </label>
        <div class="form-actions">
          <button class="btn ghost" type="button" onclick="document.getElementById('editProductModal').classList.remove('active'); return false; "><?php echo $t('buttons.dismiss'); ?></button>
          <button class="btn primary" type="submit"><?php echo $t('buttons.save'); ?></button>
        </div>
      </form>
    </div>
  </div>

  <script>
    (function() {
      function placeholder(text, idx) {
        const bg = idx % 2 === 0 ? '%23f9f4e6' : '%23f2f2f2';
        const fg = '%230a0a0a';
        const label = encodeURIComponent(text + ' #' + (idx + 1));
        return `data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' width='640' height='480'><rect width='100%' height='100%' fill='${bg}'/><text x='50%' y='50%' dominant-baseline='middle' text-anchor='middle' fill='${fg}' font-family='Inter,Segoe UI,sans-serif' font-size='32'>${label}</text></svg>`;
      }

      function initSlider(slider) {
        let images;
        try { images = JSON.parse(slider.dataset.images || '[]'); } catch(e) { images = []; }
        if (!images.length) return;

        // reset markup to avoid stacked listeners
        slider.innerHTML = "<img class=\"product-image\" src=\"\" alt=\"Product\" />" +
          "<div class=\"slider-arrows\"><button class=\"slider-prev\">‹</button><button class=\"slider-next\">›</button></div>" +
          "<div class=\"product-slider-dots\"></div>";

        const imgEl = slider.querySelector('.product-image');
        const dotsEl = slider.querySelector('.product-slider-dots');
        const prev = slider.querySelector('.slider-prev');
        const next = slider.querySelector('.slider-next');
        let current = 0;

        function renderDots() {
          dotsEl.innerHTML = '';
          images.forEach((_, idx) => {
            const dot = document.createElement('span');
            if (idx === current) dot.classList.add('active');
            dot.addEventListener('click', () => setSlide(idx));
            dotsEl.appendChild(dot);
          });
        }

        function setSlide(i) {
          current = (i + images.length) % images.length;
          imgEl.src = placeholder(images[current], current);
          Array.from(dotsEl.children).forEach((dot, idx) => {
            dot.classList.toggle('active', idx === current);
          });
        }

        prev && prev.addEventListener('click', (e) => { e.preventDefault(); setSlide(current - 1); });
        next && next.addEventListener('click', (e) => { e.preventDefault(); setSlide(current + 1); });

        renderDots();
        setSlide(0);
      }

      document.querySelectorAll('.product-slider').forEach(initSlider);

      const editModal = document.getElementById('editProductModal');
      const editName = document.getElementById('editName');
      const editPrice = document.getElementById('editPrice');
      const editDesc = document.getElementById('editDesc');
      const editSlider = document.getElementById('editSlider');

      document.querySelectorAll('.product-card .edit-btn').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
          e.preventDefault();
          const card = btn.closest('.product-card');
          if (!card) return;
          const images = card.dataset.images || '[]';
          editName.value = card.dataset.name || '';
          editPrice.value = card.dataset.price || '';
          editDesc.value = card.dataset.desc || '';
          editSlider.dataset.images = images;
          editModal.classList.add('active');
          // re-init slider for modal
          initSlider(editSlider);
        });
      });
    })();
  </script>
</section>
