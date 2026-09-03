<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (empty($_SESSION['user_id'])) { http_response_code(401); exit; }
/**
 * AJAX endpoint — returns just the doctor-grid HTML fragment for the
 * given search/department filters. Used by doctors.php's live search.
 */
require_once __DIR__ . '/config/db.php';

$search = trim($_GET['q'] ?? '');
$deptId = $_GET['department'] ?? '';

$conditions = ["d.status = 'active'"];
$params = [];

if ($search !== '') {
    $conditions[] = 'd.name LIKE ?';
    $params[] = '%' . $search . '%';
}
if ($deptId !== '' && ctype_digit((string)$deptId)) {
    $conditions[] = 'd.department_id = ?';
    $params[] = (int)$deptId;
}

$sql = 'SELECT d.id, d.name, d.designation, d.experience_years, d.bio, d.photo,
               dept.name AS department_name
        FROM doctors d
        JOIN departments dept ON dept.id = d.department_id
        WHERE ' . implode(' AND ', $conditions) . '
        ORDER BY d.name ASC';
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$doctors = $stmt->fetchAll();

require __DIR__ . '/partials/doctor-grid.php';
