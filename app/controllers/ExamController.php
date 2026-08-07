<?php
class ExamController extends Controller {

    public function __construct() {
        $apiFile = dirname(__DIR__) . '/services/PracticeApi.php';
        if (file_exists($apiFile)) {
            require_once $apiFile;
        }
    }

    public function placement() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['flash_error'] = 'Bạn cần đăng nhập để làm bài kiểm tra.';
            header('Location: ' . URLROOT . '/auth/login');
            exit;
        }

        $questionModel = $this->model('Question');
        $userModel = $this->model('User');
        $questions = $questionModel->getPlacementQuestions();

        $score = null;
        $levelResult = null;
        $review = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['answers'])) {
            $answers = $_POST['answers'];
            $right = 0;
            $total = count($questions);
            foreach ($questions as $q) {
                $qid = $q['id'];
                $userAns = strtoupper(trim($answers[$qid] ?? ''));
                $correct = strtoupper($q['correct_option']);
                $ok = ($userAns === $correct);
                if ($ok) $right++;
                $review[$qid] = [
                    'user' => $userAns,
                    'correct' => $correct,
                    'ok' => $ok,
                    'explanation' => $q['explanation'],
                ];
            }
            $percent = $total ? round($right / $total * 100) : 0;
            $levelResult = Question::suggestLevel($percent);
            $score = ['right' => $right, 'total' => $total, 'percent' => $percent];

            // Lưu attempt + cập nhật level user
            try {
                $stmt = Database::conn('learning')->prepare(
                    "INSERT INTO test_attempts (user_id, type, score, total_questions, level_result, status, submitted_at)
                     VALUES (:uid, 'placement', :score, :total, :level, 'submitted', NOW())"
                );
                $stmt->execute([
                    ':uid' => $_SESSION['user_id'],
                    ':score' => $right,
                    ':total' => $total,
                    ':level' => $levelResult,
                ]);
                $userModel->updateLevel($_SESSION['user_id'], $levelResult);
            } catch (Exception $e) {
                // ignore if table missing
            }
        }

        $this->view('exam/placement', [
            'title'        => 'Placement Test – Xếp lớp',
            'questions'    => $questions,
            'score'        => $score,
            'level_result' => $levelResult,
            'review'       => $review,
        ]);
    }

    public function quiz($id = null) {
        $this->placement(); // reuse for now
    }

    public function practice() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['flash_error'] = 'Bạn cần đăng nhập để làm bài luyện tập từ API.';
            header('Location: ' . URLROOT . '/auth/login');
            exit;
        }
        if (!class_exists('PracticeApi')) {
            require_once dirname(__DIR__) . '/services/PracticeApi.php';
        }

        $level    = $_GET['level'] ?? 'B1';
        $amount   = (int)($_GET['amount'] ?? 10);
        $category = isset($_GET['category']) ? (int)$_GET['category'] : 10;
        $difficulty = PracticeApi::mapDifficulty($level);

        $result = PracticeApi::fetchTriviaQuestions($amount, $difficulty, $category ?: null);
        $vocab  = PracticeApi::fetchIeltsWord();

        $score = null;
        $review = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['answers']) && is_array($_POST['answers'])) {
            $posted = $_POST['answers'];
            $correctMap = $_SESSION['practice_answers'] ?? [];
            $total = count($correctMap);
            $right = 0;
            foreach ($correctMap as $qid => $correct) {
                $userAns = trim($posted[$qid] ?? '');
                $isOk = ($userAns === $correct);
                if ($isOk) $right++;
                $review[$qid] = ['user' => $userAns, 'correct' => $correct, 'ok' => $isOk];
            }
            $score = ['right' => $right, 'total' => $total, 'percent' => $total ? round($right / $total * 100) : 0];
        }

        if (!empty($result['ok']) && !empty($result['questions'])) {
            $map = [];
            foreach ($result['questions'] as $q) {
                $map[$q['id']] = $q['correct'];
            }
            $_SESSION['practice_answers'] = $map;
        }

        $this->view('exam/practice', [
            'title'      => 'Luyện đề thật (API)',
            'questions'  => $result['questions'] ?? [],
            'api_ok'     => $result['ok'] ?? false,
            'api_message'=> $result['message'] ?? '',
            'source'     => $result['source'] ?? 'Open Trivia DB',
            'level'      => $level,
            'difficulty' => $difficulty,
            'amount'     => $amount,
            'category'   => $category,
            'categories' => PracticeApi::fetchCategories(),
            'score'      => $score,
            'review'     => $review,
            'vocab'      => ($vocab['ok'] ?? false) ? $vocab['word'] : null,
        ]);
    }
}
