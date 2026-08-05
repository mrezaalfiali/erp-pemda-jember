<?php
require_once '../../includes/auth.php';
check_login();

if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    $stmt = $dbh->prepare("DELETE FROM tbl_rekam_medis WHERE id = :id");
    $stmt->execute([':id' => $id]);
    logAudit($dbh, $_SESSION['user_id'], 'DELETE', 'tbl_rekam_medis', $id);
    setFlash('success', 'Data rekam medis berhasil dihapus');
    redirect('rekam_medis.php');
}

$search = $_GET['search'] ?? '';
$where = "WHERE 1=1";
$params = [];
if ($search) {
    $where .= " AND (w.nik LIKE :search OR w.nama_lengkap LIKE :search2 OR rm.diagnosa LIKE :search3)";
    $params[':search'] = "%$search%";
    $params[':search2'] = "%$search%";
    $params[':search3'] = "%$search%";
}

$page = max(1, intval($_GET['page'] ?? 1));
$perPage = 10;
$countSql = "SELECT COUNT(*) FROM tbl_rekam_medis rm JOIN tbl_warga w ON w.id = rm.warga_id $where";
$stmt = $dbh->prepare($countSql);
$stmt->execute($params);
$total = $stmt->fetchColumn();
$pagination = paginate($total, $perPage, $page);

$sql = "SELECT rm.*, w.nama_lengkap, w.nik, rs.nama_rs 
        FROM tbl_rekam_medis rm 
        JOIN tbl_warga w ON w.id = rm.warga_id 
        LEFT JOIN tbl_rumah_sakit rs ON rs.id = rm.rumah_sakit_id 
        $where 
        ORDER BY rm.tanggal DESC 
        LIMIT :limit OFFSET :offset";
$stmt = $dbh->prepare($sql);
foreach ($params as $k => $v) $stmt->bindValue($k, $v);
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $pagination['offset'], PDO::PARAM_INT);
$stmt->execute();
$rekamMedisList = $stmt->fetchAll();

$wargaList = $dbh->query("SELECT id, nik, nama_lengkap FROM tbl_warga WHERE status_aktif = 1 ORDER BY nama_lengkap")->fetchAll();
$rsList = $dbh->query("SELECT id, nama_rs FROM tbl_rumah_sakit WHERE status = 'Aktif' ORDER BY nama_rs")->fetchAll();

if (isset($_POST['submit_rm'])) {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) die('Token tidak valid');
    
    $nomorRM = generateCode('RM');
    $stmt = $dbh->prepare("INSERT INTO tbl_rekam_medis (warga_id, nomor_rm, tanggal, diagnosa, gejala, tindakan, obat_diberikan, nama_dokter, rumah_sakit_id, status) VALUES (:warga, :nomor, :tanggal, :diagnosa, :gejala, :tindakan, :obat, :dokter, :rs, :status)");
    $stmt->execute([
        ':warga' => $_POST['warga_id'],
        ':nomor' => $nomorRM,
        ':tanggal' => $_POST['tanggal'],
        ':diagnosa' => $_POST['diagnosa'],
        ':gejala' => $_POST['gejala'],
        ':tindakan' => $_POST['tindakan'],
        ':obat' => $_POST['obat'],
        ':dokter' => $_POST['dokter'],
        ':rs' => $_POST['rumah_sakit_id'] ?: null,
        ':status' => $_POST['status']
    ]);
    logAudit($dbh, $_SESSION['user_id'], 'INSERT', 'tbl_rekam_medis');
    setFlash('success', 'Data rekam medis berhasil ditambahkan');
    redirect('rekam_medis.php');
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
                <span class="icon bg-success"><i class="mdi mdi-medical-bag"></i></span>
                Rekam Medis
            </h3>
            <div class="breadcrumb"><a href="../dashboard/index.php">Beranda</a> / Rekam Medis</div>
        </div>

        <?php $flash = getFlash(); if ($flash): ?>
            <div class="alert alert-<?= $flash['type'] ?>"><?= sanitize($flash['message']) ?></div>
        <?php endif; ?>

        <div class="card mb-3">
            <div class="card-body d-flex justify-content-between align-items-center">
                <form method="get" class="form-inline">
                    <input type="text" class="form-control mr-2" name="search" placeholder="Cari NIK/Nama/Diagnosa..." value="<?= sanitize($search) ?>">
                    <button type="submit" class="btn btn-primary mr-2"><i class="mdi mdi-magnify"></i></button>
                    <a href="rekam_medis.php" class="btn btn-secondary mr-2"><i class="mdi mdi-refresh"></i></a>
                </form>
                <button class="btn btn-success" data-toggle="modal" data-target="#modalTambah">
                    <i class="mdi mdi-plus"></i> Tambah Rekam Medis
                </button>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>No. RM</th>
                                <th>Nama Warga</th>
                                <th>Tanggal</th>
                                <th>Diagnosa</th>
                                <th>Dokter</th>
                                <th>RS</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $cnt = $pagination['offset'] + 1; foreach ($rekamMedisList as $row): ?>
                            <tr>
                                <td class="text-center"><?= $cnt++ ?></td>
                                <td><code><?= sanitize($row->nomor_rm) ?></code></td>
                                <td><?= sanitize($row->nama_lengkap) ?></td>
                                <td><?= formatDate($row->tanggal) ?></td>
                                <td><?= sanitize(substr($row->diagnosa, 0, 50)) ?>...</td>
                                <td><?= sanitize($row->nama_dokter) ?></td>
                                <td><?= sanitize($row->nama_rs ?? '-') ?></td>
                                <td class="text-center">
                                    <?php
                                    $badgeClass = match($row->status) {
                                        'Selesai' => 'success',
                                        'Rawat Inap' => 'warning',
                                        'Meninggal' => 'danger',
                                        default => 'info'
                                    };
                                    ?>
                                    <span class="badge badge-<?= $badgeClass ?>"><?= $row->status ?></span>
                                </td>
                                <td class="text-center">
                                    <a href="rekam_medis.php?hapus=<?= $row->id ?>" class="btn btn-danger btn-sm btn-delete"><i class="mdi mdi-delete"></i></a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?= renderPagination($pagination, 'rekam_medis.php') ?>
            </div>
        </div>

        <div class="modal fade" id="modalTambah" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Rekam Medis</h5>
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
                                        <label>Tanggal <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="tanggal" value="<?= date('Y-m-d') ?>" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Dokter <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="dokter" required placeholder="Nama dokter">
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
                            <div class="form-group">
                                <label>Gejala</label>
                                <textarea class="form-control" name="gejala" rows="2" placeholder="Gejala yang dirasakan pasien"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Diagnosa <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="diagnosa" rows="2" required placeholder="Hasil diagnosa"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Tindakan</label>
                                <textarea class="form-control" name="tindakan" rows="2" placeholder="Tindakan yang dilakukan"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Obat Diberikan</label>
                                <textarea class="form-control" name="obat" rows="2" placeholder="Obat yang diberikan"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Status <span class="text-danger">*</span></label>
                                <select class="form-control" name="status" required>
                                    <option value="Rawat Jalan">Rawat Jalan</option>
                                    <option value="Rawat Inap">Rawat Inap</option>
                                    <option value="Selesai">Selesai</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" name="submit_rm" class="btn btn-primary"><i class="mdi mdi-content-save"></i> Simpan</button>
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
