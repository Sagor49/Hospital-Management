<?php
/**
 * One-time migration: creates the "users" and "password_resets" tables
 * needed for the public sign-up / login / forgot-password system.
 *
 * Visit this file once in your browser:
 *   http://localhost/hospital/migrate_users.php
 *
 * Safe to run more than once — it only creates tables that don't exist yet.
 */

require_once __DIR__ . '/config/db.php';

header('Content-Type: text/html; charset=utf-8');

function page($title, $bodyHtml, $ok = true) {
    $color = $ok ? '#1f4b43' : '#c62828';
    echo "<!DOCTYPE html><html><head><meta charset='utf-8'>
    <title>{$title}</title>
    <style>
      body{font-family:Poppins,Segoe UI,Arial,sans-serif;background:#f4f6f5;margin:0;padding:60px 20px;color:#1c2a26;}
      .box{max-width:560px;margin:0 auto;background:#fff;border-radius:16px;padding:40px;box-shadow:0 10px 40px rgba(0,0,0,0.08);}
      h1{font-size:1.3rem;color:{$color};margin-top:0;}
      a.btn{display:inline-block;margin-top:10px;margin-right:10px;padding:10px 20px;background:#1f4b43;color:#fff;border-radius:30px;text-decoration:none;font-size:0.92rem;}
      code{background:#f0f2f0;padding:2px 6px;border-radius:4px;}
    </style></head><body><div class='box'>{$bodyHtml}</div></body></html>";
    exit;
}

try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(120) NOT NULL,
        email VARCHAR(150) NOT NULL UNIQUE,
        password_hash VARCHAR(255) NOT NULL,
        remember_token VARCHAR(255) NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

    $pdo->exec("CREATE TABLE IF NOT EXISTS password_resets (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(150) NOT NULL,
        token VARCHAR(100) NOT NULL,
        expires_at DATETIME NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_pr_email (email),
        INDEX idx_pr_token (token)
    ) ENGINE=InnoDB CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
} catch (PDOException $e) {
    page('Migration failed', "<h1>Something went wrong</h1><p>" . htmlspecialchars($e->getMessage()) . "</p>
        <p>Please tell Claude this exact error message.</p>", false);
}

page('User accounts ready', "<h1>&#9989; User accounts are ready</h1>
    <p>The <code>users</code> and <code>password_resets</code> tables are set up.</p>
    <a class='btn' href='signup.php'>Go to Sign Up</a>
    <a class='btn' href='login.php'>Go to Login</a>
    <p style='margin-top:20px;color:#888;font-size:0.85rem;'>You can safely delete <code>migrate_users.php</code> now.</p>");
