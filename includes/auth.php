<?php
// ============================================
// Authentication & Authorization
// ============================================
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/dbconnection.php';
require_once __DIR__ . '/functions.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
function check_login() {
    if (!isset($_SESSION['user_id'])) {
        setFlash('warning', 'Silakan login terlebih dahulu');
        redirect(APP_URL . '/index.php');
    }
    
    // Check session timeout
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > SESSION_TIMEOUT) {
        session_unset();
        session_destroy();
        setFlash('warning', 'Sesi telah berakhir. Silakan login kembali');
        redirect(APP_URL . '/index.php');
    }
    $_SESSION['last_activity'] = time();
}

// Login
function doLogin($dbh, $username, $password) {
    $stmt = $dbh->prepare("SELECT u.*, r.nama_role, r.level_akses FROM tbl_users u JOIN tbl_roles r ON u.role_id = r.id WHERE u.username = :username AND u.status = 1");
    $stmt->execute([':username' => $username]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user->password_hash)) {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['username'] = $user->username;
        $_SESSION['nama_lengkap'] = $user->nama_lengkap;
        $_SESSION['user_role'] = $user->nama_role;
        $_SESSION['user_level'] = $user->level_akses;
        $_SESSION['user_foto'] = $user->foto;
        $_SESSION['last_activity'] = time();
        
        // Update last login
        $stmt = $dbh->prepare("UPDATE tbl_users SET last_login = NOW() WHERE id = :id");
        $stmt->execute([':id' => $user->id]);
        
        return ['success' => true, 'user' => $user];
    }
    
    return ['success' => false, 'message' => 'Username atau password salah'];
}

// Logout
function doLogout() {
    session_unset();
    session_destroy();
    setFlash('success', 'Berhasil logout');
}
