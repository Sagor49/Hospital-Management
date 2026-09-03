<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (empty($_SESSION['user_id'])) { http_response_code(401); exit; }
/**
 * AJAX endpoint — returns a small JSON list of doctors matching the
 * typed-in name, for the live search-box autosuggestion dropdown.
 */
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/config/db.php';

$search = trim($_GET['q'] ?? '');

if ($search === '') {
    echo json_encode([]);
    exit;
}

$stmt = $pdo->prepare(
    "SELECT d.id, d.name, d.designation, d.photo, dept.name AS department_name
     FROM doctors d
     JOIN departments dept ON dept.id = d.department_id
     WHERE d.status = 'active' AND d.name LIKE ?
     ORDER BY d.name ASC
     LIMIT 6"
);
$stmt->execute(['%' . $search . '%']);
echo json_encode($stmt->fetchAll(), JSON_UNESCAPED_UNICODE);
