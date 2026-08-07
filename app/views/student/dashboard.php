<?php require_once '../app/views/layouts/header.php'; ?>
<div class="container my-4">
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold text-success"><i class="bi bi-mortarboard me-2"></i>Góc Học Tập Cá Nhân</h2>
            <p class="text-muted">Chào mừng học viên <strong><?= $_SESSION['fullname'] ?></strong>!</p>
        </div>
    </div>

    <div class="row g-4">
        <!-- Tiến độ học tập -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                <h5 class="fw-bold mb-3"><i class="bi bi-play-circle me-2"></i>Khóa học đang theo học</h5>
                
                <div class="mb-3 border p-3 rounded-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="fw-bold">Tiếng Anh Giao Tiếp Căn Bản</span>
                        <span class="text-primary fw-bold">65%</span>
                    </div>
                    <div class="progress" style="height: 10px;">
                        <div class="progress-bar bg-primary" style="width: 65%;"></div>
                    </div>
                </div>

                <div class="mb-3 border p-3 rounded-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="fw-bold">Luyện Ngữ Pháp & Từ Vựng A2-B1</span>
                        <span class="text-success fw-bold">40%</span>
                    </div>
                    <div class="progress" style="height: 10px;">
                        <div class="progress-bar bg-success" style="width: 40%;"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tiện ích học viên -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center">
                <i class="bi bi-patch-question text-warning fs-1 mb-2"></i>
                <h5 class="fw-bold">Kiểm Tra Trình Độ</h5>
                <p class="text-muted small">Đánh giá lại năng lực tiếng Anh của bạn với bài test xếp lớp tự động.</p>
                <a href="<?= URLROOT ?>/exam/placement" class="btn btn-warning fw-bold text-dark">Làm bài test ngay</a>
            </div>
        </div>
    </div>
</div>
<?php require_once '../app/views/layouts/footer.php'; ?>