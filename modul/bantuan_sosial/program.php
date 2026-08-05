<?php
require_once '../../includes/auth.php';
check_login();

if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    $stmt = $dbh->prepare("UPDATE tbl_program_bantuan SET status = 'Non-aktif' WHERE id = :id");
    $stmt->execute([':id' => $id]);
    logAudit($dbh, $_SESSION['user_id'], 'DELETE', 'tbl_program_bantuan', $id);
    setFlash('success', 'Program bantuan berhasil di non-aktifkan');
    redirect('program.php');
}

$programList = $dbh->query("SELECT * FROM tbl_program_bantuan WHERE status != 'Non-aktif' ORDER BY created_at DESC")->fetchAll();

if (isset($_POST['submit_program'])) {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) die('Token tidak valid');
    $stmt = $dbh->prepare("INSERT INTO tbl_program_bantuan (nama_program, kategori, anggaran, deskripsi, tahun, status) VALUES (:nama, :kategori, :anggaran, :deskripsi, :tahun, 'Aktif')");
    $stmt->execute([':nama' => $_POST['nama_program'], ':kategori' => $_POST['kategori'], ':anggaran' => $_POST['anggaran'], ':deskripsi' => $_POST['deskripsi'], ':tahun' => $_POST['tahun']]);
    logAudit($dbh, $_SESSION['user_id'], 'INSERT', 'tbl_program_bantuan');
    setFlash('success', 'Program bantuan berhasil ditambahkan');
    redirect('program.php');
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
                <span class="icon bg-success"><i class="mdi mdi-clipboard-text"></i></span>
                Program Bantuan Sosial
            </h3>
            <div class="breadcrumb"><a href="../dashboard/index.php">Beranda</a> / Program Bantuan</div>
        </div>

        <?php $flash = getFlash(); if ($flash): ?>
            <div class="alert alert-<?= $flash['type'] ?>"><?= sanitize($flash['message']) ?></div>
        <?php endif; ?>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div></div>
            <button class="btn btn-success" data-toggle="modal" data-target="#modalTambah">
                <i class="mdi mdi-plus"></i> Tambah Program
            </button>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Nama Program</th>
                                <th>Kategori</th>
                                <th>Anggaran</th>
                                <th>Tahun</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $cnt = 1; foreach ($programList as $row): ?>
                            <tr>
                                <td class="text-center"><?= $cnt++ ?></td>
                                <td><strong><?= sanitize($row->nama_program) ?></strong><br><small class="text-muted"><?= sanitize(substr($row->deskripsi ?? '', 0, 80)) ?></small></td>
                                <td><span class="badge badge-info"><?= $row->kategori ?></span></td>
                                <td><?= formatRupiah($row->anggaran) ?></td>
                                <td class="text-center"><?= $row->tahun ?></td>
                                <td><span class="badge badge-<?= $row->status === 'Aktif' ? 'success' : 'secondary' ?>"><?= $row->status ?></span></td>
                                <td class="text-center">
                                    <a href="program.php?hapus=<?= $row->id ?>" class="btn btn-danger btn-sm btn-delete"><i class="mdi mdi-delete"></i></a>
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
                <div class="modal-header"><h5 class="modal-title">Tambah Program Bantuan</h5><button type="button" class="close" data-dismiss="modal"><span>&times;</span></button></div>
                <form method="post">
                    <?= csrfField() ?>
                    <div class="modal-body">
                        <div class="form-group"><label>Nama Program</label><input type="text" class="form-control" name="nama_program" required></div>
                        <div style="display:grid; grid-template-columns: repeat(2, 1fr); gap:15px;">
                            <div class="form-group"><label>Kategori</label><select class="form-control" name="kategori" required><option value="BLT">BLT</option><option value="Beras">Beras</option><option value="Pendidikan">Pendidikan</option><option value="Kesehatan">Kesehatan</option><option value="Perumahan">Perumahan</option><option value="Usaha">Usaha</option><option value="Lainnya">Lainnya</option></select></div>
                            <div class="form-group"><label>Tahun</label><input type="number" class="form-control" name="tahun" value="<?= date('Y') ?>" required></div>
                        </div>
                        <div class="form-group"><label>Anggaran</label><input type="number" class="form-control" name="anggaran" min="0" required></div>
                        <div class="form-group"><label>Deskripsi</label><textarea class="form-control" name="deskripsi" rows="3"></textarea></div>
                    </div>
                    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button><button type="submit" name="submit_program" class="btn btn-primary">Simpan</button></div>
                </form>
            </div>
        </div>
    </div>

    <?php include '../../includes/foot.php'; ?>
</body>
</html>
