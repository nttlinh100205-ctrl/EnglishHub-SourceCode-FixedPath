<?php require_once dirname(__DIR__) . '/layouts/header.php'; ?>

<style>
.home-hero {
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #2563eb 100%);
    color: #fff;
    padding: 3.5rem 0;
}
.home-hero .badge-soft { background: #fff; color: #4f46e5; }
.card-hover { transition: transform .2s, box-shadow .2s; }
.card-hover:hover { transform: translateY(-4px); box-shadow: 0 12px 24px rgba(0,0,0,.1) !important; }
.lesson-icon {
    width: 48px; height: 48px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0;
}
.lesson-row {
    border: 1px solid #e2e8f0; border-radius: .85rem;
    transition: border-color .15s, background .15s;
}
.lesson-row:hover { border-color: #c7d2fe; background: #f5f3ff; }
.section-title { font-weight: 700; }
</style>

<?php
$lessons   = $data['lessons'] ?? [];
$courses   = $data['courses'] ?? [];
$quizzes   = $data['quizzes'] ?? [];
$loggedIn  = !empty($data['logged_in']);
$contentTypeIcon = [
    'video'     => ['bi-play-circle-fill', 'bg-danger bg-opacity-10 text-danger'],
    'text'      => ['bi-file-text-fill', 'bg-primary bg-opacity-10 text-primary'],
    'flashcard' => ['bi-card-heading', 'bg-warning bg-opacity-10 text-warning'],
    'quiz'      => ['bi-question-circle-fill', 'bg-success bg-opacity-10 text-success'],
    'mixed'     => ['bi-collection-play-fill', 'bg-info bg-opacity-10 text-info'],
    'audio'     => ['bi-headphones', 'bg-secondary bg-opacity-10 text-secondary'],
];
?>

<!-- Hero -->
<section class="home-hero">
    <div class="container py-4">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <span class="badge bg-white text-primary mb-3 px-3 py-2">Học tiếng Anh thông minh</span>
                <h1 class="display-5 fw-bold mb-3">EnglishHub — Lộ trình cá nhân hóa cho mọi trình độ</h1>
                <p class="fs-5 opacity-90 mb-4">Bài học mẫu miễn phí xem trước. Bài kiểm tra xếp lớp và luyện tập — đăng nhập để bắt đầu.</p>
                <div class="d-flex flex-wrap gap-2">
                    <?php if ($loggedIn): ?>
                        <a href="<?= URLROOT ?>/student" class="btn btn-light btn-lg fw-semibold text-primary">
                            <i class="bi bi-house-door me-1"></i> Vào góc học tập
                        </a>
                    <?php else: ?>
                        <a href="<?= URLROOT ?>/auth/login" class="btn btn-light btn-lg fw-semibold text-primary">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Đăng nhập để làm test
                        </a>
                        <a href="#lessons" class="btn btn-outline-light btn-lg">Xem bài học mẫu</a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-lg-5 d-none d-lg-block text-center">
                <i class="bi bi-translate" style="font-size:8rem;opacity:.25"></i>
            </div>
        </div>
    </div>
</section>

<div class="container my-5">

    <!-- Khóa học -->
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-end mb-3">
            <div>
                <h3 class="section-title mb-1">Khóa học nổi bật</h3>
                <p class="text-muted mb-0 small">Khám phá lộ trình phù hợp với bạn</p>
            </div>
        </div>
        <div class="row g-3">
            <?php foreach ($courses as $c): ?>
            <div class="col-sm-6 col-lg-3">
                <div class="card border-0 shadow-sm rounded-4 card-hover h-100 overflow-hidden">
                    <div class="bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="height:100px">
                        <i class="bi bi-book text-primary" style="font-size:2rem;opacity:.4"></i>
                    </div>
                    <div class="card-body p-3">
                        <div class="d-flex gap-1 flex-wrap mb-2">
                            <?php if (!empty($c['level_code']) || !empty($c['level_name'])): ?>
                                <span class="badge bg-secondary"><?= htmlspecialchars($c['level_code'] ?? $c['level_name']) ?></span>
                            <?php endif; ?>
                            <?php if (!empty($c['is_free'])): ?>
                                <span class="badge bg-success">Miễn phí</span>
                            <?php endif; ?>
                        </div>
                        <h6 class="fw-bold mb-1"><?= htmlspecialchars($c['title']) ?></h6>
                        <p class="text-muted small mb-2"><?= htmlspecialchars($c['subtitle'] ?? '') ?></p>
                        <div class="small text-muted mb-2">
                            <i class="bi bi-collection-play me-1"></i><?= (int)($c['total_lessons'] ?? 0) ?> bài
                            · <i class="bi bi-clock me-1"></i><?= number_format((float)($c['duration_hours'] ?? 0), 1) ?>h
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Bài học mẫu (public) -->
    <section class="mb-5" id="lessons">
        <div class="d-flex justify-content-between align-items-end mb-3">
            <div>
                <h3 class="section-title mb-1"><i class="bi bi-play-circle text-primary me-2"></i>Bài học mẫu</h3>
                <p class="text-muted mb-0 small">Xem trước nội dung — một số bài mở miễn phí</p>
            </div>
        </div>
        <div class="row g-3">
            <?php foreach ($lessons as $ls):
                $ct = $ls['content_type'] ?? 'mixed';
                $icon = $contentTypeIcon[$ct] ?? $contentTypeIcon['mixed'];
                $isPreview = !empty($ls['is_preview']);
            ?>
            <div class="col-md-6">
                <div class="lesson-row p-3 d-flex align-items-center gap-3 bg-white shadow-sm">
                    <div class="lesson-icon <?= $icon[1] ?>">
                        <i class="bi <?= $icon[0] ?>"></i>
                    </div>
                    <div class="flex-grow-1 min-w-0">
                        <div class="fw-semibold text-truncate"><?= htmlspecialchars($ls['title']) ?></div>
                        <small class="text-muted">
                            <?= htmlspecialchars($ls['course_title'] ?? '') ?>
                            <?php if (!empty($ls['level_name'])): ?> · <?= htmlspecialchars($ls['level_name']) ?><?php endif; ?>
                            <?php if (!empty($ls['duration_minutes'])): ?> · <?= (int)$ls['duration_minutes'] ?> phút<?php endif; ?>
                        </small>
                    </div>
                    <?php if ($isPreview): ?>
                        <span class="badge bg-success">Xem trước</span>
                        <a href="<?= $loggedIn ? URLROOT . '/student' : URLROOT . '/auth/login' ?>" class="btn btn-sm btn-outline-primary">Học</a>
                    <?php else: ?>
                        <span class="badge bg-secondary"><i class="bi bi-lock-fill me-1"></i>Đăng nhập</span>
                        <a href="<?= URLROOT ?>/auth/login" class="btn btn-sm btn-outline-secondary">Mở khóa</a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Bài test — bắt buộc đăng nhập -->
    <section class="mb-5" id="tests">
        <div class="d-flex justify-content-between align-items-end mb-3">
            <div>
                <h3 class="section-title mb-1"><i class="bi bi-clipboard2-check text-success me-2"></i>Bài kiểm tra</h3>
                <p class="text-muted mb-0 small">
                    <i class="bi bi-shield-lock me-1"></i>Yêu cầu đăng nhập để làm bài và lưu kết quả
                </p>
            </div>
        </div>
        <div class="row g-3">
            <?php foreach ($quizzes as $q):
                $qid = (int)($q['id'] ?? 0);
                $testUrl = $loggedIn
                    ? (URLROOT . '/exam/' . (($q['quiz_type'] ?? '') === 'placement' ? 'placement' : 'quiz/' . $qid))
                    : (URLROOT . '/auth/login');
            ?>
            <div class="col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm rounded-4 h-100 card-hover">
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="mb-3">
                            <span class="badge bg-<?= ($q['quiz_type'] ?? '') === 'placement' ? 'warning text-dark' : 'success' ?>">
                                <?= htmlspecialchars($q['quiz_type'] ?? 'practice') ?>
                            </span>
                        </div>
                        <h6 class="fw-bold flex-grow-1"><?= htmlspecialchars($q['title'] ?? 'Bài kiểm tra') ?></h6>
                        <p class="text-muted small mb-3">
                            <i class="bi bi-clock me-1"></i><?= (int)($q['time_limit_min'] ?? 15) ?> phút
                            <?php if (isset($q['pass_score'])): ?>
                                · Đạt <?= (int)$q['pass_score'] ?>%
                            <?php endif; ?>
                        </p>
                        <?php if ($loggedIn): ?>
                            <a href="<?= $testUrl ?>" class="btn btn-success w-100">
                                <i class="bi bi-pencil-square me-1"></i> Làm bài
                            </a>
                        <?php else: ?>
                            <a href="<?= URLROOT ?>/auth/login" class="btn btn-outline-secondary w-100">
                                <i class="bi bi-lock-fill me-1"></i> Đăng nhập để làm
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- CTA -->
    <?php if (!$loggedIn): ?>
    <section class="text-center py-5 px-3 bg-white rounded-4 shadow-sm">
        <h4 class="fw-bold mb-2">Sẵn sàng kiểm tra trình độ?</h4>
        <p class="text-muted mb-3">Đăng nhập bằng email hoặc Google để làm Placement Test và nhận lộ trình phù hợp.</p>
        <a href="<?= URLROOT ?>/auth/login" class="btn btn-primary btn-lg px-4 me-2">Đăng nhập</a>
        <a href="<?= URLROOT ?>/auth/google" class="btn btn-outline-dark btn-lg px-4">
            <i class="bi bi-google me-1"></i> Google
        </a>
    </section>
    <?php endif; ?>
</div>

<?php require_once dirname(__DIR__) . '/layouts/footer.php'; ?>
