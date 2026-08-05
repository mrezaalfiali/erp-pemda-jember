<?php
// ============================================
// ERP PEMERINTAHAN DAERAH JEMBER
// Konfigurasi Aplikasi
// ============================================

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// Database
define('DB_HOST', 'localhost');
define('DB_NAME', 'erp_jember');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Aplikasi
define('APP_NAME', 'ERP Pemerintahan Daerah Jember');
define('APP_VERSION', '1.0.0');
define('APP_URL', 'http://localhost/comp/erppemda');
define('BASE_PATH', dirname(__DIR__));

// Upload
define('UPLOAD_PATH', BASE_PATH . '/uploads/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_IMAGE_EXT', ['jpg', 'jpeg', 'png', 'gif']);

// Session
define('SESSION_TIMEOUT', 3600); // 1 jam

// Timezone
date_default_timezone_set('Asia/Jakarta');
