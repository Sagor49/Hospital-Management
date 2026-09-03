<?php
require_once __DIR__ . '/includes/user_auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>City Care Hospital | Home</title>

<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Playfair+Display:ital@1&display=swap" rel="stylesheet">

<link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- ============ NAVBAR ============ -->
<header class="site-header" id="siteHeader">
  <nav class="navbar navbar-light bg-white">
    <div class="container">
      <a class="navbar-brand" href="index.php">
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
            <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="about.php">About Us</a></li>
            <li class="nav-item"><a class="nav-link" href="services.php">Services</a></li>
            <li class="nav-item"><a class="nav-link" href="doctors.php">Doctors</a></li>
            <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
          </ul>
          <div class="d-flex gap-2 justify-content-center mt-3 mt-lg-0">
            <?php if ($isLoggedIn): ?>
              <a href="logout.php" class="btn btn-login"><i class="bi bi-box-arrow-right"></i> Logout</a>
            <?php else: ?>
              <a href="login.php" class="btn btn-login">Login</a>
            <?php endif; ?>
            <a href="appointment.html" class="btn btn-dark-pill">
              Book Appointment <i class="bi bi-arrow-right"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
  </nav>
</header>

<!-- ============ HERO ============ -->
<section class="hero">
  <div id="heroCarousel" class="carousel slide carousel-fade hero-carousel" data-bs-ride="carousel" data-bs-interval="5000">
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img src="Image/darkostojanovic-doctor-563429.jpg" alt="Expert care at City Care Hospital">
      </div>
      <div class="carousel-item">
        <img src="Image/tungart7-doctor-8656663.jpg" alt="Compassionate care for every family">
      </div>
      <div class="carousel-item">
        <img src="Image/sasint-surgery-1807541.jpg" alt="Advanced surgical care">
      </div>
    </div>
  </div>
  <div class="hero-overlay"></div>
  <div class="container position-relative">
    <div class="row">
      <div class="col-lg-7 hero-text">
        <span class="badge-pill">TRUSTED HEALTHCARE</span>
        <h1 class="hero-title">Your Trusted Partner for <em>Health</em> and <em>Wellness</em></h1>
        <p class="hero-desc">Get expert medical care with trusted professionals, advanced technology, and personalized treatment to ensure your well-being and a healthier future.</p>
        <div class="d-flex flex-wrap align-items-center gap-4 hero-actions">
          <a href="appointment.html" class="btn btn-cream-pill">
            Book Appointment <i class="bi bi-arrow-right"></i>
          </a>
          <a href="#" class="watch-now" data-bs-toggle="modal" data-bs-target="#videoModal">
            <span class="play-circle"><i class="bi bi-play-fill"></i></span>
            Watch Now
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ============ ABOUT ============ -->
<section class="about-section section-pad">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-6">
        <div class="about-img-wrap reveal">
          <img src="Image/About-CU9S3T4.jpg" class="about-img" alt="Doctor and patient">
          <div class="about-quote-card">
            <div class="d-flex align-items-center gap-2 mb-2">
              <img src="Image/portrait-of-young.webp" class="rounded-circle" alt="Dr. Michael Reynolds">
              <div>
                <h6 class="mb-0">Dr. Michael Reynolds</h6>
                <small class="text-muted">Founder, City Care</small>
              </div>
            </div>
            <p class="mb-0 small">"Our mission is to provide accessible, high-quality healthcare with advanced technology and personalized treatment for a healthier future."</p>
          </div>
        </div>
      </div>
      <div class="col-lg-6 reveal reveal-delay-1">
        <span class="badge-outline">ABOUT US</span>
        <h2 class="section-title">Compassionate Care for <em>Your Health</em></h2>
        <p class="section-desc">Dedicated to providing expert healthcare with advanced technology, experienced professionals, and personalized treatments to ensure your well-being and a healthier future.</p>

        <div class="feature-row">
          <div class="feature-icon"><i class="bi bi-person-badge"></i></div>
          <div>
            <h6>Expert Doctors</h6>
            <p class="mb-0">Our team of highly skilled and experienced medical professionals delivers top-quality healthcare with compassion and precision.</p>
          </div>
        </div>
        <div class="feature-row">
          <div class="feature-icon"><i class="bi bi-cpu"></i></div>
          <div>
            <h6>Advanced Technology</h6>
            <p class="mb-0">We utilize cutting-edge medical technology and modern treatment to ensure accurate diagnoses and effective care.</p>
          </div>
        </div>

        <a href="about.php" class="btn btn-dark-pill mt-3">More About Us <i class="bi bi-arrow-right"></i></a>
      </div>
    </div>

    <div class="row stats-row text-center">
      <div class="col-6 col-md-3">
        <h3>25<span>+</span></h3>
        <p>Years Experience</p>
      </div>
      <div class="col-6 col-md-3">
        <h3>100<span>+</span></h3>
        <p>Patients Treated</p>
      </div>
      <div class="col-6 col-md-3">
        <h3>50<span>+</span></h3>
        <p>Expert Doctors</p>
      </div>
      <div class="col-6 col-md-3">
        <h3>200<span>+</span></h3>
        <p>Successful Surgeries</p>
      </div>
    </div>
  </div>
</section>

<!-- ============ SERVICES ============ -->
<section class="services-section section-pad">
  <div class="container">
    <div class="services-box">
      <div class="row align-items-end mb-5">
        <div class="col-lg-8 reveal">
          <span class="badge-outline">OUR SERVICES</span>
          <h2 class="section-title mb-0">Comprehensive Care for <em>Every Need</em></h2>
        </div>
        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
          <a href="services.php" class="btn btn-dark-pill">View All Services <i class="bi bi-arrow-right"></i></a>
        </div>
      </div>

      <div class="row g-4">
        <div class="col-md-6 reveal">
          <div class="service-card">
            <img src="Image/Services-NSNPZVV.jpg" alt="General Consultation">
            <a href="#" class="service-arrow"><i class="bi bi-arrow-up-right"></i></a>
            <div class="service-caption">
              <h6>General Consultation</h6>
              <p class="mb-0">Get expert medical advice tailored to your health needs by experienced doctors.</p>
            </div>
          </div>
        </div>
        <div class="col-md-6 reveal">
          <div class="service-card">
            <img src="Image/Services-RGE28LF.jpg" alt="Specialized Treatment">
            <a href="#" class="service-arrow"><i class="bi bi-arrow-up-right"></i></a>
            <div class="service-caption">
              <h6>Specialized Treatment</h6>
              <p class="mb-0">Advanced, specialty-specific treatment plans designed around your condition.</p>
            </div>
          </div>
        </div>
        <div class="col-md-6 reveal">
          <div class="service-card">
            <img src="Image/sasint-surgery-1807541.jpg" alt="Emergency Care">
            <a href="#" class="service-arrow"><i class="bi bi-arrow-up-right"></i></a>
            <div class="service-caption">
              <h6>Emergency Care</h6>
              <p class="mb-0">24/7 emergency response with a dedicated critical-care team ready to help.</p>
            </div>
          </div>
        </div>
        <div class="col-md-6 reveal">
          <div class="service-card">
            <img src="Image/Services-Z7JB6LZ.jpg" alt="Medical Checkup">
            <a href="#" class="service-arrow"><i class="bi bi-arrow-up-right"></i></a>
            <div class="service-caption">
              <h6>Medical Checkup</h6>
              <p class="mb-0">Full-body diagnostic checkups to catch issues early and stay ahead of them.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ============ PROCESS ============ -->
<section class="process-section section-pad">
  <div class="container">
    <div class="text-center mb-5 reveal">
      <span class="badge-outline">OUR PROCESS</span>
      <h2 class="section-title">Seamless Care in <em>Three Steps</em></h2>
    </div>

    <div class="row process-steps">
      <div class="col-md-4">
        <span class="step-num">(01)</span>
        <h5>Book Appointment</h5>
        <p>Easily schedule your appointment online or by phone. Choose a convenient time and get quick confirmation.</p>
      </div>
      <div class="col-md-4">
        <span class="step-num">(02)</span>
        <h5>Medical Consultation</h5>
        <p>Meet our qualified doctors for a detailed consultation, clear answers, and tailored recommendations.</p>
      </div>
      <div class="col-md-4">
        <span class="step-num">(03)</span>
        <h5>Treatment & Care</h5>
        <p>Receive personalized treatment with continuous monitoring and dedicated aftercare support.</p>
      </div>
    </div>

    <div class="process-img mt-5 reveal">
      <img src="Image/tungart7-doctor-8656663.jpg" alt="Our medical team">
    </div>
  </div>
</section>

<!-- ============ APPOINTMENT CTA ============ -->
<section class="appointment-section section-pad">
  <div class="container">
    <div class="row g-0 appointment-box reveal">
      <div class="col-lg-6 appointment-img">
        <img src="Image/Book-Form-GZA68DM.jpg" alt="Book appointment">
        <div class="appointment-img-text">
          <span class="badge-pill">EASY ACCESS</span>
          <h2>Book Your Medical Appointment <em>Quickly and Easily</em></h2>
          <p>Schedule your healthcare visit in a few steps. Fill the form and our team will confirm your appointment immediately.</p>
          <a href="appointment.html" class="btn btn-cream-pill">Get Free Consultation <i class="bi bi-arrow-right"></i></a>
        </div>
      </div>
      <div class="col-lg-6">
        <form class="appointment-form">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Full Name</label>
              <input type="text" class="form-control" placeholder="e.g. Peter Johnson">
            </div>
            <div class="col-md-6">
              <label class="form-label">Your Email</label>
              <input type="email" class="form-control" placeholder="e.g. hello@citycare.com">
            </div>
            <div class="col-md-6">
              <label class="form-label">Phone</label>
              <input type="text" class="form-control" placeholder="e.g. 0812 3456 7890">
            </div>
            <div class="col-md-6">
              <label class="form-label">Department</label>
              <select class="form-select">
                <option selected>Select</option>
                <option>Cardiology</option>
                <option>Neurology</option>
                <option>Orthopedics</option>
                <option>Pediatrics</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Date</label>
              <input type="date" class="form-control">
            </div>
            <div class="col-md-6">
              <label class="form-label">Time</label>
              <input type="time" class="form-control">
            </div>
            <div class="col-12">
              <label class="form-label">Message</label>
              <textarea class="form-control" rows="3" placeholder="Write your message here..."></textarea>
            </div>
            <div class="col-12">
              <button type="submit" class="btn btn-dark-pill">Book Appointment <i class="bi bi-arrow-right"></i></button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>

<!-- ============ DOCTORS ============ -->
<section class="doctors-section section-pad">
  <div class="container">
    <div class="text-center mb-5 reveal">
      <span class="badge-outline">EXPERT DOCTOR</span>
      <h2 class="section-title">Meet Professional <em>Doctors &amp; Specialists</em></h2>
    </div>

    <div class="row g-4">
      <div class="col-lg-3 col-md-6 reveal">
        <div class="doctor-mini-card">
          <img src="Image/Doctor-GN7824H.jpg" alt="Dr. Emily Carter">
          <div class="doctor-socials">
            <a href="#"><i class="bi bi-linkedin"></i></a>
            <a href="#"><i class="bi bi-facebook"></i></a>
            <a href="#"><i class="bi bi-twitter-x"></i></a>
          </div>
          <div class="doctor-name-tag">
            <h6>Dr. Emily Carter</h6>
            <small>Cardiologist Specialist</small>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 reveal">
        <div class="doctor-mini-card">
          <img src="Image/Doctor-TNB7BYN.jpg" alt="Dr. James Mitchel">
          <div class="doctor-socials">
            <a href="#"><i class="bi bi-linkedin"></i></a>
            <a href="#"><i class="bi bi-facebook"></i></a>
            <a href="#"><i class="bi bi-twitter-x"></i></a>
          </div>
          <div class="doctor-name-tag">
            <h6>Dr. James Mitchel</h6>
            <small>Pediatrician Specialist</small>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 reveal">
        <div class="doctor-mini-card">
          <img src="Image/Doctors-4U3BVJV.jpg" alt="Dr. Sophia Ramirez">
          <div class="doctor-socials">
            <a href="#"><i class="bi bi-linkedin"></i></a>
            <a href="#"><i class="bi bi-facebook"></i></a>
            <a href="#"><i class="bi bi-twitter-x"></i></a>
          </div>
          <div class="doctor-name-tag">
            <h6>Dr. Sophia Ramirez</h6>
            <small>Dermatologist Specialist</small>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 reveal">
        <div class="doctor-mini-card">
          <img src="Image/Doctors-UB84FXW.jpg" alt="Dr. Daniel Wong">
          <div class="doctor-socials">
            <a href="#"><i class="bi bi-linkedin"></i></a>
            <a href="#"><i class="bi bi-facebook"></i></a>
            <a href="#"><i class="bi bi-twitter-x"></i></a>
          </div>
          <div class="doctor-name-tag">
            <h6>Dr. Daniel Wong</h6>
            <small>Orthopedic Surgeon</small>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ============ TESTIMONIALS ============ -->
<section class="testimonial-section section-pad">
  <div class="container">
    <div class="testimonial-box reveal">
      <div class="row g-4 align-items-start">
        <div class="col-lg-5">
          <span class="badge-pill-dark">HAPPY PATIENTS</span>
          <h2 class="section-title">What Our Patients <em>Say About Us</em></h2>
          <p class="section-desc">Dedicated to providing expert healthcare with advanced technology, experienced professionals, and personalized treatment for your well-being.</p>
          <a href="#" class="btn btn-dark-pill">View All Testimonials <i class="bi bi-arrow-right"></i></a>
        </div>
        <div class="col-lg-7">
          <div class="testimonial-main-card">
            <div class="stars"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-half"></i></div>
            <p>"The doctors were caring and professional. The staff made me feel comfortable, and the treatment plan was clear. I felt supported every step — highly recommended!"</p>
            <div class="d-flex align-items-center gap-3 mt-3">
              <img src="https://placehold.co/60x60/2c3e3d/ffffff?text=HK" class="rounded-circle" alt="Heller Keano">
              <div>
                <h6 class="mb-0">Heller Keano</h6>
                <small class="text-muted">Patient Parent</small>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="testimonial-slider">
        <div class="testimonial-slider-track">
          <div class="testimonial-slide-set">
          <div class="testimonial-mini-card">
            <div class="stars"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i></div>
            <p>"Booking was simple and fast. The staff made me feel comfortable and explained everything in detail. Truly excellent service."</p>
            <div class="d-flex align-items-center gap-2 mt-3">
              <img src="https://placehold.co/44x44/2c3e3d/ffffff?text=ML" class="rounded-circle" alt="Michael Lee">
              <div>
                <h6 class="mb-0">Michael Lee</h6>
                <small class="text-muted">Patient</small>
              </div>
            </div>
          </div>
          <div class="testimonial-mini-card">
            <div class="stars"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i></div>
            <p>"My child received the best care here. The pediatrician was very attentive, and we're grateful for the kindness shown."</p>
            <div class="d-flex align-items-center gap-2 mt-3">
              <img src="https://placehold.co/44x44/2c3e3d/ffffff?text=ER" class="rounded-circle" alt="Emma Rodriguez">
              <div>
                <h6 class="mb-0">Emma Rodriguez</h6>
                <small class="text-muted">Patient's Parent</small>
              </div>
            </div>
          </div>
          <div class="testimonial-mini-card">
            <div class="stars"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i></div>
            <p>"I was impressed with the modern facilities and friendly staff. They made me feel safe throughout my treatment process."</p>
            <div class="d-flex align-items-center gap-2 mt-3">
              <img src="https://placehold.co/44x44/2c3e3d/ffffff?text=DS" class="rounded-circle" alt="David Smith">
              <div>
                <h6 class="mb-0">David Smith</h6>
                <small class="text-muted">Patient</small>
              </div>
            </div>
          </div>
          </div>
          <div class="testimonial-slide-set" aria-hidden="true">
          <div class="testimonial-mini-card">
            <div class="stars"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i></div>
            <p>"Booking was simple and fast. The staff made me feel comfortable and explained everything in detail. Truly excellent service."</p>
            <div class="d-flex align-items-center gap-2 mt-3">
              <img src="https://placehold.co/44x44/2c3e3d/ffffff?text=ML" class="rounded-circle" alt="Michael Lee">
              <div>
                <h6 class="mb-0">Michael Lee</h6>
                <small class="text-muted">Patient</small>
              </div>
            </div>
          </div>
          <div class="testimonial-mini-card">
            <div class="stars"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i></div>
            <p>"My child received the best care here. The pediatrician was very attentive, and we're grateful for the kindness shown."</p>
            <div class="d-flex align-items-center gap-2 mt-3">
              <img src="https://placehold.co/44x44/2c3e3d/ffffff?text=ER" class="rounded-circle" alt="Emma Rodriguez">
              <div>
                <h6 class="mb-0">Emma Rodriguez</h6>
                <small class="text-muted">Patient's Parent</small>
              </div>
            </div>
          </div>
          <div class="testimonial-mini-card">
            <div class="stars"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i></div>
            <p>"I was impressed with the modern facilities and friendly staff. They made me feel safe throughout my treatment process."</p>
            <div class="d-flex align-items-center gap-2 mt-3">
              <img src="https://placehold.co/44x44/2c3e3d/ffffff?text=DS" class="rounded-circle" alt="David Smith">
              <div>
                <h6 class="mb-0">David Smith</h6>
                <small class="text-muted">Patient</small>
              </div>
            </div>
          </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ============ FAQ ============ -->
<section class="faq-section section-pad">
  <div class="container">
    <div class="row g-5">
      <div class="col-lg-4 reveal">
        <span class="badge-outline">COMMON QUESTIONS</span>
        <h2 class="section-title">Frequently Asked Questions <em>About Our Services</em></h2>

        <div class="faq-img-wrap">
          <img src="Image/FAQ-X2K67DU.jpg" alt="Support team">
        </div>
        <div class="faq-help-card">
          <h6>Have More Questions?</h6>
          <p class="mb-3">Our support team is happy to help — reach out any time and we'll get back to you quickly.</p>
          <a href="contact.php" class="btn btn-dark-pill">Get Free Consultation <i class="bi bi-arrow-right"></i></a>
        </div>
      </div>
      <div class="col-lg-8 reveal reveal-delay-1">
        <div class="accordion" id="faqAccordion">

          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                How can I book an appointment?
              </button>
            </h2>
            <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
              <div class="accordion-body">
                You can book online through our website or call our support line to schedule at your convenience. We work with most major insurance providers — please check with our staff for coverage details.
              </div>
            </div>
          </div>

          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                Do you accept health insurance?
              </button>
            </h2>
            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
              <div class="accordion-body">
                Yes, we accept most major health insurance providers. Please bring your insurance details to your appointment for verification.
              </div>
            </div>
          </div>

          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                What should I bring to my appointment?
              </button>
            </h2>
            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
              <div class="accordion-body">
                Please bring a valid ID, your insurance card (if applicable), a list of current medications, and any relevant medical records.
              </div>
            </div>
          </div>

          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                Can I reschedule or cancel my appointment?
              </button>
            </h2>
            <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
              <div class="accordion-body">
                Yes, you can reschedule or cancel up to 24 hours before your appointment through our website or by calling our office.
              </div>
            </div>
          </div>

          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                Do you offer emergency services?
              </button>
            </h2>
            <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
              <div class="accordion-body">
                Yes, our emergency department is open 24/7 with a dedicated team ready to respond to urgent medical needs.
              </div>
            </div>
          </div>

          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq6">
                Are your doctors board-certified?
              </button>
            </h2>
            <div id="faq6" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
              <div class="accordion-body">
                Absolutely. All our doctors are board-certified specialists with extensive training and experience in their respective fields.
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
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

<!-- ============ VIDEO MODAL ============ -->
<div class="modal fade video-modal" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <button type="button" class="btn-close-video" data-bs-dismiss="modal" aria-label="Close"><i class="bi bi-x-lg"></i></button>
      <div class="ratio ratio-16x9">
        <iframe id="videoModalFrame" src="" title="City Care Hospital video" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
      </div>
    </div>
  </div>
</div>

<!-- ============ FOOTER ============ -->
<footer class="site-footer">
  <div class="container">
    <div class="row g-5">
      <div class="col-lg-4">
        <a class="footer-brand" href="index.php"><i class="bi bi-heart-pulse-fill"></i>City Care</a>
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
          <li><a href="about.php">About Us</a></li>
          <li><a href="#">Careers</a></li>
          <li><a href="doctors.php">Our Doctors</a></li>
          <li><a href="#">Our Patients</a></li>
        </ul>
      </div>
      <div class="col-lg-2 col-6">
        <h6>Services</h6>
        <ul class="footer-links">
          <li><a href="services.php">General Consultation</a></li>
          <li><a href="services.php">Specialized Treatment</a></li>
          <li><a href="services.php">Emergency Care</a></li>
          <li><a href="services.php">Medical Checkup</a></li>
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
  // Video modal — load the YouTube embed only while the modal is open
  document.addEventListener('DOMContentLoaded', function () {
    var videoModal = document.getElementById('videoModal');
    if (videoModal) {
      var videoFrame = document.getElementById('videoModalFrame');
      var videoSrc = 'https://www.youtube.com/embed/mHAITUtELZo?autoplay=1&rel=0';
      videoModal.addEventListener('show.bs.modal', function () {
        videoFrame.src = videoSrc;
      });
      videoModal.addEventListener('hidden.bs.modal', function () {
        videoFrame.src = '';
      });
    }
  });

  // Sticky header shadow on scroll
  document.addEventListener('DOMContentLoaded', function () {
    var header = document.getElementById('siteHeader');
    function toggleHeaderShadow() {
      if (window.scrollY > 8) {
        header.classList.add('is-scrolled');
      } else {
        header.classList.remove('is-scrolled');
      }
    }
    toggleHeaderShadow();
    window.addEventListener('scroll', toggleHeaderShadow, { passive: true });

    // Close the mobile menu automatically when a nav link is clicked
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

  // Scroll-reveal animation for sections & cards
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

  // Animated count-up for stats numbers
  document.addEventListener('DOMContentLoaded', function () {
    var statEls = document.querySelectorAll('.stats-row h3');
    if (!statEls.length) return;

    var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    function parseStat(el) {
      var textNode = el.childNodes[0];
      if (!textNode || textNode.nodeType !== 3) return null;
      var match = textNode.textContent.trim().match(/^(\d+)(.*)$/);
      if (!match) return null;
      return { el: el, node: textNode, target: parseInt(match[1], 10), suffix: match[2] };
    }

    var stats = [];
    statEls.forEach(function (el) {
      var stat = parseStat(el);
      if (stat) {
        stats.push(stat);
        if (!reduceMotion) stat.node.textContent = '0' + stat.suffix;
      }
    });

    if (reduceMotion || !stats.length) return;

    function animateStat(stat) {
      var duration = 1600;
      var start = null;
      function step(ts) {
        if (!start) start = ts;
        var progress = Math.min((ts - start) / duration, 1);
        var eased = 1 - Math.pow(1 - progress, 3);
        stat.node.textContent = Math.floor(eased * stat.target) + stat.suffix;
        if (progress < 1) {
          requestAnimationFrame(step);
        } else {
          stat.node.textContent = stat.target + stat.suffix;
        }
      }
      requestAnimationFrame(step);
    }

    if (!('IntersectionObserver' in window)) {
      stats.forEach(animateStat);
      return;
    }

    var statObserver = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          var stat = stats.find(function (s) { return s.el === entry.target; });
          if (stat) animateStat(stat);
          statObserver.unobserve(entry.target);
        }
      });
    }, { threshold: 0.4 });

    stats.forEach(function (stat) { statObserver.observe(stat.el); });
  });
</script>
</body>
</html>
