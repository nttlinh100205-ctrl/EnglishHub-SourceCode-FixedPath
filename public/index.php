<?php
session_start();

// Nạp config
require_once '../config/config.php';

// Nạp core
require_once '../app/core/App.php';
require_once '../app/core/Controller.php';
require_once '../app/core/Database.php';

// Khởi chạy ứng dụng
$app = new App();
