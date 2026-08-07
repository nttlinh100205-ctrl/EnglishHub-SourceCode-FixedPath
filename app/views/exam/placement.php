<?php require_once '../app/views/layouts/header.php'; ?>
<div class="card shadow-sm border-0 rounded-4">
    <div class="card-body p-5">
        <h2 class="fw-bold text-center text-primary mb-4">Bài kiểm tra đầu vào (Placement Test)</h2>
        <p class="text-center text-muted mb-5">Hệ thống sẽ dựa vào điểm số của bài test này để gợi ý lộ trình học và khóa học phù hợp với trình độ của bạn (A1-C2).</p>
        
        <form>
            <!-- Câu hỏi 1 -->
            <div class="mb-4 p-4 bg-light rounded-3">
                <h5>1. Choose the correct answer: I _____ to the store yesterday.</h5>
                <div class="form-check mt-3">
                    <input class="form-check-input" type="radio" name="q1" id="q1a">
                    <label class="form-check-label" for="q1a">go</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="q1" id="q1b">
                    <label class="form-check-label" for="q1b">went</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="q1" id="q1c">
                    <label class="form-check-label" for="q1c">gone</label>
                </div>
            </div>
            
            <button type="button" class="btn btn-success btn-lg w-100 mt-3">Nộp bài & Nhận lộ trình</button>
        </form>
    </div>
</div>
<?php require_once '../app/views/layouts/footer.php'; ?>
