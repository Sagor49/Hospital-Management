<?php
/**
 * Include this at the very top of every protected admin page:
 *   require_once __DIR__ . '/includes/auth.php';
 * Redirects to the login page if nobody is logged in.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['admin_id'])) {
    header('Location: ../login.php');
    exit;
}
