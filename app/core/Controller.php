<?php
class Controller {
    public function model($model) {
        // Sử dụng dirname(__DIR__) để xác định chính xác thư mục /app
        $file = dirname(__DIR__) . '/models/' . $model . '.php';

        if (file_exists($file)) {
            require_once $file;
            return new $model();
        } else {
            die("<br><strong>Lỗi Model:</strong> Không tìm thấy file <code>{$file}</code>. Vui lòng kiểm tra lại tên file hoặc vị trí lưu trữ.");
        }
    }

    public function view($view, $data = []) {
        $file = dirname(__DIR__) . '/views/' . $view . '.php';

        if (file_exists($file)) {
            require_once $file;
        } else {
            die("<br><strong>Lỗi View:</strong> Không tìm thấy file <code>{$file}</code>.");
        }
    }
}