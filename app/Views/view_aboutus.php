<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<head>
  <title>addictech – About Us</title>
  <link rel="stylesheet" href="<?= base_url('/public/css/aboutus.css') ?>?v=<?= filemtime(FCPATH . 'public/css/aboutus.css') ?>" />
</head>
<body>

<main class="about-page">
<div class="about-container">
  <header class="about-header">
    <p class="about-eyebrow">WHO WE ARE</p>
    <h1 class="about-title">Built by a team that cares about the details.</h1>
    <p class="about-subtitle">Meet the people behind Addictech.</p>
  </header>

  <section class="website-overview" aria-labelledby="website-overview-title">
    <div class="overview-heading">
      <p class="about-eyebrow">THE WEBSITE</p>
      <h2 id="website-overview-title">Tech essentials, thoughtfully brought together.</h2>
    </div>
    <div class="overview-copy">
      <p>
        Addictech is an online store for gaming and computer peripherals. Browse
        products by category, check availability, and save favorites or add
        products to your cart as you find the right gear for your setup.
      </p>
      <ul class="category-list" aria-label="Product categories">
        <li>Keyboards</li>
        <li>Mouse</li>
        <li>Headsets</li>
        <li>Monitors</li>
        <li>Speakers</li>
        <li>Webcams</li>
      </ul>
    </div>
  </section>

  <section class="team-section" aria-label="Addictech team members">
    <?php
    $teamMembers = [
        [
            'name' => 'Luther Dean M. Sambeli',
            'role' => 'Lead Developer',
            'bio' => 'Full-stack development',
            'image' => 'lutherpic.jpg',
            'linkedin' => 'https://www.linkedin.com/in/luther-dean/?isSelfProfile=false',
        ],
        [
            'name' => 'Christian Joshua S. Molina',
            'role' => 'Frontend Developer',
            'bio' => 'Frontend design and implementation',
            'image' => 'cjpic.jpg',
            'linkedin' => 'https://www.linkedin.com/in/christian-joshua-molina-107995418/',
        ],
        [
            'name' => 'Ping-Wei H. Chen',
            'role' => 'Database / Frontend',
            'bio' => 'Database and frontend development',
            'image' => 'pingpic.png',
            'linkedin' => 'https://www.linkedin.com/in/ping-wei-chen-3960b4381/?isSelfProfile=false',
        ],
        [
            'name' => 'Jaymard T. Licas',
            'role' => 'Frontend Developer',
            'bio' => 'Frontend design and implementation',
            'image' => 'jaypic.jpg',
            'linkedin' => 'https://www.linkedin.com/in/jaymard-licas-16011b33a/?isSelfProfile=true',
        ],
    ];
    ?>
    <?php foreach ($teamMembers as $index => $member): ?>
    <article class="member-card">
      <span class="member-index" aria-hidden="true"><?= sprintf('%02d', $index + 1) ?></span>
      <div class="member-avatar">
        <?php if (is_file(FCPATH . 'public/images/' . $member['image'])): ?>
          <img
            src="<?= base_url('public/images/' . $member['image']) ?>"
            alt="<?= esc($member['name']) ?>"
            class="member-photo"
            loading="lazy"
          >
        <?php else: ?>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
            <rect x="3" y="3" width="18" height="18" rx="2"/>
            <circle cx="12" cy="10" r="3"/>
            <path d="M6 21v-1a6 6 0 0 1 12 0v1"/>
          </svg>
        <?php endif; ?>
      </div>
      <div class="member-details">
        <p class="member-role"><?= esc($member['role']) ?></p>
        <h2 class="member-name"><?= esc($member['name']) ?></h2>
        <p class="member-bio"><?= esc($member['bio']) ?></p>
      </div>
      <a
        class="member-card-mark"
        href="<?= esc($member['linkedin']) ?>"
        target="_blank"
        rel="noopener noreferrer"
        aria-label="Visit <?= esc($member['name']) ?>'s LinkedIn profile (opens in a new tab)"
      >↗</a>
    </article>
    <?php endforeach; ?>
  </section>

  <section class="portfolio-credit" aria-label="Portfolio credit">
    <div class="credit-header">
      <div class="credit-rule"></div>
      <div>
        <p class="credit-label">Portfolio credit</p>
        <h2 class="credit-title">Improved by <span>Jaymard T. Licas</span></h2>
      </div>
      <p class="credit-note">Addictech interface improvements</p>
    </div>
    <p class="credit-intro">
      Recent work focused on making Addictech easier to explore, clearer to use,
      and more polished across screen sizes.
    </p>
    <ul class="improvement-list">
      <li>
        <span class="improvement-number">01</span>
        <div>
          <h3>Featured product discovery</h3>
          <p>Added a randomized, continuously looping carousel featuring in-stock products, with a smooth pause on hover or interaction.</p>
        </div>
      </li>
      <li>
        <span class="improvement-number">02</span>
        <div>
          <h3>Product details and shopping actions</h3>
          <p>Featured products open a detail modal with stock status, quantity controls, wishlist and sharing actions, plus Add to Cart and Buy Now.</p>
        </div>
      </li>
      <li>
        <span class="improvement-number">03</span>
        <div>
          <h3>Refined page interactions</h3>
          <p>Polished link hover states, product modal transitions, location and email links, and focused homepage promotional effects.</p>
        </div>
      </li>
      <li>
        <span class="improvement-number">04</span>
        <div>
          <h3>Catalog and wishlist browsing</h3>
          <p>Added clearer search, category filters, sorting, result counts, empty-result messages, randomized default product order, and responsive product layouts.</p>
        </div>
      </li>
      <li>
        <span class="improvement-number">05</span>
        <div>
          <h3>A clearer shopping cart</h3>
          <p>Refined the cart layout with item counts, per-item totals, accessible quantity controls, clearer removal actions, and a more visible order summary.</p>
        </div>
      </li>
      <li>
        <span class="improvement-number">06</span>
        <div>
          <h3>Simplified checkout form</h3>
          <p>Removed decorative asterisks from required fields while keeping required-field validation, and clearly labeled order notes as optional.</p>
        </div>
      </li>
      <li>
        <span class="improvement-number">07</span>
        <div>
          <h3>Consistent page backgrounds</h3>
          <p>Fixed background coverage on the cart and About pages so content sections flow together without distracting color breaks.</p>
        </div>
      </li>
    </ul>
  </section>

  <?php if (session()->get('isAdmin')): ?>
  <div class="upload-note">
    <p>To update team portraits, replace the corresponding image in <code>/public/images/</code>.</p>
  </div>
  <?php endif; ?>
</div>
</main>

</body>
<?= $this->endSection() ?>