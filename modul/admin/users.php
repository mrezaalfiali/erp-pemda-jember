<?php
require_once '../../includes/auth.php';
check_login();
if (!hasRole('Admin')) { setFlash('danger', 'Akses ditolak'); redirect('../dashboard/index.php'); }

if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    if ($id == $_SESSION['user_id']) { setFlash('danger', 'Tidak bisa menghapus akun sendiri'); redirect('users.php'); }
    $stmt = $dbh->prepare("UPDATE tbl_users SET status = 0 WHERE id = :id");
    $stmt->execute([':id' => $id]);
    logAudit($dbh, $_SESSION['user_id'], 'DELETE', 'tbl_users', $id);
    setFlash('success', 'User berhasil dinonaktifkan');
    redirect('users.php');
}

$userList = $dbh->query("SELECT u.*, r.nama_role FROM tbl_users u JOIN tbl_roles r ON u.role_id = r.id WHERE u.status = 1 ORDER BY u.created_at DESC")->fetchAll();
$roleList = $dbh->query("SELECT * FROM tbl_roles ORDER BY level_akses")->fetchAll();

if (isset($_POST['submit_user'])) {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) die('Token tidak valid');
    $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $stmt = $dbh->prepare("INSERT INTO tbl_users (username, password_hash, nama_lengkap, email, no_hp, role_id, status) VALUES (:user, :pass, :nama, :email, :hp, :role, 1)");
    $stmt->execute([':user' => $_POST['username'], ':pass' => $hash, ':nama' => $_POST['nama_lengkap'], ':email' => $_POST['email'], ':hp' => $_POST['no_hp'], ':role' => $_POST['role_id']]);
    logAudit($dbh, $_SESSION['user_id'], 'INSERT', 'tbl_users');
    setFlash('success', 'User berhasil ditambahkan');
    redirect('users.php');
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
                <span class="icon bg-primary"><i class="mdi mdi-account-multiple"></i></span>
                Manajemen User
            </h3>
            <div class="breadcrumb"><a href="../dashboard/index.php">Beranda</a> / Manajemen User</div>
        </div>

        <?php $flash = getFlash(); if ($flash): ?>
            <div class="alert alert-<?= $flash['type'] ?>"><?= sanitize($flash['message']) ?></div>
        <?php endif; ?>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div></div>
            <button class="btn btn-success" data-toggle="modal" data-target="#modalTambah">
                <i class="mdi mdi-plus"></i> Tambah User
            </button>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Username</th>
                                <th>Nama Lengkap</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Last Login</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $cnt = 1; foreach ($userList as $row): ?>
                            <tr>
                                <td class="text-center"><?= $cnt++ ?></td>
                                <td><strong><?= sanitize($row->username) ?></strong></td>
                                <td><?= sanitize($row->nama_lengkap) ?></td>
                                <td><?= sanitize($row->email) ?></td>
                                <td><span class="badge badge-primary"><?= $row->nama_role ?></span></td>
                                <td><?= $row->last_login ? formatDate($row->last_login, 'd M Y H:i') : '-' ?></td>
                                <td class="text-center">
                                    <a href="users.php?hapus=<?= $row->id ?>" class="btn btn-danger btn-sm btn-delete"><i class="mdi mdi-delete"></i></a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <?php include '../../includes/footer.php'; ?>
    </div>

    <div class="modal fade" id="modalTambah" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title">Tambah User</h5><button type="button" class="close" data-dismiss="modal"><span>&times;</span></button></div>
                <form method="post">
                    <?= csrfField() ?>
                    <div class="modal-body">
                        <div class="form-group"><label>Username</label><input type="text" class="form-control" name="username" required></div>
                        <div class="form-group"><label>Nama Lengkap</label><input type="text" class="form-control" name="nama_lengkap" required></div>
                        <div class="form-group"><label>Email</label><input type="email" class="form-control" name="email"></div>
                        <div class="form-group"><label>No. HP</label><input type="text" class="form-control" name="no_hp"></div>
                        <div class="form-group"><label>Password</label><input type="password" class="form-control" name="password" required minlength="6"></div>
                        <div class="form-group"><label>Role</label><select class="form-control" name="role_id" required><?php foreach ($roleList as $r): ?><option value="<?= $r->id ?>"><?= sanitize($r->nama_role) ?></option><?php endforeach; ?></select></div>
                    </div>
                    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button><button type="submit" name="submit_user" class="btn btn-primary">Simpan</button></div>
                </form>
            </div>
        </div>
    </div>

    <?php include '../../includes/foot.php'; ?>
</body>
</html>
