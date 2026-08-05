<?php
require_once '../../includes/auth.php';
check_login();

if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    $dbh->prepare("UPDATE tbl_warga SET status_aktif = 0 WHERE id = :id")->execute([':id' => $id]);
    logAudit($dbh, $_SESSION['user_id'], 'DELETE', 'tbl_warga', $id);
    setFlash('success', 'Data warga berhasil dihapus');
    redirect('warga.php');
}

$search = $_GET['search'] ?? '';
$filterKecamatan = $_GET['kecamatan'] ?? '';
$filterDesa = $_GET['desa'] ?? '';

$where = "WHERE w.status_aktif = 1";
$params = [];
if ($search) { $where .= " AND (w.nik LIKE :search OR w.nama_lengkap LIKE :search2)"; $params[':search'] = "%$search%"; $params[':search2'] = "%$search%"; }
if ($filterKecamatan) { $where .= " AND d.kecamatan_id = :kecamatan"; $params[':kecamatan'] = $filterKecamatan; }
if ($filterDesa) { $where .= " AND w.desa_id = :desa"; $params[':desa'] = $filterDesa; }

$page = max(1, intval($_GET['page'] ?? 1));
$perPage = 10;
$countQuery = "SELECT COUNT(*) FROM tbl_warga w LEFT JOIN tbl_desa d ON d.id = w.desa_id $where";
$stmt = $dbh->prepare($countQuery); $stmt->execute($params); $total = $stmt->fetchColumn();
$pagination = paginate($total, $perPage, $page);

$sql = "SELECT w.*, d.nama_desa, k.nama_kecamatan FROM tbl_warga w LEFT JOIN tbl_desa d ON d.id = w.desa_id LEFT JOIN tbl_kecamatan k ON k.id = d.kecamatan_id $where ORDER BY w.created_at DESC LIMIT :limit OFFSET :offset";
$stmt = $dbh->prepare($sql);
foreach ($params as $key => $val) $stmt->bindValue($key, $val);
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $pagination['offset'], PDO::PARAM_INT);
$stmt->execute();
$wargaList = $stmt->fetchAll();

$kecamatanList = $dbh->query("SELECT * FROM tbl_kecamatan ORDER BY nama_kecamatan")->fetchAll();
$desaList = [];
if ($filterKecamatan) { $stmt = $dbh->prepare("SELECT * FROM tbl_desa WHERE kecamatan_id = :id ORDER BY nama_desa"); $stmt->execute([':id' => $filterKecamatan]); $desaList = $stmt->fetchAll(); }
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
                <span class="icon bg-primary"><i class="mdi mdi-account-group"></i></span>
                Data Kependudukan
            </h3>
            <div class="breadcrumb"><a href="../dashboard/index.php">Beranda</a> / Data Warga</div>
        </div>

        <?php $flash = getFlash(); if ($flash): ?>
            <div class="alert alert-<?= $flash['type'] ?>">
                <i class="mdi mdi-<?= $flash['type'] === 'success' ? 'check-circle' : 'information' ?>"></i>
                <?= sanitize($flash['message']) ?>
            </div>
        <?php endif; ?>

        <!-- Search & Filter -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="get" class="d-flex" style="gap:10px; flex-wrap:wrap; align-items:flex-end;">
                    <div class="form-group mb-0" style="flex:1; min-width:180px;">
                        <label style="font-size:0.75rem; margin-bottom:4px;">Cari</label>
                        <input type="text" class="form-control" name="search" placeholder="NIK atau Nama..." value="<?= sanitize($search) ?>">
                    </div>
                    <div class="form-group mb-0" style="min-width:160px;">
                        <label style="font-size:0.75rem; margin-bottom:4px;">Kecamatan</label>
                        <select class="form-control" name="kecamatan" id="filterKecamatan">
                            <option value="">Semua</option>
                            <?php foreach ($kecamatanList as $k): ?>
                                <option value="<?= $k->id ?>" <?= $filterKecamatan == $k->id ? 'selected' : '' ?>><?= sanitize($k->nama_kecamatan) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group mb-0" style="min-width:160px;">
                        <label style="font-size:0.75rem; margin-bottom:4px;">Desa</label>
                        <select class="form-control" name="desa">
                            <option value="">Semua</option>
                            <?php foreach ($desaList as $d): ?>
                                <option value="<?= $d->id ?>" <?= $filterDesa == $d->id ? 'selected' : '' ?>><?= sanitize($d->nama_desa) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="mdi mdi-magnify"></i> Cari</button>
                    <a href="warga.php" class="btn btn-secondary"><i class="mdi mdi-refresh"></i></a>
                    <button type="button" class="btn btn-success ml-auto" data-toggle="modal" data-target="#modalTambah"><i class="mdi mdi-plus"></i> Tambah</button>
                </form>
            </div>
        </div>

        <!-- Table -->
        <div class="card">
            <div class="card-header">
                <h5><i class="mdi mdi-table-large"></i> Data Warga</h5>
                <span class="badge badge-primary"><?= $total ?> data</span>
            </div>
            <div class="card-body" style="padding: 0;">
                <div class="table-responsive">
                    <table class="table table-mobile">
                        <thead>
                            <tr>
                                <th class="text-center" width="50">No</th>
                                <th>NIK</th>
                                <th>Nama Lengkap</th>
                                <th class="text-center">Jenis Kelamin</th>
                                <th>Tanggal Lahir</th>
                                <th>Desa</th>
                                <th>Kecamatan</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $cnt = $pagination['offset'] + 1; if (empty($wargaList)): ?>
                                <tr><td colspan="8" class="text-center text-muted p-4">
                                    <i class="mdi mdi-account-search" style="font-size:2rem; display:block; margin-bottom:8px;"></i>
                                    Tidak ada data ditemukan
                                </td></tr>
                            <?php else: foreach ($wargaList as $row): ?>
                            <tr>
                                <td data-label="No" class="text-center"><?= $cnt++ ?></td>
                                <td data-label="NIK"><code style="font-size:0.8rem;"><?= sanitize($row->nik) ?></code></td>
                                <td data-label="Nama"><strong><?= sanitize($row->nama_lengkap) ?></strong><br><small class="text-muted"><?= sanitize($row->pekerjaan ?? '-') ?></small></td>
                                <td data-label="Jenis Kelamin" class="text-center"><?= $row->jenis_kelamin ?></td>
                                <td data-label="Tanggal Lahir"><?= formatDate($row->tanggal_lahir) ?></td>
                                <td data-label="Desa"><?= sanitize($row->nama_desa ?? '-') ?></td>
                                <td data-label="Kecamatan"><?= sanitize($row->nama_kecamatan ?? '-') ?></td>
                                <td data-label="Aksi" class="text-center" style="white-space:nowrap;">
                                    <button class="btn btn-info btn-sm btn-view" data-id="<?= $row->id ?>" title="Lihat"><i class="mdi mdi-eye"></i></button>
                                    <button class="btn btn-warning btn-sm btn-edit" data-id="<?= $row->id ?>" title="Edit"><i class="mdi mdi-pencil"></i></button>
                                    <a href="warga.php?hapus=<?= $row->id ?>" class="btn btn-danger btn-sm btn-delete" title="Hapus"><i class="mdi mdi-delete"></i></a>
                                </td>
                            </tr>
                            <?php endforeach; endif; ?>
                        </tbody>
                    </table>
                </div>
                <?= renderPagination($pagination, 'warga.php') ?>
            </div>
        </div>

        <?php include '../../includes/footer.php'; ?>
    </div>

    <div class="modal fade" id="modalTambah" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content">
        <div class="modal-header"><h5><i class="mdi mdi-account-plus"></i> Tambah Data Warga</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>
        <div class="modal-body" id="formTambahBody"><p class="text-center text-muted">Memuat form...</p></div>
    </div></div></div>

    <div class="modal fade" id="modalEdit" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content">
        <div class="modal-header"><h5><i class="mdi mdi-pencil"></i> Edit Data Warga</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>
        <div class="modal-body" id="formEditBody"><p class="text-center text-muted">Memuat form...</p></div>
    </div></div></div>

    <div class="modal fade" id="modalView" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content">
        <div class="modal-header"><h5><i class="mdi mdi-eye"></i> Detail Data Warga</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>
        <div class="modal-body" id="viewBody"><p class="text-center text-muted">Memuat data...</p></div>
    </div></div></div>

    <?php include '../../includes/foot.php'; ?>
    <script>
    $(document).ready(function(){
        $.get('../../api/form_warga.php', function(data) { $('#formTambahBody').html(data); });

        $(document).on('click','.btn-edit', function(){
            var id = $(this).data('id');
            $.post('../../api/form_warga.php', {id: id}, function(data) { $('#formEditBody').html(data); $('#modalEdit').modal('show'); });
        });

        $(document).on('click','.btn-view', function(){
            var id = $(this).data('id');
            $.post('../../api/view_warga.php', {id: id}, function(data) { $('#viewBody').html(data); $('#modalView').modal('show'); });
        });

        $('#filterKecamatan').on('change', function() {
            var kecId = $(this).val();
            if (kecId) {
                $.get('../../api/get_desa.php?kecamatan_id=' + kecId, function(data) {
                    var opts = '<option value="">Semua</option>';
                    data.forEach(function(d) { opts += '<option value="'+d.id+'">'+d.nama_desa+'</option>'; });
                    $('select[name="desa"]').html(opts);
                });
            } else {
                $('select[name="desa"]').html('<option value="">Semua</option>');
            }
        });
    });
    </script>
</body>
</html>
