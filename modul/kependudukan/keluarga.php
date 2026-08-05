<?php
require_once '../../includes/auth.php';
check_login();

// Handle Delete
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    $stmt = $dbh->prepare("UPDATE tbl_keluarga SET status_aktif = 0 WHERE id = :id");
    $stmt->execute([':id' => $id]);
    logAudit($dbh, $_SESSION['user_id'], 'DELETE', 'tbl_keluarga', $id);
    setFlash('success', 'Data KK berhasil dihapus');
    redirect('keluarga.php');
}

// Get data
$page = max(1, intval($_GET['page'] ?? 1));
$perPage = 10;

$total = $dbh->query("SELECT COUNT(*) FROM tbl_keluarga WHERE status_aktif = 1")->fetchColumn();
$pagination = paginate($total, $perPage, $page);

$stmt = $dbh->prepare("
    SELECT kk.*, w.nama_lengkap as nama_kepala, d.nama_desa, k.nama_kecamatan 
    FROM tbl_keluarga kk 
    LEFT JOIN tbl_warga w ON w.id = kk.kepala_keluarga_id 
    LEFT JOIN tbl_desa d ON d.id = kk.desa_id 
    LEFT JOIN tbl_kecamatan k ON k.id = d.kecamatan_id 
    WHERE kk.status_aktif = 1 
    ORDER BY kk.created_at DESC 
    LIMIT :limit OFFSET :offset
");
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $pagination['offset'], PDO::PARAM_INT);
$stmt->execute();
$kkList = $stmt->fetchAll();

// Get data for form
$wargaList = $dbh->query("SELECT id, nik, nama_lengkap FROM tbl_warga WHERE status_aktif = 1 ORDER BY nama_lengkap")->fetchAll();
$kecamatanList = $dbh->query("SELECT * FROM tbl_kecamatan ORDER BY nama_kecamatan")->fetchAll();

// Handle form submit
if (isset($_POST['submit_keluarga'])) {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) die('Token tidak valid');
    
    $noKK = $_POST['no_kk'];
    if (!validateNoKK($noKK)) {
        setFlash('danger', 'No KK harus 16 digit angka');
        redirect('keluarga.php');
    }
    
    $data = [
        ':no_kk' => $noKK,
        ':kepala_keluarga_id' => $_POST['kepala_keluarga_id'],
        ':alamat' => $_POST['alamat'],
        ':rt' => $_POST['rt'],
        ':rw' => $_POST['rw'],
        ':desa_id' => $_POST['desa_id']
    ];
    
    $sql = "INSERT INTO tbl_keluarga (no_kk, kepala_keluarga_id, alamat, rt, rw, desa_id, jumlah_anggota) 
            VALUES (:no_kk, :kepala_keluarga_id, :alamat, :rt, :rw, :desa_id, 1)";
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
    $kkId = $dbh->lastInsertId();
    
    // Add kepala keluarga as member
    $stmt = $dbh->prepare("INSERT INTO tbl_keluarga_anggota (keluarga_id, warga_id, hubungan) VALUES (:kk, :warga, 'Kepala Keluarga')");
    $stmt->execute([':kk' => $kkId, ':warga' => $data[':kepala_keluarga_id']]);
    
    logAudit($dbh, $_SESSION['user_id'], 'INSERT', 'tbl_keluarga', $kkId);
    setFlash('success', 'Data KK berhasil ditambahkan');
    redirect('keluarga.php');
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
                <span class="icon bg-primary"><i class="mdi mdi-home-account"></i></span>
                Kartu Keluarga
            </h3>
            <div class="breadcrumb"><a href="../dashboard/index.php">Beranda</a> / Kartu Keluarga</div>
        </div>

        <?php $flash = getFlash(); if ($flash): ?>
            <div class="alert alert-<?= $flash['type'] ?>"><?= sanitize($flash['message']) ?></div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar Kartu Keluarga</h5>
                <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalTambah">
                    <i class="mdi mdi-plus"></i> Tambah KK
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-items-center table-flush table-hover">
                        <thead>
                            <tr>
                                <th class="text-center" width="50">No</th>
                                <th>No. KK</th>
                                <th>Kepala Keluarga</th>
                                <th>Alamat</th>
                                <th>Desa</th>
                                <th>Kecamatan</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $cnt = $pagination['offset'] + 1; foreach ($kkList as $row): ?>
                            <tr>
                                <td class="text-center"><?= $cnt++ ?></td>
                                <td><code><?= sanitize($row->no_kk) ?></code></td>
                                <td><?= sanitize($row->nama_kepala ?? '-') ?></td>
                                <td><?= sanitize($row->alamat) ?></td>
                                <td><?= sanitize($row->nama_desa ?? '-') ?></td>
                                <td><?= sanitize($row->nama_kecamatan ?? '-') ?></td>
                                <td class="text-center">
                                    <a href="keluarga.php?hapus=<?= $row->id ?>" class="btn btn-danger btn-sm btn-delete">
                                        <i class="mdi mdi-delete"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?= renderPagination($pagination, 'keluarga.php') ?>
            </div>
        </div>

        <?php include '../../includes/footer.php'; ?>
    </div>

    <!-- Modal Tambah -->
    <div class="modal fade" id="modalTambah" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kartu Keluarga</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <form method="post">
                    <?= csrfField() ?>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>No. KK <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="no_kk" maxlength="16" pattern="[0-9]{16}" required placeholder="16 digit nomor KK">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Kepala Keluarga <span class="text-danger">*</span></label>
                                    <select class="form-control" name="kepala_keluarga_id" required>
                                        <option value="">Pilih Warga</option>
                                        <?php foreach ($wargaList as $w): ?>
                                            <option value="<?= $w->id ?>"><?= sanitize($w->nik) ?> - <?= sanitize($w->nama_lengkap) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Alamat</label>
                            <textarea class="form-control" name="alamat" rows="2" placeholder="Alamat lengkap"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Kecamatan</label>
                                    <select class="form-control" name="kecamatan_id" id="kecamatan_id_form">
                                        <option value="">Pilih</option>
                                        <?php foreach ($kecamatanList as $k): ?>
                                            <option value="<?= $k->id ?>"><?= sanitize($k->nama_kecamatan) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Desa</label>
                                    <select class="form-control" name="desa_id" id="desa_id_form">
                                        <option value="">Pilih Desa</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>RT / RW</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="rt" maxlength="3" placeholder="RT">
                                        <div class="input-group-append"><span class="input-group-text">/</span></div>
                                        <input type="text" class="form-control" name="rw" maxlength="3" placeholder="RW">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" name="submit_keluarga" class="btn btn-primary"><i class="mdi mdi-content-save"></i> Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include '../../includes/foot.php'; ?>
    <script>
    $(document).on('change', '#kecamatan_id_form', function() {
        var kecId = $(this).val();
        if (kecId) {
            $.get('../../api/get_desa.php?kecamatan_id=' + kecId, function(data) {
                var options = '<option value="">Pilih Desa</option>';
                data.forEach(function(d) {
                    options += '<option value="' + d.id + '">' + d.nama_desa + '</option>';
                });
                $('#desa_id_form').html(options);
            });
        }
    });
    </script>
</body>
</html>