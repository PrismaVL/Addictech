    /* ──────────────────────────────────────
       JAVASCRIPT FUNCTIONS (mostly for UI)
    ────────────────────────────────────── */
    
    // Get PHP data for JavaScript
    // const ordersData = <?php //echo json_encode($orders); ?>;
    // const currentUser = <?php //echo json_encode($user); ?>;

    /* ──────────────────────────────────────
       TABS
    ────────────────────────────────────── */
    function switchTab(tab) {
      document.getElementById('panelHistory').classList.toggle('hidden', tab !== 'history');
      document.getElementById('panelSettings').classList.toggle('hidden', tab !== 'settings');
      document.getElementById('tabHistory').classList.toggle('active', tab === 'history');
      document.getElementById('tabSettings').classList.toggle('active', tab === 'settings');
      document.getElementById('tabHistory').setAttribute('aria-selected', String(tab === 'history'));
      document.getElementById('tabSettings').setAttribute('aria-selected', String(tab === 'settings'));
    }

    function openAccountSettings() {
      switchTab('settings');
      document.getElementById('panelSettings').scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    /* ──────────────────────────────────────
       ORDER FILTERING
    ────────────────────────────────────── */
    let orderFilter = 'all';

    function filterOrders(btn) {
      document.querySelectorAll('.order-filter').forEach(b => {
        b.classList.remove('active');
        b.setAttribute('aria-pressed', 'false');
      });
      btn.classList.add('active');
      btn.setAttribute('aria-pressed', 'true');
      orderFilter = btn.dataset.filter;
      renderOrders();
    }

    function renderOrders() {
      const searchInput = document.getElementById('orderSearch');
      if (!searchInput) return;

      const searchTerm = (searchInput.value || '').toLowerCase();
      const rows = document.querySelectorAll('.orders-row');
      let visibleCount = 0;

      rows.forEach(row => {
        const status = row.dataset.status;
        const name = row.dataset.name;
        const payment = row.dataset.payment;
        
        const matchesFilter = orderFilter === 'all' || status === orderFilter;
        const matchesSearch = name.includes(searchTerm) || payment.includes(searchTerm);
        
        if (matchesFilter && matchesSearch) {
          row.style.display = 'grid';
          visibleCount++;
        } else {
          row.style.display = 'none';
        }
      });

      // Show empty message if no rows visible
      const tbody = document.getElementById('ordersBody');
      if (visibleCount === 0 && rows.length > 0) {
        // Check if empty message already exists
        if (!document.querySelector('.orders-empty-dynamic')) {
          const emptyMsg = document.createElement('div');
          emptyMsg.className = 'orders-empty orders-empty-dynamic';
          emptyMsg.textContent = 'No orders match your filter.';
          tbody.appendChild(emptyMsg);
        }
      } else {
        const emptyMsg = document.querySelector('.orders-empty-dynamic');
        if (emptyMsg) emptyMsg.remove();
      }
    }

    /* ──────────────────────────────────────
       PROFILE EDIT TOGGLE
    ────────────────────────────────────── */
    function toggleEditProfile() {
      document.getElementById('profileView').classList.toggle('hidden');
      document.getElementById('profileEdit').classList.toggle('hidden');
    }

    function cancelEdit() {
      document.getElementById('profileView').classList.remove('hidden');
      document.getElementById('profileEdit').classList.add('hidden');
    }

    function capitalizeFirstLetter(input) {
      const value = input.value;
      const match = value.match(/^(\s*)(\S)/u);
      if (!match) return;

      const capitalized = match[1] + match[2].toLocaleUpperCase() + value.slice(match[0].length);
      if (capitalized === value) return;

      const start = input.selectionStart;
      const end = input.selectionEnd;
      const lengthChange = capitalized.length - value.length;
      input.value = capitalized;

      if (start !== null && end !== null) {
        input.setSelectionRange(
          start > match[0].length ? start + lengthChange : start,
          end > match[0].length ? end + lengthChange : end
        );
      }
    }

    document.querySelectorAll(
      '#profileEdit [name="first_name"], #profileEdit [name="last_name"], ' +
      '#profileEdit [name="city"], #profileEdit [name="country"], #profileEdit [name="address"]'
    ).forEach(input => {
      capitalizeFirstLetter(input);
      input.addEventListener('input', () => capitalizeFirstLetter(input));
    });

    const profileForm = document.querySelector('#profileEdit form');
    const countryInput = profileForm?.querySelector('[name="country"]');
    const phoneInput = profileForm?.querySelector('[name="phone"]');

    function normalizePhilippinePhone() {
      if (!phoneInput || countryInput?.value.trim().toLocaleLowerCase() !== 'philippines') return;

      let digits = phoneInput.value.replace(/\D/g, '');
      if (!digits) return;

      if (digits.startsWith('63')) digits = digits.slice(2);
      if (digits.startsWith('09')) {
        phoneInput.value = digits;
      } else if (digits.startsWith('9')) {
        phoneInput.value = `0${digits}`;
      } else if (digits.startsWith('0')) {
        phoneInput.value = `09${digits.slice(1)}`;
      } else {
        phoneInput.value = `09${digits}`;
      }
    }

    phoneInput?.addEventListener('blur', normalizePhilippinePhone);
    countryInput?.addEventListener('change', normalizePhilippinePhone);
    profileForm?.addEventListener('submit', normalizePhilippinePhone);

    /* ──────────────────────────────────────
       PASSWORD CARD
    ────────────────────────────────────── */
    document.getElementById('linkPassword')?.addEventListener('click', e => {
      e.preventDefault();
      switchTab('settings');
      document.getElementById('passwordCard').style.display = 'block';
      document.getElementById('passwordCard').scrollIntoView({ behavior: 'smooth' });
    });

    /* ──────────────────────────────────────
       TOAST
    ────────────────────────────────────── */
    function showToast(msg) {
      const t = document.getElementById('acctToast');
      t.textContent = msg;
      t.classList.add('show');
      setTimeout(() => t.classList.remove('show'), 2200);
    }

    /* ──────────────────────────────────────
       Toggle order drop
      ────────────────────────────────────── */
  function toggleDrop(index) {
    const panel   = document.getElementById('accordion-' + index);
    const chevron = document.getElementById('chevron-' + index);
  
    if (!panel) return;
  
    const isOpen = panel.classList.contains('open');
  
    // Close all open panels first
    document.querySelectorAll('.order-drop.open').forEach(p => p.classList.remove('open'));
    document.querySelectorAll('.drop-chevron.open').forEach(c => c.classList.remove('open'));
  
    // If it wasn't open, open it
    if (!isOpen) {
      panel.classList.add('open');
      chevron.classList.add('open');
    }
  }

    /* ── INIT ── */
    renderOrders();