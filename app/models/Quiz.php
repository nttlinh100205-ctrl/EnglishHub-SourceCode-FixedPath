<?php
class Quiz {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    // Lấy tất cả bài thi/bài tập
    public function getAllQuizzes() {
        $query = "SELECT * FROM quizzes ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy lịch sử làm bài của 1 học viên
    // Schema quiz_attempts: started_at (không có created_at)
    public function getStudentAttempts($studentId) {
        $query = "SELECT qa.*, q.title AS quiz_title 
                  FROM quiz_attempts qa
                  JOIN quizzes q ON qa.quiz_id = q.id
                  WHERE qa.user_id = :user_id
                  ORDER BY qa.started_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $studentId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy toàn bộ lượt làm bài (dành cho Giáo viên kiểm tra)
    // Schema users: full_name (không có fullname)
    public function getAllAttempts() {
        $query = "SELECT qa.*, q.title AS quiz_title, 
                         u.full_name, u.username 
                  FROM quiz_attempts qa
                  JOIN quizzes q ON qa.quiz_id = q.id
                  JOIN users u ON qa.user_id = u.id
                  ORDER BY qa.started_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
