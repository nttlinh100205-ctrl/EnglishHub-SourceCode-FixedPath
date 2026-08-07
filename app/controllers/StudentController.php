<?php
class StudentController extends Controller {
    private $quizModel;
    private $courseModel;

    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . URLROOT . '/auth/login');
            exit;
        }
        if (($_SESSION['role'] ?? '') === 'admin') {
            header('Location: ' . URLROOT . '/admin');
            exit;
        }
        $this->quizModel = $this->model('Quiz');
        $this->courseModel = $this->model('Course');
    }

    public function index() {
        $studentId = $_SESSION['user_id'];

        $quizzes = $this->quizModel->getAllQuizzes();
        $myAttempts = $this->quizModel->getStudentAttempts($studentId);
        $courses = $this->courseModel->getPublishedCourses(12);
        // Nếu chưa có khóa published, lấy tất cả để demo
        if (empty($courses)) {
            $courses = $this->courseModel->getAllCourses(12);
        }
        $lessons = $this->courseModel->getRecentLessons(8);
        $enrolled = $this->courseModel->getEnrolledCourses($studentId);

        $this->view('student/index', [
            'title'    => 'Góc Học Tập - Học Viên',
            'quizzes'  => $quizzes,
            'attempts' => $myAttempts,
            'courses'  => $courses,
            'lessons'  => $lessons,
            'enrolled' => $enrolled,
        ]);
    }
}
