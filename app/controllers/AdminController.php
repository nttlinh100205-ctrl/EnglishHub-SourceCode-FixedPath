<?php
class AdminController extends Controller {
    private $userModel;

    public function __construct() {
        // BẢO VỆ QUYỀN: Chỉ có admin mới được vào
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: ' . URLROOT . '/auth/login');
            exit;
        }
        $this->userModel = $this->model('User');
    }

    // Trang Bảng điều khiển Admin
    public function index() {
        $users = $this->userModel->getAllUsers();
        $this->view('admin/dashboard', [
            'title' => 'Bảng Quản Trị - Admin',
            'users' => $users
        ]);
    }
}