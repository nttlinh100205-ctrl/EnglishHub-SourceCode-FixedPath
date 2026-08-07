<?php
class AuthController extends Controller {
    private $userModel;

    public function __construct() {
        $this->userModel = $this->model('User');
    }

    /** /auth → chuyển sang trang đăng nhập */
    public function index() {
        header('Location: ' . URLROOT . '/auth/login');
        exit;
    }

    public function login() {
        if (isset($_SESSION['user_id'])) {
            $this->redirectByRole($_SESSION['role']);
        }

        $data = [
            'title' => 'Đăng nhập',
            'error' => '',
            'google_enabled' => $this->isGoogleConfigured(),
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $account = trim($_POST['account'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if (empty($account) || empty($password)) {
                $data['error'] = 'Vui lòng nhập đầy đủ tên đăng nhập và mật khẩu!';
            } else {
                $result = $this->userModel->login($account, $password);
                if ($result['status']) {
                    $this->setSessionFromUser($result['user']);
                    $this->redirectByRole($_SESSION['role']);
                } else {
                    $data['error'] = $result['message'];
                }
            }
        }

        $this->view('auth/login', $data);
    }

    /**
     * Bước 1: Chuyển hướng sang Google OAuth
     */
    public function google() {
        if (!$this->isGoogleConfigured()) {
            $_SESSION['flash_error'] = 'Google OAuth chưa được cấu hình. Vui lòng thêm Client ID/Secret trong config.php';
            header('Location: ' . URLROOT . '/auth/login');
            exit;
        }

        $params = [
            'client_id'     => GOOGLE_CLIENT_ID,
            'redirect_uri'  => GOOGLE_REDIRECT_URI,
            'response_type' => 'code',
            'scope'         => 'openid email profile',
            'access_type'   => 'online',
            'prompt'        => 'select_account',
        ];
        $url = 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query($params);
        header('Location: ' . $url);
        exit;
    }

    /**
     * Bước 2: Google callback — đổi code lấy token + userinfo
     */
    public function googleCallback() {
        if (!$this->isGoogleConfigured()) {
            header('Location: ' . URLROOT . '/auth/login');
            exit;
        }

        $error = $_GET['error'] ?? null;
        if ($error) {
            $_SESSION['flash_error'] = 'Đăng nhập Google bị hủy hoặc lỗi: ' . htmlspecialchars($error);
            header('Location: ' . URLROOT . '/auth/login');
            exit;
        }

        $code = $_GET['code'] ?? '';
        if ($code === '') {
            $_SESSION['flash_error'] = 'Không nhận được mã xác thực từ Google.';
            header('Location: ' . URLROOT . '/auth/login');
            exit;
        }

        // Đổi authorization code lấy access_token
        $tokenData = $this->httpPost('https://oauth2.googleapis.com/token', [
            'code'          => $code,
            'client_id'     => GOOGLE_CLIENT_ID,
            'client_secret' => GOOGLE_CLIENT_SECRET,
            'redirect_uri'  => GOOGLE_REDIRECT_URI,
            'grant_type'    => 'authorization_code',
        ]);

        if (empty($tokenData['access_token'])) {
            $_SESSION['flash_error'] = 'Không lấy được token Google. Kiểm tra Client ID/Secret và Redirect URI.';
            header('Location: ' . URLROOT . '/auth/login');
            exit;
        }

        // Lấy thông tin user
        $profile = $this->httpGet(
            'https://www.googleapis.com/oauth2/v3/userinfo',
            $tokenData['access_token']
        );

        $email = $profile['email'] ?? null;
        $name  = $profile['name'] ?? ($profile['given_name'] ?? 'Google User');
        $picture = $profile['picture'] ?? null;

        if (!$email) {
            $_SESSION['flash_error'] = 'Google không trả về email. Hãy cho phép quyền email.';
            header('Location: ' . URLROOT . '/auth/login');
            exit;
        }

        $result = $this->userModel->findOrCreateByGoogle($email, $name, $picture);
        if (!$result['status']) {
            $_SESSION['flash_error'] = $result['message'] ?? 'Đăng nhập Google thất bại.';
            header('Location: ' . URLROOT . '/auth/login');
            exit;
        }

        $this->setSessionFromUser($result['user']);
        $this->redirectByRole($_SESSION['role']);
    }

    private function setSessionFromUser($user) {
        $roleCode = $user['role_code'] ?? 'student';
        if ($roleCode === 'teacher') {
            $roleCode = 'student';
        }
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['fullname']  = $user['full_name'] ?? $user['fullname'] ?? '';
        $_SESSION['username']  = $user['username'];
        $_SESSION['role']      = $roleCode;
        $_SESSION['role_name'] = ($roleCode === 'admin') ? 'Quản trị viên' : 'Học viên';
        $_SESSION['avatar']    = $user['avatar'] ?? 'default.png';
    }

    private function redirectByRole($role) {
        if ($role === 'admin') {
            header('Location: ' . URLROOT . '/admin');
        } else {
            header('Location: ' . URLROOT . '/student');
        }
        exit;
    }

    private function isGoogleConfigured() {
        return defined('GOOGLE_CLIENT_ID')
            && defined('GOOGLE_CLIENT_SECRET')
            && GOOGLE_CLIENT_ID !== 'YOUR_GOOGLE_CLIENT_ID.apps.googleusercontent.com'
            && GOOGLE_CLIENT_SECRET !== 'YOUR_GOOGLE_CLIENT_SECRET'
            && GOOGLE_CLIENT_ID !== ''
            && GOOGLE_CLIENT_SECRET !== '';
    }

    private function httpPost($url, $fields) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => http_build_query($fields),
            CURLOPT_HTTPHEADER     => ['Content-Type: application/x-www-form-urlencoded'],
            CURLOPT_TIMEOUT        => 20,
        ]);
        $raw = curl_exec($ch);
        curl_close($ch);
        return json_decode($raw ?: '{}', true) ?: [];
    }

    private function httpGet($url, $accessToken) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => ['Authorization: Bearer ' . $accessToken],
            CURLOPT_TIMEOUT        => 20,
        ]);
        $raw = curl_exec($ch);
        curl_close($ch);
        return json_decode($raw ?: '{}', true) ?: [];
    }

    public function logout() {
        session_destroy();
        header('Location: ' . URLROOT . '/');
        exit;
    }
}
