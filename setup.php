<?php
/**
 * One-time database installer for City Care Hospital.
 *
 * Visit this file once in your browser:
 *   http://localhost/hospital/setup.php
 *
 * It connects to your XAMPP MySQL, creates the "city_care_hospital"
 * database, all tables (with proper foreign keys), the default admin
 * account, and a few starter doctors — automatically, no phpMyAdmin
 * steps needed.
 *
 * It safely refuses to run a second time once the database exists,
 * so it's fine to leave this file in place (though deleting it
 * afterwards is good practice).
 */

$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'city_care_hospital';

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
      ul{padding-left:20px;} li{margin-bottom:6px;}
    </style></head><body><div class='box'>{$bodyHtml}</div></body></html>";
    exit;
}

$mysqli = @new mysqli($DB_HOST, $DB_USER, $DB_PASS);
if ($mysqli->connect_errno) {
    page('Setup failed', "<h1>Could not connect to MySQL</h1>
        <p>{$mysqli->connect_error}</p>
        <p>Please make sure XAMPP's <strong>MySQL</strong> module is running (green in the Control Panel), then reload this page.</p>", false);
}

// ---- Guard: don't re-run if already installed ----
$dbCheck = $mysqli->query("SHOW DATABASES LIKE '{$DB_NAME}'");
if ($dbCheck && $dbCheck->num_rows > 0) {
    $mysqli->select_db($DB_NAME);
    $tblCheck = $mysqli->query("SHOW TABLES LIKE 'admins'");
    if ($tblCheck && $tblCheck->num_rows > 0) {
        page('Already installed', "<h1>✅ Database already set up</h1>
            <p>The <code>{$DB_NAME}</code> database and its tables already exist — nothing to do.</p>
            <a class='btn' href='admin/login.php'>Go to Admin Login</a>
            <a class='btn' href='doctors.php'>View Doctors Page</a>
            <p style='margin-top:20px;color:#888;font-size:0.85rem;'>You can safely delete <code>setup.php</code> now.</p>");
    }
}

// ---- Run the schema ----
$schemaPath = __DIR__ . '/database/schema.sql';
if (!is_file($schemaPath)) {
    page('Setup failed', "<h1>schema.sql not found</h1><p>Expected it at <code>database/schema.sql</code>.</p>", false);
}
$sql = file_get_contents($schemaPath);

if (!$mysqli->multi_query($sql)) {
    page('Setup failed', "<h1>Setup failed</h1><p>{$mysqli->error}</p>", false);
}
do {
    if ($result = $mysqli->store_result()) {
        $result->free();
    }
    if ($mysqli->errno) {
        page('Setup failed', "<h1>Setup failed partway through</h1><p>{$mysqli->error}</p>
            <p>Please tell Claude this exact error message.</p>", false);
    }
} while ($mysqli->more_results() && $mysqli->next_result());

$mysqli->close();

page('Setup complete', "<h1>✅ Database created successfully</h1>
    <p>The <code>{$DB_NAME}</code> database, all tables, the default admin account, and 4 starter doctors are ready.</p>
    <ul>
      <li>Admin panel: <code>admin/login.php</code> — username <code>admin</code>, password <code>Admin@123</code></li>
      <li>Public doctors page: <code>doctors.php</code></li>
    </ul>
    <a class='btn' href='admin/login.php'>Go to Admin Login</a>
    <a class='btn' href='doctors.php'>View Doctors Page</a>
    <p style='margin-top:20px;color:#888;font-size:0.85rem;'>For security, you can delete <code>setup.php</code> now — it won't run again anyway since the database already exists.</p>");
