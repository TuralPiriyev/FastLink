<?php
require_once __DIR__ . '/../../services/UserService.php';
require_once __DIR__ . '/../../services/ProductService.php';

$config = require __DIR__ . '/../../config/config.php';
$baseUrl = rtrim($config['app']['base_url'] ?? '', '/');

$userService = new UserService();
$productService = new ProductService();

$slugify = function (string $value): string {
    $value = strtolower($value);
    $value = preg_replace('/[^a-z0-9]+/i', '-', $value);
    $value = trim($value ?? '', '-');
    return $value;
};

function detectSlugFromPath(): string {
    $path = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: '';
    $parts = array_values(array_filter(explode('/', $path)));
    $slug = $_GET['slug'] ?? $_GET['b'] ?? '';
    if (!empty($slug)) {
        return $slug;
    }
    $shopIndex = array_search('shop', $parts, true);
    if ($shopIndex !== false && $shopIndex > 0) {
        return $parts[$shopIndex - 1];
    }
    if (!empty($parts)) {
        return end($parts);
    }
    return '';
}

$requestedSlug = $slugify(detectSlugFromPath());
$user = $requestedSlug !== '' ? $userService->findUserByBusinessSlug($requestedSlug) : null;

if (!$user) {
    http_response_code(404);
}

$products = $user ? $productService->getProductsWithImages((int)$user['id']) : [];
$products = array_values(array_filter($products, fn($p) => !empty($p['is_active'])));

$shopCss = $baseUrl . '/CSS/shop.css';
$shopCssPath = __DIR__ . '/../../CSS/shop.css';
$cssVersion = file_exists($shopCssPath) ? filemtime($shopCssPath) : null;

$esc = fn($v) => htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
$imageUrl = function (?string $path) use ($baseUrl): string {
    if (empty($path)) {
        return $baseUrl . '/public/placeholder-product.svg';
    }
    $clean = ltrim($path, '/');
    return $baseUrl . '/' . $clean;
};

$logoLetter = $user ? strtoupper(substr(trim((string)($user['business_name'] ?? 'O')), 0, 1)) : 'O';

$jsProducts = array_map(function ($p) use ($imageUrl) {
    $img = $p['images'][0] ?? null;
    return [
        'id' => (int)$p['id'],
        'name' => $p['name'],
        'price' => (float)$p['price'],
        'description' => $p['description'] ?? '',
        'image' => $imageUrl($img),
        'in_stock' => true,
    ];
}, $products);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $user ? $esc($user['business_name']) . ' | Shop' : 'Shop'; ?></title>
  <link rel="stylesheet" href="<?php echo $shopCss . ($cssVersion ? ('?v=' . $cssVersion) : ''); ?>">
</head>
<body>
  <div class="shop-shell">
    <header class="shop-header">
      <div class="brand-block">
        <div class="logo-circle"><?php echo $esc($logoLetter); ?></div>
        <div>
          <div class="biz-name"><?php echo $user ? $esc($user['business_name']) : 'Store not found'; ?></div>
          <div class="biz-meta"><?php echo $user ? $esc($user['city'] ?? '') : ''; ?><?php if ($user && !empty($user['location'])): ?> • <?php echo $esc($user['location']); ?><?php endif; ?></div>
        </div>
      </div>
      <div class="header-actions">
        <span class="pill secure">Secure checkout</span>
        <button class="icon-btn cart" id="cartButton" aria-label="Cart">
          <span class="icon">🛒</span>
          <span class="badge" id="cartCount">0</span>
        </button>
      </div>
    </header>

    <?php if (!$user): ?>
      <section class="empty">
        <h2>Shop unavailable</h2>
        <p>Please check the link or contact the business owner.</p>
      </section>
    <?php else: ?>
      <main class="shop-layout">
        <aside class="filters" id="filterPanel">
          <div class="filter-head">
            <div>
              <div class="eyebrow">Filters</div>
              <h2>Find your product</h2>
            </div>
            <button class="icon-btn close" id="closeFilters" aria-label="Close filters">✕</button>
          </div>
          <div class="filter-card">
            <div class="filter-title">Categories</div>
            <label class="check"><input type="checkbox" checked> <span>All</span></label>
            <label class="check"><input type="checkbox"> <span>Food</span></label>
            <label class="check"><input type="checkbox"> <span>Drinks</span></label>
            <label class="check"><input type="checkbox"> <span>Other</span></label>
          </div>
          <div class="filter-card">
            <div class="filter-title">Price range</div>
            <div class="range-row">
              <span>0</span>
              <input type="range" min="0" max="500" value="250">
              <span>500 AZN</span>
            </div>
          </div>
          <div class="filter-card">
            <div class="filter-title">Availability</div>
            <label class="check"><input type="checkbox" checked> <span>In stock</span></label>
          </div>
          <button class="btn ghost" id="clearFilters" type="button">Clear filters</button>
        </aside>

        <section class="catalog">
          <div class="catalog-head">
            <div>
              <div class="eyebrow">Products</div>
              <h2>Shop our menu</h2>
            </div>
            <div class="catalog-actions">
              <button class="btn ghost lg-hidden" id="openFilters" type="button">Filters</button>
              <div class="muted small" id="productCount"><?php echo count($products); ?> items</div>
            </div>
          </div>

          <?php if (empty($products)): ?>
            <section class="empty">
              <h2>No products yet</h2>
              <p>This store has not added products. Please check back later.</p>
            </section>
          <?php else: ?>
            <div class="product-grid">
              <?php foreach ($products as $product): ?>
                <?php $img = $product['images'][0] ?? null; ?>
                <article class="product-card" data-product-id="<?php echo (int)$product['id']; ?>">
                  <div class="thumb" style="background-image: url('<?php echo $esc($imageUrl($img)); ?>');"></div>
                  <div class="product-body">
                    <h3><?php echo $esc($product['name']); ?></h3>
                    <p class="price"><?php echo number_format((float)$product['price'], 2); ?> AZN</p>
                    <?php if (!empty($product['description'])): ?>
                      <p class="desc"><?php echo $esc($product['description']); ?></p>
                    <?php endif; ?>
                    <div class="card-actions">
                      <button class="btn primary add-cart" data-product-id="<?php echo (int)$product['id']; ?>">Add to cart</button>
                      <button class="btn ghost view-details" data-product-id="<?php echo (int)$product['id']; ?>">Details</button>
                    </div>
                  </div>
                </article>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </section>
      </main>
    <?php endif; ?>
  </div>

  <div class="overlay" id="productModal">
    <div class="modal">
      <button class="icon-btn close" id="closeModal">✕</button>
      <div class="modal-body">
        <div class="modal-media">
          <div class="modal-image" id="modalImage"></div>
          <div class="thumb-row" id="modalThumbs"></div>
        </div>
        <div class="modal-info">
          <div class="eyebrow">Product</div>
          <h3 id="modalTitle"></h3>
          <p class="price" id="modalPrice"></p>
          <p class="availability">In stock</p>
          <p class="desc" id="modalDesc"></p>
          <div class="qty-row">
            <button class="qty-btn" data-qty="-1">-</button>
            <input type="number" id="modalQty" value="1" min="1">
            <button class="qty-btn" data-qty="1">+</button>
          </div>
          <div class="modal-actions">
            <button class="btn primary" id="modalAdd">Add to cart</button>
            <button class="btn ghost" id="modalAddView">Add & view cart</button>
          </div>
          <div class="related" id="relatedBlock" hidden>
            <div class="eyebrow">Related</div>
            <div class="related-row" id="relatedRow"></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="overlay" id="cartOverlay"></div>
  <aside class="cart-drawer" id="cartDrawer">
    <div class="cart-head">
      <h3>Your cart</h3>
      <button class="icon-btn close" id="closeCart">✕</button>
    </div>
    <div class="cart-body" id="cartItems">
      <div class="cart-empty" id="cartEmpty">
        <p>Cart is empty</p>
        <small>Add items to start checkout.</small>
      </div>
    </div>
    <div class="cart-summary">
      <div class="row">
        <span>Subtotal</span>
        <strong id="cartSubtotal">0.00 AZN</strong>
      </div>
      <button class="btn primary" id="checkoutBtn">Proceed to checkout</button>
    </div>
    <div class="checkout-form" id="checkoutForm" hidden>
      <h4>Checkout</h4>
      <label>Full name<input type="text" placeholder="Ad Soyad"></label>
      <label>Phone<input type="tel" placeholder="+994..."></label>
      <label>Email<input type="email" placeholder="you@example.com"></label>
      <label>Notes (optional)<textarea rows="2" placeholder="Delivery notes"></textarea></label>
      <button class="btn primary" type="button">Confirm order</button>
    </div>
  </aside>

  <script>
    const products = <?php echo json_encode($jsProducts, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;

    const state = {
      cart: [],
      currentProduct: null,
    };

    const qs = (sel) => document.querySelector(sel);
    const qsa = (sel) => Array.from(document.querySelectorAll(sel));

    const cartDrawer = qs('#cartDrawer');
    const cartOverlay = qs('#cartOverlay');
    const cartCount = qs('#cartCount');
    const cartItemsEl = qs('#cartItems');
    const cartEmpty = qs('#cartEmpty');
    const cartSubtotal = qs('#cartSubtotal');
    const checkoutForm = qs('#checkoutForm');

    function findProduct(id) {
      return products.find(p => p.id === id);
    }

    function renderCart() {
      cartItemsEl.innerHTML = '';
      if (!state.cart.length) {
        cartItemsEl.append(cartEmpty);
        cartEmpty.hidden = false;
        cartSubtotal.textContent = '0.00 AZN';
        cartCount.textContent = '0';
        return;
      }
      cartEmpty.hidden = true;
      let subtotal = 0;
      state.cart.forEach(item => {
        const product = findProduct(item.id);
        if (!product) return;
        const line = document.createElement('div');
        line.className = 'cart-line';
        line.innerHTML = `
          <div class="cart-line-meta">
            <div class="thumb-sm" style="background-image:url('${product.image}')"></div>
            <div>
              <div class="title">${product.name}</div>
              <div class="muted small">${product.price.toFixed(2)} AZN</div>
            </div>
          </div>
          <div class="cart-line-actions">
            <div class="qty-inline" data-id="${product.id}">
              <button class="qty-btn" data-qty="-1">-</button>
              <input type="number" value="${item.qty}" min="1">
              <button class="qty-btn" data-qty="1">+</button>
            </div>
            <button class="icon-btn ghost" data-remove="${product.id}" aria-label="Remove">✕</button>
          </div>`;
        cartItemsEl.append(line);
        subtotal += product.price * item.qty;
      });
      cartSubtotal.textContent = `${subtotal.toFixed(2)} AZN`;
      cartCount.textContent = String(state.cart.reduce((s, i) => s + i.qty, 0));
    }

    function addToCart(id, qty = 1) {
      const product = findProduct(id);
      if (!product) return;
      const existing = state.cart.find(i => i.id === id);
      if (existing) {
        existing.qty += qty;
      } else {
        state.cart.push({ id, qty });
      }
      renderCart();
    }

    function openCart() {
      cartDrawer.classList.add('open');
      cartOverlay.classList.add('active');
    }
    function closeCart() {
      cartDrawer.classList.remove('open');
      cartOverlay.classList.remove('active');
      checkoutForm.hidden = true;
    }

    function openModal(product) {
      state.currentProduct = product;
      qs('#modalTitle').textContent = product.name;
      qs('#modalPrice').textContent = `${product.price.toFixed(2)} AZN`;
      qs('#modalDesc').textContent = product.description || '';
      qs('#modalImage').style.backgroundImage = `url('${product.image}')`;
      qs('#modalQty').value = 1;

      const relatedRow = qs('#relatedRow');
      relatedRow.innerHTML = '';
      const others = products.filter(p => p.id !== product.id).slice(0, 3);
      if (others.length) {
        qs('#relatedBlock').hidden = false;
        others.forEach(r => {
          const span = document.createElement('button');
          span.className = 'related-pill';
          span.textContent = r.name;
          span.onclick = () => openModal(r);
          relatedRow.append(span);
        });
      } else {
        qs('#relatedBlock').hidden = true;
      }

      qs('#productModal').classList.add('active');
    }

    function closeModal() {
      qs('#productModal').classList.remove('active');
    }

    document.addEventListener('click', (e) => {
      if (e.target.closest('#closeModal') || (e.target.id === 'productModal')) closeModal();
      if (e.target.closest('#closeCart') || e.target.id === 'cartOverlay') closeCart();
      if (e.target.dataset.remove) {
        const id = Number(e.target.dataset.remove);
        state.cart = state.cart.filter(i => i.id !== id);
        renderCart();
      }
      if (e.target.closest('.qty-btn')) {
        const btn = e.target.closest('.qty-btn');
        const delta = Number(btn.dataset.qty || 0);
        const wrap = btn.closest('.qty-inline');
        if (wrap) {
          const id = Number(wrap.dataset.id);
          const item = state.cart.find(i => i.id === id);
          if (item) {
            item.qty = Math.max(1, item.qty + delta);
            renderCart();
          }
        } else {
          const modalQty = qs('#modalQty');
          modalQty.value = Math.max(1, Number(modalQty.value || 1) + delta);
        }
      }
    });

    qs('#cartButton')?.addEventListener('click', () => { openCart(); });
    qs('#checkoutBtn')?.addEventListener('click', () => { checkoutForm.hidden = false; });
    qsa('.add-cart').forEach(btn => btn.addEventListener('click', (e) => {
      e.stopPropagation();
      const id = Number(btn.dataset.productId);
      addToCart(id, 1);
      openCart();
    }));
    qsa('.view-details, .product-card').forEach(el => el.addEventListener('click', (ev) => {
      const idAttr = el.dataset.productId;
      const id = idAttr ? Number(idAttr) : Number(ev.currentTarget.dataset.productId);
      const product = findProduct(id);
      if (product) openModal(product);
    }));
    qs('#modalAdd')?.addEventListener('click', () => {
      if (!state.currentProduct) return;
      const qty = Math.max(1, Number(qs('#modalQty').value || 1));
      addToCart(state.currentProduct.id, qty);
      closeModal();
      openCart();
    });
    qs('#modalAddView')?.addEventListener('click', () => {
      if (!state.currentProduct) return;
      const qty = Math.max(1, Number(qs('#modalQty').value || 1));
      addToCart(state.currentProduct.id, qty);
      closeModal();
      openCart();
    });

    // Filters (UI only)
    const openFilters = qs('#openFilters');
    const closeFilters = qs('#closeFilters');
    const filterPanel = qs('#filterPanel');
    openFilters?.addEventListener('click', () => filterPanel.classList.add('show')); 
    closeFilters?.addEventListener('click', () => filterPanel.classList.remove('show'));
    qs('#clearFilters')?.addEventListener('click', () => {
      filterPanel.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
    });

    renderCart();
  </script>
</body>
</html>
