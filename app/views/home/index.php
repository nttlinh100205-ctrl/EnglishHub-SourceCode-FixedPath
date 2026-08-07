<?php require_once dirname(__DIR__) . '/layouts/header.php'; ?>

<style>
:root {
  --eh-primary: #0d9488;
  --eh-primary-soft: #ccfbf1;
  --eh-ink: #1e293b;
  --eh-muted: #64748b;
  --eh-bg: #f8fafc;
  --eh-card: #ffffff;
  --eh-radius: 1rem;
}
body { background: var(--eh-bg); }
.home-hero {
  background: linear-gradient(160deg, #f0fdfa 0%, #e0f2fe 45%, #f8fafc 100%);
  border-bottom: 1px solid #e2e8f0;
  padding: 3rem 0 2.5rem;
}
.home-hero h1 { color: var(--eh-ink); font-weight: 800; letter-spacing: -.02em; }
.home-hero .lead { color: var(--eh-muted); max-width: 36rem; }
.btn-eh {
  background: var(--eh-primary); color: #fff; border: none;
  border-radius: 999px; padding: .65rem 1.4rem; font-weight: 600;
}
.btn-eh:hover { background: #0f766e; color: #fff; }
.btn-eh-outline {
  background: #fff; color: var(--eh-primary); border: 1.5px solid var(--eh-primary);
  border-radius: 999px; padding: .65rem 1.4rem; font-weight: 600;
}
.btn-eh-outline:hover { background: var(--eh-primary-soft); color: #0f766e; }
.section-title { font-weight: 800; color: var(--eh-ink); letter-spacing: -.02em; }
.section-sub { color: var(--eh-muted); }

/* Card kiểu DOL / practice test */
.pt-card {
  background: var(--eh-card);
  border-radius: 1.1rem;
  border: 1px solid #e2e8f0;
  overflow: hidden;
  transition: transform .2s, box-shadow .2s;
  height: 100%;
  display: flex; flex-direction: column;
}
.pt-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 12px 28px rgba(15, 23, 42, .08);
}
.pt-card .pt-img-wrap {
  position: relative;
  height: 160px;
  overflow: hidden;
  background: #e2e8f0;
}
.pt-card .pt-img-wrap img {
  width: 100%; height: 100%; object-fit: cover;
  transition: transform .35s;
}
.pt-card:hover .pt-img-wrap img { transform: scale(1.05); }
.pt-card .pt-badge {
  position: absolute; top: 12px; left: 12px;
  background: #fff; color: var(--eh-ink);
  font-size: .75rem; font-weight: 700;
  padding: .35rem .7rem; border-radius: 999px;
  box-shadow: 0 2px 8px rgba(0,0,0,.08);
}
.pt-card .pt-body { padding: 1rem 1.1rem 1.15rem; flex: 1; display: flex; flex-direction: column; }
.pt-card .pt-title {
  font-weight: 700; color: var(--eh-ink); font-size: 1.05rem;
  line-height: 1.35; margin-bottom: .35rem;
  display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
}
.pt-card .pt-meta { color: var(--eh-muted); font-size: .85rem; margin-bottom: .85rem; }
.pt-card .pt-btn {
  margin-top: auto;
  display: inline-flex; align-items: center; gap: .4rem;
  border: 1.5px solid #e2e8f0; background: #fff;
  border-radius: 999px; padding: .45rem 1rem;
  font-size: .875rem; font-weight: 600; color: var(--eh-ink);
  text-decoration: none; width: fit-content;
  transition: border-color .15s, background .15s, color .15s;
}
.pt-card .pt-btn:hover {
  border-color: var(--eh-primary); color: var(--eh-primary); background: var(--eh-primary-soft);
}
.pt-card .pt-btn.locked { color: var(--eh-muted); }
.feature-soft {
  background: #fff; border: 1px solid #e2e8f0; border-radius: 1rem;
  padding: 1.25rem; height: 100%; transition: box-shadow .2s;
}
.feature-soft:hover { box-shadow: 0 8px 20px rgba(15,23,42,.06); }
.feature-soft .ico {
  width: 44px; height: 44px; border-radius: 12px;
  display: flex; align-items: center; justify-content: center;
  font-size: 1.25rem; margin-bottom: .75rem;
}
.skill-pill {
  display: block; text-decoration: none; color: inherit;
  background: #fff; border: 1px solid #e2e8f0; border-radius: 1rem;
  padding: 1.1rem 1rem; text-align: center; height: 100%;
  transition: border-color .15s, box-shadow .15s;
}
.skill-pill:hover { border-color: #99f6e4; box-shadow: 0 6px 16px rgba(13,148,136,.1); }
.skill-pill .skill-ico {
  width: 52px; height: 52px; border-radius: 50%; margin: 0 auto .6rem;
  display: flex; align-items: center; justify-content: center; font-size: 1.4rem; color: #fff;
}
.level-chip {
  background: #fff; border: 1px solid #e2e8f0; border-radius: 1rem;
  padding: 1rem; height: 100%;
}
.level-chip .lv-code {
  font-weight: 800; color: var(--eh-primary); font-size: 1.1rem;
}
.cta-soft {
  background: linear-gradient(135deg, #f0fdfa, #e0f2fe);
  border: 1px solid #e2e8f0; border-radius: 1.25rem;
  padding: 2.5rem 1.5rem; text-align: center;
}
</style>

<?php
$lessons      = $data['lessons'] ?? [];
$courses      = $data['courses'] ?? [];
$quizzes      = $data['quizzes'] ?? [];
$features     = $data['features'] ?? [];
$skills       = $data['skills'] ?? [];
$levelCatalog = $data['levelCatalog'] ?? [];
$mockTests    = $data['mockTests'] ?? [];
$loggedIn     = !empty($data['logged_in']);

/* Ảnh Unsplash theo chủ đề — nhẹ, chuyên nghiệp */
$coverPool = [
  'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=600&q=80',
  'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=600&q=80',
  'https://images.unsplash.com/photo-1470071459604-3b5ec3a7fe05?w=600&q=80',
  'https://images.unsplash.com/photo-1469474968028-56623f02e42e?w=600&q=80',
  'https://images.unsplash.com/photo-1501785888041-af3ef285b470?w=600&q=80',
  'https://images.unsplash.com/photo-1472214103451-9374bd1c798e?w=600&q=80',
  'https://images.unsplash.com/photo-1426604966848-d7adac402bff?w=600&q=80',
  'https://images.unsplash.com/photo-1439066615861-d1af74d74000?w=600&q=80',
];
function eh_cover($i, $pool) {
  return $pool[$i % count($pool)];
}
/** Ảnh khóa học từ cột image — URL đầy đủ hoặc file trong public/uploads/courses */
function eh_course_image($course, $i, $pool) {
  $img = trim($course['image'] ?? '');
  if ($img === '' || $img === 'course_default.jpg') {
    return eh_cover($i, $pool);
  }
  if (preg_match('#^https?://#i', $img)) {
    return $img;
  }
  // file local: uploads/courses/xxx.jpg hoặc images/xxx
  $img = ltrim(str_replace('\\', '/', $img), '/');
  return URLROOT . '/uploads/courses/' . basename($img);
}
?>

<!-- HERO nhẹ -->
<section class="home-hero">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-7">
        <span class="badge rounded-pill mb-3 px-3 py-2" style="background:#ccfbf1;color:#0f766e;font-weight:600">
          Tự học tiếng Anh · CEFR A1–C1
        </span>
        <h1 class="display-5 mb-3">Luyện đề &amp; bài học<br>thiết kế rõ ràng, dễ theo dõi</h1>
        <p class="lead mb-4">Grammar · Listening · Reading · Writing · Vocabulary. Xem trước bài miễn phí; làm test sau khi đăng nhập.</p>
        <div class="d-flex flex-wrap gap-2">
          <?php if ($loggedIn): ?>
            <a href="<?= URLROOT ?>/student" class="btn btn-eh">Vào góc học tập</a>
          <?php else: ?>
            <a href="<?= URLROOT ?>/auth/login" class="btn btn-eh">Đăng nhập miễn phí</a>
            <a href="#practice-cards" class="btn btn-eh-outline">Xem bài luyện</a>
          <?php endif; ?>
        </div>
      </div>
      <div class="col-lg-5 d-none d-lg-block text-center">
        <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=480&q=80"
             alt="" class="img-fluid rounded-4 shadow-sm"
             style="max-height:280px;object-fit:cover;border:1px solid #e2e8f0" loading="lazy">
      </div>
    </div>
  </div>
</section>

<div class="container py-5">

  <!-- Tính năng -->
  <?php if (!empty($features)): ?>
  <section class="mb-5">
    <div class="text-center mb-4">
      <h2 class="section-title h3 mb-1">Bạn sẽ luyện những gì?</h2>
      <p class="section-sub mb-0">Nội dung theo kỹ năng &amp; cấp độ CEFR</p>
    </div>
    <div class="row g-3">
      <?php foreach (array_slice($features, 0, 6) as $f): ?>
      <div class="col-md-6 col-lg-4">
        <div class="feature-soft">
          <div class="ico" style="background:<?= htmlspecialchars($f['color']) ?>18;color:<?= htmlspecialchars($f['color']) ?>">
            <i class="bi <?= htmlspecialchars($f['icon']) ?>"></i>
          </div>
          <h6 class="fw-bold mb-1" style="color:var(--eh-ink)"><?= htmlspecialchars($f['title']) ?></h6>
          <p class="small mb-0" style="color:var(--eh-muted)"><?= htmlspecialchars($f['desc']) ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </section>
  <?php endif; ?>

  <!-- 4 kỹ năng pills -->
  <?php if (!empty($skills)): ?>
  <section class="mb-5">
    <h2 class="section-title h4 mb-3">Theo kỹ năng</h2>
    <div class="row g-3">
      <?php foreach ($skills as $sk): ?>
      <div class="col-6 col-md-3">
        <a class="skill-pill" href="<?= $loggedIn ? URLROOT.'/student' : URLROOT.'/auth/login' ?>">
          <div class="skill-ico" style="background:<?= htmlspecialchars($sk['color']) ?>">
            <i class="bi <?= htmlspecialchars($sk['icon']) ?>"></i>
          </div>
          <div class="fw-bold" style="color:var(--eh-ink)"><?= htmlspecialchars($sk['name']) ?></div>
          <small style="color:var(--eh-muted)"><?= htmlspecialchars($sk['desc']) ?></small>
        </a>
      </div>
      <?php endforeach; ?>
    </div>
  </section>
  <?php endif; ?>

  <!-- CARDS kiểu hình mẫu: ảnh + badge + title + meta + Làm bài -->
  <section class="mb-5" id="practice-cards">
    <div class="d-flex flex-wrap justify-content-between align-items-end gap-2 mb-3">
      <div>
        <h2 class="section-title h4 mb-1">Bài luyện nổi bật</h2>
        <p class="section-sub small mb-0"> Bấm Làm bài để bắt đầu</p>
      </div>
      <?php if ($loggedIn): ?>
        <a href="<?= URLROOT ?>/exam/practice" class="btn btn-sm btn-eh-outline">Luyện từ API →</a>
      <?php endif; ?>
    </div>
    <div class="row g-4">
      <?php
      $cardItems = [];
      // Ưu tiên lessons từ DB
      foreach ($lessons as $i => $ls) {
        $cardItems[] = [
          'title'  => $ls['title'] ?? 'Bài học',
          'meta'   => ($ls['course_title'] ?? '') . (!empty($ls['level_name']) ? ' · '.$ls['level_name'] : ''),
          'badge'  => !empty($ls['duration_minutes']) ? ((int)$ls['duration_minutes'].' phút') : 'Bài học',
          'img'    => eh_cover($i, $coverPool),
          'free'   => !empty($ls['is_preview']) || empty($ls['is_locked']),
          'href'   => (!empty($ls['is_preview']) || empty($ls['is_locked']) || $loggedIn)
                        ? (URLROOT . '/course/lesson/' . (int)($ls['id'] ?? 0))
                        : (URLROOT . '/auth/login'),
        ];
      }
      // Thêm mock tests
      foreach ($mockTests as $i => $m) {
        $cardItems[] = [
          'title' => $m['title'] ?? 'Mock test',
          'meta'  => ($m['attempts'] ?? '—') . ' lượt làm',
          'badge' => ((int)($m['tests'] ?? 0)) . ' bài',
          'img'   => eh_cover($i + 3, $coverPool),
          'free'  => false,
          'href'  => $loggedIn ? (URLROOT.'/exam/placement') : (URLROOT.'/auth/login'),
        ];
      }
      // Quizzes DB
      foreach ($quizzes as $i => $q) {
        $qid = (int)($q['id'] ?? 0);
        $href = $loggedIn
          ? (URLROOT.'/exam/'.((($q['quiz_type']??'')==='placement')?'placement':'practice'))
          : (URLROOT.'/auth/login');
        $cardItems[] = [
          'title' => $q['title'] ?? 'Quiz',
          'meta'  => ((int)($q['time_limit_min']??15)).' phút · '.($q['quiz_type']??'practice'),
          'badge' => ((int)($q['question_count']??10)).' câu',
          'img'   => eh_cover($i + 5, $coverPool),
          'free'  => false,
          'href'  => $href,
        ];
      }
      $cardItems = array_slice($cardItems, 0, 8);
      if (empty($cardItems)):
      ?>
        <div class="col-12"><p class="text-muted">Chưa có bài .</p></div>
      <?php else:
        foreach ($cardItems as $item):
      ?>
      <div class="col-sm-6 col-lg-3">
        <article class="pt-card">
          <div class="pt-img-wrap">
            <img src="<?= htmlspecialchars($item['img']) ?>" alt="" loading="lazy">
            <span class="pt-badge"><?= htmlspecialchars($item['badge']) ?></span>
          </div>
          <div class="pt-body">
            <h3 class="pt-title"><?= htmlspecialchars($item['title']) ?></h3>
            <p class="pt-meta mb-0"><?= htmlspecialchars($item['meta']) ?></p>
            <?php if (!empty($item['free'])): ?>
              <span class="badge mb-2 align-self-start" style="background:#ccfbf1;color:#0f766e;font-weight:600">Miễn phí</span>
            <?php endif; ?>
            <a href="<?= htmlspecialchars($item['href']) ?>" class="pt-btn <?= (!$loggedIn && empty($item['free'])) ? 'locked' : '' ?>">
              <?php if (!$loggedIn && empty($item['free'])): ?>
                <i class="bi bi-lock-fill"></i> Đăng nhập
              <?php else: ?>
                <i class="bi bi-play-fill text-danger"></i> Làm bài
              <?php endif; ?>
            </a>
          </div>
        </article>
      </div>
      <?php endforeach; endif; ?>
    </div>
  </section>

  <!-- Khóa học -->
  <section class="mb-5">
    <h2 class="section-title h4 mb-3">Khóa học</h2>
    <div class="row g-4">
      <?php foreach ($courses as $i => $c): ?>
      <div class="col-sm-6 col-lg-3">
        <article class="pt-card">
          <div class="pt-img-wrap">
            <img src="<?= htmlspecialchars(eh_course_image($c, $i + 2, $coverPool)) ?>" alt="<?= htmlspecialchars($c['title'] ?? '') ?>" loading="lazy"
                 onerror="this.src='<?= htmlspecialchars(eh_cover($i + 2, $coverPool)) ?>'">
            <span class="pt-badge"><?= htmlspecialchars($c['level_code'] ?? $c['level'] ?? 'A1') ?></span>
          </div>
          <div class="pt-body">
            <h3 class="pt-title"><?= htmlspecialchars($c['title']) ?></h3>
            <p class="pt-meta"><?= htmlspecialchars($c['subtitle'] ?? $c['description'] ?? '') ?></p>
            <p class="pt-meta small mb-2">
              <i class="bi bi-collection me-1"></i><?= (int)($c['total_lessons'] ?? 0) ?> bài
              <?php if (!empty($c['is_free'])): ?> · Miễn phí<?php endif; ?>
            </p>
            <a href="<?= URLROOT ?>/course/detail/<?= (int)($c['id'] ?? 0) ?>" class="pt-btn">
              <i class="bi bi-arrow-right"></i> Xem khóa
            </a>
          </div>
        </article>
      </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- Catalog level (gọn) -->
  <?php if (!empty($levelCatalog)): ?>
  <section class="mb-5" id="levels">
    <h2 class="section-title h4 mb-1">Theo cấp độ CEFR</h2>
    <p class="section-sub small mb-3">Pre-A1 → C1 · Grammar · Listening · Reading…</p>
    <div class="row g-3">
      <?php foreach ($levelCatalog as $lv): ?>
      <div class="col-6 col-md-4 col-lg-2">
        <div class="level-chip text-center">
          <div class="lv-code"><?= htmlspecialchars($lv['code']) ?></div>
          <div class="small fw-semibold" style="color:var(--eh-ink)"><?= htmlspecialchars($lv['name']) ?></div>
          <div class="small mt-1" style="color:var(--eh-muted)"><?= count($lv['items'] ?? []) ?> nhóm bài</div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </section>
  <?php endif; ?>

  <?php if (!$loggedIn): ?>
  <section class="cta-soft mb-2">
    <h3 class="section-title h4 mb-2">Sẵn sàng luyện đề?</h3>
    <p class="section-sub mb-3">Đăng nhập để làm Placement Test, lưu điểm và mở bài khóa.</p>
    <a href="<?= URLROOT ?>/auth/login" class="btn btn-eh me-2">Đăng nhập</a>
    <a href="<?= URLROOT ?>/auth/google" class="btn btn-eh-outline"><i class="bi bi-google me-1"></i> Google</a>
  </section>
  <?php endif; ?>

</div>

<?php require_once dirname(__DIR__) . '/layouts/footer.php'; ?>
