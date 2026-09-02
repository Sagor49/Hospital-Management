<?php
require_once __DIR__ . '/config/db.php';

$departments = $pdo->query('SELECT id, name FROM departments ORDER BY name')->fetchAll();

$search = trim($_GET['q'] ?? '');
$deptId = $_GET['department'] ?? '';

$conditions = ["d.status = 'active'"];
$params = [];

if ($search !== '') {
    $conditions[] = 'd.name LIKE ?';
    $params[] = '%' . $search . '%';
}
if ($deptId !== '' && ctype_digit((string)$deptId)) {
    $conditions[] = 'd.department_id = ?';
    $params[] = (int)$deptId;
}

$sql = 'SELECT d.id, d.name, d.designation, d.experience_years, d.bio, d.photo,
               dept.name AS department_name
        FROM doctors d
        JOIN departments dept ON dept.id = d.department_id
        WHERE ' . implode(' AND ', $conditions) . '
        ORDER BY d.name ASC';
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$doctors = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Our Doctors | City Care Hospital</title>

<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Playfair+Display:ital@1&display=swap" rel="stylesheet">

<!-- Site-wide styles (navbar, footer, buttons, cta-banner, section utilities) -->
<link rel="stylesheet" href="css/style.css">
<!-- Doctors page only styles -->
<link rel="stylesheet" href="css/doctors-page.css">
</head>
<body>

<!-- ============ NAVBAR ============ -->
<header class="site-header" id="siteHeader">
  <nav class="navbar navbar-light bg-white">
    <div class="container">
      <a class="navbar-brand" href="index.html">
        <i class="bi bi-heart-pulse-fill"></i>City Care
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu"
              aria-controls="navMenu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="toggler-line line1"></span>
        <span class="toggler-line line2"></span>
        <span class="toggler-line line3"></span>
      </button>
      <div class="collapse navbar-collapse" id="navMenu">
        <div class="mobile-menu-card">
          <ul class="navbar-nav mx-auto mb-2 mb-lg-0 text-center">
            <li class="nav-item"><a class="nav-link" href="index.html">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="about.html">About Us</a></li>
            <li class="nav-item"><a class="nav-link" href="services.html">Services</a></li>
            <li class="nav-item"><a class="nav-link active" href="doctors.php">Doctors</a></li>
            <li class="nav-item"><a class="nav-link" href="contact.html">Contact</a></li>
          </ul>
          <div class="d-flex gap-2 justify-content-center mt-3 mt-lg-0">
            <a href="login.php" class="btn btn-login">Login</a>
            <a href="appointment.html" class="btn btn-dark-pill">
              Book Appointment <i class="bi bi-arrow-right"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
  </nav>
</header>

<!-- ============ PAGE HEADER (banner) ============ -->
<section class="page-header">
  <img src="Image/sasint-surgery-1807541.jpg" alt="Our Doctors" class="page-header-img">
  <div class="page-header-overlay"></div>
  <div class="container position-relative">
    <span class="badge-pill">OUR TEAM</span>
    <h1>Meet Our <em>Expert Doctors</em></h1>
    <nav aria-label="breadcrumb">
      <ol class="page-header-breadcrumb">
        <li><a href="index.html">Home</a></li>
        <li class="active">Doctors</li>
      </ol>
    </nav>
  </div>
</section>

<!-- ============ FILTER BAR ============ -->
<section class="doctor-filter-bar">
  <div class="container">
    <form method="get" class="row g-3 align-items-center justify-content-center">
      <div class="col-md-5">
        <input type="text" name="q" class="form-control" placeholder="Search doctor by name..."
               value="<?php echo htmlspecialchars($search); ?>">
      </div>
      <div class="col-md-4">
        <select name="department" class="form-select">
          <option value="">All Departments</option>
          <?php foreach ($departments as $dept): ?>
            <option value="<?php echo $dept['id']; ?>" <?php echo ((string)$deptId === (string)$dept['id']) ? 'selected' : ''; ?>>
              <?php echo htmlspecialchars($dept['name']); ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-2">
        <button type="submit" class="btn btn-dark-pill w-100 justify-content-center">
          <i class="bi bi-search"></i> Search
        </button>
      </div>
      <?php if ($search !== '' || $deptId !== ''): ?>
        <div class="col-12 text-center">
          <a href="doctors.php" class="doctor-clear-filter">Clear filters <i class="bi bi-x-circle"></i></a>
        </div>
      <?php endif; ?>
    </form>
  </div>
</section>

<!-- ============ DOCTORS GRID ============ -->
<section class="doctor-grid-section section-pad">
  <div class="container">
    <?php if (!$doctors): ?>
      <div class="text-center text-muted py-5">
        <i class="bi bi-emoji-frown display-6 d-block mb-3"></i>
        No doctors matched your search. Try a different name or department.
      </div>
    <?php else: ?>
      <div class="row g-4">
        <?php foreach ($doctors as $doc): ?>
          <div class="col-lg-3 col-md-6 reveal">
            <div class="doctor-card">
              <div class="doctor-card-img">
                <?php if ($doc['photo']): ?>
                  <img src="Image/doctors/<?php echo htmlspecialchars($doc['photo']); ?>" alt="<?php echo htmlspecialchars($doc['name']); ?>">
                <?php else: ?>
                  <span class="doctor-card-img-placeholder"><i class="bi bi-person-fill"></i></span>
                <?php endif; ?>
                <span class="doctor-card-dept"><?php echo htmlspecialchars($doc['department_name']); ?></span>
              </div>
              <div class="doctor-card-body">
                <h6><?php echo htmlspecialchars($doc['name']); ?></h6>
                <p class="doctor-card-designation"><?php echo htmlspecialchars($doc['designation']); ?></p>
                <p class="doctor-card-exp"><i class="bi bi-briefcase-fill"></i> <?php echo (int)$doc['experience_years']; ?> Years Experience</p>
                <a href="appointment.html?doctor=<?php echo $doc['id']; ?>" class="btn btn-outline-dark-pill w-100">
                  <i class="bi bi-calendar-check"></i> Book Appointment
                </a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>

<!-- ============ CTA BANNER ============ -->
<section class="cta-banner-section section-pad">
  <div class="container">
    <div class="cta-banner reveal">
      <span class="badge-pill"><i class="bi bi-stars"></i>GET STARTED</span>
      <h2>Book Your Appointment <em>With Medical Experts</em></h2>
      <p>Take the first step toward better health today. Schedule your appointment with our trusted doctors and experience compassionate, professional care.</p>
      <a href="appointment.html" class="btn btn-cream-pill">Book Appointment Now <i class="bi bi-arrow-right"></i></a>
      <div class="cta-trust-row">
        <div class="cta-trust-item"><i class="bi bi-shield-check"></i>Verified Doctors</div>
        <div class="cta-trust-item"><i class="bi bi-clock-history"></i>24/7 Support</div>
        <div class="cta-trust-item"><i class="bi bi-star-fill"></i>4.9/5 Patient Rating</div>
      </div>
    </div>
  </div>
</section>

<!-- ============ FOOTER ============ -->
<footer class="site-footer">
  <div class="container">
    <div class="row g-5">
      <div class="col-lg-4">
        <a class="footer-brand" href="index.html"><i class="bi bi-heart-pulse-fill"></i>City Care</a>
        <p>Dedicated to providing expert healthcare with advanced technology and treatments to ensure your well-being and a healthier future.</p>
        <div class="footer-socials">
          <a href="#"><i class="bi bi-linkedin"></i></a>
          <a href="#"><i class="bi bi-facebook"></i></a>
          <a href="#"><i class="bi bi-twitter-x"></i></a>
          <a href="#"><i class="bi bi-youtube"></i></a>
        </div>
      </div>
      <div class="col-lg-2 col-6">
        <h6>Company</h6>
        <ul class="footer-links">
          <li><a href="about.html">About Us</a></li>
          <li><a href="#">Careers</a></li>
          <li><a href="doctors.php">Our Doctors</a></li>
          <li><a href="#">Our Patients</a></li>
        </ul>
      </div>
      <div class="col-lg-2 col-6">
        <h6>Services</h6>
        <ul class="footer-links">
          <li><a href="services.html">General Consultation</a></li>
          <li><a href="services.html">Specialized Treatment</a></li>
          <li><a href="services.html">Emergency Care</a></li>
          <li><a href="services.html">Medical Checkup</a></li>
        </ul>
      </div>
      <div class="col-lg-4">
        <h6>Opening Hours</h6>
        <div class="hours-row"><span>Monday - Wednesday</span><span>6AM - 1PM</span></div>
        <div class="hours-row"><span>Thursday - Friday</span><span>5AM - 12PM</span></div>
        <div class="hours-row"><span>Weekend</span><span>5AM - 11PM</span></div>

        <h6 class="mt-4">Stay Updated</h6>
        <form class="newsletter-form">
          <input type="email" placeholder="Your email">
          <button type="submit"><i class="bi bi-arrow-right"></i></button>
        </form>
      </div>
    </div>

    <div class="footer-bottom">
      <p class="mb-0">&copy; 2026 City Care Hospital Management System. All Rights Reserved.</p>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    var header = document.getElementById('siteHeader');
    function toggleHeaderShadow() {
      if (window.scrollY > 8) { header.classList.add('is-scrolled'); }
      else { header.classList.remove('is-scrolled'); }
    }
    toggleHeaderShadow();
    window.addEventListener('scroll', toggleHeaderShadow, { passive: true });

    var navMenu = document.getElementById('navMenu');
    if (navMenu) {
      navMenu.querySelectorAll('.nav-link, .btn-login, .btn-dark-pill').forEach(function (link) {
        link.addEventListener('click', function () {
          if (navMenu.classList.contains('show') && window.bootstrap) {
            var collapseInstance = bootstrap.Collapse.getOrCreateInstance(navMenu);
            collapseInstance.hide();
          }
        });
      });
    }
  });

  document.addEventListener('DOMContentLoaded', function () {
    var revealEls = document.querySelectorAll('.reveal');
    if (!('IntersectionObserver' in window)) {
      revealEls.forEach(function (el) { el.classList.add('is-visible'); });
      return;
    }
    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.15, rootMargin: '0px 0px -60px 0px' });
    revealEls.forEach(function (el) { observer.observe(el); });
  });
</script>
</body>
</html>
