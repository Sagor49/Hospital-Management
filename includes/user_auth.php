<?php
/**
 * Public site login gate. Include this at the very top of every page
 * (before any HTML output) that should require a logged-in visitor:
 *   require_once __DIR__ . '/includes/user_auth.php';
 *
 * Tries a "remember me" cookie first, then redirects to login.php if
 * nobody is logged in. Sets $isLoggedIn (always true past this point)
 * and $userName for use in the page's header.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Prevent the browser (and its back/forward cache) from ever showing a
// stale cached copy of a logged-in page after logging out, or vice versa.
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: Sat, 01 Jan 2000 00:00:00 GMT');

if (empty($_SESSION['user_id']) && !empty($_COOKIE['remember_me'])) {
    require_once __DIR__ . '/../config/db.php';

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

if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$isLoggedIn = true;
$userName   = $_SESSION['user_name'];
