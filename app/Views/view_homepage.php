<?= $this->extend('layout/main') ?>
<?= $this->section('head') ?>
<title>addictech – Homepage</title>
<link rel="stylesheet" href="<?= base_url('/public/css/homepage.css') ?>?v=<?= filemtime(FCPATH . 'public/css/homepage.css') ?>" />
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<!-- ═══════════════════════════════════════
     HERO
═══════════════════════════════════════ -->
<section class="hero">
  <div class="hero-img-placeholder"></div>

  <!-- scrolling brand marquee -->
  <div class="hero-marquee-wrap">
    <div class="hero-marquee">
      <span>addictech /</span><span>addictech /</span><span>addictech /</span>
      <span>addictech /</span><span>addictech /</span><span>addictech /</span>
      <span>addictech /</span><span>addictech /</span><span>addictech /</span>
      <span>addictech /</span><span>addictech /</span><span>addictech /</span>
    </div>
  </div>
 
</section>
 
<div class="home-bottom">
 
  <div class="shop-by">
    <h2 class="shop-by-heading" style="-webkit-user-select: none; user-select: none;">Shop by<br>Product Type</h2>
    <nav class="shop-by-links">
      <a href="<?= base_url('catalog?category=keyboard') ?>"  class="shop-by-link">KEYBOARDS</a>
      <a href="<?= base_url('catalog?category=mouse') ?>"     class="shop-by-link">MOUSE</a>
      <a href="<?= base_url('catalog?category=headset') ?>"   class="shop-by-link">HEADSETS</a>
      <a href="<?= base_url('catalog?category=monitor') ?>"   class="shop-by-link">MONITORS</a>
      <a href="<?= base_url('catalog?category=speaker') ?>"   class="shop-by-link">SPEAKERS</a>
      <a href="<?= base_url('catalog?category=cam') ?>"       class="shop-by-link">WEB CAMS</a>
    </nav>
  </div>
 
  <div class="promo-stack">
    <div class="promo-panel">
 
      <div class="promo-img">
        <div class="promo-img-placeholder"></div>
      </div>
 
      <div class="promo-topleft">
        <a href="<?= base_url('catalog') ?>" class="promo-shop-link">SHOP›</a>
        <p class="promo-panel-text" style="-webkit-user-select: none; user-select: none;">WE<br>THE<br>BEST</p>
      </div>
 
      <div class="promo-bottomright">
        <p class="promo-panel-text" style="-webkit-user-select: none; user-select: none;">YOU<br>THE<br>BEST</p>
      </div>
 
    </div>
  </div>
 
</div>
 
<div class="banner-4"></div>
 
<section class="bestsellers">
  <h2 class="section-heading" style="-webkit-user-select: none; user-select: none;">FEATURED PRODUCTS</h2>
  <?php $featured = $featured_products ?? []; ?>
  <?php if (!empty($featured)): ?>
  <div class="bs-carousel" role="region" aria-label="Featured products carousel">
    <div class="bs-grid">
    <?php foreach ($featured as $product): ?>
    <a href="<?= base_url('catalog') ?>" class="bs-card" data-product-id="<?= (int) $product['id'] ?>">
      <div class="bs-img">
        <?php if (!empty($product['image'])): ?>
          <img src="<?= base_url('public/images/products/' . esc($product['image'])) ?>" alt="<?= esc($product['name']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
        <?php else: ?>
          <svg width="100%" height="100%" viewBox="0 0 300 280" preserveAspectRatio="none" fill="none">
            <rect x="1" y="1" width="298" height="278" stroke="#c8c4bc" stroke-width="1.5"/>
            <line x1="1" y1="1" x2="299" y2="279" stroke="#c8c4bc" stroke-width="1.5"/>
            <line x1="299" y1="1" x2="1" y2="279" stroke="#c8c4bc" stroke-width="1.5"/>
          </svg>
        <?php endif; ?>
      </div>
      <p class="bs-name"><?= strtoupper(esc($product['name'] ?? 'PRODUCT')) ?></p>
      <p class="bs-price">₱<?= number_format($product['price'] ?? 0, 0) ?></p>
    </a>
    <?php endforeach; ?>
    </div>
  </div>
  <?php else: ?>
  <p class="bs-empty">No in-stock featured products right now.</p>
  <?php endif; ?>
 
  <div class="bs-cta">
    <a href="<?= base_url('catalog') ?>" class="btn-viewall">VIEW ALL PRODUCTS</a>
  </div>
</section>

<?php
$featured_products_json = json_encode([
    'products' => array_map(static function ($product) {
    return [
        'id'          => (int) $product['id'],
        'name'        => $product['name'],
        'price'       => (float) $product['price'],
        'description' => $product['description'] ?? '',
        'stock'       => (int) $product['stock'],
        'image'       => $product['image'] ?? '',
    ];
    }, $featured),
    'wishlistedIds' => array_map('intval', $wishlisted_ids ?? []),
], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
?>
<script type="application/json" id="featuredProductsData"><?= $featured_products_json ?></script>
<div class="featured-modal-backdrop" id="featuredModalBackdrop" aria-hidden="true" data-wishlist-url="<?= esc(base_url('wishlist/toggle')) ?>" data-image-base="<?= esc(base_url('public/images/products/')) ?>">
  <div class="featured-modal" id="featuredProductModal" role="dialog" aria-modal="true" aria-labelledby="featuredModalName">
    <button type="button" class="featured-modal-close" id="featuredModalClose" aria-label="Close product details">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
        <line x1="6" y1="6" x2="18" y2="18"/>
        <line x1="18" y1="6" x2="6" y2="18"/>
      </svg>
    </button>
    <div class="featured-modal-left">
      <div class="featured-modal-img"><img id="featuredModalImage" src="" alt=""></div>
      <p class="featured-modal-product-name" id="featuredModalName"></p>
    </div>
    <div class="featured-modal-right">
      <div class="featured-modal-desc-box">
        <p class="featured-modal-desc-title">Product Description</p>
        <p class="featured-modal-desc-text" id="featuredModalDescription"></p>
      </div>
      <p class="featured-modal-availability">AVAILABLE: <span class="featured-modal-stock">IN STOCK</span></p>
      <div class="featured-modal-action-row">
        <button type="button" class="featured-modal-share-btn" id="featuredModalShare">
          SHARE
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
            <polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/>
          </svg>
        </button>
        <button type="button" class="featured-modal-wish-btn" id="featuredModalWishlist" aria-label="Add to wishlist">
          <svg id="featuredWishIcon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
          </svg>
          <span id="featuredWishLabel">WISHLIST</span>
        </button>
      </div>
      <p class="featured-modal-price" id="featuredModalPrice"></p>
      <hr class="featured-modal-divider">
      <div class="featured-modal-qty-row">
        <span class="featured-modal-qty-label">Quantity:</span>
        <div class="featured-modal-qty-ctrl">
          <button type="button" class="featured-qty-btn" id="featuredQtyMinus" aria-label="Decrease quantity">−</button>
          <span class="featured-modal-qty-val" id="featuredQtyValue">1</span>
          <button type="button" class="featured-qty-btn" id="featuredQtyPlus" aria-label="Increase quantity">+</button>
        </div>
      </div>
      <form method="POST" id="featuredModalForm">
        <?= csrf_field() ?>
        <input type="hidden" name="product_id" id="featuredFormProductId">
        <input type="hidden" name="quantity" id="featuredFormQuantity" value="1">
        <button class="featured-modal-submit" type="submit" formaction="<?= base_url('cart/add') ?>">ADD TO CART</button>
        <button class="featured-modal-submit" type="submit" formaction="<?= base_url('orders/buynow') ?>">BUY NOW</button>
      </form>
    </div>
  </div>
</div>
<div class="featured-modal-toast" id="featuredModalToast" role="status" aria-live="polite"></div>
<div class="location-split">
 
  <!-- LEFT: location text box -->
  <div class="location-left">
    <a class="location-box" href="https://maps.app.goo.gl/jhB7F3HEBXj4ArXt9" target="_blank" rel="noopener noreferrer" style="color: #1a1a1a !important; text-decoration: none !important;">
      <p class="location-label" style="color: #1a1a1a !important; text-decoration: none !important;">Our Locations</p>
      <p class="location-address" style="color: #1a1a1a !important; text-decoration: none !important;">P. Paredes Street<br> Sampaloc,<br>Manila</p>
    </a>
  </div>
  <div class="location-right">
    <div class="location-img-placeholder"></div>
  </div>
 
</div>
<div class="contact-split" id="contact-section">
 
  <!-- LEFT: image placeholder -->
  <div class="contact-img-placeholder"></div>
 
  <!-- RIGHT: contact form -->
  <div class="contact-right">
    <h2 class="contact-heading" style="-webkit-user-select: none; user-select: none;">CONTACT US</h2>
 
    <form class="contact-form" method="POST" action="<?= base_url('contact/send') ?>">
      <?= csrf_field() ?>
 
      <div class="contact-field">
        <label class="contact-label">Full Name</label>
        <input type="text" name="full_name" class="contact-input" required/>
      </div>
      <div class="contact-field">
        <label class="contact-label">Email Address</label>
        <input type="email" name="email" class="contact-input" required/>
      </div>
      <div class="contact-field">
        <label class="contact-label">Concern</label>
        <textarea name="concern" class="contact-input contact-textarea" rows="3" required></textarea>
      </div>
 
      <button type="submit" class="btn-submit">SUBMIT</button>
    </form>
 
    <p class="contact-need">NEED ANYTHING?</p>
    <a href="https://mail.google.com/mail/?view=cm&amp;fs=1&amp;to=addictechthebest%40gmail.com" class="contact-email" target="_blank" rel="noopener noreferrer">addictechthebest@gmail.com</a>
 
    <!-- social icons -->
    <div class="contact-socials">
      <a href="#" class="contact-social" aria-label="Facebook">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
          <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>
        </svg>
      </a>
      <a href="#" class="contact-social" aria-label="Twitter">
        <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor">
          <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
        </svg>
      </a>
      <a href="#" class="contact-social" aria-label="Instagram">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
          <rect x="2" y="2" width="20" height="20" rx="5" ry="5"/>
          <circle cx="12" cy="12" r="4"/>
          <circle cx="17.5" cy="6.5" r="0.5" fill="currentColor"/>
        </svg>
      </a>
    </div>
 
    <p class="contact-handle">@addictechthebest</p>
  </div>
 
</div>

<script src="<?= base_url('/public/js/homepage.js') ?>?v=<?= filemtime(FCPATH . 'public/js/homepage.js') ?>"></script>
<?= $this->endSection() ?>