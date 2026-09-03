<?php
session_start();

// Prevent the browser (and its back/forward cache) from showing a stale
// cached copy of this page.
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: Sat, 01 Jan 2000 00:00:00 GMT');

require_once __DIR__ . '/config/db.php';

$errors    = [];
$sent      = false;
$email     = '';
$resetLink = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    } else {
        $stmt = $pdo->prepare('SELECT id, name FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            $pdo->prepare('DELETE FROM password_resets WHERE email = ?')->execute([$email]);

            $token     = bin2hex(random_bytes(32));
            $expiresAt = date('Y-m-d H:i:s', time() + 3600);
            $pdo->prepare('INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)')
                ->execute([$email, $token, $expiresAt]);

            // No email server is configured for this project, so the reset
            // link is shown directly on this page instead of being emailed.
            $resetLink = (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . '/hospital/reset-password.php?token=' . $token;
        }

        $sent = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Forgot Password | City Care Hospital</title>

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

  <?php if ($sent): ?>
    <div class="text-center">
      <?php if ($resetLink): ?>
        <div class="auth-success-icon"><i class="bi bi-link-45deg"></i></div>
        <h1 class="auth-title">Your reset link is ready</h1>
        <p class="auth-subtitle">No email server is set up for this project, so here's your password reset link directly. It expires in 1 hour.</p>
        <div class="auth-reset-link-box">
          <input type="text" id="resetLinkInput" class="form-control" readonly value="<?php echo htmlspecialchars($resetLink); ?>">
          <button type="button" class="auth-copy-btn" id="copyResetLink"><i class="bi bi-clipboard"></i> Copy</button>
        </div>
        <a href="<?php echo htmlspecialchars($resetLink); ?>" class="auth-submit-btn d-inline-flex mt-3" style="width:auto;padding:12px 30px;">
          <i class="bi bi-shield-lock-fill"></i> Reset My Password Now
        </a>
      <?php else: ?>
        <div class="auth-success-icon"><i class="bi bi-envelope-check-fill"></i></div>
        <h1 class="auth-title">Check the email you entered</h1>
        <p class="auth-subtitle">If an account exists for <strong><?php echo htmlspecialchars($email); ?></strong>, a reset link would appear here.</p>
      <?php endif; ?>
      <p class="auth-switch mt-4"><a href="login.php">Back to login</a></p>
    </div>
  <?php else: ?>
    <h1 class="auth-title">Forgot your password?</h1>
    <p class="auth-subtitle">Enter your account email and we'll give you a link to reset it.</p>

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
        <label class="form-label" for="email">Email address</label>
        <input type="email" class="form-control" id="email" name="email" required
               value="<?php echo htmlspecialchars($email); ?>" placeholder="you@example.com">
      </div>
      <button type="submit" class="auth-submit-btn"><i class="bi bi-send-fill"></i> Get Reset Link</button>
    </form>

    <p class="auth-switch">Remember your password? <a href="login.php">Log in</a></p>
  <?php endif; ?>
</div>

<script>
  var copyBtn = document.getElementById('copyResetLink');
  if (copyBtn) {
    copyBtn.addEventListener('click', function () {
      var input = document.getElementById('resetLinkInput');
      input.select();
      input.setSelectionRange(0, 99999);
      navigator.clipboard.writeText(input.value).then(function () {
        copyBtn.innerHTML = '<i class="bi bi-check2"></i> Copied';
        setTimeout(function () {
          copyBtn.innerHTML = '<i class="bi bi-clipboard"></i> Copy';
        }, 1800);
      });
    });
  }
</script>
</body>
</html>
