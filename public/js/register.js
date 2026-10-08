/* ═══════════════════════════════════════
   register.js — addictech signup
═══════════════════════════════════════ */

window.addEventListener('load', function () {

  const form      = document.getElementById('signupForm');
  const firstEl   = document.getElementById('first_name');
  const lastEl    = document.getElementById('last_name');
  const emailEl   = document.getElementById('email');
  const passEl    = document.getElementById('password');
  const togglePasswordEl = document.getElementById('togglePassword');
  const confirmEl = document.getElementById('confirm_password');
  const fieldErrors = {
    first_name: document.getElementById('first_name_error'),
    last_name: document.getElementById('last_name_error'),
    email: document.getElementById('email_error'),
    password: document.getElementById('password_error'),
    confirm_password: document.getElementById('confirm_password_error')
  };

  if (!form || !firstEl || !lastEl || !emailEl || !passEl || !togglePasswordEl || !confirmEl
      || Object.values(fieldErrors).some(function (error) { return !error; })) {
    console.warn('register.js: missing elements.');
    return;
  }

  /* ── show / hide error ── */
  function block(field, msg) {
    const errorEl = fieldErrors[field];
    errorEl.textContent = msg;
    errorEl.hidden = false;
  }

  function clearMsg() {
    Object.values(fieldErrors).forEach(function (errorEl) {
      errorEl.textContent = '';
      errorEl.hidden = true;
    });
  }

  Object.keys(fieldErrors).forEach(function (field) {
    const input = document.getElementById(field);
    input.addEventListener('input', function () {
      fieldErrors[field].textContent = '';
      fieldErrors[field].hidden = true;
    });
  });

  function capitalizeFirstLetter(value) {
    const characters = Array.from(value);
    if (characters.length === 0) return value;
    return characters[0].toLocaleUpperCase() + characters.slice(1).join('');
  }

  [firstEl, lastEl].forEach(function (input) {
    input.addEventListener('input', function () {
      this.value = capitalizeFirstLetter(this.value);
    });
  });

  togglePasswordEl.addEventListener('click', function () {
    const isVisible = passEl.type === 'text';
    passEl.type = isVisible ? 'password' : 'text';
    this.classList.toggle('is-visible', !isVisible);
    this.setAttribute('aria-label', isVisible ? 'Show password' : 'Hide password');
  });

  /* ── known domains ── */
  const VALID_DOMAINS = [
    'gmail.com',
    'yahoo.com',
    'yahoo.com.ph',
    'outlook.com',
    'hotmail.com',
    'icloud.com',
    'live.com'
  ];

  function isDomainValid(domain) {
    return VALID_DOMAINS.includes(domain.toLowerCase());
  }

  /* ── password hint live feedback ── */
  passEl.addEventListener('input', function () {
    const hint = document.querySelector('.field-hint');
    if (!hint) return;
    const n = this.value.length;
    if (n === 0)    { hint.style.color = '#888';    hint.textContent = 'Minimum 6 characters'; }
    else if (n < 6) { hint.style.color = '#c0392b'; hint.textContent = 'Too short — ' + n + '/6'; }
    else            { hint.style.color = '#4caf7d'; hint.textContent = '✓ ' + n + ' characters'; }
  });

  /* ─────────────────────────────────────
     SUBMIT HANDLER
  ───────────────────────────────────── */
  form.addEventListener('submit', function (e) {

    e.preventDefault(); /* always block first */
    clearMsg();

    firstEl.value = capitalizeFirstLetter(firstEl.value.trim());
    lastEl.value = capitalizeFirstLetter(lastEl.value.trim());
    const email   = emailEl.value.trim();
    const pass    = passEl.value;
    const confirm = confirmEl.value;

    /* 1 — names required */
    if (!firstEl.value) {
      block('first_name', 'First name is required.');
      firstEl.focus();
      return;
    }

    if (!lastEl.value) {
      block('last_name', 'Last name is required.');
      lastEl.focus();
      return;
    }

    /* 2 — email required */
    if (!email) {
      block('email', 'Email address is required.');
      emailEl.focus();
      return;
    }

    /* 2 — must contain @ and a dot after it */
    const emailParts = email.split('@');
    if (emailParts.length !== 2 || !emailParts[1].includes('.')) {
      block('email', 'Please enter a valid email address.');
      emailEl.focus();
      return;
    }

    /* 3 — domain must be a known provider */
    const domain = emailParts[1].toLowerCase();
    if (!isDomainValid(domain)) {
      block('email', 'Invalid email domain "' + domain + '". Please use a valid provider (e.g. gmail.com, yahoo.com, outlook.com).');
      emailEl.focus();
      return;
    }

    /* 4 — password required */
    if (!pass) {
      block('password', 'Password is required.');
      passEl.focus();
      return;
    }

    /* 5 — minimum 6 characters */
    if (pass.length < 6) {
      block('password', 'Password must be at least 6 characters. You entered ' + pass.length + '.');
      passEl.focus();
      return;
    }

    /* 6 — passwords match */
    if (pass !== confirm) {
      block('confirm_password', 'Passwords do not match.');
      confirmEl.focus();
      return;
    }

    /* ✅ all checks passed */
    form.submit();
  });

});