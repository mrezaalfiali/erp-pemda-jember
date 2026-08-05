<?php
require_once '../../includes/auth.php';
check_login();
if (!hasRole('Admin')) { setFlash('danger', 'Akses ditolak'); redirect('../dashboard/index.php'); }

$currentUser = getCurrentUser($dbh);

if (isset($_POST['submit_profile'])) {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) die('Token tidak valid');
    $stmt = $dbh->prepare("UPDATE tbl_users SET nama_lengkap = :nama, email = :email, no_hp = :hp WHERE id = :id");
    $stmt->execute([':nama' => $_POST['nama_lengkap'], ':email' => $_POST['email'], ':hp' => $_POST['no_hp'], ':id' => $currentUser->id]);
    $_SESSION['nama_lengkap'] = $_POST['nama_lengkap'];
    logAudit($dbh, $_SESSION['user_id'], 'UPDATE', 'tbl_users', $currentUser->id);
    setFlash('success', 'Profil berhasil diupdate');
    redirect('profile.php');
}

if (isset($_POST['submit_foto'])) {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) die('Token tidak valid');
    if (!empty($_FILES['foto']['name'])) {
        $dest = UPLOAD_PATH . 'foto_profil/';
        $result = uploadFile($_FILES['foto'], $dest, ALLOWED_IMAGE_EXT);
        if ($result['success']) {
            $oldFoto = $currentUser->foto;
            $stmt = $dbh->prepare("UPDATE tbl_users SET foto = :foto WHERE id = :id");
            $stmt->execute([':foto' => $result['filename'], ':id' => $currentUser->id]);
            $_SESSION['user_foto'] = $result['filename'];
            if ($oldFoto && $oldFoto !== 'default.jpg' && file_exists($dest . $oldFoto)) {
                unlink($dest . $oldFoto);
            }
            logAudit($dbh, $_SESSION['user_id'], 'UPDATE', 'tbl_users', $currentUser->id);
            setFlash('success', 'Foto profil berhasil diupdate');
        } else {
            setFlash('danger', $result['message']);
        }
    } else {
        setFlash('danger', 'Pilih foto terlebih dahulu');
    }
    redirect('profile.php');
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
                <span class="icon bg-primary"><i class="mdi mdi-account"></i></span>
                Profil Saya
            </h3>
            <div class="breadcrumb"><a href="../dashboard/index.php">Beranda</a> / Profil</div>
        </div>

        <?php $flash = getFlash(); if ($flash): ?>
            <div class="alert alert-<?= $flash['type'] ?>"><?= sanitize($flash['message']) ?></div>
        <?php endif; ?>

        <div style="display:grid; grid-template-columns: 2fr 1fr; gap:20px;">
            <div class="card">
                <div class="card-body">
                    <form method="post">
                        <?= csrfField() ?>
                        <div class="form-group"><label>Username</label><input type="text" class="form-control" value="<?= sanitize($currentUser->username) ?>" readonly></div>
                        <div class="form-group"><label>Nama Lengkap</label><input type="text" class="form-control" name="nama_lengkap" value="<?= sanitize($currentUser->nama_lengkap) ?>" required></div>
                        <div class="form-group"><label>Email</label><input type="email" class="form-control" name="email" value="<?= sanitize($currentUser->email ?? '') ?>"></div>
                        <div class="form-group"><label>No. HP</label><input type="text" class="form-control" name="no_hp" value="<?= sanitize($currentUser->no_hp ?? '') ?>"></div>
                        <div class="form-group"><label>Role</label><input type="text" class="form-control" value="<?= sanitize($currentUser->nama_role) ?>" readonly></div>
                        <button type="submit" name="submit_profile" class="btn btn-primary"><i class="mdi mdi-content-save"></i> Update Profil</button>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-body text-center">
                    <img src="../../uploads/foto_profil/<?= $currentUser->foto ?>" class="rounded-circle mb-3" width="150" height="150" alt="Foto Profil" style="object-fit:cover;" onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($currentUser->nama_lengkap) ?>&background=4f46e5&color=fff&size=150'">
                    <h5><?= sanitize($currentUser->nama_lengkap) ?></h5>
                    <p class="text-muted"><?= sanitize($currentUser->nama_role) ?></p>
                    <p class="small text-muted">Terdaftar: <?= formatDate($currentUser->created_at, 'd M Y') ?></p>
                    <hr>
                    <form method="post" enctype="multipart/form-data">
                        <?= csrfField() ?>
                        <div class="form-group">
                            <input type="file" class="form-control" name="foto" accept="image/*" required id="inputFoto">
                        </div>
                        <button type="submit" name="submit_foto" class="btn btn-primary btn-block"><i class="mdi mdi-camera"></i> Ubah Foto</button>
                    </form>
                </div>
            </div>
        </div>

        <?php include '../../includes/footer.php'; ?>
    </div>

    <?php include '../../includes/foot.php'; ?>
</body>
</html>
