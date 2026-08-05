<?php
require_once '../../includes/auth.php';
check_login();

$bpsData = $dbh->query("SELECT * FROM tbl_bps_data ORDER BY created_at DESC")->fetchAll();
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
                <span class="icon bg-warning"><i class="mdi mdi-chart-bar"></i></span>
                Data BPS
            </h3>
            <div class="breadcrumb"><a href="../dashboard/index.php">Beranda</a> / Data BPS</div>
        </div>

        <?php $flash = getFlash(); if ($flash): ?>
            <div class="alert alert-<?= $flash['type'] ?>"><?= sanitize($flash['message']) ?></div>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead><tr><th>Kode Wilayah</th><th>Nama Wilayah</th><th>Tahun</th><th>Data Statistik</th></tr></thead>
                        <tbody>
                            <?php if (empty($bpsData)): ?>
                                <tr><td colspan="4" class="text-center text-muted">Belum ada data</td></tr>
                            <?php else: foreach ($bpsData as $row): ?>
                            <tr>
                                <td><code><?= sanitize($row->kode_wilayah) ?></code></td>
                                <td><?= sanitize($row->nama_wilayah) ?></td>
                                <td><?= $row->tahun ?></td>
                                <td><pre class="mb-0" style="font-size: 0.75rem; max-height: 100px; overflow-y: auto;"><?= $row->data_statistik ?></pre></td>
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
