<?php
session_start();
require_once __DIR__ . '/config/db.php';

if (!empty($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$errors = [];
$name   = '';
$email  = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    if ($name === '') {
        $errors[] = 'Please enter your full name.';
    }
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }
    if (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters long.';
    }
    if ($password !== $confirm) {
        $errors[] = 'Passwords do not match.';
    }

    if (!$errors) {
        $check = $pdo->prepare('SELECT id FROM users WHERE email = ?');
        $check->execute([$email]);
        if ($check->fetch()) {
            $errors[] = 'An account with this email already exists. Please log in instead.';
        }
    }

    if (!$errors) {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare('INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)');
        $stmt->execute([$name, $email, $hash]);

        header('Location: login.php?signup=success');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sign Up | City Care Hospital</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Playfair+Display:ital@1&display=swap" rel="stylesheet">

<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/auth.css">
</head>
<body class="auth-body">

<a href="index.php" class="auth-back-home"><i class="bi bi-arrow-left"></i> Back to Home</a>

<div class="auth-card">
  <a href="index.php" class="auth-logo"><i class="bi bi-heart-pulse-fill"></i>City Care</a>
  <h1 class="auth-title">Create your account</h1>
  <p class="auth-subtitle">Sign up to book appointments and manage your care with us.</p>

  <?php if ($errors): ?>
    <div class="auth-alert auth-alert-danger">
      <ul>
        <?php foreach ($errors as $error): ?>
          <li><?php echo htmlspecialchars($error); ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <form method="post" class="auth-form" novalidate>
    <div class="form-group">
      <label class="form-label" for="name">Full name</label>
      <input type="text" class="form-control" id="name" name="name" required
             value="<?php echo htmlspecialchars($name); ?>" placeholder="Your full name">
    </div>
    <div class="form-group">
      <label class="form-label" for="email">Email address</label>
      <input type="email" class="form-control" id="email" name="email" required
             value="<?php echo htmlspecialchars($email); ?>" placeholder="you@example.com">
    </div>
    <div class="form-group">
      <label class="form-label" for="password">Password</label>
      <div class="auth-password-wrap">
        <input type="password" class="form-control" id="password" name="password" required minlength="8" placeholder="At least 8 characters">
        <button type="button" class="auth-password-toggle js-toggle-password" data-target="password" aria-label="Show password">
          <i class="bi bi-eye"></i>
        </button>
      </div>
    </div>
    <div class="form-group">
      <label class="form-label" for="confirm_password">Confirm password</label>
      <div class="auth-password-wrap">
        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required minlength="8" placeholder="Re-enter your password">
        <button type="button" class="auth-password-toggle js-toggle-password" data-target="confirm_password" aria-label="Show password">
          <i class="bi bi-eye"></i>
        </button>
      </div>
    </div>

    <button type="submit" class="auth-submit-btn mt-2"><i class="bi bi-person-plus-fill"></i> Create Account</button>
  </form>

  <p class="auth-switch">Already have an account? <a href="login.php">Log in</a></p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  document.querySelectorAll('.js-toggle-password').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var input = document.getElementById(btn.dataset.target);
      var icon = btn.querySelector('i');
      if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('bi-eye', 'bi-eye-slash');
      } else {
        input.type = 'password';
        icon.classList.replace('bi-eye-slash', 'bi-eye');
      }
    });
  });
</script>
</body>
</html>
