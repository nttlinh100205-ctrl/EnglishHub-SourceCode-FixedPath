<?php
class AdminController extends Controller {
    private $userModel;
    private $courseModel;
    private $questionModel;

    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: ' . URLROOT . '/auth/login');
            exit;
        }
        $this->userModel = $this->model('User');
        $this->courseModel = $this->model('Course');
        $this->questionModel = $this->model('Question');
    }

    public function index() {
        $users = $this->userModel->getAllUsers();
        $courses = $this->courseModel->getAllCourses(100);

        // Trạng thái 2 database
        $dbStatus = [];
        try {
            $pdoL = Database::conn('learning');
            $n = (int)$pdoL->query('SELECT COUNT(*) FROM courses')->fetchColumn();
            $dbStatus['learning'] = ['ok' => true, 'name' => 'english_learning', 'courses' => $n];
        } catch (Exception $e) {
            $dbStatus['learning'] = ['ok' => false, 'name' => 'english_learning', 'error' => $e->getMessage()];
        }
        try {
            $pdoH = Database::conn('hub');
            $tables = $pdoH->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);
            $dbStatus['hub'] = ['ok' => true, 'name' => 'english_hub', 'tables' => count($tables)];
        } catch (Exception $e) {
            $dbStatus['hub'] = ['ok' => false, 'name' => 'english_hub', 'error' => $e->getMessage()];
        }

        $this->view('admin/dashboard', [
            'title'     => 'Bảng Quản Trị - Admin',
            'users'     => $users,
            'courses'   => $courses,
            'db_status' => $dbStatus,
        ]);
    }

    /** Danh sách khóa học */
    public function courses() {
        $courses = $this->courseModel->getAllCourses(100);
        $this->view('admin/courses', [
            'title'   => 'Quản lý khóa học',
            'courses' => $courses,
            'flash'   => $_SESSION['flash_ok'] ?? $_SESSION['flash_error'] ?? null,
        ]);
        unset($_SESSION['flash_ok'], $_SESSION['flash_error']);
    }

    /** Thêm / sửa khóa học */
    public function courseForm($id = null) {
        $course = $id ? $this->courseModel->findById($id) : null;
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'title'       => trim($_POST['title'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
                'objective'   => trim($_POST['objective'] ?? ''),
                'duration'    => trim($_POST['duration'] ?? ''),
                'skill'       => $_POST['skill'] ?? 'vocabulary',
                'level'       => $_POST['level'] ?? 'A1',
                'image'       => trim($_POST['image'] ?? 'course_default.jpg'),
                'is_active'   => isset($_POST['is_active']) ? 1 : 0,
                'created_by'  => $_SESSION['user_id'],
            ];
            if ($data['title'] === '') {
                $error = 'Vui lòng nhập tên khóa học.';
            } else {
                if ($id) {
                    $this->courseModel->update($id, $data);
                    $_SESSION['flash_ok'] = 'Đã cập nhật khóa học.';
                } else {
                    $this->courseModel->create($data);
                    $_SESSION['flash_ok'] = 'Đã thêm khóa học mới.';
                }
                header('Location: ' . URLROOT . '/admin/courses');
                exit;
            }
        }

        $this->view('admin/course_form', [
            'title'  => $id ? 'Sửa khóa học' : 'Thêm khóa học',
            'course' => $course,
            'error'  => $error,
        ]);
    }

    public function courseDelete($id) {
        $this->courseModel->delete((int)$id);
        $_SESSION['flash_ok'] = 'Đã xóa khóa học.';
        header('Location: ' . URLROOT . '/admin/courses');
        exit;
    }

    /** Bài học của 1 khóa */
    public function lessons($courseId) {
        $course = $this->courseModel->findById($courseId);
        if (!$course) {
            header('Location: ' . URLROOT . '/admin/courses');
            exit;
        }
        $lessons = $this->courseModel->getLessonsByCourse($courseId);
        $this->view('admin/lessons', [
            'title'   => 'Bài học: ' . $course['title'],
            'course'  => $course,
            'lessons' => $lessons,
        ]);
    }

    public function lessonForm($courseId, $lessonId = null) {
        $course = $this->courseModel->findById($courseId);
        if (!$course) {
            header('Location: ' . URLROOT . '/admin/courses');
            exit;
        }
        $lesson = $lessonId ? $this->courseModel->getLessonById($lessonId) : null;
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'course_id'  => (int)$courseId,
                'title'      => trim($_POST['title'] ?? ''),
                'content'    => trim($_POST['content'] ?? ''),
                'order_num'  => (int)($_POST['order_num'] ?? 1),
                'is_locked'  => isset($_POST['is_locked']) ? 1 : 0,
            ];
            if ($data['title'] === '') {
                $error = 'Vui lòng nhập tên bài học.';
            } else {
                if ($lessonId) {
                    $this->courseModel->updateLesson($lessonId, $data);
                    $_SESSION['flash_ok'] = 'Đã cập nhật bài học.';
                } else {
                    $this->courseModel->createLesson($data);
                    $_SESSION['flash_ok'] = 'Đã thêm bài học.';
                }
                header('Location: ' . URLROOT . '/admin/lessons/' . (int)$courseId);
                exit;
            }
        }

        $this->view('admin/lesson_form', [
            'title'  => $lessonId ? 'Sửa bài học' : 'Thêm bài học',
            'course' => $course,
            'lesson' => $lesson,
            'error'  => $error,
        ]);
    }

    public function lessonDelete($courseId, $lessonId) {
        $this->courseModel->deleteLesson((int)$lessonId);
        $_SESSION['flash_ok'] = 'Đã xóa bài học.';
        header('Location: ' . URLROOT . '/admin/lessons/' . (int)$courseId);
        exit;
    }
}
