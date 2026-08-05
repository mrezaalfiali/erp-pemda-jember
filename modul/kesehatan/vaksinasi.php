<?php
require_once '../../includes/auth.php';
check_login();

if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    $stmt = $dbh->prepare("DELETE FROM tbl_vaksinasi WHERE id = :id");
    $stmt->execute([':id' => $id]);
    logAudit($dbh, $_SESSION['user_id'], 'DELETE', 'tbl_vaksinasi', $id);
    setFlash('success', 'Data vaksinasi berhasil dihapus');
    redirect('vaksinasi.php');
}

$page = max(1, intval($_GET['page'] ?? 1));
$perPage = 10;
$total = $dbh->query("SELECT COUNT(*) FROM tbl_vaksinasi v JOIN tbl_warga w ON w.id = v.warga_id")->fetchColumn();
$pagination = paginate($total, $perPage, $page);

$stmt = $dbh->prepare("
    SELECT v.*, w.nama_lengkap, w.nik 
    FROM tbl_vaksinasi v 
    JOIN tbl_warga w ON w.id = v.warga_id 
    ORDER BY v.tanggal_vaksinasi DESC 
    LIMIT :limit OFFSET :offset
");
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $pagination['offset'], PDO::PARAM_INT);
$stmt->execute();
$vaksinList = $stmt->fetchAll();

$wargaList = $dbh->query("SELECT id, nik, nama_lengkap FROM tbl_warga WHERE status_aktif = 1 ORDER BY nama_lengkap")->fetchAll();

if (isset($_POST['submit_vaksin'])) {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) die('Token tidak valid');
    
    $stmt = $dbh->prepare("INSERT INTO tbl_vaksinasi (warga_id, jenis_vaksin, tanggal_vaksinasi, dosis, petugas, lokasi, status) VALUES (:warga, :vaksin, :tanggal, :dosis, :petugas, :lokasi, :status)");
    $stmt->execute([
        ':warga' => $_POST['warga_id'],
        ':vaksin' => $_POST['jenis_vaksin'],
        ':tanggal' => $_POST['tanggal_vaksinasi'],
        ':dosis' => $_POST['dosis'],
        ':petugas' => $_POST['petugas'],
        ':lokasi' => $_POST['lokasi'],
        ':status' => $_POST['status']
    ]);
    logAudit($dbh, $_SESSION['user_id'], 'INSERT', 'tbl_vaksinasi');
    setFlash('success', 'Data vaksinasi berhasil ditambahkan');
    redirect('vaksinasi.php');
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
                <span class="icon bg-info"><i class="mdi mdi-needle"></i></span>
                Vaksinasi
            </h3>
            <div class="breadcrumb"><a href="../dashboard/index.php">Beranda</a> / Vaksinasi</div>
        </div>

        <?php $flash = getFlash(); if ($flash): ?>
            <div class="alert alert-<?= $flash['type'] ?>"><?= sanitize($flash['message']) ?></div>
        <?php endif; ?>

        <div style="display:flex; justify-content:flex-end; margin-bottom:1rem;">
            <button class="btn btn-success" data-toggle="modal" data-target="#modalTambah">
                <i class="mdi mdi-plus"></i> Tambah Vaksinasi
            </button>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Nama Warga</th>
                                <th>NIK</th>
                                <th>Jenis Vaksin</th>
                                <th>Tanggal</th>
                                <th>Dosis</th>
                                <th>Petugas</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $cnt = $pagination['offset'] + 1; foreach ($vaksinList as $row): ?>
                            <tr>
                                <td class="text-center"><?= $cnt++ ?></td>
                                <td><?= sanitize($row->nama_lengkap) ?></td>
                                <td><code><?= sanitize($row->nik) ?></code></td>
                                <td><?= sanitize($row->jenis_vaksin) ?></td>
                                <td><?= formatDate($row->tanggal_vaksinasi) ?></td>
                                <td class="text-center"><?= $row->dosis ?></td>
                                <td><?= sanitize($row->petugas) ?></td>
                                <td class="text-center">
                                    <span class="badge badge-<?= $row->status === 'Selesai' ? 'success' : ($row->status === 'Batal' ? 'danger' : 'warning') ?>">
                                        <?= $row->status ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="vaksinasi.php?hapus=<?= $row->id ?>" class="btn btn-danger btn-sm btn-delete"><i class="mdi mdi-delete"></i></a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?= renderPagination($pagination, 'vaksinasi.php') ?>
            </div>
        </div>

        <div class="modal fade" id="modalTambah" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Vaksinasi</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <form method="post">
                        <?= csrfField() ?>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Warga <span class="text-danger">*</span></label>
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
                                        <label>Jenis Vaksin <span class="text-danger">*</span></label>
                                        <select class="form-control" name="jenis_vaksin" required>
                                            <option value="">Pilih Vaksin</option>
                                            <option value="COVID-19">COVID-19</option>
                                            <option value="Polio">Polio</option>
                                            <option value="Campak">Campak</option>
                                            <option value="Hepatitis">Hepatitis</option>
                                            <option value="BCG">BCG</option>
                                            <option value="DPT">DPT</option>
                                            <option value="Lainnya">Lainnya</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Tanggal Vaksinasi <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="tanggal_vaksinasi" value="<?= date('Y-m-d') ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Dosis</label>
                                        <input type="number" class="form-control" name="dosis" value="1" min="1" max="10">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Status</label>
                                        <select class="form-control" name="status">
                                            <option value="Selesai">Selesai</option>
                                            <option value="Terjadwal">Terjadwal</option>
                                            <option value="Tunda">Tunda</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Petugas</label>
                                        <input type="text" class="form-control" name="petugas" placeholder="Nama petugas">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Lokasi</label>
                                        <input type="text" class="form-control" name="lokasi" placeholder="Lokasi vaksinasi">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" name="submit_vaksin" class="btn btn-primary"><i class="mdi mdi-content-save"></i> Simpan</button>
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
