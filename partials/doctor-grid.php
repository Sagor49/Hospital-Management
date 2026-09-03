<?php
/**
 * Renders the doctor cards grid (or the "no results" message).
 * Expects $doctors (array of doctor rows) to already be set by the includer.
 * Shared by doctors.php (initial page load) and doctors_search.php (AJAX).
 */
if (!$doctors): ?>
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
