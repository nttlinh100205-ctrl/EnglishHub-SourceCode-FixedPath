<?php require_once dirname(__DIR__) . '/layouts/header.php'; ?>
<div class="container my-4">
  <h2 class="fw-bold mb-3" style="color:#0f766e">Bảng quản trị</h2>

  <?php $dbStatus = $data['db_status'] ?? []; if ($dbStatus): ?>
  <div class="row g-3 mb-4">
    <?php foreach ($dbStatus as $key => $st): ?>
    <div class="col-md-6">
      <div class="card border-0 shadow-sm rounded-4 h-100 border-start border-4 <?= !empty($st['ok']) ? 'border-success' : 'border-danger' ?>">
        <div class="card-body p-3">
          <div class="d-flex justify-content-between">
            <strong><?= htmlspecialchars($st['name'] ?? $key) ?></strong>
            <span class="badge bg-<?= !empty($st['ok']) ? 'success' : 'danger' ?>"><?= !empty($st['ok']) ? 'Connected' : 'Error' ?></span>
          </div>
          <?php if (!empty($st['ok'])): ?>
            <p class="small text-muted mb-0 mt-1">
              <?php if (isset($st['courses'])): ?>courses: <?= (int)$st['courses'] ?><?php endif; ?>
              <?php if (isset($st['tables'])): ?>tables: <?= (int)$st['tables'] ?><?php endif; ?>
              <?php if ($key === 'learning'): ?> · <em>PRIMARY (users, courses, lessons)</em><?php endif; ?>
              <?php if ($key === 'hub'): ?> · <em>SECONDARY</em><?php endif; ?>
            </p>
          <?php else: ?>
            <p class="small text-danger mb-0 mt-1"><?= htmlspecialchars($st['error'] ?? 'Lỗi') ?></p>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>

  <div class="row g-3 mb-4">
    <div class="col-md-4">
      <a href="<?= URLROOT ?>/admin/courses" class="card border-0 shadow-sm rounded-4 text-decoration-none h-100">
        <div class="card-body p-4">
          <i class="bi bi-journal-bookmark fs-2 text-success"></i>
          <h5 class="fw-bold mt-2 text-dark">Khóa học &amp; Bài học</h5>
          <p class="text-muted small mb-0">Thêm / sửa khóa học, bài học trong database</p>
        </div>
      </a>
    </div>
    <div class="col-md-4">
      <div class="card border-0 shadow-sm rounded-4 h-100">
        <div class="card-body p-4">
          <i class="bi bi-people fs-2 text-primary"></i>
          <h5 class="fw-bold mt-2"><?= count($data['users'] ?? []) ?> người dùng</h5>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card border-0 shadow-sm rounded-4 h-100">
        <div class="card-body p-4">
          <i class="bi bi-collection fs-2 text-warning"></i>
          <h5 class="fw-bold mt-2"><?= count($data['courses'] ?? []) ?> khóa học</h5>
        </div>
      </div>
    </div>
  </div>

  <h5 class="fw-bold mb-2">Người dùng</h5>
  <div class="table-responsive bg-white shadow-sm rounded-4">
    <table class="table table-hover mb-0">
      <thead class="table-light"><tr><th>ID</th><th>Username</th><th>Email</th><th>Họ tên</th><th>Role</th><th>Level</th></tr></thead>
      <tbody>
        <?php foreach (($data['users'] ?? []) as $u): ?>
        <tr>
          <td><?= (int)$u['id'] ?></td>
          <td><?= htmlspecialchars($u['username']) ?></td>
          <td><?= htmlspecialchars($u['email']) ?></td>
          <td><?= htmlspecialchars($u['full_name'] ?? '') ?></td>
          <td><span class="badge bg-<?= ($u['role']??'')==='admin'?'danger':'secondary' ?>"><?= htmlspecialchars($u['role']??'') ?></span></td>
          <td><?= htmlspecialchars($u['level']??'') ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php require_once dirname(__DIR__) . '/layouts/footer.php'; ?>
