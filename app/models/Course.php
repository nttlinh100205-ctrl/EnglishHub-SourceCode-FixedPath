<?php
/**
 * Schema english_learning: courses, lessons, enrollments, flashcards
 */
class Course {
    private $conn;

    public function __construct() {
        $db = new Database('learning');
        $this->conn = $db->getConnection();
    }

    public function getPublishedCourses($limit = 20) {
        $limit = (int)$limit;
        $sql = "SELECT c.*,
                       c.level AS level_code,
                       c.level AS level_name,
                       c.description AS subtitle,
                       1 AS is_free,
                       0 AS duration_hours,
                       (SELECT COUNT(*) FROM lessons l WHERE l.course_id = c.id) AS total_lessons
                FROM courses c
                WHERE c.is_active = 1
                ORDER BY c.id DESC
                LIMIT {$limit}";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllCourses($limit = 50) {
        $limit = (int)$limit;
        $sql = "SELECT c.*,
                       c.level AS level_code,
                       c.level AS level_name,
                       c.description AS subtitle,
                       1 AS is_free,
                       0 AS duration_hours,
                       (SELECT COUNT(*) FROM lessons l WHERE l.course_id = c.id) AS total_lessons
                FROM courses c
                ORDER BY c.id DESC
                LIMIT {$limit}";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM courses WHERE id = :id LIMIT 1");
        $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $sql = "INSERT INTO courses (title, description, objective, duration, skill, level, image, is_active, created_by)
                VALUES (:title, :description, :objective, :duration, :skill, :level, :image, :is_active, :created_by)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':title'       => $data['title'],
            ':description' => $data['description'] ?? null,
            ':objective'   => $data['objective'] ?? null,
            ':duration'    => $data['duration'] ?? null,
            ':skill'       => $data['skill'] ?? 'vocabulary',
            ':level'       => $data['level'] ?? 'A1',
            ':image'       => $data['image'] ?? 'course_default.jpg',
            ':is_active'   => isset($data['is_active']) ? (int)$data['is_active'] : 1,
            ':created_by'  => $data['created_by'] ?? null,
        ]) ? (int)$this->conn->lastInsertId() : false;
    }

    public function update($id, $data) {
        $sql = "UPDATE courses SET
                    title = :title, description = :description, objective = :objective,
                    duration = :duration, skill = :skill, level = :level,
                    image = :image, is_active = :is_active
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':title'       => $data['title'],
            ':description' => $data['description'] ?? null,
            ':objective'   => $data['objective'] ?? null,
            ':duration'    => $data['duration'] ?? null,
            ':skill'       => $data['skill'] ?? 'vocabulary',
            ':level'       => $data['level'] ?? 'A1',
            ':image'       => $data['image'] ?? 'course_default.jpg',
            ':is_active'   => isset($data['is_active']) ? (int)$data['is_active'] : 1,
            ':id'          => (int)$id,
        ]);
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM courses WHERE id = :id");
        return $stmt->execute([':id' => (int)$id]);
    }

    public function getLessonsByCourse($courseId) {
        $stmt = $this->conn->prepare(
            "SELECT * FROM lessons WHERE course_id = :cid ORDER BY order_num ASC, id ASC"
        );
        $stmt->execute([':cid' => (int)$courseId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRecentLessons($limit = 8) {
        $limit = (int)$limit;
        $sql = "SELECT l.*, c.title AS course_title, c.level AS level_name, c.skill,
                       1 AS is_preview, 'mixed' AS content_type, 15 AS duration_minutes
                FROM lessons l
                JOIN courses c ON c.id = l.course_id
                WHERE c.is_active = 1
                ORDER BY l.id DESC
                LIMIT {$limit}";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLessonById($id) {
        $stmt = $this->conn->prepare(
            "SELECT l.*, c.title AS course_title, c.level, c.skill
             FROM lessons l
             JOIN courses c ON c.id = l.course_id
             WHERE l.id = :id LIMIT 1"
        );
        $stmt->execute([':id' => (int)$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createLesson($data) {
        $sql = "INSERT INTO lessons (course_id, title, content, order_num, is_locked)
                VALUES (:course_id, :title, :content, :order_num, :is_locked)";
        $stmt = $this->conn->prepare($sql);
        $ok = $stmt->execute([
            ':course_id' => (int)$data['course_id'],
            ':title'     => $data['title'],
            ':content'   => $data['content'] ?? null,
            ':order_num' => (int)($data['order_num'] ?? 1),
            ':is_locked' => (int)($data['is_locked'] ?? 0),
        ]);
        return $ok ? (int)$this->conn->lastInsertId() : false;
    }

    public function updateLesson($id, $data) {
        $sql = "UPDATE lessons SET title = :title, content = :content,
                    order_num = :order_num, is_locked = :is_locked
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':title'     => $data['title'],
            ':content'   => $data['content'] ?? null,
            ':order_num' => (int)($data['order_num'] ?? 1),
            ':is_locked' => (int)($data['is_locked'] ?? 0),
            ':id'        => (int)$id,
        ]);
    }

    public function deleteLesson($id) {
        $stmt = $this->conn->prepare("DELETE FROM lessons WHERE id = :id");
        return $stmt->execute([':id' => (int)$id]);
    }

    public function getFlashcards($lessonId) {
        $stmt = $this->conn->prepare(
            "SELECT * FROM flashcards WHERE lesson_id = :lid ORDER BY order_num ASC"
        );
        $stmt->execute([':lid' => (int)$lessonId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getEnrolledCourses($userId) {
        $sql = "SELECT c.*,
                       c.level AS level_name,
                       c.level AS level_code,
                       e.progress AS progress_pct,
                       e.status AS enroll_status,
                       e.enrolled_at,
                       (SELECT COUNT(*) FROM lessons l WHERE l.course_id = c.id) AS total_lessons
                FROM enrollments e
                JOIN courses c ON c.id = e.course_id
                WHERE e.user_id = :uid AND e.status != 'cancelled'
                ORDER BY e.enrolled_at DESC";
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':uid' => (int)$userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }
}
