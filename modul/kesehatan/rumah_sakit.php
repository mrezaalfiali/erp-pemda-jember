<?php
require_once '../../includes/auth.php';
check_login();

if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    $stmt = $dbh->prepare("UPDATE tbl_rumah_sakit SET status = 'Non-aktif' WHERE id = :id");
    $stmt->execute([':id' => $id]);
    logAudit($dbh, $_SESSION['user_id'], 'DELETE', 'tbl_rumah_sakit', $id);
    setFlash('success', 'Data RS berhasil dihapus');
    redirect('rumah_sakit.php');
}

if (isset($_GET['hapus_puskesmas'])) {
    $id = intval($_GET['hapus_puskesmas']);
    $stmt = $dbh->prepare("UPDATE tbl_puskesmas SET status = 'Non-aktif' WHERE id = :id");
    $stmt->execute([':id' => $id]);
    logAudit($dbh, $_SESSION['user_id'], 'DELETE', 'tbl_puskesmas', $id);
    setFlash('success', 'Data Puskesmas berhasil dihapus');
    redirect('rumah_sakit.php');
}

$rsList = $dbh->query("SELECT * FROM tbl_rumah_sakit WHERE status = 'Aktif' ORDER BY nama_rs")->fetchAll();
$puskesmasList = $dbh->query("SELECT p.*, d.nama_desa, k.nama_kecamatan FROM tbl_puskesmas p LEFT JOIN tbl_desa d ON d.id = p.desa_id LEFT JOIN tbl_kecamatan k ON k.id = d.kecamatan_id WHERE p.status = 'Aktif' ORDER BY p.nama_puskesmas")->fetchAll();

if (isset($_POST['submit_rs'])) {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) die('Token tidak valid');
    $stmt = $dbh->prepare("INSERT INTO tbl_rumah_sakit (nama_rs, alamat, tipe, telepon, kapasitas, status) VALUES (:nama, :alamat, :tipe, :telp, :kapasitas, 'Aktif')");
    $stmt->execute([':nama' => $_POST['nama_rs'], ':alamat' => $_POST['alamat'], ':tipe' => $_POST['tipe'], ':telp' => $_POST['telepon'], ':kapasitas' => $_POST['kapasitas']]);
    logAudit($dbh, $_SESSION['user_id'], 'INSERT', 'tbl_rumah_sakit');
    setFlash('success', 'Data RS berhasil ditambahkan');
    redirect('rumah_sakit.php');
}

if (isset($_POST['submit_puskesmas'])) {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) die('Token tidak valid');
    $stmt = $dbh->prepare("INSERT INTO tbl_puskesmas (nama_puskesmas, alamat, telepon, desa_id, jadwal_praktek, status) VALUES (:nama, :alamat, :telp, :desa, :jadwal, 'Aktif')");
    $stmt->execute([':nama' => $_POST['nama_puskesmas'], ':alamat' => $_POST['alamat'], ':telp' => $_POST['telepon'], ':desa' => $_POST['desa_id'] ?: null, ':jadwal' => $_POST['jadwal']]);
    logAudit($dbh, $_SESSION['user_id'], 'INSERT', 'tbl_puskesmas');
    setFlash('success', 'Data Puskesmas berhasil ditambahkan');
    redirect('rumah_sakit.php');
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
                <span class="icon bg-warning"><i class="mdi mdi-hospital-building"></i></span>
                Rumah Sakit & Puskesmas
            </h3>
            <div class="breadcrumb"><a href="../dashboard/index.php">Beranda</a> / RS & Puskesmas</div>
        </div>

        <?php $flash = getFlash(); if ($flash): ?>
            <div class="alert alert-<?= $flash['type'] ?>"><?= sanitize($flash['message']) ?></div>
        <?php endif; ?>

        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap:20px;">
            <!-- RS -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Rumah Sakit</h5>
                    <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalRS"><i class="mdi mdi-plus"></i></button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nama RS</th>
                                    <th>Tipe</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($rsList as $rs): ?>
                                <tr>
                                    <td><?= sanitize($rs->nama_rs) ?></td>
                                    <td><span class="badge badge-info"><?= $rs->tipe ?></span></td>
                                    <td><a href="rumah_sakit.php?hapus=<?= $rs->id ?>" class="btn btn-danger btn-sm btn-delete"><i class="mdi mdi-delete"></i></a></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Puskesmas -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Puskesmas</h5>
                    <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalPuskesmas"><i class="mdi mdi-plus"></i></button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Desa</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($puskesmasList as $p): ?>
                                <tr>
                                    <td><?= sanitize($p->nama_puskesmas) ?></td>
                                    <td><?= sanitize($p->nama_desa ?? '-') ?></td>
                                    <td><a href="rumah_sakit.php?hapus_puskesmas=<?= $p->id ?>" class="btn btn-danger btn-sm btn-delete"><i class="mdi mdi-delete"></i></a></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal RS -->
        <div class="modal fade" id="modalRS" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Rumah Sakit</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <form method="post">
                        <?= csrfField() ?>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Nama RS</label>
                                <input type="text" class="form-control" name="nama_rs" required>
                            </div>
                            <div class="form-group">
                                <label>Alamat</label>
                                <textarea class="form-control" name="alamat" rows="2"></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Tipe</label>
                                        <select class="form-control" name="tipe">
                                            <option value="RSUD">RSUD</option>
                                            <option value="RS Swasta">RS Swasta</option>
                                            <option value="RS Khusus">RS Khusus</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Telepon</label>
                                        <input type="text" class="form-control" name="telepon">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Kapasitas</label>
                                <input type="number" class="form-control" name="kapasitas" value="0">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" name="submit_rs" class="btn btn-primary"><i class="mdi mdi-content-save"></i> Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Puskesmas -->
        <div class="modal fade" id="modalPuskesmas" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Puskesmas</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <form method="post">
                        <?= csrfField() ?>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Nama Puskesmas</label>
                                <input type="text" class="form-control" name="nama_puskesmas" required>
                            </div>
                            <div class="form-group">
                                <label>Alamat</label>
                                <textarea class="form-control" name="alamat" rows="2"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Telepon</label>
                                <input type="text" class="form-control" name="telepon">
                            </div>
                            <div class="form-group">
                                <label>Jadwal Praktek</label>
                                <input type="text" class="form-control" name="jadwal" placeholder="Senin-Sabtu 08:00-14:00">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" name="submit_puskesmas" class="btn btn-primary"><i class="mdi mdi-content-save"></i> Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <?php include '../../includes/footer.php'; ?>
    </div>

    <?php include '../../includes/foot.php'; ?>
</body>
</html>
