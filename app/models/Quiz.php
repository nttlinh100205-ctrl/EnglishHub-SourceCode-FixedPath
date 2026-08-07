<?php
/**
 * Quiz helpers trên schema english_learning
 * - questions (type: lesson|placement|quiz)
 * - test_attempts
 */
class Quiz {
    private $conn;

    public function __construct() {
        $db = new Database('learning');
        $this->conn = $db->getConnection();
    }

    public function getAllQuizzes() {
        $rows = [];
        $cntP = (int)$this->conn->query("SELECT COUNT(*) FROM questions WHERE type = 'placement'")->fetchColumn();
        if ($cntP > 0) {
            $rows[] = [
                'id' => 1,
                'title' => 'Placement Test – Xếp lớp (DB)',
                'time_limit_min' => 20,
                'quiz_type' => 'placement',
                'pass_score' => 60,
                'question_count' => $cntP,
            ];
        }
        $cntQ = (int)$this->conn->query("SELECT COUNT(*) FROM questions WHERE type = 'quiz'")->fetchColumn();
        if ($cntQ > 0) {
            $rows[] = [
                'id' => 2,
                'title' => 'Practice Quiz (DB)',
                'time_limit_min' => 15,
                'quiz_type' => 'practice',
                'pass_score' => 70,
                'question_count' => $cntQ,
            ];
        }
        // Luôn có link luyện API
        $rows[] = [
            'id' => 0,
            'title' => 'Luyện đề thật (Open Trivia API)',
            'time_limit_min' => 15,
            'quiz_type' => 'api',
            'pass_score' => 0,
            'question_count' => 10,
        ];
        return $rows;
    }

    /**
     * Lịch sử làm bài của học viên (bảng test_attempts)
     */
    public function getStudentAttempts($userId, $limit = 20) {
        $limit = (int)$limit;
        $sql = "SELECT ta.*,
                       ta.score AS total_score,
                       ta.total_questions,
                       ta.level_result,
                       ta.type AS quiz_type,
                       ta.started_at,
                       ta.submitted_at,
                       CASE
                         WHEN ta.type = 'placement' THEN 'Placement Test'
                         ELSE 'Practice Quiz'
                       END AS title
                FROM test_attempts ta
                WHERE ta.user_id = :uid
                ORDER BY ta.started_at DESC
                LIMIT {$limit}";
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':uid' => (int)$userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }
}
