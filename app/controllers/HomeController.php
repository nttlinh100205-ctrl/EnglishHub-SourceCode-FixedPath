<?php
class HomeController extends Controller {
    public function index() {
        $courseModel = $this->model('Course');
        $quizModel   = $this->model('Quiz');

        $lessons = $courseModel->getRecentLessons(8);
        $courses = $courseModel->getPublishedCourses(8);
        if (empty($courses)) {
            $courses = $courseModel->getAllCourses(8);
        }
        $quizzes = $quizModel->getAllQuizzes();

        // Bài học mẫu khi DB trống — hiển thị demo trên trang public
        if (empty($lessons)) {
            $lessons = $this->sampleLessons();
        }
        if (empty($courses)) {
            $courses = $this->sampleCourses();
        }
        if (empty($quizzes)) {
            $quizzes = $this->sampleQuizzes();
        }

        $this->view('home/index', [
            'title'   => 'Trang chủ - EnglishHub',
            'lessons' => $lessons,
            'courses' => $courses,
            'quizzes' => $quizzes,
            'logged_in' => isset($_SESSION['user_id']),
        ]);
    }

    private function sampleCourses() {
        return [
            [
                'id' => 0, 'title' => 'Tiếng Anh Giao Tiếp Cơ Bản', 'slug' => 'demo-giao-tiep',
                'subtitle' => 'Chào hỏi, giới thiệu, tình huống hàng ngày',
                'level_name' => 'A1', 'level_code' => 'A1', 'skill_name' => 'Speaking',
                'total_lessons' => 5, 'duration_hours' => 8, 'is_free' => 1, 'thumbnail' => null,
            ],
            [
                'id' => 0, 'title' => 'Ngữ Pháp & Đọc Hiểu A2', 'slug' => 'demo-ngu-phap',
                'subtitle' => 'Nền tảng Present Simple, Past Simple',
                'level_name' => 'A2', 'level_code' => 'A2', 'skill_name' => 'Grammar',
                'total_lessons' => 4, 'duration_hours' => 6.5, 'is_free' => 1, 'thumbnail' => null,
            ],
            [
                'id' => 0, 'title' => 'Luyện Nghe TOEIC Part 1-2', 'slug' => 'demo-toeic',
                'subtitle' => 'Photo description & Question-Response',
                'level_name' => 'B1', 'level_code' => 'B1', 'skill_name' => 'Listening',
                'total_lessons' => 6, 'duration_hours' => 10, 'is_free' => 0, 'thumbnail' => null,
            ],
            [
                'id' => 0, 'title' => 'Từ vựng theo chủ đề', 'slug' => 'demo-vocab',
                'subtitle' => 'Flashcard 200 từ thiết yếu',
                'level_name' => 'A1', 'level_code' => 'A1', 'skill_name' => 'Vocabulary',
                'total_lessons' => 8, 'duration_hours' => 4, 'is_free' => 1, 'thumbnail' => null,
            ],
        ];
    }

    private function sampleLessons() {
        return [
            [
                'id' => 1, 'title' => 'Chào hỏi & Giới thiệu bản thân', 'course_title' => 'Tiếng Anh Giao Tiếp Cơ Bản',
                'content_type' => 'video', 'duration_minutes' => 15, 'level_name' => 'A1',
                'is_preview' => 1, 'course_id' => 0, 'summary' => 'Các mẫu câu khi gặp mặt lần đầu',
            ],
            [
                'id' => 2, 'title' => 'Từ vựng chủ đề gia đình', 'course_title' => 'Tiếng Anh Giao Tiếp Cơ Bản',
                'content_type' => 'flashcard', 'duration_minutes' => 10, 'level_name' => 'A1',
                'is_preview' => 1, 'course_id' => 0, 'summary' => '20 từ vựng thiết yếu',
            ],
            [
                'id' => 3, 'title' => 'Thì hiện tại đơn (Present Simple)', 'course_title' => 'Ngữ Pháp & Đọc Hiểu A2',
                'content_type' => 'text', 'duration_minutes' => 20, 'level_name' => 'A2',
                'is_preview' => 1, 'course_id' => 0, 'summary' => 'Cấu trúc và cách dùng',
            ],
            [
                'id' => 4, 'title' => 'Luyện nghe hội thoại quán café', 'course_title' => 'Tiếng Anh Giao Tiếp Cơ Bản',
                'content_type' => 'mixed', 'duration_minutes' => 12, 'level_name' => 'A1',
                'is_preview' => 0, 'course_id' => 0, 'summary' => 'Listening practice',
            ],
            [
                'id' => 5, 'title' => 'Photo description – TOEIC Part 1', 'course_title' => 'Luyện Nghe TOEIC Part 1-2',
                'content_type' => 'video', 'duration_minutes' => 18, 'level_name' => 'B1',
                'is_preview' => 1, 'course_id' => 0, 'summary' => 'Mẹo mô tả tranh',
            ],
            [
                'id' => 6, 'title' => 'Bài tập thì quá khứ đơn', 'course_title' => 'Ngữ Pháp & Đọc Hiểu A2',
                'content_type' => 'quiz', 'duration_minutes' => 15, 'level_name' => 'A2',
                'is_preview' => 0, 'course_id' => 0, 'summary' => 'Practice Past Simple',
            ],
        ];
    }

    private function sampleQuizzes() {
        return [
            ['id' => 1, 'title' => 'Placement Test – Xếp lớp đầu vào', 'time_limit_min' => 20, 'quiz_type' => 'placement', 'pass_score' => 70],
            ['id' => 2, 'title' => 'Kiểm tra Present Simple', 'time_limit_min' => 15, 'quiz_type' => 'practice', 'pass_score' => 70],
            ['id' => 3, 'title' => 'Vocabulary – Family & Friends', 'time_limit_min' => 10, 'quiz_type' => 'practice', 'pass_score' => 60],
            ['id' => 4, 'title' => 'Listening Mini Test A2', 'time_limit_min' => 15, 'quiz_type' => 'lesson_test', 'pass_score' => 70],
        ];
    }
}
