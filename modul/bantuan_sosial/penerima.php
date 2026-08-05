<?php
require_once '../../includes/auth.php';
check_login();

$page = max(1, intval($_GET['page'] ?? 1));
$perPage = 10;
$total = $dbh->query("SELECT COUNT(*) FROM tbl_penerima_bantuan p JOIN tbl_warga w ON w.id = p.warga_id JOIN tbl_program_bantuan pr ON pr.id = p.program_id")->fetchColumn();
$pagination = paginate($total, $perPage, $page);

$stmt = $dbh->prepare("
    SELECT p.*, w.nama_lengkap, w.nik, pr.nama_program 
    FROM tbl_penerima_bantuan p 
    JOIN tbl_warga w ON w.id = p.warga_id 
    JOIN tbl_program_bantuan pr ON pr.id = p.program_id 
    ORDER BY p.tanggal_terima DESC 
    LIMIT :limit OFFSET :offset
");
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $pagination['offset'], PDO::PARAM_INT);
$stmt->execute();
$penerimaList = $stmt->fetchAll();
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
                <span class="icon bg-primary"><i class="mdi mdi-account-check"></i></span>
                Penerima Bantuan
            </h3>
            <div class="breadcrumb"><a href="../dashboard/index.php">Beranda</a> / Penerima</div>
        </div>

        <?php $flash = getFlash(); if ($flash): ?>
            <div class="alert alert-<?= $flash['type'] ?>"><?= sanitize($flash['message']) ?></div>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Nama Penerima</th>
                                <th>NIK</th>
                                <th>Program</th>
                                <th>Tanggal Terima</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $cnt = $pagination['offset'] + 1; foreach ($penerimaList as $row): ?>
                            <tr>
                                <td class="text-center"><?= $cnt++ ?></td>
                                <td><?= sanitize($row->nama_lengkap) ?></td>
                                <td><code><?= sanitize($row->nik) ?></code></td>
                                <td><?= sanitize($row->nama_program) ?></td>
                                <td><?= formatDate($row->tanggal_terima) ?></td>
                                <td><span class="badge badge-<?= $row->status === 'Diterima' ? 'success' : 'warning' ?>"><?= $row->status ?></span></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?= renderPagination($pagination, 'penerima.php') ?>
            </div>
        </div>

        <?php include '../../includes/footer.php'; ?>
    </div>

    <?php include '../../includes/foot.php'; ?>
</body>
</html>
