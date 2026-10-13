<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<head>
  <title>addictech – Admin Overview</title>
  <link rel="stylesheet" href="<?= base_url('/public/css/admin_dashboard.css') ?>?v=<?= filemtime(FCPATH . 'public/css/admin_dashboard.css') ?>" />
  <link rel="stylesheet" href="<?= base_url('/public/css/admin_overview.css') ?>?v=<?= filemtime(FCPATH . 'public/css/admin_overview.css') ?>" />
</head>
<body>
  <main class="admin-overview-page">
    <aside class="sidebar-nav" aria-label="Admin navigation">
      <nav>
        <ul>
          <li><a href="<?= base_url('admin/dashboard') ?>" class="active" aria-current="page">OVERVIEW</a></li>
          <li><a href="<?= base_url('admin/contacts') ?>">CONCERNS<?php if ($unreadConcerns > 0): ?><span class="nav-count"><?= esc($unreadConcerns) ?></span><?php endif; ?></a></li>
          <li><a href="<?= base_url('admin/products') ?>">PRODUCTS</a></li>
          <li><a href="<?= base_url('admin/orders') ?>">ORDERS<?php if ($pendingOrders > 0): ?><span class="nav-count"><?= esc($pendingOrders) ?></span><?php endif; ?></a></li>
          <li><a href="<?= base_url('admin/users') ?>">USERS</a></li>
          <li><a href="<?= base_url('auth/logout') ?>">LOGOUT</a></li>
        </ul>
      </nav>
    </aside>

    <section class="admin-overview-main">
      <header class="overview-header">
        <div>
          <p class="overview-eyebrow">ADDICTECH / ADMINISTRATION</p>
          <h1>Dashboard</h1>
        </div>
      </header>

      <section class="overview-stat-grid" aria-label="Store totals">
        <a class="overview-stat-card" href="<?= base_url('admin/orders') ?>">
          <span class="overview-stat-label">Total orders</span>
          <strong class="overview-stat-value"><?= number_format($totalOrders) ?></strong>
          <span class="overview-stat-note"><?= number_format($pendingOrders) ?> pending review</span>
        </a>
        <a class="overview-stat-card" href="<?= base_url('admin/products') ?>">
          <span class="overview-stat-label">Products</span>
          <strong class="overview-stat-value"><?= number_format($totalProducts) ?></strong>
          <span class="overview-stat-note">Manage your catalog</span>
        </a>
        <a class="overview-stat-card" href="<?= base_url('admin/users') ?>">
          <span class="overview-stat-label">Users</span>
          <strong class="overview-stat-value"><?= number_format($totalUsers) ?></strong>
          <span class="overview-stat-note">View registered accounts</span>
        </a>
        <a class="overview-stat-card <?= $unreadConcerns > 0 ? 'is-attention' : '' ?>" href="<?= base_url('admin/contacts') ?>">
          <span class="overview-stat-label">Unread concerns</span>
          <strong class="overview-stat-value"><?= number_format($unreadConcerns) ?></strong>
          <span class="overview-stat-note"><?= $unreadConcerns > 0 ? 'Messages waiting for a reply' : 'You are all caught up' ?></span>
        </a>
      </section>

      <section class="overview-content-grid">
        <article class="overview-panel overview-orders">
          <div class="overview-panel-heading">
            <div>
              <p class="overview-eyebrow">LATEST ACTIVITY</p>
              <h2>Recent orders</h2>
            </div>
            <a href="<?= base_url('admin/orders') ?>">All orders <span aria-hidden="true">→</span></a>
          </div>
          <?php if (!empty($recentOrders)): ?>
            <div class="overview-order-list">
              <?php foreach ($recentOrders as $order): ?>
                <a class="overview-order-row" href="<?= base_url('admin/orders/view/' . $order['id']) ?>">
                  <span class="overview-order-id">
                    <strong><?= esc($order['order_number'] ?? 'Order #' . $order['id']) ?></strong>
                    <small><?= esc($order['customer_name'] ?: 'Customer') ?></small>
                  </span>
                  <span class="overview-order-total">₱<?= number_format((float) $order['total'], 2) ?></span>
                  <span class="overview-order-status status-<?= esc(strtolower($order['status'] ?? 'pending')) ?>"><?= esc($order['status'] ?? 'pending') ?></span>
                </a>
              <?php endforeach; ?>
            </div>
          <?php else: ?>
            <p class="overview-empty">No orders yet. New orders will appear here.</p>
          <?php endif; ?>
        </article>

        <div class="overview-attention-column">
          <article class="overview-panel">
            <div class="overview-panel-heading">
              <div>
                <p class="overview-eyebrow">INVENTORY</p>
                <h2>Low stock</h2>
              </div>
              <a href="<?= base_url('admin/products') ?>">Catalog <span aria-hidden="true">→</span></a>
            </div>
            <?php if (!empty($lowStockProducts)): ?>
              <ul class="overview-compact-list">
                <?php foreach ($lowStockProducts as $product): ?>
                  <li>
                    <span><?= esc($product['name']) ?></span>
                    <strong class="<?= (int) $product['stock'] === 0 ? 'stock-empty' : '' ?>"><?= (int) $product['stock'] ?> left</strong>
                  </li>
                <?php endforeach; ?>
              </ul>
            <?php else: ?>
              <p class="overview-empty">No active products are low on stock.</p>
            <?php endif; ?>
          </article>
        </div>
      </section>
    </section>
  </main>
</body>
<?= $this->endSection() ?>
