<?php
require_once '../../includes/auth.php';
check_login();

if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    $stmt = $dbh->prepare("DELETE FROM tbl_pengajuan_bantuan WHERE id = :id");
    $stmt->execute([':id' => $id]);
    logAudit($dbh, $_SESSION['user_id'], 'DELETE', 'tbl_pengajuan_bantuan', $id);
    setFlash('success', 'Pengajuan berhasil dihapus');
    redirect('pengajuan.php');
}

// Handle status update
if (isset($_POST['update_status'])) {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) die('Token tidak valid');
    $id = intval($_POST['pengajuan_id']);
    $status = $_POST['status_baru'];
    $stmt = $dbh->prepare("UPDATE tbl_pengajuan_bantuan SET status = :status WHERE id = :id");
    $stmt->execute([':status' => $status, ':id' => $id]);
    
    // If approved, create penerima record
    if ($status === 'Disetujui') {
        $stmt = $dbh->prepare("SELECT * FROM tbl_pengajuan_bantuan WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $pengajuan = $stmt->fetch();
        if ($pengajuan) {
            $stmt = $dbh->prepare("INSERT INTO tbl_penerima_bantuan (program_id, warga_id, pengajuan_id, tanggal_terima, status) VALUES (:prog, :warga, :pengajuan, CURDATE(), 'Diterima')");
            $stmt->execute([':prog' => $pengajuan->program_id, ':warga' => $pengajuan->warga_id, ':pengajuan' => $id]);
        }
    }
    logAudit($dbh, $_SESSION['user_id'], 'UPDATE', 'tbl_pengajuan_bantuan', $id);
    setFlash('success', 'Status pengajuan berhasil diupdate');
    redirect('pengajuan.php');
}

$page = max(1, intval($_GET['page'] ?? 1));
$perPage = 10;
$total = $dbh->query("SELECT COUNT(*) FROM tbl_pengajuan_bantuan p JOIN tbl_warga w ON w.id = p.warga_id JOIN tbl_program_bantuan pr ON pr.id = p.program_id")->fetchColumn();
$pagination = paginate($total, $perPage, $page);

$stmt = $dbh->prepare("
    SELECT p.*, w.nama_lengkap, w.nik, pr.nama_program, pr.kategori 
    FROM tbl_pengajuan_bantuan p 
    JOIN tbl_warga w ON w.id = p.warga_id 
    JOIN tbl_program_bantuan pr ON pr.id = p.program_id 
    ORDER BY p.created_at DESC 
    LIMIT :limit OFFSET :offset
");
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $pagination['offset'], PDO::PARAM_INT);
$stmt->execute();
$pengajuanList = $stmt->fetchAll();

$wargaList = $dbh->query("SELECT id, nik, nama_lengkap FROM tbl_warga WHERE status_aktif = 1 ORDER BY nama_lengkap")->fetchAll();
$programList = $dbh->query("SELECT id, nama_program FROM tbl_program_bantuan WHERE status = 'Aktif' ORDER BY nama_program")->fetchAll();

if (isset($_POST['submit_pengajuan'])) {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) die('Token tidak valid');
    $stmt = $dbh->prepare("INSERT INTO tbl_pengajuan_bantuan (warga_id, program_id, tanggal_pengajuan, keterangan, status) VALUES (:warga, :program, CURDATE(), :keterangan, 'Diajukan')");
    $stmt->execute([':warga' => $_POST['warga_id'], ':program' => $_POST['program_id'], ':keterangan' => $_POST['keterangan']]);
    logAudit($dbh, $_SESSION['user_id'], 'INSERT', 'tbl_pengajuan_bantuan');
    setFlash('success', 'Pengajuan bantuan berhasil diajukan');
    redirect('pengajuan.php');
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
                <span class="icon bg-warning"><i class="mdi mdi-file-check"></i></span>
                Pengajuan Bantuan
            </h3>
            <div class="breadcrumb"><a href="../dashboard/index.php">Beranda</a> / Pengajuan</div>
        </div>

        <?php $flash = getFlash(); if ($flash): ?>
            <div class="alert alert-<?= $flash['type'] ?>"><?= sanitize($flash['message']) ?></div>
        <?php endif; ?>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div></div>
            <button class="btn btn-success" data-toggle="modal" data-target="#modalTambah">
                <i class="mdi mdi-plus"></i> Ajukan Bantuan
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
                                <th>Program</th>
                                <th>Kategori</th>
                                <th>Tanggal</th>
                                <th>Keterangan</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $cnt = $pagination['offset'] + 1; foreach ($pengajuanList as $row): ?>
                            <tr>
                                <td class="text-center"><?= $cnt++ ?></td>
                                <td><?= sanitize($row->nama_lengkap) ?></td>
                                <td><?= sanitize($row->nama_program) ?></td>
                                <td><span class="badge badge-info"><?= $row->kategori ?></span></td>
                                <td><?= formatDate($row->tanggal_pengajuan) ?></td>
                                <td><?= sanitize(substr($row->keterangan ?? '', 0, 50)) ?></td>
                                <td class="text-center">
                                    <?php
                                    $badgeClass = match($row->status) {
                                        'Disetujui' => 'success',
                                        'Ditolak' => 'danger',
                                        'Verifikasi' => 'warning',
                                        default => 'info'
                                    };
                                    ?>
                                    <span class="badge badge-<?= $badgeClass ?>"><?= $row->status ?></span>
                                </td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="mdi mdi-dots-vertical"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <?php if ($row->status === 'Diajukan'): ?>
                                            <button class="dropdown-item btn-proses" data-id="<?= $row->id ?>" data-status="Verifikasi">
                                                <i class="mdi mdi-clock-outline text-info"></i> Verifikasi
                                            </button>
                                            <button class="dropdown-item btn-proses" data-id="<?= $row->id ?>" data-status="Disetujui">
                                                <i class="mdi mdi-check-circle text-success"></i> Setujui
                                            </button>
                                            <button class="dropdown-item btn-proses" data-id="<?= $row->id ?>" data-status="Ditolak">
                                                <i class="mdi mdi-close-circle text-danger"></i> Tolak
                                            </button>
                                            <div class="dropdown-divider"></div>
                                            <?php elseif ($row->status === 'Verifikasi'): ?>
                                            <button class="dropdown-item btn-proses" data-id="<?= $row->id ?>" data-status="Disetujui">
                                                <i class="mdi mdi-check-circle text-success"></i> Setujui
                                            </button>
                                            <button class="dropdown-item btn-proses" data-id="<?= $row->id ?>" data-status="Ditolak">
                                                <i class="mdi mdi-close-circle text-danger"></i> Tolak
                                            </button>
                                            <div class="dropdown-divider"></div>
                                            <?php endif; ?>
                                            <a class="dropdown-item text-danger btn-delete" href="pengajuan.php?hapus=<?= $row->id ?>">
                                                <i class="mdi mdi-delete"></i> Hapus
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?= renderPagination($pagination, 'pengajuan.php') ?>
            </div>
        </div>

        <?php include '../../includes/footer.php'; ?>
    </div>

    <div class="modal fade" id="modalTambah" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title">Ajukan Bantuan</h5><button type="button" class="close" data-dismiss="modal"><span>&times;</span></button></div>
                <form method="post">
                    <?= csrfField() ?>
                    <div class="modal-body">
                        <div class="form-group"><label>Warga</label><select class="form-control" name="warga_id" required><option value="">Pilih Warga</option><?php foreach ($wargaList as $w): ?><option value="<?= $w->id ?>"><?= sanitize($w->nik) ?> - <?= sanitize($w->nama_lengkap) ?></option><?php endforeach; ?></select></div>
                        <div class="form-group"><label>Program Bantuan</label><select class="form-control" name="program_id" required><option value="">Pilih Program</option><?php foreach ($programList as $p): ?><option value="<?= $p->id ?>"><?= sanitize($p->nama_program) ?></option><?php endforeach; ?></select></div>
                        <div class="form-group"><label>Keterangan</label><textarea class="form-control" name="keterangan" rows="3" placeholder="Alasan pengajuan"></textarea></div>
                    </div>
                    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button><button type="submit" name="submit_pengajuan" class="btn btn-primary">Ajukan</button></div>
                </form>
            </div>
        </div>
    </div>

    <form id="formStatus" method="post" style="display:none;">
        <?= csrfField() ?>
        <input type="hidden" name="pengajuan_id" id="pengajuan_id">
        <input type="hidden" name="status_baru" id="status_baru">
        <input type="hidden" name="update_status" value="1">
    </form>

    <?php include '../../includes/foot.php'; ?>
    <script>
    $(document).on('click', '.btn-proses', function() {
        var id = $(this).data('id');
        var status = $(this).data('status');
        if (confirm('Yakin ingin mengubah status menjadi ' + status + '?')) {
            $('#pengajuan_id').val(id);
            $('#status_baru').val(status);
            $('#formStatus').submit();
        }
    });
    </script>
</body>
</html>
