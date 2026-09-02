<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../../config/db.php';

define('BASE_URL', '/hospital');
$pageTitle = 'Edit Doctor';

$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
if ($id <= 0) {
    header('Location: list.php');
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM doctors WHERE id = ?');
$stmt->execute([$id]);
$doctor = $stmt->fetch();

if (!$doctor) {
    $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Doctor not found.'];
    header('Location: list.php');
    exit;
}

$departments = $pdo->query('SELECT id, name FROM departments ORDER BY name')->fetchAll();

$errors = [];
$form = [
    'name'             => $doctor['name'],
    'designation'      => $doctor['designation'],
    'department_id'    => $doctor['department_id'],
    'email'            => $doctor['email'],
    'phone'            => $doctor['phone'],
    'experience_years' => $doctor['experience_years'],
    'bio'              => $doctor['bio'],
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($form as $key => $default) {
        $form[$key] = trim($_POST[$key] ?? '');
    }

    if ($form['name'] === '')          $errors[] = 'Name is required.';
    if ($form['designation'] === '')   $errors[] = 'Designation is required.';
    if ($form['department_id'] === '') $errors[] = 'Please choose a department.';
    if ($form['email'] !== '' && !filter_var($form['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }
    if ($form['experience_years'] !== '' && !ctype_digit((string)$form['experience_years'])) {
        $errors[] = 'Experience (years) must be a whole number.';
    }

    $photoFilename = $doctor['photo'];
    $uploadDir = __DIR__ . '/../../Image/doctors/';

    if (!empty($_FILES['photo']['name'])) {
        $allowed = ['jpg' => true, 'jpeg' => true, 'png' => true, 'webp' => true];
        $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));

        if ($_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'Photo upload failed. Please try again.';
        } elseif (!isset($allowed[$ext])) {
            $errors[] = 'Photo must be a JPG, PNG, or WEBP file.';
        } elseif ($_FILES['photo']['size'] > 3 * 1024 * 1024) {
            $errors[] = 'Photo must be smaller than 3MB.';
        } else {
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $newFilename = 'doc_' . bin2hex(random_bytes(8)) . '.' . $ext;
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadDir . $newFilename)) {
                // remove the old photo file once the new one is safely saved
                if ($photoFilename && is_file($uploadDir . $photoFilename)) {
                    @unlink($uploadDir . $photoFilename);
                }
                $photoFilename = $newFilename;
            } else {
                $errors[] = 'Could not save the uploaded photo.';
            }
        }
    }

    // "Remove photo" checkbox
    if (!empty($_POST['remove_photo']) && empty($_FILES['photo']['name'])) {
        if ($photoFilename && is_file($uploadDir . $photoFilename)) {
            @unlink($uploadDir . $photoFilename);
        }
        $photoFilename = null;
    }

    if (!$errors) {
        $stmt = $pdo->prepare(
            'UPDATE doctors
             SET name = ?, designation = ?, department_id = ?, email = ?, phone = ?,
                 experience_years = ?, bio = ?, photo = ?
             WHERE id = ?'
        );
        $stmt->execute([
            $form['name'],
            $form['designation'],
            $form['department_id'],
            $form['email'] !== '' ? $form['email'] : null,
            $form['phone'] !== '' ? $form['phone'] : null,
            $form['experience_years'] !== '' ? (int)$form['experience_years'] : 0,
            $form['bio'] !== '' ? $form['bio'] : null,
            $photoFilename,
            $id,
        ]);

        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Doctor updated successfully.'];
        header('Location: list.php');
        exit;
    }
}

require __DIR__ . '/../includes/admin_header.php';
?>

<div class="admin-card admin-form-card">
  <?php if ($errors): ?>
    <div class="alert alert-danger py-2">
      <ul class="mb-0 ps-3">
        <?php foreach ($errors as $err): ?><li><?php echo htmlspecialchars($err); ?></li><?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <form method="post" enctype="multipart/form-data" novalidate>
    <input type="hidden" name="id" value="<?php echo $doctor['id']; ?>">

    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Full Name *</label>
        <input type="text" name="name" class="form-control" required
               value="<?php echo htmlspecialchars($form['name']); ?>">
      </div>
      <div class="col-md-6">
        <label class="form-label">Department *</label>
        <select name="department_id" class="form-select" required>
          <?php foreach ($departments as $dept): ?>
            <option value="<?php echo $dept['id']; ?>" <?php echo ($form['department_id'] == $dept['id']) ? 'selected' : ''; ?>>
              <?php echo htmlspecialchars($dept['name']); ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-md-6">
        <label class="form-label">Designation *</label>
        <input type="text" name="designation" class="form-control" required
               value="<?php echo htmlspecialchars($form['designation']); ?>">
      </div>
      <div class="col-md-6">
        <label class="form-label">Experience (years)</label>
        <input type="number" min="0" name="experience_years" class="form-control"
               value="<?php echo htmlspecialchars($form['experience_years']); ?>">
      </div>

      <div class="col-md-6">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control"
               value="<?php echo htmlspecialchars($form['email']); ?>">
      </div>
      <div class="col-md-6">
        <label class="form-label">Phone</label>
        <input type="text" name="phone" class="form-control"
               value="<?php echo htmlspecialchars($form['phone']); ?>">
      </div>

      <div class="col-12">
        <label class="form-label">Short Bio</label>
        <textarea name="bio" rows="3" class="form-control"><?php echo htmlspecialchars($form['bio']); ?></textarea>
      </div>

      <div class="col-12">
        <label class="form-label">Photo</label>
        <?php if ($doctor['photo']): ?>
          <div class="admin-current-photo">
            <img src="<?php echo BASE_URL; ?>/Image/doctors/<?php echo htmlspecialchars($doctor['photo']); ?>" alt="">
            <label class="admin-remove-photo">
              <input type="checkbox" name="remove_photo" value="1"> Remove current photo
            </label>
          </div>
        <?php endif; ?>
        <input type="file" name="photo" class="form-control mt-2" accept=".jpg,.jpeg,.png,.webp">
        <div class="form-text">Upload a new file to replace the current photo. JPG, PNG or WEBP, max 3MB.</div>
      </div>
    </div>

    <div class="d-flex gap-2 mt-4">
      <button type="submit" class="btn btn-dark-pill">Update Doctor <i class="bi bi-check-lg"></i></button>
      <a href="list.php" class="btn btn-outline-dark-pill">Cancel</a>
    </div>
  </form>
</div>

<?php require __DIR__ . '/../includes/admin_footer.php'; ?>
