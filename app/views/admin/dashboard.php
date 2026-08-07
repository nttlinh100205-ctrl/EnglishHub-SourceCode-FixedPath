<?php require_once '../app/views/layouts/header.php'; ?>
<div class="container my-4">
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold text-danger"><i class="bi bi-speedometer2 me-2"></i>Bảng Điều Khiển Quản Trị Viên</h2>
            <p class="text-muted">Chào mừng <strong><?= htmlspecialchars($_SESSION['fullname'] ?? $_SESSION['username'] ?? 'Admin') ?></strong> quay trở lại hệ thống!</p>
        </div>
    </div>

    <!-- Thống kê tổng quan -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card bg-danger text-white border-0 shadow-sm rounded-4 p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1 text-uppercase">Tổng Người Dùng</h6>
                        <h3 class="fw-bold mb-0"><?= count($data['users'] ?? []) ?></h3>
                    </div>
                    <i class="bi bi-people fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-primary text-white border-0 shadow-sm rounded-4 p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1 text-uppercase">Khóa Học Hệ Thống</h6>
                        <h3 class="fw-bold mb-0">12</h3>
                    </div>
                    <i class="bi bi-journal-bookmark fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white border-0 shadow-sm rounded-4 p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1 text-uppercase">Lượt Kiểm Tra</h6>
                        <h3 class="fw-bold mb-0">158</h3>
                    </div>
                    <i class="bi bi-file-earmark-check fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Danh sách người dùng -->
    <div class="card border-0 shadow-sm rounded-4 p-4">
        <h5 class="fw-bold mb-3"><i class="bi bi-person-lines-fill me-2"></i>Quản lý tài khoản hệ thống</h5>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Họ tên</th>
                        <th>Tên đăng nhập</th>
                        <th>Email</th>
                        <th>Quyền (Role)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data['users'] as $u): ?>
                    <tr>
                        <td>#<?= $u['id'] ?></td>
                        <td><?= htmlspecialchars($u['full_name']) ?></td>
                        <td><code><?= htmlspecialchars($u['username']) ?></code></td>
                        <td><?= htmlspecialchars($u['email']) ?></td>
                        <td>
                            <span class="badge bg-<?= ($u['role_code'] ?? '') === 'admin' ? 'danger' : 'success' ?>">
                                <?= ($u['role_code'] ?? '') === 'admin' ? 'Quản trị viên' : 'Học viên' ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php require_once '../app/views/layouts/footer.php'; ?>