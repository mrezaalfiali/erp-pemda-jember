<?php
require_once '../../includes/auth.php';
check_login();

if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    $stmt = $dbh->prepare("DELETE FROM tbl_stok_obat WHERE id = :id");
    $stmt->execute([':id' => $id]);
    logAudit($dbh, $_SESSION['user_id'], 'DELETE', 'tbl_stok_obat', $id);
    setFlash('success', 'Data obat berhasil dihapus');
    redirect('stok_obat.php');
}

$obatList = $dbh->query("SELECT s.*, rs.nama_rs FROM tbl_stok_obat s LEFT JOIN tbl_rumah_sakit rs ON rs.id = s.rumah_sakit_id ORDER BY s.nama_obat")->fetchAll();
$rsList = $dbh->query("SELECT id, nama_rs FROM tbl_rumah_sakit WHERE status = 'Aktif' ORDER BY nama_rs")->fetchAll();

if (isset($_POST['submit_obat'])) {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) die('Token tidak valid');
    $stok = intval($_POST['stok']);
    $status = 'Tersedia';
    if ($stok <= 0) $status = 'Habis';
    elseif ($_POST['tanggal_kadaluarsa'] && $_POST['tanggal_kadaluarsa'] < date('Y-m-d')) $status = 'Kadaluarsa';
    
    $stmt = $dbh->prepare("INSERT INTO tbl_stok_obat (nama_obat, kategori, stok, satuan, harga, tanggal_kadaluarsa, rumah_sakit_id, status) VALUES (:nama, :kategori, :stok, :satuan, :harga, :kadaluarsa, :rs, :status)");
    $stmt->execute([':nama' => $_POST['nama_obat'], ':kategori' => $_POST['kategori'], ':stok' => $stok, ':satuan' => $_POST['satuan'], ':harga' => $_POST['harga'], ':kadaluarsa' => $_POST['tanggal_kadaluarsa'] ?: null, ':rs' => $_POST['rumah_sakit_id'] ?: null, ':status' => $status]);
    logAudit($dbh, $_SESSION['user_id'], 'INSERT', 'tbl_stok_obat');
    setFlash('success', 'Data obat berhasil ditambahkan');
    redirect('stok_obat.php');
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
                <span class="icon bg-danger"><i class="mdi mdi-pill"></i></span>
                Stok Obat
            </h3>
            <div class="breadcrumb"><a href="../dashboard/index.php">Beranda</a> / Stok Obat</div>
        </div>

        <?php $flash = getFlash(); if ($flash): ?>
            <div class="alert alert-<?= $flash['type'] ?>"><?= sanitize($flash['message']) ?></div>
        <?php endif; ?>

        <div style="display:flex; justify-content:flex-end; margin-bottom:1rem;">
            <button class="btn btn-success" data-toggle="modal" data-target="#modalTambah">
                <i class="mdi mdi-plus"></i> Tambah Obat
            </button>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Nama Obat</th>
                                <th>Kategori</th>
                                <th class="text-center">Stok</th>
                                <th>Satuan</th>
                                <th>Harga</th>
                                <th>Kadaluarsa</th>
                                <th>RS</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $cnt = 1; foreach ($obatList as $row): ?>
                            <tr>
                                <td class="text-center"><?= $cnt++ ?></td>
                                <td><strong><?= sanitize($row->nama_obat) ?></strong></td>
                                <td><?= sanitize($row->kategori ?? '-') ?></td>
                                <td class="text-center"><?= $row->stok ?></td>
                                <td><?= sanitize($row->satuan) ?></td>
                                <td><?= formatRupiah($row->harga) ?></td>
                                <td><?= $row->tanggal_kadaluarsa ? formatDate($row->tanggal_kadaluarsa) : '-' ?></td>
                                <td><?= sanitize($row->nama_rs ?? '-') ?></td>
                                <td class="text-center">
                                    <span class="badge badge-<?= $row->status === 'Tersedia' ? 'success' : ($row->status === 'Kadaluarsa' ? 'danger' : 'warning') ?>">
                                        <?= $row->status ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="stok_obat.php?hapus=<?= $row->id ?>" class="btn btn-danger btn-sm btn-delete"><i class="mdi mdi-delete"></i></a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalTambah" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Stok Obat</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <form method="post">
                        <?= csrfField() ?>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nama Obat</label>
                                        <input type="text" class="form-control" name="nama_obat" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Kategori</label>
                                        <select class="form-control" name="kategori">
                                            <option value="Generik">Generik</option>
                                            <option value="Paten">Paten</option>
                                            <option value="Herbal">Herbal</option>
                                            <option value="Vitamin">Vitamin</option>
                                            <option value="Lainnya">Lainnya</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Stok</label>
                                        <input type="number" class="form-control" name="stok" min="0" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Satuan</label>
                                        <select class="form-control" name="satuan">
                                            <option value="pcs">Pcs</option>
                                            <option value="strip">Strip</option>
                                            <option value="botol">Botol</option>
                                            <option value="tablet">Tablet</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Harga</label>
                                        <input type="number" class="form-control" name="harga" min="0" value="0">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Tanggal Kadaluarsa</label>
                                        <input type="date" class="form-control" name="tanggal_kadaluarsa">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Rumah Sakit</label>
                                        <select class="form-control" name="rumah_sakit_id">
                                            <option value="">Pilih RS</option>
                                            <?php foreach ($rsList as $rs): ?>
                                                <option value="<?= $rs->id ?>"><?= sanitize($rs->nama_rs) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" name="submit_obat" class="btn btn-primary"><i class="mdi mdi-content-save"></i> Simpan</button>
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
