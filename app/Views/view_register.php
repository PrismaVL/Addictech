<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<head>
  <title>addictech – Register </title>
  <link rel="stylesheet" href="<?= base_url('/public/css/register.css') ?>" />
</head>

  <?php
  $error = '';
  $success = '';
  $email = '';
  $first_name = '';
  $last_name = '';
  $registerErrors = session()->getFlashdata('register_errors') ?? [];

  ?>

  <div class="page-title-bar">
    <h1 class="page-title">SIGN UP</h1>
  </div>

  <section class="form-section">
    <form method="POST" action="<?= base_url('auth/register') ?>" id="signupForm" novalidate>
      <?= csrf_field() ?>
      <div class="form-card">

        <div class="field-group">
          <label for="first_name" class="field-label">FIRST NAME</label>
          <input id="first_name" name="first_name" type="text" class="field-input" placeholder="John" required aria-describedby="first_name_error"
                 value="<?php echo htmlspecialchars($first_name); ?>" autocomplete="given-name"/>
          <p class="field-error" id="first_name_error" <?= empty($registerErrors['first_name']) ? 'hidden' : '' ?>><?= esc($registerErrors['first_name'] ?? '') ?></p>
        </div>
        
        <div class="field-group">
          <label for="last_name" class="field-label">LAST NAME</label>
          <input id="last_name" name="last_name" type="text" class="field-input" placeholder="Doe" required aria-describedby="last_name_error"
                 value="<?php echo htmlspecialchars($last_name); ?>" autocomplete="family-name"/>
          <p class="field-error" id="last_name_error" <?= empty($registerErrors['last_name']) ? 'hidden' : '' ?>><?= esc($registerErrors['last_name'] ?? '') ?></p>
        </div>
        
        <div class="field-group">
          <label for="email" class="field-label">EMAIL ADDRESS</label>
          <!-- type="text" + novalidate: disables browser validation so JS takes full control -->
          <input id="email" name="email" type="text" class="field-input" 
                 placeholder="name@gmail.com" autocomplete="email" aria-describedby="email_error"
                 value="<?php echo htmlspecialchars($email); ?>"/>
          <p class="field-error" id="email_error" <?= empty($registerErrors['email']) ? 'hidden' : '' ?>><?= esc($registerErrors['email'] ?? '') ?></p>
        </div>
        
        <div class="field-group">
          <label for="password" class="field-label">CREATE PASSWORD</label>
          <div class="password-input-wrap">
            <input id="password" name="password" type="password" class="field-input"
                   autocomplete="new-password" aria-describedby="password_hint password_error"/>
            <button type="button" class="password-toggle" id="togglePassword" aria-label="Show password" aria-controls="password">
              <svg class="password-toggle-icon" viewBox="0 0 24 24" aria-hidden="true">
                <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12Z"/>
                <circle cx="12" cy="12" r="3"/>
                <path class="password-toggle-slash" d="m3 3 18 18"/>
              </svg>
            </button>
          </div>
          <small class="field-hint" id="password_hint">Minimum 6 characters</small>
          <p class="field-error" id="password_error" <?= empty($registerErrors['password']) ? 'hidden' : '' ?>><?= esc($registerErrors['password'] ?? '') ?></p>
        </div>
        
        <div class="field-group">
          <label for="confirm_password" class="field-label">CONFIRM PASSWORD</label>
          <input id="confirm_password" name="confirm_password" type="password" class="field-input" 
                 autocomplete="new-password" aria-describedby="confirm_password_error"/>
          <p class="field-error" id="confirm_password_error" <?= empty($registerErrors['confirm_password']) ? 'hidden' : '' ?>><?= esc($registerErrors['confirm_password'] ?? '') ?></p>
        </div>

        <?php if(session()->getFlashdata('success')): ?>
        <p style="color:green"><?= session()->getFlashdata('success') ?></p>
        <?php endif; ?>
      </div>

      <div class="cta-section">
        <button type="submit" class="btn-signup" id="signupBtn">CREATE ACCOUNT</button>
        <p class="register-prompt">
          Already have an account? <a href="<?= base_url('login')?>" class="link-register">Login</a>
        </p>
      </div>
    </form>
  </section>
  <script src="<?= base_url('/public/js/register.js') ?>"></script>

<?= $this->endSection() ?>