<?php
session_start();
require_once __DIR__ . '/../config/db.php';

define('BASE_URL', '/hospital');

// Already logged in? go straight to the doctors list.
if (!empty($_SESSION['admin_id'])) {
    header('Location: doctors/list.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Please enter both username and password.';
    } else {
        $stmt = $pdo->prepare('SELECT id, username, password_hash FROM admins WHERE username = ? LIMIT 1');
        $stmt->execute([$username]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['admin_id']       = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            header('Location: doctors/list.php');
            exit;
        } else {
            $error = 'Invalid username or password.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login | City Care Hospital</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/style.css">
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/admin.css">
</head>
<body class="admin-login-body">
  <div class="admin-login-card">
    <a href="<?php echo BASE_URL; ?>/index.php" class="admin-logo mb-4">
      <i class="bi bi-heart-pulse-fill"></i>City Care <span>Admin</span>
    </a>
    <h5 class="mb-1">Welcome back</h5>
    <p class="text-muted mb-4">Log in to manage doctors.</p>

    <?php if ($error): ?>
      <div class="alert alert-danger py-2 small"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="post" novalidate>
      <div class="mb-3">
        <label class="form-label">Username</label>
        <input type="text" name="username" class="form-control" required autofocus
               value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
      </div>
      <div class="mb-4">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-dark-pill w-100 justify-content-center">Login <i class="bi bi-arrow-right"></i></button>
    </form>

    <p class="text-muted small mt-4 mb-0 text-center">
      Default: <code>admin</code> / <code>Admin@123</code> — please change this after logging in.
    </p>
  </div>
</body>
</html>
