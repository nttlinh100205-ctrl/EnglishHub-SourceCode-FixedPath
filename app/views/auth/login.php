<?php require_once dirname(__DIR__) . '/layouts/header.php'; ?>

<?php
$flash = $_SESSION['flash_error'] ?? '';
unset($_SESSION['flash_error']);
$error = $data['error'] ?? '';
if ($flash) $error = $flash;
$googleEnabled = !empty($data['google_enabled']);
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4 p-md-5">
                    <h3 class="text-center mb-1 fw-bold">Đăng nhập</h3>
                    <p class="text-center text-muted small mb-4">Học viên & Quản trị viên</p>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= htmlspecialchars($error) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <a href="<?= URLROOT ?>/auth/google"
                       class="btn btn-outline-dark w-100 d-flex align-items-center justify-content-center gap-2 py-2 mb-3">
                        <svg width="18" height="18" viewBox="0 0 48 48" aria-hidden="true">
                            <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                            <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                            <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
                            <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
                        </svg>
                        Đăng nhập bằng Google
                    </a>

                    <?php if (!$googleEnabled): ?>
                        <p class="text-muted small text-center mb-3">
                            <i class="bi bi-info-circle me-1"></i>
                            Cấu hình <code>GOOGLE_CLIENT_ID</code> trong <code>config/config.php</code> để bật Google.
                        </p>
                    <?php endif; ?>

                    <div class="d-flex align-items-center gap-2 mb-3">
                        <hr class="flex-grow-1">
                        <span class="text-muted small">hoặc email</span>
                        <hr class="flex-grow-1">
                    </div>

                    <form method="POST" action="<?= URLROOT ?>/auth/login">
                        <div class="mb-3">
                            <label class="form-label">Email / Tên đăng nhập</label>
                            <input type="text" name="account" class="form-control form-control-lg"
                                   placeholder="admin@englishhub.com"
                                   value="<?= htmlspecialchars($_POST['account'] ?? '') ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mật khẩu</label>
                            <input type="password" name="password" class="form-control form-control-lg"
                                   placeholder="••••••••" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg w-100">Đăng nhập</button>
                    </form>

                    <p class="text-center mt-4 mb-0 text-muted small">
                        Chưa có tài khoản? Dùng Google ở trên hoặc
                        <a href="<?= URLROOT ?>/auth/register" class="text-decoration-none fw-semibold">đăng ký</a>.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once dirname(__DIR__) . '/layouts/footer.php'; ?>
