<?php
require_once __DIR__ . '/../services/ProductService.php';
$config = require __DIR__ . '/../config/config.php';
$baseUrl = rtrim($config['app']['base_url'] ?? '', '/');
$productService = new ProductService();
$userId = (int)($_SESSION['user_id'] ?? 0);
$products = $userId > 0 ? $productService->getProductsWithImages($userId) : [];
$placeholderImg = 'data:image/svg+xml;utf8,' . rawurlencode("<svg xmlns='http://www.w3.org/2000/svg' width='640' height='480'><rect width='100%' height='100%' fill='%23f1f5f9'/><text x='50%' y='50%' dominant-baseline='middle' text-anchor='middle' fill='%230a0a0a' font-family='Inter,Segoe UI,sans-serif' font-size='28'>No Image</text></svg>");

$errors = $_SESSION['errors'] ?? [];
$success = $_SESSION['success'] ?? '';
$old = $_SESSION['old'] ?? [];
unset($_SESSION['errors'], $_SESSION['success'], $_SESSION['old']);
?>

<section id="products" class="page">
  <div class="page-head">
    <div>
      <div class="eyebrow"><?php echo $t('products.eyebrow'); ?></div>
      <h1><?php echo $t('products.title'); ?></h1>
    </div>
    <div class="btn-row">
      <button class="btn ghost"><?php echo $t('products.monitor'); ?></button>
      <button class="btn primary" id="openAddProduct">Məhsul əlavə et</button>
    </div>
  </div>

  <?php if (!empty($errors['general'])): ?>
    <div class="card" style="border:1px solid #ef4444;color:#ef4444;font-weight:700;">
      <?php echo $errors['general']; ?>
    </div>
  <?php endif; ?>
  <?php if (!empty($success)): ?>
    <div class="card" style="border:1px solid #16a34a;color:#166534;font-weight:700;">
      <?php echo htmlentities($success, ENT_QUOTES, 'UTF-8'); ?>
    </div>
  <?php endif; ?>

  <div class="toolbar">
    <div class="search">
      <span class="icon">⌕</span>
      <input type="search" placeholder="<?php echo $t('search_placeholder'); ?>" />
    </div>
    <div class="btn-row">
      <button class="pill active"><?php echo $t('buttons.grid'); ?></button>
      <button class="pill" disabled><?php echo $t('buttons.table'); ?></button>
      <button class="btn ghost" disabled><?php echo $t('buttons.status'); ?></button>
    </div>
  </div>

  <div class="product-grid">
    <?php foreach ($products as $product): ?>
      <?php
        $images = $product['images'] ?? [];
        $imgPaths = array_map(function($p) use ($baseUrl) {
          $clean = ltrim($p, '/');
          // Backward compatibility for older rows without the public/ prefix
          if (strpos($clean, 'public/') !== 0) {
            $clean = 'public/' . $clean;
          }
          return $baseUrl . '/' . $clean;
        }, $images);
        $first = $imgPaths[0] ?? $placeholderImg;
        $imagesJson = htmlentities(json_encode($imgPaths), ENT_QUOTES, 'UTF-8');
      ?>
      <article class="card product-card" data-id="<?php echo $product['id']; ?>" data-name="<?php echo htmlentities($product['name'], ENT_QUOTES, 'UTF-8'); ?>" data-price="<?php echo $product['price']; ?>" data-desc="<?php echo htmlentities($product['description'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" data-status="<?php echo $product['is_active'] ? 'active' : 'inactive'; ?>" data-images='<?php echo $imagesJson; ?>'>
        <div class="product-thumb">
          <div class="product-slider" data-images='<?php echo $imagesJson; ?>'>
            <img class="product-image" src="<?php echo $first; ?>" alt="Product" />
            <div class="slider-arrows"><button class="slider-prev">‹</button><button class="slider-next">›</button></div>
            <div class="product-slider-dots"></div>
          </div>
        </div>
        <div class="product-meta">
          <div class="product-name"><?php echo htmlentities($product['name'], ENT_QUOTES, 'UTF-8'); ?></div>
          <div class="product-price"><?php echo number_format($product['price'], 2); ?> ₼</div>
        </div>
        <div class="product-desc"><?php echo htmlentities($product['description'] ?? '', ENT_QUOTES, 'UTF-8'); ?></div>
        <div class="product-status">
          <label class="toggle"><input type="checkbox" <?php echo $product['is_active'] ? 'checked' : ''; ?> disabled /><span></span></label>
          <span><?php echo $product['is_active'] ? $t('statuses.new') : $t('statuses.pending'); ?></span>
        </div>
        <div class="product-actions">
          <button class="link edit-btn"><?php echo $t('buttons.edit'); ?></button>
          <button class="link muted"><?php echo $t('buttons.delete'); ?></button>
        </div>
      </article>
    <?php endforeach; ?>

    <?php if (empty($products)): ?>
      <div class="card product-card" style="text-align:center;grid-column:1/-1;">
        <div class="product-name"><?php echo $t('products.empty'); ?></div>
      </div>
    <?php endif; ?>
  </div>

  <div class="modal overlay" id="addProductModal">
    <div class="modal sheet">
      <div class="modal-head">
        <h3><?php echo $t('products.modal_title'); ?></h3>
        <button class="icon-btn close-btn" onclick="closeAddProduct(); return false;">✕</button>
      </div>
      <form class="form-grid" id="addProductForm" action="<?php echo $baseUrl; ?>/public/products/store.php" method="POST" enctype="multipart/form-data">
        <label><?php echo $t('products.name'); ?>
          <input id="addName" name="name" type="text" placeholder="Məs: Latte" required value="<?php echo htmlentities($old['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" />
          <?php if (!empty($errors['name'])): ?><div class="error-text" style="color:#e5484d;font-size:13px;"><?php echo $errors['name']; ?></div><?php endif; ?>
        </label>
        <label><?php echo $t('products.price_azn'); ?>
          <input id="addPrice" name="price" type="number" placeholder="0" step="0.01" min="0" required value="<?php echo htmlentities($old['price'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" />
          <?php if (!empty($errors['price'])): ?><div class="error-text" style="color:#e5484d;font-size:13px;"><?php echo $errors['price']; ?></div><?php endif; ?>
        </label>
        <label><?php echo $t('products.description'); ?>
          <textarea id="addDesc" name="description" placeholder="Qısa təsvir"><?php echo htmlentities($old['description'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
        </label>
        <label><?php echo $t('products.upload'); ?>
          <div class="upload multi">
            <input id="addImages" name="images[]" type="file" accept="image/*" multiple style="display:none;" />
            <button class="btn ghost" type="button" onclick="document.getElementById('addImages').click(); return false;">Şəkil seç</button>
            <div class="thumbs" id="addThumbs"></div>
          </div>
          <?php if (!empty($errors['images'])): ?><div class="error-text" style="color:#e5484d;font-size:13px;"><?php echo $errors['images']; ?></div><?php endif; ?>
        </label>
        <label class="toggle-row"><?php echo $t('products.active'); ?>
          <label class="toggle"><input id="addActive" name="is_active" type="checkbox" value="1" <?php echo !isset($old['is_active']) || (int)($old['is_active']) === 1 ? 'checked' : ''; ?> /><span></span></label>
        </label>
        <div class="form-actions">
          <button class="btn ghost" type="button" onclick="closeAddProduct(); return false; "><?php echo $t('buttons.dismiss'); ?></button>
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
      <form class="form-grid" id="editProductForm" action="<?php echo $baseUrl; ?>/public/products/update.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="product_id" id="editProductId" />
        <label><?php echo $t('products.name'); ?>
          <input id="editName" name="name" type="text" required />
        </label>
        <label><?php echo $t('products.price_azn'); ?>
          <input id="editPrice" name="price" type="number" step="0.01" min="0" required />
        </label>
        <label><?php echo $t('products.description'); ?>
          <textarea id="editDesc" name="description"></textarea>
        </label>
        <label class="toggle-row"><?php echo $t('products.active'); ?>
          <label class="toggle"><input id="editActive" name="is_active" type="checkbox" value="1" /><span></span></label>
        </label>
        <label><?php echo $t('products.upload'); ?>
          <div class="product-thumb" style="height: 200px;">
            <div class="product-slider" id="editSlider" data-images="[]">
              <img class="product-image" src="" alt="Product" />
              <div class="slider-arrows"><button class="slider-prev">‹</button><button class="slider-next">›</button></div>
              <div class="product-slider-dots"></div>
            </div>
          </div>
          <p style="margin:8px 0 4px;font-size:13px;color:#475569;">Yeni şəkillər seçsən, köhnələr əvəzlənəcək.</p>
          <div class="upload multi">
            <input id="editImages" name="images[]" type="file" accept="image/*" multiple style="display:none;" />
            <button class="btn ghost" type="button" onclick="document.getElementById('editImages').click(); return false;">Yeni şəkil seç (optional)</button>
            <div class="thumbs" id="editThumbs"></div>
          </div>
        </label>
        <div class="form-actions">
          <button class="btn ghost" type="button" onclick="document.getElementById('editProductModal').classList.remove('active'); return false; "><?php echo $t('buttons.dismiss'); ?></button>
          <button class="btn primary" type="submit"><?php echo $t('buttons.save'); ?></button>
        </div>
      </form>
    </div>
  </div>

  <form id="deleteProductForm" action="<?php echo $baseUrl; ?>/public/products/delete.php" method="POST" style="display:none;">
    <input type="hidden" name="product_id" id="deleteProductId" />
  </form>

  <script>
    (function() {
      const addModal = document.getElementById('addProductModal');
      const openAddBtn = document.getElementById('openAddProduct');
      const addImages = document.getElementById('addImages');
      const addThumbs = document.getElementById('addThumbs');
      const addForm = document.getElementById('addProductForm');
      const searchInput = document.querySelector('#products .toolbar .search input');
      const productGrid = document.querySelector('#products .product-grid');
      const productCards = Array.from(document.querySelectorAll('#products .product-card[data-id]'));
      let addSelectedFiles = [];

      function applySearch(term) {
        const q = (term || '').toLowerCase();
        productCards.forEach((card) => {
          const name = (card.dataset.name || '').toLowerCase();
          const match = !q || name.includes(q);
          card.style.display = match ? '' : 'none';
        });

        // Recount visible after display changes (rely on offsetParent to avoid hidden elements)
        const visible = productCards.reduce((cnt, card) => card.offsetParent ? cnt + 1 : cnt, 0);

        let noRes = productGrid.querySelector('.no-results');
        if (!noRes) {
          noRes = document.createElement('div');
          noRes.className = 'card product-card no-results';
          noRes.style.gridColumn = '1 / -1';
          noRes.style.textAlign = 'center';
          noRes.textContent = 'Nəticə tapılmadı';
          productGrid.appendChild(noRes);
        }

        const shouldShow = productCards.length > 0 && visible === 0;
        noRes.style.display = shouldShow ? '' : 'none';
      }

      searchInput && searchInput.addEventListener('input', (e) => applySearch(e.target.value));
      applySearch('');

      window.closeAddProduct = function() {
        addModal.classList.remove('active');
      };

      openAddBtn && openAddBtn.addEventListener('click', (e) => {
        e.preventDefault();
        addModal.classList.add('active');
      });

      function syncInputFiles() {
        const dt = new DataTransfer();
        addSelectedFiles.forEach((f) => dt.items.add(f));
        addImages.files = dt.files;
      }

      function renderThumbs(files) {
        addThumbs.innerHTML = '';
        files.forEach((file, idx) => {
          const url = URL.createObjectURL(file);
          const box = document.createElement('div');
          box.className = 'thumb';

          const img = document.createElement('img');
          img.src = url;
          img.alt = file.name;

          const removeBtn = document.createElement('button');
          removeBtn.type = 'button';
          removeBtn.className = 'remove-thumb';
          removeBtn.textContent = '✕';
          removeBtn.addEventListener('click', () => {
            addSelectedFiles = addSelectedFiles.filter((_, i) => i !== idx);
            syncInputFiles();
            renderThumbs(addSelectedFiles);
          });

          box.appendChild(img);
          box.appendChild(removeBtn);
          addThumbs.appendChild(box);
        });
      }

      addImages && addImages.addEventListener('change', (e) => {
        addSelectedFiles = Array.from(e.target.files || []);
        renderThumbs(addSelectedFiles);
      });

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
          imgEl.src = images[current];
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
      const editActive = document.getElementById('editActive');
      const editForm = document.getElementById('editProductForm');
      const editImages = document.getElementById('editImages');
      const editThumbs = document.getElementById('editThumbs');
      const editId = document.getElementById('editProductId');
      let editSelectedFiles = [];

      function syncEditFiles() {
        const dt = new DataTransfer();
        editSelectedFiles.forEach((f) => dt.items.add(f));
        editImages.files = dt.files;
      }

      function renderEditThumbs(files) {
        editThumbs.innerHTML = '';
        files.forEach((file, idx) => {
          const url = URL.createObjectURL(file);
          const box = document.createElement('div');
          box.className = 'thumb';

          const img = document.createElement('img');
          img.src = url;
          img.alt = file.name;

          const removeBtn = document.createElement('button');
          removeBtn.type = 'button';
          removeBtn.className = 'remove-thumb';
          removeBtn.textContent = '✕';
          removeBtn.addEventListener('click', () => {
            editSelectedFiles = editSelectedFiles.filter((_, i) => i !== idx);
            syncEditFiles();
            renderEditThumbs(editSelectedFiles);
          });

          box.appendChild(img);
          box.appendChild(removeBtn);
          editThumbs.appendChild(box);
        });
      }

      editImages && editImages.addEventListener('change', (e) => {
        editSelectedFiles = Array.from(e.target.files || []);
        renderEditThumbs(editSelectedFiles);
      });

      document.querySelectorAll('.product-card .edit-btn').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
          e.preventDefault();
          const card = btn.closest('.product-card');
          if (!card) return;
          const images = card.dataset.images || '[]';
          editId.value = card.dataset.id || card.getAttribute('data-id') || card.dataset.productId || '';
          editName.value = card.dataset.name || '';
          editPrice.value = card.dataset.price || '';
          editDesc.value = card.dataset.desc || '';
          editActive.checked = (card.dataset.status || '') === 'active';
          editSlider.dataset.images = images;
          editSelectedFiles = [];
          renderEditThumbs(editSelectedFiles);
          editModal.classList.add('active');
          // re-init slider for modal
          initSlider(editSlider);
        });
      });

      const deleteForm = document.getElementById('deleteProductForm');
      const deleteInput = document.getElementById('deleteProductId');
      document.querySelectorAll('.product-card .muted').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
          e.preventDefault();
          const card = btn.closest('.product-card');
          if (!card) return;
          const id = card.dataset.id || card.getAttribute('data-id') || card.dataset.productId || '';
          if (!id) return;
          if (!confirm('Məhsulu silmək istəyirsən?')) return;
          deleteInput.value = id;
          deleteForm.submit();
        });
      });
    })();
  </script>
</section>
