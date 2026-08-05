<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/dbconnection.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

if (isset($_SESSION['user_id'])) {
    redirect('modul/dashboard/index.php');
}

$error = '';
if (isset($_POST['login'])) {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = 'Token keamanan tidak valid';
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        
        if (empty($username) || empty($password)) {
            $error = 'Username dan password harus diisi';
        } else {
            $result = doLogin($dbh, $username, $password);
            if ($result['success']) {
                logAudit($dbh, $_SESSION['user_id'], 'LOGIN', 'tbl_users', $_SESSION['user_id']);
                redirect('modul/dashboard/index.php');
            } else {
                $error = $result['message'];
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<?php include 'includes/head.php'; ?>
<body>
    <div class="auth-wrapper">
        <div class="auth-card">
            <div class="brand-logo">
                <img src="uploads/logo/logo.png" alt="Logo" onerror="this.src='https://ui-avatars.com/api/?name=Jember&background=4f46e5&color=fff&size=160'">
                <h4><?= APP_NAME ?></h4>
                <p>Sistem Informasi Terintegrasi</p>
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-danger">
                    <i class="mdi mdi-alert-circle"></i>
                    <?= sanitize($error) ?>
                </div>
            <?php endif; ?>
            
            <?php $flash = getFlash(); if ($flash): ?>
                <div class="alert alert-<?= $flash['type'] ?>">
                    <i class="mdi mdi-<?= $flash['type'] === 'success' ? 'check-circle' : 'information' ?>"></i>
                    <?= sanitize($flash['message']) ?>
                </div>
            <?php endif; ?>
            
            <form method="post">
                <?= csrfField() ?>
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" class="form-control" name="username" placeholder="Masukkan username" required autofocus>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" class="form-control" name="password" placeholder="Masukkan password" required>
                </div>
                <button name="login" class="btn btn-primary btn-block btn-lg">
                    <i class="mdi mdi-login"></i> MASUK
                </button>
                <div class="text-center mt-3">
                    <small class="text-muted">
                        <i class="mdi mdi-shield-check"></i> Dilindungi oleh enkripsi
                    </small>
                </div>
            </form>
        </div>
    </div>
    <?php include 'includes/foot.php'; ?>
</body>
</html>
