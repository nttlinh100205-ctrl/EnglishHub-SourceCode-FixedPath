<?php
class Course {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    /**
     * Lấy danh sách khóa học đã publish
     */
    public function getPublishedCourses($limit = 12) {
        $query = "SELECT c.*, 
                         l.name AS level_name, l.code AS level_code,
                         s.name AS skill_name
                  FROM courses c
                  LEFT JOIN levels l ON c.level_id = l.id
                  LEFT JOIN skills s ON c.main_skill_id = s.id
                  WHERE c.status = 'published'
                  ORDER BY c.is_featured DESC, c.created_at DESC
                  LIMIT :lim";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':lim', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy tất cả khóa học (admin / hiển thị đầy đủ)
     */
    public function getAllCourses($limit = 20) {
        $query = "SELECT c.*, 
                         l.name AS level_name, l.code AS level_code,
                         s.name AS skill_name
                  FROM courses c
                  LEFT JOIN levels l ON c.level_id = l.id
                  LEFT JOIN skills s ON c.main_skill_id = s.id
                  ORDER BY c.created_at DESC
                  LIMIT :lim";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':lim', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy bài học theo khóa học
     */
    public function getLessonsByCourse($courseId) {
        $query = "SELECT ls.*, cs.title AS section_title
                  FROM lessons ls
                  LEFT JOIN course_sections cs ON ls.section_id = cs.id
                  WHERE ls.course_id = :course_id AND ls.status = 'published'
                  ORDER BY ls.sort_order ASC, ls.id ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':course_id', $courseId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy danh sách bài học mới / nổi bật (cho dashboard học viên)
     */
    public function getRecentLessons($limit = 8) {
        $query = "SELECT ls.id, ls.title, ls.slug, ls.summary, ls.content_type,
                         ls.duration_minutes, ls.is_preview, ls.course_id,
                         c.title AS course_title, c.thumbnail,
                         l.name AS level_name
                  FROM lessons ls
                  JOIN courses c ON ls.course_id = c.id
                  LEFT JOIN levels l ON c.level_id = l.id
                  WHERE ls.status = 'published' AND c.status = 'published'
                  ORDER BY ls.id DESC
                  LIMIT :lim";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':lim', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Khóa học học viên đã đăng ký
     */
    public function getEnrolledCourses($userId) {
        $query = "SELECT c.*, e.progress_pct, e.status AS enroll_status, e.last_access_at,
                         l.name AS level_name
                  FROM enrollments e
                  JOIN courses c ON e.course_id = c.id
                  LEFT JOIN levels l ON c.level_id = l.id
                  WHERE e.user_id = :user_id AND e.status IN ('active', 'completed')
                  ORDER BY e.last_access_at DESC, e.enrolled_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Đếm thống kê nhanh
     */
    public function countCourses() {
        $stmt = $this->conn->query("SELECT COUNT(*) FROM courses");
        return (int)$stmt->fetchColumn();
    }

    public function countLessons() {
        $stmt = $this->conn->query("SELECT COUNT(*) FROM lessons");
        return (int)$stmt->fetchColumn();
    }
}
