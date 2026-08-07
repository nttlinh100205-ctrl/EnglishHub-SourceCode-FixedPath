<?php
define('DB_HOST', '127.0.0.1');
define('DB_PORT', '3307');
define('DB_NAME', 'english_hub');
define('DB_USER', 'root');
define('DB_PASS', '');

// URL Root
$script_dir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
define('URLROOT', rtrim($script_dir, '/'));

// Google OAuth 2.0
// Tạo tại: https://console.cloud.google.com/apis/credentials
// Authorized redirect URI: {URLROOT}/auth/googleCallback  (vd: http://localhost/EnglishHub-SourceCode-FixedPath/public/auth/googleCallback)
define('GOOGLE_CLIENT_ID', '870905635234-oshep5fv98vbo3k59s0o70al9fcd7k1h.apps.googleusercontent.com');
define('GOOGLE_CLIENT_SECRET', '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');
define('GOOGLE_REDIRECT_URI', URLROOT . '/auth/googleCallback');
