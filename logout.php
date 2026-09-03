<?php
session_start();
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
session_destroy();

header('Location: index.php');
exit;
