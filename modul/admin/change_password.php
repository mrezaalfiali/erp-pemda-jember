<?php
require_once '../../includes/auth.php';
check_login();

if (isset($_POST['submit_password'])) {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) die('Token tidak valid');
    
    $currentUser = getCurrentUser($dbh);
    
    if (!password_verify($_POST['password_lama'], $currentUser->password_hash)) {
        setFlash('danger', 'Password lama salah');
        redirect('change_password.php');
    }
    
    if ($_POST['password_baru'] !== $_POST['password_konfirmasi']) {
        setFlash('danger', 'Password baru dan konfirmasi tidak cocok');
        redirect('change_password.php');
    }
    
    if (strlen($_POST['password_baru']) < 6) {
        setFlash('danger', 'Password baru minimal 6 karakter');
        redirect('change_password.php');
    }
    
    $hash = password_hash($_POST['password_baru'], PASSWORD_DEFAULT);
    $stmt = $dbh->prepare("UPDATE tbl_users SET password_hash = :pass WHERE id = :id");
    $stmt->execute([':pass' => $hash, ':id' => $_SESSION['user_id']]);
    
    logAudit($dbh, $_SESSION['user_id'], 'UPDATE', 'tbl_users', $_SESSION['user_id']);
    setFlash('success', 'Password berhasil diubah');
    redirect('change_password.php');
}
?>
<!DOCTYPE html>
<html lang="id">
<?php include '../../includes/head.php'; ?>
<body>
    <?php include '../../includes/header.php'; ?>
    <?php include '../../includes/sidebar.php'; ?>

    <div class="app-main">
        <div class="page-header">
            <h3>
                <span class="icon bg-warning"><i class="mdi mdi-key"></i></span>
                Ubah Password
            </h3>
            <div class="breadcrumb"><a href="../dashboard/index.php">Beranda</a> / Ubah Password</div>
        </div>

        <?php $flash = getFlash(); if ($flash): ?>
            <div class="alert alert-<?= $flash['type'] ?>"><?= sanitize($flash['message']) ?></div>
        <?php endif; ?>

        <div class="card" style="max-width:600px;">
            <div class="card-body">
                <form method="post">
                    <?= csrfField() ?>
                    <div class="form-group"><label>Password Lama</label><input type="password" class="form-control" name="password_lama" required></div>
                    <div class="form-group"><label>Password Baru</label><input type="password" class="form-control" name="password_baru" required minlength="6"></div>
                    <div class="form-group"><label>Konfirmasi Password Baru</label><input type="password" class="form-control" name="password_konfirmasi" required></div>
                    <button type="submit" name="submit_password" class="btn btn-primary"><i class="mdi mdi-content-save"></i> Ubah Password</button>
                </form>
            </div>
        </div>

        <?php include '../../includes/footer.php'; ?>
    </div>

    <?php include '../../includes/foot.php'; ?>
</body>
</html>
