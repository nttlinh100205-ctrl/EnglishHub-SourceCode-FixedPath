<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($data['title'] ?? 'EnglishHub') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        :root {
            --eh-primary: #4f46e5;
            --eh-primary-dark: #3730a3;
            --eh-success: #059669;
            --eh-bg: #f8fafc;
        }
        body { background: var(--eh-bg); min-height: 100vh; }
        .navbar-brand { letter-spacing: -0.5px; }
        .navbar { box-shadow: 0 1px 3px rgba(0,0,0,.06); }
        .card-hover { transition: transform .2s ease, box-shadow .2s ease; }
        .card-hover:hover { transform: translateY(-4px); box-shadow: 0 12px 24px rgba(0,0,0,.08) !important; }
        .lesson-icon {
            width: 48px; height: 48px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.25rem;
        }
        .progress-thin { height: 6px; border-radius: 99px; }
        .hero-student {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #2563eb 100%);
            border-radius: 1.25rem;
        }
        .stat-pill {
            background: rgba(255,255,255,.15);
            border-radius: 999px;
            padding: .35rem .9rem;
            font-size: .85rem;
        }
        .badge-level { font-weight: 600; letter-spacing: .02em; }
        .section-title { font-weight: 700; letter-spacing: -.02em; }
        .empty-state { padding: 2.5rem 1rem; color: #94a3b8; }
        .empty-state i { font-size: 2.5rem; opacity: .5; }
        .quiz-row, .lesson-row {
            border: 1px solid #e2e8f0;
            border-radius: .85rem;
            transition: border-color .15s, background .15s;
        }
        .quiz-row:hover, .lesson-row:hover {
            border-color: #c7d2fe;
            background: #f5f3ff;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg bg-white sticky-top">
  <div class="container">
    <a class="navbar-brand fw-bold text-primary fs-4" href="<?= URLROOT ?>">
        <i class="bi bi-translate me-1"></i>EnglishHub
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMain">
      <ul class="navbar-nav me-auto">
        <?php if (isset($_SESSION['role'])): ?>
            <?php if ($_SESSION['role'] === 'admin'): ?>
                <li class="nav-item">
                    <a class="nav-link fw-semibold text-danger" href="<?= URLROOT ?>/admin">
                        <i class="bi bi-shield-lock me-1"></i>Admin
                    </a>
                </li>
            <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link fw-semibold" href="<?= URLROOT ?>/student">
                        <i class="bi bi-house-door me-1"></i>Trang chủ
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-semibold" href="<?= URLROOT ?>/student">
                        <i class="bi bi-journal-bookmark me-1"></i>Khóa học
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-semibold" href="<?= URLROOT ?>/exam/placement">
                        <i class="bi bi-clipboard-check me-1"></i>Kiểm tra
                    </a>
                </li>
            <?php endif; ?>
        <?php endif; ?>
      </ul>
      <div class="d-flex align-items-center gap-2">
        <?php if (isset($_SESSION['user_id'])): ?>
            <div class="dropdown">
              <button class="btn btn-light border dropdown-toggle d-flex align-items-center gap-2 py-1 px-2" data-bs-toggle="dropdown">
                <span class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center" style="width:32px;height:32px">
                    <i class="bi bi-person-fill"></i>
                </span>
                <span class="d-none d-sm-inline"><?= htmlspecialchars($_SESSION['fullname'] ?? $_SESSION['username'] ?? 'User') ?></span>
                <span class="badge bg-<?= ($_SESSION['role'] ?? '') === 'admin' ? 'danger' : 'success' ?>">
                    <?= htmlspecialchars($_SESSION['role_name'] ?? $_SESSION['role'] ?? '') ?>
                </span>
              </button>
              <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                <li><a class="dropdown-item text-danger" href="<?= URLROOT ?>/auth/logout"><i class="bi bi-box-arrow-right me-2"></i>Đăng xuất</a></li>
              </ul>
            </div>
        <?php else: ?>
            <a href="<?= URLROOT ?>/auth/login" class="btn btn-outline-primary btn-sm">Đăng nhập</a>
            <a href="<?= URLROOT ?>/auth/register" class="btn btn-primary btn-sm">Đăng ký</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>
