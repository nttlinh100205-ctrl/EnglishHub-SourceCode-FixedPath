<?php
class CourseController extends Controller {
    public function index() {
        $this->view('course/index', ['title' => 'Danh sách khóa học']);
    }
    
    public function detail($id = null) {
        $this->view('course/detail', ['title' => 'Chi tiết khóa học', 'id' => $id]);
    }
}
