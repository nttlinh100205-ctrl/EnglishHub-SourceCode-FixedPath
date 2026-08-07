<?php
define('DB_HOST', '127.0.0.1');
define('DB_PORT', '3307');
define('DB_USER', 'root');
define('DB_PASS', '');

/**
 * Hai database song song (cùng MySQL port 3307)
 * - english_learning: khóa học, bài học, câu hỏi, users (admin/student) — PRIMARY
 * - english_hub:     schema cũ / dữ liệu bổ sung (nếu còn dùng)
 */
define('DB_NAME', 'english_learning');      // kết nối mặc định
define('DB_NAME_LEARNING', 'english_learning');
define('DB_NAME_HUB', 'english_hub');

// URL Root
$script_dir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
define('URLROOT', rtrim($script_dir, '/'));

// Google OAuth 2.0
define('GOOGLE_CLIENT_ID', 'YOUR_GOOGLE_CLIENT_ID.apps.googleusercontent.com');
define('GOOGLE_CLIENT_SECRET', 'YOUR_GOOGLE_CLIENT_SECRET');
define('GOOGLE_REDIRECT_URI', URLROOT . '/auth/googleCallback');

define('PRACTICE_API_ENABLED', true);
