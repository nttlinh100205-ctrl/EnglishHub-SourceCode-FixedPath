<?php
/**
 * Schema: english_learning.users
 * columns: id, username, email, password, full_name, avatar, role (admin|student), level (A1–C2)
 */
class User {
    private $conn;
    private $table = 'users';

    public function __construct() {
        $db = new Database('learning');
        $this->conn = $db->getConnection();
    }

    public function findByEmailOrUsername($keyword) {
        // PDO native prepares không cho dùng 1 placeholder 2 lần → tách :email và :username
        $sql = "SELECT * FROM {$this->table}
                WHERE email = :email OR username = :username
                LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':email'    => $keyword,
            ':username' => $keyword,
        ]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            $user['fullname'] = $user['full_name'] ?? $user['username'];
            // Tương thích code cũ dùng role_code
            $user['role_code'] = $user['role'] ?? 'student';
            $user['role_name'] = ($user['role'] ?? '') === 'admin' ? 'Quản trị viên' : 'Học viên';
            $user['password_hash'] = $user['password'] ?? '';
        }
        return $user;
    }

    public function findById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = :id LIMIT 1");
        $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            $user['fullname'] = $user['full_name'] ?? $user['username'];
            $user['role_code'] = $user['role'] ?? 'student';
        }
        return $user;
    }

    public function findByEmail($email) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE email = :email LIMIT 1");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            $user['fullname'] = $user['full_name'] ?? $user['username'];
            $user['role_code'] = $user['role'] ?? 'student';
            $user['password_hash'] = $user['password'] ?? '';
        }
        return $user;
    }

    public function register($fullname, $username, $email, $password, $role = 'student') {
        if ($this->findByEmailOrUsername($email) || $this->findByEmailOrUsername($username)) {
            return ['status' => false, 'message' => 'Email hoặc Tên đăng nhập đã tồn tại!'];
        }
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $sql = "INSERT INTO {$this->table} (full_name, username, email, password, role, level)
                VALUES (:full_name, :username, :email, :password, :role, 'A1')";
        $stmt = $this->conn->prepare($sql);
        $ok = $stmt->execute([
            ':full_name' => $fullname,
            ':username'  => $username,
            ':email'     => $email,
            ':password'  => $hash,
            ':role'      => $role === 'admin' ? 'admin' : 'student',
        ]);
        return $ok
            ? ['status' => true, 'message' => 'Đăng ký tài khoản thành công!']
            : ['status' => false, 'message' => 'Lỗi khi tạo tài khoản.'];
    }

    /** Google OAuth — tạo user học viên nếu chưa có */
    public function findOrCreateByGoogle($email, $fullName, $avatar = null) {
        $user = $this->findByEmail($email);
        if ($user) {
            return ['status' => true, 'user' => $user];
        }

        $base = preg_replace('/[^a-z0-9]/i', '', strstr($email, '@', true) ?: 'user');
        $username = $base . rand(100, 999);
        // tránh trùng username
        while ($this->findByEmailOrUsername($username)) {
            $username = $base . rand(1000, 9999);
        }
        $hash = password_hash(bin2hex(random_bytes(16)), PASSWORD_BCRYPT);
        $sql = "INSERT INTO {$this->table} (full_name, username, email, password, role, level, avatar)
                VALUES (:full_name, :username, :email, :password, 'student', 'A1', :avatar)";
        $stmt = $this->conn->prepare($sql);
        try {
            $stmt->execute([
                ':full_name' => $fullName ?: $username,
                ':username'  => $username,
                ':email'     => $email,
                ':password'  => $hash,
                ':avatar'    => $avatar ?: 'default.png',
            ]);
        } catch (Exception $e) {
            return ['status' => false, 'message' => 'Không tạo được tài khoản Google.'];
        }
        $user = $this->findByEmail($email);
        if (!$user) {
            return ['status' => false, 'message' => 'Tạo user Google thất bại.'];
        }
        return ['status' => true, 'user' => $user];
    }

    public function login($account, $password) {
        $user = $this->findByEmailOrUsername($account);
        if (!$user) {
            return ['status' => false, 'message' => 'Tài khoản không tồn tại!'];
        }
        if (!password_verify($password, $user['password'])) {
            return ['status' => false, 'message' => 'Mật khẩu không đúng!'];
        }
        return ['status' => true, 'user' => $user];
    }

    public function updateLevel($userId, $level) {
        $allowed = ['A1','A2','B1','B2','C1','C2'];
        if (!in_array($level, $allowed, true)) return false;
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET level = :lv WHERE id = :id");
        return $stmt->execute([':lv' => $level, ':id' => (int)$userId]);
    }

    public function getAllUsers() {
        $stmt = $this->conn->query("SELECT id, username, email, full_name, role, level, created_at FROM {$this->table} ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllStudents() {
        $stmt = $this->conn->query("SELECT id, username, email, full_name, level, created_at FROM {$this->table} WHERE role = 'student' ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
