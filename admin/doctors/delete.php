<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: list.php');
    exit;
}

$id     = (int)($_POST['id'] ?? 0);
$action = $_POST['action'] ?? 'deactivate';

if ($id <= 0) {
    header('Location: list.php');
    exit;
}

if ($action === 'purge') {
    // Permanent delete — only allowed for doctors that are already inactive.
    $stmt = $pdo->prepare('SELECT photo, status FROM doctors WHERE id = ?');
    $stmt->execute([$id]);
    $doctor = $stmt->fetch();

    if (!$doctor) {
        $_SESSION['flash'] = [
            'type'    => 'danger',
            'message' => 'Doctor not found.',
        ];
    } elseif ($doctor['status'] !== 'inactive') {
        $_SESSION['flash'] = [
            'type'    => 'danger',
            'message' => 'Only deactivated doctors can be permanently deleted. Deactivate them first.',
        ];
    } else {
        // Related doctor_schedules rows are removed automatically via ON DELETE CASCADE.
        $del = $pdo->prepare('DELETE FROM doctors WHERE id = ? AND status = "inactive"');
        $del->execute([$id]);

        if ($del->rowCount() > 0) {
            if (!empty($doctor['photo'])) {
                $photoPath = __DIR__ . '/../../Image/doctors/' . $doctor['photo'];
                if (is_file($photoPath)) {
                    @unlink($photoPath);
                }
            }
            $_SESSION['flash'] = [
                'type'    => 'success',
                'message' => 'Doctor permanently deleted from the site and database.',
            ];
        } else {
            $_SESSION['flash'] = [
                'type'    => 'danger',
                'message' => 'Could not delete the doctor. Please try again.',
            ];
        }
    }

    header('Location: list.php');
    exit;
}

// ---- Soft delete / reactivate (existing behaviour) ----
$newStatus = $action === 'activate' ? 'active' : 'inactive';

$stmt = $pdo->prepare('UPDATE doctors SET status = ? WHERE id = ?');
$stmt->execute([$newStatus, $id]);

$_SESSION['flash'] = [
    'type'    => 'success',
    'message' => $newStatus === 'inactive'
        ? 'Doctor deactivated (soft-deleted). You can reactivate them anytime.'
        : 'Doctor reactivated.',
];

header('Location: list.php');
exit;
