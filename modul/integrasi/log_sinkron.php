<?php
require_once '../../includes/auth.php';
check_login();

$logs = $dbh->query("SELECT * FROM tbl_sync_log ORDER BY tanggal DESC LIMIT 50")->fetchAll();
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
                <span class="icon bg-info"><i class="mdi mdi-history"></i></span>
                Log Sinkronisasi
            </h3>
            <div class="breadcrumb"><a href="../dashboard/index.php">Beranda</a> / Log Sinkronisasi</div>
        </div>

        <?php $flash = getFlash(); if ($flash): ?>
            <div class="alert alert-<?= $flash['type'] ?>"><?= sanitize($flash['message']) ?></div>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead><tr><th>Tanggal</th><th>Sumber</th><th>Jumlah Data</th><th>Status</th><th>Keterangan</th></tr></thead>
                        <tbody>
                            <?php if (empty($logs)): ?>
                                <tr><td colspan="5" class="text-center text-muted">Belum ada log</td></tr>
                            <?php else: foreach ($logs as $row): ?>
                            <tr>
                                <td><?= formatDate($row->tanggal, 'd M Y H:i:s') ?></td>
                                <td><span class="badge badge-primary"><?= $row->sumber ?></span></td>
                                <td><?= $row->jumlah_data ?> data</td>
                                <td><span class="badge badge-<?= $row->status === 'Berhasil' ? 'success' : 'danger' ?>"><?= $row->status ?></span></td>
                                <td><?= sanitize($row->keterangan) ?></td>
                            </tr>
                            <?php endforeach; endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <?php include '../../includes/footer.php'; ?>
    </div>

    <?php include '../../includes/foot.php'; ?>
</body>
</html>
