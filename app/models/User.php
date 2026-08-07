<?php
class User {
    private $conn;
    private $table = 'users';

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    /**
     * Tìm thông tin người dùng theo Email hoặc Username
     * Kèm theo thông tin vai trò từ bảng `roles` (role_code, role_name)
     * Schema: full_name, password_hash (khớp english_hub.sql)
     */
    public function findByEmailOrUsername($keyword) {
        $query = "SELECT u.id, u.role_id, u.level_id, u.full_name, u.username, u.email,
                         u.password_hash, u.phone, u.avatar, u.status, u.is_verified,
                         u.last_login_at, u.created_at,
                         r.code AS role_code, r.name AS role_name 
                  FROM " . $this->table . " u
                  LEFT JOIN roles r ON u.role_id = r.id
                  WHERE u.email = :keyword OR u.username = :keyword 
                  LIMIT 1";
                  
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':keyword', $keyword);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Alias tiện dùng trong code (fullname = full_name)
        if ($user) {
            $user['fullname'] = $user['full_name'];
        }
        return $user;
    }

    /**
     * Đăng ký tài khoản mới
     * Mặc định role_id = 3 (Học viên - Student)
     * Cột DB: full_name, password_hash
     */
    public function register($fullname, $username, $email, $password, $role_id = 3) {
        // Kiểm tra xem Username hoặc Email đã tồn tại chưa
        if ($this->findByEmailOrUsername($email) || $this->findByEmailOrUsername($username)) {
            return ['status' => false, 'message' => 'Email hoặc Tên đăng nhập đã tồn tại!'];
        }

        // Mã hóa mật khẩu
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        $query = "INSERT INTO " . $this->table . " (full_name, username, email, password_hash, role_id, status, is_verified) 
                  VALUES (:full_name, :username, :email, :password_hash, :role_id, 'active', 1)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':full_name', $fullname);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password_hash', $hashed_password);
        $stmt->bindParam(':role_id', $role_id);

        if ($stmt->execute()) {
            return ['status' => true, 'message' => 'Đăng ký tài khoản thành công!'];
        }
        return ['status' => false, 'message' => 'Đã có lỗi xảy ra khi khởi tạo tài khoản.'];
    }

    /**
     * Đăng nhập tài khoản
     * Cột mật khẩu: password_hash
     */
    public function login($account, $password) {
        $user = $this->findByEmailOrUsername($account);
        if (!$user) {
            return ['status' => false, 'message' => 'Tài khoản không tồn tại!'];
        }

        // Kiểm tra trạng thái tài khoản
        if (isset($user['status']) && $user['status'] === 'locked') {
            return ['status' => false, 'message' => 'Tài khoản đã bị khóa!'];
        }

        $hashedPassword = $user['password_hash'] ?? null;

        if ($hashedPassword === null || $hashedPassword === '') {
            return ['status' => false, 'message' => 'Tài khoản chưa có mật khẩu. Vui lòng liên hệ quản trị viên!'];
        }

        // Xác thực mật khẩu mã hóa
        if (password_verify($password, $hashedPassword)) {
            // Cập nhật last_login_at
            $this->updateLastLogin($user['id']);
            return ['status' => true, 'user' => $user];
        } else {
            return ['status' => false, 'message' => 'Mật khẩu không chính xác!'];
        }
    }

    /**
     * Cập nhật thời gian đăng nhập gần nhất
     */
    private function updateLastLogin($userId) {
        $query = "UPDATE " . $this->table . " SET last_login_at = NOW() WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $userId);
        $stmt->execute();
    }

    /**
     * Lấy danh sách toàn bộ người dùng (Phục vụ trang Quản trị Admin)
     */
    public function getAllUsers() {
        $query = "SELECT u.id, u.full_name, u.username, u.email, u.status, u.created_at, u.role_id,
                         r.code AS role_code, r.name AS role_name 
                  FROM " . $this->table . " u
                  LEFT JOIN roles r ON u.role_id = r.id
                  ORDER BY u.id DESC";
                  
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Cập nhật quyền (role_id) cho người dùng
     */
    public function updateRole($userId, $roleId) {
        $query = "UPDATE " . $this->table . " SET role_id = :role_id WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':role_id', $roleId);
        $stmt->bindParam(':id', $userId);
        return $stmt->execute();
    }
}
