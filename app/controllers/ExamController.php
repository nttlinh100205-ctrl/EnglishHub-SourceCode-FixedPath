<?php
class ExamController extends Controller {
    public function placement() {
        $this->view('exam/placement', ['title' => 'Bài kiểm tra đầu vào (Placement Test)']);
    }
}
