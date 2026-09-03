<?php
session_start();

// Prevent the browser (and its back/forward cache) from showing a stale
// cached copy of this page.
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: Sat, 01 Jan 2000 00:00:00 GMT');

require_once __DIR__ . '/config/db.php';

$token      = trim($_GET['token'] ?? $_POST['token'] ?? '');
$errors     = [];
$success    = false;
$validToken = false;
$resetEmail = null;

if ($token !== '') {
    $stmt = $pdo->prepare('SELECT email, expires_at FROM password_resets WHERE token = ?');
    $stmt->execute([$token]);
    $row = $stmt->fetch();
    if ($row && strtotime($row['expires_at']) > time()) {
        $validToken = true;
        $resetEmail = $row['email'];
    }
}

if ($validToken && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    if (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters long.';
    }
    if ($password !== $confirm) {
        $errors[] = 'Passwords do not match.';
    }

    if (!$errors) {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $pdo->prepare('UPDATE users SET password_hash = ?, remember_token = NULL WHERE email = ?')->execute([$hash, $resetEmail]);
        $pdo->prepare('DELETE FROM password_resets WHERE token = ?')->execute([$token]);
        $success = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reset Password | City Care Hospital</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Playfair+Display:ital@1&display=swap" rel="stylesheet">

<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/auth.css">
</head>
<body class="auth-body">

<a href="login.php" class="auth-back-home"><i class="bi bi-arrow-left"></i> Back to Login</a>

<div class="auth-card">
  <a href="index.php" class="auth-logo"><i class="bi bi-heart-pulse-fill"></i>City Care</a>

  <?php if ($success): ?>
    <div class="text-center">
      <div class="auth-success-icon"><i class="bi bi-check-circle-fill"></i></div>
      <h1 class="auth-title">Password updated</h1>
      <p class="auth-subtitle">Your password has been reset successfully.</p>
      <a href="login.php?reset=success" class="auth-submit-btn d-inline-flex" style="width:auto;padding:12px 30px;">
        <i class="bi bi-box-arrow-in-right"></i> Go to Login
      </a>
    </div>
  <?php elseif (!$validToken): ?>
    <div class="text-center">
      <div class="auth-success-icon" style="background:rgba(198,40,40,0.08);color:#c62828;"><i class="bi bi-x-circle-fill"></i></div>
      <h1 class="auth-title">Link expired or invalid</h1>
      <p class="auth-subtitle">This password reset link is no longer valid. Please request a new one.</p>
      <a href="forgot-password.php" class="auth-submit-btn d-inline-flex" style="width:auto;padding:12px 30px;">
        <i class="bi bi-arrow-repeat"></i> Request New Link
      </a>
    </div>
  <?php else: ?>
    <h1 class="auth-title">Set a new password</h1>
    <p class="auth-subtitle">Choose a new password for <strong><?php echo htmlspecialchars($resetEmail); ?></strong>.</p>

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
      <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
      <div class="form-group">
        <label class="form-label" for="password">New password</label>
        <div class="auth-password-wrap">
          <input type="password" class="form-control" id="password" name="password" required minlength="8" placeholder="At least 8 characters">
          <button type="button" class="auth-password-toggle js-toggle-password" data-target="password" aria-label="Show password">
            <i class="bi bi-eye"></i>
          </button>
        </div>
      </div>
      <div class="form-group">
        <label class="form-label" for="confirm_password">Confirm new password</label>
        <div class="auth-password-wrap">
          <input type="password" class="form-control" id="confirm_password" name="confirm_password" required minlength="8" placeholder="Re-enter new password">
          <button type="button" class="auth-password-toggle js-toggle-password" data-target="confirm_password" aria-label="Show password">
            <i class="bi bi-eye"></i>
          </button>
        </div>
      </div>
      <button type="submit" class="auth-submit-btn mt-2"><i class="bi bi-shield-lock-fill"></i> Reset Password</button>
    </form>
  <?php endif; ?>
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
