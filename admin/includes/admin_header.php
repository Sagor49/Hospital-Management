<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!defined('BASE_URL')) {
    define('BASE_URL', '/hospital');
}
$currentAdmin = $_SESSION['admin_username'] ?? 'Admin';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) . ' | ' : ''; ?>Admin | City Care Hospital</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/style.css">
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/admin.css">
</head>
<body class="admin-body">

<div class="admin-shell">
  <aside class="admin-sidebar">
    <a href="<?php echo BASE_URL; ?>/admin/doctors/list.php" class="admin-logo">
      <i class="bi bi-heart-pulse-fill"></i>City Care <span>Admin</span>
    </a>
    <nav class="admin-nav">
      <a href="<?php echo BASE_URL; ?>/admin/doctors/list.php" class="<?php echo (basename($_SERVER['SCRIPT_NAME']) !== 'login.php' ? 'active' : ''); ?>">
        <i class="bi bi-people-fill"></i> Doctors
      </a>
      <a href="<?php echo BASE_URL; ?>/doctors.php" target="_blank">
        <i class="bi bi-box-arrow-up-right"></i> View Public Site
      </a>
    </nav>
    <div class="admin-sidebar-footer">
      <a href="<?php echo BASE_URL; ?>/admin/logout.php" class="admin-logout-link">
        <i class="bi bi-box-arrow-right"></i> Logout
      </a>
    </div>
  </aside>

  <div class="admin-main">
    <header class="admin-topbar">
      <h1><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Admin'; ?></h1>
      <div class="admin-topbar-user">
        <i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($currentAdmin); ?>
      </div>
    </header>
    <main class="admin-content">
