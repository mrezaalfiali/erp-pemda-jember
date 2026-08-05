<?php
require_once '../../includes/auth.php';
check_login();

// Handle sync from SIAK
if (isset($_POST['sync_siak'])) {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) die('Token tidak valid');
    
    // Simulate sync - in production this would connect to SIAK API
    $sampleData = [
        ['nik' => '3509010101010001', 'nama' => 'Budi Santoso', 'data' => '{"alamat":"Jl. Merdeka No. 1","ttl":"1985-01-01","jk":"Laki-laki"}'],
        ['nik' => '3509010101010002', 'nama' => 'Siti Rahayu', 'data' => '{"alamat":"Jl. Sudirman No. 5","ttl":"1990-05-15","jk":"Perempuan"}'],
        ['nik' => '3509010101010003', 'nama' => 'Ahmad Hidayat', 'data' => '{"alamat":"Jl. Pahlawan No. 10","ttl":"1988-03-20","jk":"Laki-laki"}'],
    ];
    
    $count = 0;
    foreach ($sampleData as $item) {
        $stmt = $dbh->prepare("INSERT INTO tbl_siak_data (nik, nama, data_json, terakhir_sinkron, status) VALUES (:nik, :nama, :data, NOW(), 'Valid') ON DUPLICATE KEY UPDATE data_json = :data2, terakhir_sinkron = NOW()");
        $stmt->execute([':nik' => $item['nik'], ':nama' => $item['nama'], ':data' => $item['data'], ':data2' => $item['data']]);
        $count++;
    }
    
    // Log sync
    $stmt = $dbh->prepare("INSERT INTO tbl_sync_log (sumber, tanggal, jumlah_data, status, keterangan) VALUES ('SIAK', NOW(), :jumlah, 'Berhasil', 'Sinkronisasi dari SIAK')");
    $stmt->execute([':jumlah' => $count]);
    
    logAudit($dbh, $_SESSION['user_id'], 'SYNC', 'tbl_siak_data', null, null, ['sumber' => 'SIAK', 'jumlah' => $count]);
    setFlash('success', "Berhasil sinkronisasi $count data dari SIAK");
    redirect('siak.php');
}

// Handle sync from BPS
if (isset($_POST['sync_bps'])) {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) die('Token tidak valid');
    
    $sampleData = [
        ['kode' => '35.09.01', 'nama' => 'Kecamatan Ajung', 'data' => '{"jumlah_penduduk":45000,"luas":25.5,"pendidikan":"78%","kesehatan":"85%"}'],
        ['kode' => '35.09.02', 'nama' => 'Kecamatan Ambulu', 'data' => '{"jumlah_penduduk":52000,"luas":30.2,"pendidikan":"75%","kesehatan":"82%"}'],
    ];
    
    $count = 0;
    foreach ($sampleData as $item) {
        $stmt = $dbh->prepare("INSERT INTO tbl_bps_data (kode_wilayah, nama_wilayah, data_statistik, tahun) VALUES (:kode, :nama, :data, YEAR(NOW()))");
        $stmt->execute([':kode' => $item['kode'], ':nama' => $item['nama'], ':data' => $item['data']]);
        $count++;
    }
    
    $stmt = $dbh->prepare("INSERT INTO tbl_sync_log (sumber, tanggal, jumlah_data, status, keterangan) VALUES ('BPS', NOW(), :jumlah, 'Berhasil', 'Sinkronisasi dari BPS')");
    $stmt->execute([':jumlah' => $count]);
    
    logAudit($dbh, $_SESSION['user_id'], 'SYNC', 'tbl_bps_data');
    setFlash('success', "Berhasil sinkronisasi $count data dari BPS");
    redirect('bps.php');
}

// Get sync logs
$siakData = $dbh->query("SELECT * FROM tbl_siak_data ORDER BY terakhir_sinkron DESC LIMIT 20")->fetchAll();
$bpsData = $dbh->query("SELECT * FROM tbl_bps_data ORDER BY created_at DESC LIMIT 20")->fetchAll();
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
                <span class="icon bg-info"><i class="mdi mdi-cloud-sync"></i></span>
                Integrasi Data Eksternal
            </h3>
            <div class="breadcrumb"><a href="../dashboard/index.php">Beranda</a> / Integrasi Data</div>
        </div>

        <?php $flash = getFlash(); if ($flash): ?>
            <div class="alert alert-<?= $flash['type'] ?>"><?= sanitize($flash['message']) ?></div>
        <?php endif; ?>

        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap:20px;" class="mb-3">
            <!-- SIAK -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="mdi mdi-database mr-2"></i>SIAK</h5>
                    <form method="post">
                        <?= csrfField() ?>
                        <button type="submit" name="sync_siak" class="btn btn-success btn-sm"><i class="mdi mdi-sync mr-1"></i> Sinkronisasi</button>
                    </form>
                </div>
                <div class="card-body">
                    <p class="text-muted small">Sistem Informasi Administrasi Kependudukan - Data kependudukan (NIK, KK, Akta)</p>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead><tr><th>NIK</th><th>Nama</th><th>Status</th><th>Sync</th></tr></thead>
                            <tbody>
                                <?php if (empty($siakData)): ?>
                                    <tr><td colspan="4" class="text-center text-muted">Belum ada data. Klik sinkronisasi untuk mulai.</td></tr>
                                <?php else: foreach ($siakData as $row): ?>
                                <tr>
                                    <td><code><?= sanitize($row->nik) ?></code></td>
                                    <td><?= sanitize($row->nama) ?></td>
                                    <td><span class="badge badge-<?= $row->status === 'Valid' ? 'success' : 'warning' ?>"><?= $row->status ?></span></td>
                                    <td><small><?= formatDate($row->terakhir_sinkron, 'd M H:i') ?></small></td>
                                </tr>
                                <?php endforeach; endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- BPS -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="mdi mdi-chart-bar mr-2"></i>BPS</h5>
                    <form method="post">
                        <?= csrfField() ?>
                        <button type="submit" name="sync_bps" class="btn btn-success btn-sm"><i class="mdi mdi-sync mr-1"></i> Sinkronisasi</button>
                    </form>
                </div>
                <div class="card-body">
                    <p class="text-muted small">Badan Pusat Statistik - Data statistik (sensus, ekonomi, sosial)</p>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead><tr><th>Kode</th><th>Wilayah</th><th>Tahun</th></tr></thead>
                            <tbody>
                                <?php if (empty($bpsData)): ?>
                                    <tr><td colspan="3" class="text-center text-muted">Belum ada data.</td></tr>
                                <?php else: foreach ($bpsData as $row): ?>
                                <tr>
                                    <td><code><?= sanitize($row->kode_wilayah) ?></code></td>
                                    <td><?= sanitize($row->nama_wilayah) ?></td>
                                    <td><?= $row->tahun ?></td>
                                </tr>
                                <?php endforeach; endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <?php include '../../includes/footer.php'; ?>
    </div>

    <?php include '../../includes/foot.php'; ?>
</body>
</html>
