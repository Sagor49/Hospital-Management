<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../../config/db.php';

define('BASE_URL', '/hospital');
$pageTitle = 'Doctors';

$filter  = $_GET['status'] ?? 'all';
$perPage = 10;
$page    = max(1, (int)($_GET['page'] ?? 1));

$counts = $pdo->query(
    "SELECT COUNT(*) AS total,
            SUM(status = 'active') AS active,
            SUM(status = 'inactive') AS inactive
     FROM doctors"
)->fetch();

$where = '';
if ($filter === 'active' || $filter === 'inactive') {
    $where = 'WHERE d.status = ' . $pdo->quote($filter);
}

$totalRows  = (int)$pdo->query("SELECT COUNT(*) FROM doctors d $where")->fetchColumn();
$totalPages = max(1, (int)ceil($totalRows / $perPage));
$page       = min($page, $totalPages);
$offset     = ($page - 1) * $perPage;

$sql = "SELECT d.id, d.name, d.designation, d.experience_years, d.photo, d.status,
               dept.name AS department_name
        FROM doctors d
        JOIN departments dept ON dept.id = d.department_id
        $where
        ORDER BY d.created_at DESC
        LIMIT $perPage OFFSET $offset";
$doctors = $pdo->query($sql)->fetchAll();

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

require __DIR__ . '/../includes/admin_header.php';
?>

<?php if ($flash): ?>
  <div class="alert alert-<?php echo htmlspecialchars($flash['type']); ?> py-2">
    <?php echo htmlspecialchars($flash['message']); ?>
  </div>
<?php endif; ?>

<div class="admin-toolbar">
  <div class="btn-group admin-status-tabs" role="group">
    <a href="list.php?status=all" class="btn btn-sm <?php echo $filter === 'all' ? 'active' : ''; ?>">All <span class="admin-tab-count"><?php echo (int)$counts['total']; ?></span></a>
    <a href="list.php?status=active" class="btn btn-sm <?php echo $filter === 'active' ? 'active' : ''; ?>">Active <span class="admin-tab-count"><?php echo (int)$counts['active']; ?></span></a>
    <a href="list.php?status=inactive" class="btn btn-sm <?php echo $filter === 'inactive' ? 'active' : ''; ?>">Inactive <span class="admin-tab-count"><?php echo (int)$counts['inactive']; ?></span></a>
  </div>
  <a href="add.php" class="btn btn-dark-pill"><i class="bi bi-plus-lg"></i> Add Doctor</a>
</div>

<div class="admin-card">
  <div class="table-responsive">
    <table class="table admin-table align-middle mb-0">
      <thead>
        <tr>
          <th>Photo</th>
          <th>Name</th>
          <th>Department</th>
          <th>Designation</th>
          <th>Experience</th>
          <th>Status</th>
          <th class="text-end">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!$doctors): ?>
          <tr><td colspan="7" class="text-center text-muted py-4">No doctors found.</td></tr>
        <?php endif; ?>
        <?php foreach ($doctors as $doc): ?>
          <tr>
            <td>
              <?php if ($doc['photo']): ?>
                <img src="<?php echo BASE_URL; ?>/Image/doctors/<?php echo htmlspecialchars($doc['photo']); ?>" class="admin-thumb" alt="">
              <?php else: ?>
                <span class="admin-thumb admin-thumb-placeholder"><i class="bi bi-person-fill"></i></span>
              <?php endif; ?>
            </td>
            <td class="fw-semibold"><?php echo htmlspecialchars($doc['name']); ?></td>
            <td><?php echo htmlspecialchars($doc['department_name']); ?></td>
            <td><?php echo htmlspecialchars($doc['designation']); ?></td>
            <td><?php echo (int)$doc['experience_years']; ?> yrs</td>
            <td>
              <span class="admin-badge admin-badge-<?php echo $doc['status']; ?>">
                <?php echo ucfirst($doc['status']); ?>
              </span>
            </td>
            <td class="text-end">
              <a href="edit.php?id=<?php echo $doc['id']; ?>" class="admin-icon-btn" title="Edit">
                <i class="bi bi-pencil-fill"></i>
              </a>
              <form action="delete.php" method="post" class="d-inline" id="doctor-form-<?php echo $doc['id']; ?>">
                <input type="hidden" name="id" value="<?php echo $doc['id']; ?>">
                <input type="hidden" name="action" value="<?php echo $doc['status'] === 'active' ? 'deactivate' : 'activate'; ?>">
              </form>
              <button type="button"
                      class="admin-icon-btn admin-icon-btn-danger js-confirm-trigger"
                      title="<?php echo $doc['status'] === 'active' ? 'Deactivate' : 'Reactivate'; ?>"
                      data-form-id="doctor-form-<?php echo $doc['id']; ?>"
                      data-action="<?php echo $doc['status'] === 'active' ? 'deactivate' : 'activate'; ?>"
                      data-doctor-name="Dr. <?php echo htmlspecialchars($doc['name']); ?>"
                      data-confirm-label="<?php echo $doc['status'] === 'active' ? 'Yes, Deactivate' : 'Yes, Reactivate'; ?>">
                <i class="bi bi-<?php echo $doc['status'] === 'active' ? 'trash3-fill' : 'arrow-counterclockwise'; ?>"></i>
              </button>
              <?php if ($doc['status'] === 'inactive'): ?>
                <form action="delete.php" method="post" class="d-inline" id="doctor-purge-form-<?php echo $doc['id']; ?>">
                  <input type="hidden" name="id" value="<?php echo $doc['id']; ?>">
                  <input type="hidden" name="action" value="purge">
                </form>
                <button type="button"
                        class="admin-icon-btn admin-icon-btn-purge js-confirm-trigger"
                        title="Delete Permanently"
                        data-form-id="doctor-purge-form-<?php echo $doc['id']; ?>"
                        data-action="purge"
                        data-doctor-name="Dr. <?php echo htmlspecialchars($doc['name']); ?>"
                        data-confirm-label="Delete">
                  <i class="bi bi-x-octagon-fill"></i>
                </button>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <?php if ($totalRows > 0): ?>
  <div class="admin-pagination">
  <span class="admin-page-summary">
    Showing <?php echo $offset + 1; ?>&ndash;<?php echo min($offset + $perPage, $totalRows); ?> of <?php echo $totalRows; ?> doctors
  </span>
  <?php if ($totalPages > 1): ?>
    <div class="admin-page-btns">
      <?php if ($page > 1): ?>
        <a href="list.php?status=<?php echo urlencode($filter); ?>&page=<?php echo $page - 1; ?>" class="admin-page-btn">
          <i class="bi bi-chevron-left"></i> Prev
        </a>
      <?php else: ?>
        <span class="admin-page-btn disabled"><i class="bi bi-chevron-left"></i> Prev</span>
      <?php endif; ?>

      <span class="admin-page-info">Page <?php echo $page; ?> of <?php echo $totalPages; ?></span>

      <?php if ($page < $totalPages): ?>
        <a href="list.php?status=<?php echo urlencode($filter); ?>&page=<?php echo $page + 1; ?>" class="admin-page-btn">
          Next <i class="bi bi-chevron-right"></i>
        </a>
      <?php else: ?>
        <span class="admin-page-btn disabled">Next <i class="bi bi-chevron-right"></i></span>
      <?php endif; ?>
    </div>
  <?php endif; ?>
  </div>
  <?php endif; ?>
</div>

<!-- ============ CONFIRM ACTION MODAL ============ -->
<div class="modal fade admin-confirm-modal" id="confirmActionModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="admin-confirm-icon" id="confirmActionIcon"><i class="bi bi-exclamation-triangle-fill"></i></div>
      <h5 class="admin-confirm-title">Are you sure?</h5>
      <p class="admin-confirm-text" id="confirmActionText"></p>
      <div class="admin-confirm-actions">
        <button type="button" class="btn btn-outline-dark-pill" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-dark-pill" id="confirmActionBtn">Yes, Continue</button>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  var modalEl = document.getElementById('confirmActionModal');
  if (!modalEl || !window.bootstrap) return;
  var modal = new bootstrap.Modal(modalEl);
  var textEl = document.getElementById('confirmActionText');
  var iconEl = document.getElementById('confirmActionIcon');
  var confirmBtn = document.getElementById('confirmActionBtn');
  var pendingForm = null;

  document.querySelectorAll('.js-confirm-trigger').forEach(function (btn) {
    btn.addEventListener('click', function () {
      pendingForm = document.getElementById(btn.dataset.formId);
      var action = btn.dataset.action;

      iconEl.classList.remove('admin-confirm-icon-danger', 'admin-confirm-icon-success', 'admin-confirm-icon-purge');
      confirmBtn.classList.remove('admin-btn-danger-pill', 'btn-dark-pill', 'admin-btn-purge-pill');
      textEl.classList.remove('admin-confirm-text-danger');

      if (action === 'deactivate') {
        textEl.textContent = 'Deactivate ' + btn.dataset.doctorName + '? They will be hidden from the public site but their data stays safe — you can reactivate anytime.';
        iconEl.classList.add('admin-confirm-icon-danger');
        confirmBtn.classList.add('admin-btn-danger-pill');
      } else if (action === 'purge') {
        textEl.textContent = 'Permanently delete ' + btn.dataset.doctorName + '? This removes them from the site and database completely, including their photo and schedule. This cannot be undone.';
        textEl.classList.add('admin-confirm-text-danger');
        iconEl.classList.add('admin-confirm-icon-purge');
        confirmBtn.classList.add('admin-btn-purge-pill');
      } else {
        textEl.textContent = 'Reactivate ' + btn.dataset.doctorName + '?';
        iconEl.classList.add('admin-confirm-icon-success');
        confirmBtn.classList.add('btn-dark-pill');
      }

      confirmBtn.textContent = btn.dataset.confirmLabel;
      modal.show();
    });
  });

  confirmBtn.addEventListener('click', function () {
    modal.hide();
    if (pendingForm) pendingForm.submit();
  });
});
</script>

<?php require __DIR__ . '/../includes/admin_footer.php'; ?>
