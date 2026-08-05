<?php
require_once '../../includes/auth.php';
check_login();

$page = max(1, intval($_GET['page'] ?? 1));
$perPage = 15;
$total = $dbh->query("SELECT COUNT(*) FROM tbl_dokumen d JOIN tbl_warga w ON w.id = d.warga_id")->fetchColumn();
$pagination = paginate($total, $perPage, $page);

$stmt = $dbh->prepare("
    SELECT d.*, w.nama_lengkap, w.nik 
    FROM tbl_dokumen d 
    JOIN tbl_warga w ON w.id = d.warga_id 
    ORDER BY d.created_at DESC 
    LIMIT :limit OFFSET :offset
");
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $pagination['offset'], PDO::PARAM_INT);
$stmt->execute();
$dokumenList = $stmt->fetchAll();

$wargaList = $dbh->query("SELECT id, nik, nama_lengkap FROM tbl_warga WHERE status_aktif = 1 ORDER BY nama_lengkap")->fetchAll();

if (isset($_POST['submit_dokumen'])) {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) die('Token tidak valid');
    
    $filePath = null;
    if (!empty($_FILES['file_dokumen']['name'])) {
        $result = uploadFile($_FILES['file_dokumen'], '../../uploads/dokumen/');
        if ($result['success']) $filePath = $result['filename'];
    }
    
    $stmt = $dbh->prepare("INSERT INTO tbl_dokumen (warga_id, jenis_dokumen, nomor_dokumen, tanggal_terbit, file_path, status) VALUES (:warga, :jenis, :nomor, :tanggal, :file, :status)");
    $stmt->execute([':warga' => $_POST['warga_id'], ':jenis' => $_POST['jenis_dokumen'], ':nomor' => $_POST['nomor_dokumen'], ':tanggal' => $_POST['tanggal_terbit'] ?: null, ':file' => $filePath, ':status' => $_POST['status']]);
    logAudit($dbh, $_SESSION['user_id'], 'INSERT', 'tbl_dokumen');
    setFlash('success', 'Dokumen berhasil ditambahkan');
    redirect('dokumen.php');
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
                <span class="icon bg-success"><i class="mdi mdi-file-document"></i></span>
                Dokumen Kependudukan
            </h3>
            <div class="breadcrumb"><a href="../dashboard/index.php">Beranda</a> / Dokumen Kependudukan</div>
        </div>

        <?php $flash = getFlash(); if ($flash): ?>
            <div class="alert alert-<?= $flash['type'] ?>"><?= sanitize($flash['message']) ?></div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header d-flex justify-content-end align-items-center">
                <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalTambah">
                    <i class="mdi mdi-plus"></i> Tambah Dokumen
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-items-center table-flush table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Nama Warga</th>
                                <th>Jenis Dokumen</th>
                                <th>Nomor</th>
                                <th>Tanggal Terbit</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $cnt = $pagination['offset'] + 1; foreach ($dokumenList as $row): ?>
                            <tr>
                                <td class="text-center"><?= $cnt++ ?></td>
                                <td><?= sanitize($row->nama_lengkap) ?></td>
                                <td><span class="badge badge-info"><?= $row->jenis_dokumen ?></span></td>
                                <td><code><?= sanitize($row->nomor_dokumen ?? '-') ?></code></td>
                                <td><?= $row->tanggal_terbit ? formatDate($row->tanggal_terbit) : '-' ?></td>
                                <td class="text-center">
                                    <span class="badge badge-<?= $row->status === 'Aktif' ? 'success' : ($row->status === 'Proses' ? 'warning' : 'secondary') ?>">
                                        <?= $row->status ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?= renderPagination($pagination, 'dokumen.php') ?>
            </div>
        </div>

        <?php include '../../includes/footer.php'; ?>
    </div>

    <div class="modal fade" id="modalTambah" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Dokumen</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <form method="post" enctype="multipart/form-data">
                    <?= csrfField() ?>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Warga</label>
                                    <select class="form-control" name="warga_id" required>
                                        <option value="">Pilih Warga</option>
                                        <?php foreach ($wargaList as $w): ?>
                                            <option value="<?= $w->id ?>"><?= sanitize($w->nik) ?> - <?= sanitize($w->nama_lengkap) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Jenis Dokumen</label>
                                    <select class="form-control" name="jenis_dokumen" required>
                                        <option value="KTP">KTP</option>
                                        <option value="KK">KK</option>
                                        <option value="Akta Kelahiran">Akta Kelahiran</option>
                                        <option value="Akta Kematian">Akta Kematian</option>
                                        <option value="Surat Pindah">Surat Pindah</option>
                                        <option value="Surat Keterangan">Surat Keterangan</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nomor Dokumen</label>
                                    <input type="text" class="form-control" name="nomor_dokumen">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tanggal Terbit</label>
                                    <input type="date" class="form-control" name="tanggal_terbit">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>File Dokumen</label>
                                    <input type="file" class="form-control" name="file_dokumen">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select class="form-control" name="status">
                                        <option value="Proses">Proses</option>
                                        <option value="Aktif">Aktif</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" name="submit_dokumen" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include '../../includes/foot.php'; ?>
</body>
</html>