<?php require_once dirname(__DIR__) . '/layouts/header.php'; ?>

<?php
$courses  = $data['courses']  ?? [];
$lessons  = $data['lessons']  ?? [];
$quizzes  = $data['quizzes']  ?? [];
$attempts = $data['attempts'] ?? [];
$enrolled = $data['enrolled'] ?? [];
$contentTypeIcon = [
    'video'     => ['bi-play-circle-fill', 'bg-danger bg-opacity-10 text-danger'],
    'text'      => ['bi-file-text-fill', 'bg-primary bg-opacity-10 text-primary'],
    'flashcard' => ['bi-card-heading', 'bg-warning bg-opacity-10 text-warning'],
    'quiz'      => ['bi-question-circle-fill', 'bg-success bg-opacity-10 text-success'],
    'mixed'     => ['bi-collection-play-fill', 'bg-info bg-opacity-10 text-info'],
];
?>

<div class="container my-4 pb-5">

    <!-- Hero chào mừng -->
    <div class="hero-student text-white p-4 p-md-5 mb-4 shadow-sm">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <p class="mb-1 opacity-75 small text-uppercase fw-semibold">Góc học tập</p>
                <h2 class="fw-bold mb-2">Xin chào, <?= htmlspecialchars($_SESSION['fullname'] ?? $_SESSION['username'] ?? 'bạn') ?>! 👋</h2>
                <p class="mb-3 opacity-90">Tiếp tục lộ trình tiếng Anh của bạn — bài học, khóa học và kiểm tra đều ở đây.</p>
                <div class="d-flex flex-wrap gap-2">
                    <span class="stat-pill"><i class="bi bi-journal-bookmark me-1"></i><?= count($courses) ?> khóa học</span>
                    <span class="stat-pill"><i class="bi bi-play-btn me-1"></i><?= count($lessons) ?> bài học</span>
                    <span class="stat-pill"><i class="bi bi-clipboard-check me-1"></i><?= count($quizzes) ?> bài thi</span>
                </div>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <a href="<?= URLROOT ?>/exam/placement" class="btn btn-light fw-semibold text-primary px-4">
                    <i class="bi bi-lightning-charge-fill me-1"></i> Test trình độ
                </a>
            </div>
        </div>
    </div>

    <!-- Khóa học đang học (nếu có enrollment) -->
    <?php if (!empty($enrolled)): ?>
    <div class="mb-4">
        <h5 class="section-title mb-3"><i class="bi bi-bookmark-check text-primary me-2"></i>Khóa học của bạn</h5>
        <div class="row g-3">
            <?php foreach ($enrolled as $ec): ?>
            <div class="col-md-6 col-xl-4">
                <div class="card border-0 shadow-sm rounded-4 card-hover h-100">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="badge bg-primary badge-level"><?= htmlspecialchars($ec['level_name'] ?? 'General') ?></span>
                            <small class="text-muted"><?= number_format((float)($ec['progress_pct'] ?? 0), 0) ?>%</small>
                        </div>
                        <h6 class="fw-bold mb-2"><?= htmlspecialchars($ec['title']) ?></h6>
                        <div class="progress progress-thin mb-2">
                            <div class="progress-bar bg-primary" style="width: <?= min(100, (float)($ec['progress_pct'] ?? 0)) ?>%"></div>
                        </div>
                        <a href="<?= URLROOT ?>/course/detail/<?= (int)$ec['id'] ?>" class="btn btn-sm btn-outline-primary w-100">Tiếp tục học</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Danh sách khóa học -->
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="section-title mb-0"><i class="bi bi-grid-3x3-gap text-indigo me-2"></i>Khóa học</h5>
        </div>
        <?php if (empty($courses)): ?>
            <div class="card border-0 shadow-sm rounded-4">
                <div class="empty-state text-center">
                    <i class="bi bi-journal-x d-block mb-2"></i>
                    <p class="mb-0">Chưa có khóa học nào. Admin hãy thêm khóa học trong CSDL.</p>
                </div>
            </div>
        <?php else: ?>
            <div class="row g-3">
                <?php foreach ($courses as $c): ?>
                <div class="col-sm-6 col-lg-4 col-xl-3">
                    <div class="card border-0 shadow-sm rounded-4 card-hover h-100 overflow-hidden">
                        <div class="bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="height:120px">
                            <?php if (!empty($c['thumbnail'])): ?>
                                <img src="<?= htmlspecialchars($c['thumbnail']) ?>" alt="" class="w-100 h-100 object-fit-cover">
                            <?php else: ?>
                                <i class="bi bi-book text-primary" style="font-size:2.5rem;opacity:.4"></i>
                            <?php endif; ?>
                        </div>
                        <div class="card-body p-3">
                            <div class="d-flex gap-1 flex-wrap mb-2">
                                <?php if (!empty($c['level_name'])): ?>
                                    <span class="badge bg-secondary badge-level"><?= htmlspecialchars($c['level_code'] ?? $c['level_name']) ?></span>
                                <?php endif; ?>
                                <?php if (!empty($c['skill_name'])): ?>
                                    <span class="badge bg-info text-dark"><?= htmlspecialchars($c['skill_name']) ?></span>
                                <?php endif; ?>
                                <?php if (!empty($c['is_free'])): ?>
                                    <span class="badge bg-success">Miễn phí</span>
                                <?php endif; ?>
                            </div>
                            <h6 class="fw-bold mb-1 text-truncate" title="<?= htmlspecialchars($c['title']) ?>">
                                <?= htmlspecialchars($c['title']) ?>
                            </h6>
                            <p class="text-muted small mb-2" style="min-height:2.4em">
                                <?= htmlspecialchars(mb_strimwidth($c['subtitle'] ?? $c['description'] ?? '', 0, 70, '…')) ?>
                            </p>
                            <div class="d-flex justify-content-between align-items-center small text-muted mb-2">
                                <span><i class="bi bi-collection-play me-1"></i><?= (int)($c['total_lessons'] ?? 0) ?> bài</span>
                                <span><i class="bi bi-clock me-1"></i><?= number_format((float)($c['duration_hours'] ?? 0), 1) ?>h</span>
                            </div>
                            <a href="<?= URLROOT ?>/course/detail/<?= (int)$c['id'] ?>" class="btn btn-sm btn-primary w-100">
                                Xem khóa học
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="row g-4">
        <!-- Bài học gần đây -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <h5 class="section-title mb-3">
                        <i class="bi bi-play-circle text-primary me-2"></i>Bài học
                    </h5>
                    <?php if (empty($lessons)): ?>
                        <div class="empty-state text-center">
                            <i class="bi bi-camera-video-off d-block mb-2"></i>
                            <p class="mb-0">Chưa có bài học. Thêm dữ liệu bảng <code>lessons</code> để hiển thị.</p>
                        </div>
                    <?php else: ?>
                        <div class="d-flex flex-column gap-2">
                            <?php foreach ($lessons as $ls):
                                $ct = $ls['content_type'] ?? 'mixed';
                                $icon = $contentTypeIcon[$ct] ?? $contentTypeIcon['mixed'];
                            ?>
                            <div class="lesson-row p-3 d-flex align-items-center gap-3">
                                <div class="lesson-icon <?= $icon[1] ?>">
                                    <i class="bi <?= $icon[0] ?>"></i>
                                </div>
                                <div class="flex-grow-1 min-w-0">
                                    <div class="fw-semibold text-dark text-truncate">
                                        <?= htmlspecialchars($ls['title']) ?>
                                    </div>
                                    <small class="text-muted">
                                        <?= htmlspecialchars($ls['course_title'] ?? '') ?>
                                        <?php if (!empty($ls['level_name'])): ?>
                                            · <?= htmlspecialchars($ls['level_name']) ?>
                                        <?php endif; ?>
                                        <?php if (!empty($ls['duration_minutes'])): ?>
                                            · <?= (int)$ls['duration_minutes'] ?> phút
                                        <?php endif; ?>
                                    </small>
                                </div>
                                <?php if (!empty($ls['is_preview'])): ?>
                                    <span class="badge bg-success-subtle text-success">Preview</span>
                                <?php endif; ?>
                                <a href="<?= URLROOT ?>/course/detail/<?= (int)($ls['course_id'] ?? 0) ?>" class="btn btn-sm btn-outline-primary flex-shrink-0">
                                    Học
                                </a>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Cột phải: Quiz + lịch sử -->
        <div class="col-lg-5">
            <!-- Bài thi -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h5 class="section-title mb-3">
                        <i class="bi bi-clipboard2-check text-success me-2"></i>Bài thi & Trắc nghiệm
                    </h5>
                    <?php if (empty($quizzes)): ?>
                        <div class="empty-state text-center py-3">
                            <i class="bi bi-journal-x d-block mb-2"></i>
                            <p class="mb-0 small">Chưa có bài thi nào.</p>
                        </div>
                    <?php else: ?>
                        <div class="d-flex flex-column gap-2">
                            <?php foreach (array_slice($quizzes, 0, 6) as $q): ?>
                            <div class="quiz-row p-3 d-flex justify-content-between align-items-center">
                                <div class="me-2 min-w-0">
                                    <div class="fw-semibold text-truncate"><?= htmlspecialchars($q['title'] ?? 'Bài kiểm tra') ?></div>
                                    <small class="text-muted">
                                        <i class="bi bi-clock me-1"></i><?= (int)($q['time_limit_min'] ?? 15) ?> phút
                                        <?php if (!empty($q['quiz_type'])): ?>
                                            · <?= htmlspecialchars($q['quiz_type']) ?>
                                        <?php endif; ?>
                                    </small>
                                </div>
                                <a href="#" class="btn btn-sm btn-success flex-shrink-0">Làm bài</a>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Lịch sử -->
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h5 class="section-title mb-3">
                        <i class="bi bi-trophy text-warning me-2"></i>Lịch sử làm bài
                    </h5>
                    <?php if (empty($attempts)): ?>
                        <div class="empty-state text-center py-3">
                            <i class="bi bi-hourglass d-block mb-2"></i>
                            <p class="mb-0 small">Bạn chưa làm bài thi nào.</p>
                        </div>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($attempts as $att): ?>
                            <div class="list-group-item px-0 d-flex justify-content-between align-items-center border-0 border-bottom">
                                <div>
                                    <span class="fw-semibold d-block"><?= htmlspecialchars($att['quiz_title'] ?? '') ?></span>
                                    <small class="text-muted">
                                        <?= date('d/m/Y H:i', strtotime($att['started_at'] ?? $att['submitted_at'] ?? 'now')) ?>
                                    </small>
                                </div>
                                <span class="badge rounded-pill bg-success">
                                    <?= $att['total_score'] ?? $att['percent'] ?? 0 ?>đ
                                </span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once dirname(__DIR__) . '/layouts/footer.php'; ?>
