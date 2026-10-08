<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<?php
  $loginError = session()->getFlashdata('error');
  $wishlistLoginRequired = session()->getFlashdata('wishlist_login_required');
  $cartLoginRequired = session()->getFlashdata('cart_login_required');
?>
<head>
  <title>addictech – Login</title>
  <link rel="stylesheet" href="<?= base_url('/public/css/login.css') ?>" />
</head>

<body>

  <div class="page-title-bar">
    <h1 class="page-title">LOGIN</h1>
  </div>

  <?php if ($loginError && $loginError !== 'Please login first.') : ?>
    <div class="flash flash-error">
      <?= esc($loginError) ?>
    </div>
  <?php endif; ?>
  <?php if (session()->getFlashdata('success')) : ?>
    <div class="flash flash-success">
      <?= session()->getFlashdata('success') ?>
    </div>
  <?php endif; ?>

  <?php if ($wishlistLoginRequired || $cartLoginRequired): ?>
    <div class="login-required-notification" id="loginRequiredNotification" role="status" aria-live="polite">
      <span>Please login first to view your <?= $cartLoginRequired ? 'cart' : 'wishlist' ?>.</span>
      <button type="button" id="closeLoginRequiredNotification" aria-label="Dismiss notification">&times;</button>
    </div>
  <?php endif; ?>

  <section class="form-section">
    <form method="POST" action="<?= base_url('auth/login') ?>">

      <div class="form-card">
        <div class="field-group">
          <label for="email" class="field-label">EMAIL ADDRESS</label>
          <input id="email" name="email" type="email" class="field-input"
                 placeholder="NAME@EXAMPLE.COM"
                 value="<?= old('email') ?>" required />
        </div>

        <div class="field-group">
          <label for="password" class="field-label">PASSWORD</label>
          <input id="password" name="password" type="password" class="field-input" required />
        </div>

        <?php if (!empty($error)): ?>
          <p class="auth-error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
      </div>

      <div class="cta-section">
        <button type="submit" class="btn-signin">SIGN IN</button>
        <a href="<?= base_url('password/forget') ?>" class="link-forgot">Forgot password?</a>
        <p class="register-prompt">Don't have an account? <a href="<?= base_url('register') ?>" class="link-register">Register</a></p>
      </div>

    </form>
  </section>

  <script src="<?= base_url('/public/js/login.js') ?>"></script>
</body>

<?= $this->endSection() ?>