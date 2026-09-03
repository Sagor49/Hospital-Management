<?php
session_start();

// Prevent the browser (and its back/forward cache) from showing a stale
// cached copy of this page after the visitor has logged in or out.
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: Sat, 01 Jan 2000 00:00:00 GMT');

require_once __DIR__ . '/config/db.php';

// Auto-login via "remember me" cookie if there's no active session.
if (empty($_SESSION['user_id']) && !empty($_COOKIE['remember_me'])) {
    $parts = explode(':', $_COOKIE['remember_me'], 2);
    if (count($parts) === 2) {
        [$uid, $validator] = $parts;
        $stmt = $pdo->prepare('SELECT id, name, remember_token FROM users WHERE id = ?');
        $stmt->execute([(int)$uid]);
        $rememberedUser = $stmt->fetch();
        if ($rememberedUser && $rememberedUser['remember_token'] && hash_equals($rememberedUser['remember_token'], hash('sha256', $validator))) {
            $_SESSION['user_id']   = $rememberedUser['id'];
            $_SESSION['user_name'] = $rememberedUser['name'];
        }
    }
}

if (!empty($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$errors  = [];
$email   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);

    if ($email === '' || $password === '') {
        $errors[] = 'Please enter both your email and password.';
    } else {
        $stmt = $pdo->prepare('SELECT id, name, password_hash FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['password_hash'])) {
            $errors[] = 'Incorrect email or password.';
        } else {
            session_regenerate_id(true);
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['user_name'] = $user['name'];

            if ($remember) {
                $validator   = bin2hex(random_bytes(32));
                $hashedToken = hash('sha256', $validator);
                $pdo->prepare('UPDATE users SET remember_token = ? WHERE id = ?')->execute([$hashedToken, $user['id']]);
                setcookie('remember_me', $user['id'] . ':' . $validator, [
                    'expires'  => time() + 60 * 60 * 24 * 30,
                    'path'     => '/hospital/',
                    'httponly' => true,
                    'samesite' => 'Lax',
                ]);
            }

            header('Location: index.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login | City Care Hospital</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Playfair+Display:ital@1&display=swap" rel="stylesheet">

<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/auth.css">
</head>
<body class="auth-body">

<div class="auth-card">
  <a href="index.php" class="auth-logo"><i class="bi bi-heart-pulse-fill"></i>City Care</a>
  <h1 class="auth-title">Welcome back</h1>
  <p class="auth-subtitle">Log in to manage your appointments and health records.</p>

  <?php if ($errors): ?>
    <div class="auth-alert auth-alert-danger">
      <ul>
        <?php foreach ($errors as $error): ?>
          <li><?php echo htmlspecialchars($error); ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <?php if (isset($_GET['reset']) && $_GET['reset'] === 'success'): ?>
    <div class="auth-alert auth-alert-success">Your password has been reset. Please log in with your new password.</div>
  <?php endif; ?>
  <?php if (isset($_GET['signup']) && $_GET['signup'] === 'success'): ?>
    <div class="auth-alert auth-alert-success">Account created! Please log in to continue.</div>
  <?php endif; ?>

  <form method="post" class="auth-form" novalidate>
    <div class="form-group">
      <label class="form-label" for="email">Email address</label>
      <input type="email" class="form-control" id="email" name="email" required
             value="<?php echo htmlspecialchars($email); ?>" placeholder="you@example.com">
    </div>
    <div class="form-group">
      <label class="form-label" for="password">Password</label>
      <div class="auth-password-wrap">
        <input type="password" class="form-control" id="password" name="password" required placeholder="Enter your password">
        <button type="button" class="auth-password-toggle js-toggle-password" data-target="password" aria-label="Show password">
          <i class="bi bi-eye"></i>
        </button>
      </div>
    </div>

    <div class="auth-row-between">
      <label class="auth-remember">
        <input type="checkbox" name="remember"> Remember me
      </label>
      <a href="forgot-password.php" class="auth-forgot-link">Forgot password?</a>
    </div>

    <button type="submit" class="auth-submit-btn"><i class="bi bi-box-arrow-in-right"></i> Log In</button>
  </form>

  <p class="auth-switch">Don't have an account? <a href="signup.php">Sign up</a></p>
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
