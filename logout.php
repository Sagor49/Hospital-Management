<?php
session_start();

// Only a real POST from the Logout button may destroy the session.
// A plain GET (e.g. the browser replaying this URL from history when
// navigating Back/Forward) just bounces to the login page, untouched.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/config/db.php';

if (!empty($_SESSION['user_id'])) {
    $pdo->prepare('UPDATE users SET remember_token = NULL WHERE id = ?')->execute([$_SESSION['user_id']]);
}

setcookie('remember_me', '', [
    'expires'  => time() - 3600,
    'path'     => '/hospital/',
    'httponly' => true,
    'samesite' => 'Lax',
]);

$_SESSION = [];

// Also clear the PHP session cookie itself (session_destroy() alone only
// removes the server-side session data, not the cookie in the browser).
if (ini_get('session.use_cookies')) {
    $cookieParams = session_get_cookie_params();
    setcookie(session_name(), '', [
        'expires'  => time() - 42000,
        'path'     => $cookieParams['path'],
        'domain'   => $cookieParams['domain'],
        'secure'   => $cookieParams['secure'],
        'httponly' => $cookieParams['httponly'],
        'samesite' => $cookieParams['samesite'] ?: 'Lax',
    ]);
}

session_destroy();

header('Location: login.php');
exit;
