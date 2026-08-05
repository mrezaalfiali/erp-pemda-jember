<?php
require_once '../../includes/auth.php';
check_login();
if (!hasRole('Admin')) { setFlash('danger', 'Akses ditolak'); redirect('../dashboard/index.php'); }

$roleList = $dbh->query("SELECT * FROM tbl_roles ORDER BY level_akses")->fetchAll();

if (isset($_POST['submit_role'])) {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) die('Token tidak valid');
    $stmt = $dbh->prepare("INSERT INTO tbl_roles (nama_role, deskripsi, level_akses) VALUES (:nama, :desk, :level)");
    $stmt->execute([':nama' => $_POST['nama_role'], ':desk' => $_POST['deskripsi'], ':level' => $_POST['level_akses']]);
    logAudit($dbh, $_SESSION['user_id'], 'INSERT', 'tbl_roles');
    setFlash('success', 'Role berhasil ditambahkan');
    redirect('roles.php');
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
                <span class="icon bg-info"><i class="mdi mdi-shield-key"></i></span>
                Role & Akses
            </h3>
            <div class="breadcrumb"><a href="../dashboard/index.php">Beranda</a> / Role & Akses</div>
        </div>

        <?php $flash = getFlash(); if ($flash): ?>
            <div class="alert alert-<?= $flash['type'] ?>"><?= sanitize($flash['message']) ?></div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar Role</h5>
                <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalTambah"><i class="mdi mdi-plus"></i></button>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <thead><tr><th>Role</th><th>Deskripsi</th><th>Level</th></tr></thead>
                    <tbody>
                        <?php foreach ($roleList as $r): ?>
                        <tr>
                            <td><strong><?= sanitize($r->nama_role) ?></strong></td>
                            <td><?= sanitize($r->deskripsi ?? '-') ?></td>
                            <td><span class="badge badge-primary">Level <?= $r->level_akses ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php include '../../includes/footer.php'; ?>
    </div>

    <div class="modal fade" id="modalTambah" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title">Tambah Role</h5><button type="button" class="close" data-dismiss="modal"><span>&times;</span></button></div>
                <form method="post">
                    <?= csrfField() ?>
                    <div class="modal-body">
                        <div class="form-group"><label>Nama Role</label><input type="text" class="form-control" name="nama_role" required></div>
                        <div class="form-group"><label>Deskripsi</label><textarea class="form-control" name="deskripsi" rows="2"></textarea></div>
                        <div class="form-group"><label>Level Akses (1=Tertinggi)</label><input type="number" class="form-control" name="level_akses" value="3" min="1" max="10" required></div>
                    </div>
                    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button><button type="submit" name="submit_role" class="btn btn-primary">Simpan</button></div>
                </form>
            </div>
        </div>
    </div>

    <?php include '../../includes/foot.php'; ?>
</body>
</html>
