<?php
require_once '../../includes/auth.php';
check_login();
$currentUser = getCurrentUser($dbh);

$totalWarga = $dbh->query("SELECT COUNT(*) FROM tbl_warga WHERE status_aktif = 1")->fetchColumn();
$totalKK = $dbh->query("SELECT COUNT(*) FROM tbl_keluarga WHERE status_aktif = 1")->fetchColumn();
$totalKecamatan = $dbh->query("SELECT COUNT(*) FROM tbl_kecamatan")->fetchColumn();
$totalDesa = $dbh->query("SELECT COUNT(*) FROM tbl_desa")->fetchColumn();
$totalMiskin = $dbh->query("SELECT COUNT(*) FROM tbl_hasil_klasifikasi WHERE kategori IN ('Sangat Miskin','Miskin') AND tahun = YEAR(NOW())")->fetchColumn();
$totalBantuan = $dbh->query("SELECT COUNT(*) FROM tbl_penerima_bantuan WHERE status = 'Diterima' AND YEAR(tanggal_terima) = YEAR(NOW())")->fetchColumn();
$totalPasien = $dbh->query("SELECT COUNT(*) FROM tbl_rekam_medis WHERE YEAR(tanggal) = YEAR(NOW())")->fetchColumn();
$totalVaksin = $dbh->query("SELECT COUNT(*) FROM tbl_vaksinasi WHERE status = 'Selesai' AND YEAR(tanggal_vaksinasi) = YEAR(NOW())")->fetchColumn();

$chartKecamatan = $dbh->query("SELECT k.nama_kecamatan, COUNT(w.id) as jumlah FROM tbl_kecamatan k LEFT JOIN tbl_desa d ON d.kecamatan_id = k.id LEFT JOIN tbl_warga w ON w.desa_id = d.id AND w.status_aktif = 1 GROUP BY k.id, k.nama_kecamatan ORDER BY jumlah DESC LIMIT 10")->fetchAll();
$chartKemiskinan = $dbh->query("SELECT kategori, COUNT(*) as jumlah FROM tbl_hasil_klasifikasi WHERE tahun = YEAR(NOW()) GROUP BY kategori")->fetchAll();
$recentWarga = $dbh->query("SELECT nama_lengkap, created_at FROM tbl_warga ORDER BY created_at DESC LIMIT 5")->fetchAll();
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
                <span class="icon bg-primary"><i class="mdi mdi-view-dashboard"></i></span>
                Beranda
            </h3>
        </div>

        <?php $flash = getFlash(); if ($flash): ?>
            <div class="alert alert-<?= $flash['type'] ?>">
                <i class="mdi mdi-<?= $flash['type'] === 'success' ? 'check-circle' : 'information' ?>"></i>
                <?= sanitize($flash['message']) ?>
            </div>
        <?php endif; ?>

        <!-- Stat Cards -->
        <div class="chart-grid" style="grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); margin-bottom: 28px;">
            <div class="stat-card border-primary animate-fade-in-up">
                <div class="stat-icon bg-primary"><i class="mdi mdi-account-group"></i></div>
                <h2><?= number_format($totalWarga, 0, ',', '.') ?></h2>
                <p>Total Penduduk</p>
            </div>
            <div class="stat-card border-success animate-fade-in-up" style="animation-delay: 0.05s;">
                <div class="stat-icon bg-success"><i class="mdi mdi-medical-bag"></i></div>
                <h2><?= number_format($totalPasien, 0, ',', '.') ?></h2>
                <p>Rekam Medis</p>
            </div>
            <div class="stat-card border-warning animate-fade-in-up" style="animation-delay: 0.1s;">
                <div class="stat-icon bg-warning"><i class="mdi mdi-chart-line"></i></div>
                <h2><?= number_format($totalMiskin, 0, ',', '.') ?></h2>
                <p>Warga Miskin</p>
            </div>
            <div class="stat-card border-info animate-fade-in-up" style="animation-delay: 0.15s;">
                <div class="stat-icon bg-info"><i class="mdi mdi-sack"></i></div>
                <h2><?= number_format($totalBantuan, 0, ',', '.') ?></h2>
                <p>Penerima Bansos</p>
            </div>
            <div class="stat-card border-danger animate-fade-in-up" style="animation-delay: 0.2s;">
                <div class="stat-icon bg-danger"><i class="mdi mdi-map-marker-radius"></i></div>
                <h2><?= $totalKecamatan ?></h2>
                <p>Kecamatan</p>
            </div>
            <div class="stat-card border-primary animate-fade-in-up" style="animation-delay: 0.25s;">
                <div class="stat-icon bg-primary"><i class="mdi mdi-domain"></i></div>
                <h2><?= $totalDesa ?></h2>
                <p>Desa/Kelurahan</p>
            </div>
            <div class="stat-card border-success animate-fade-in-up" style="animation-delay: 0.3s;">
                <div class="stat-icon bg-success"><i class="mdi mdi-needle"></i></div>
                <h2><?= number_format($totalVaksin, 0, ',', '.') ?></h2>
                <p>Vaksin Selesai</p>
            </div>
            <div class="stat-card border-info animate-fade-in-up" style="animation-delay: 0.35s;">
                <div class="stat-icon bg-info"><i class="mdi mdi-home-account"></i></div>
                <h2><?= number_format($totalKK, 0, ',', '.') ?></h2>
                <p>Kartu Keluarga</p>
            </div>
        </div>

        <!-- Charts -->
        <div class="chart-grid chart-grid-2-1" style="margin-bottom: 28px;">
            <div class="card">
                <div class="card-header"><h5>Penduduk per Kecamatan (Top 10)</h5></div>
                <div class="card-body"><canvas id="chartKecamatan" height="280"></canvas></div>
            </div>
            <div class="card">
                <div class="card-header"><h5>Klasifikasi Kemiskinan</h5></div>
                <div class="card-body"><canvas id="chartKemiskinan" height="280"></canvas></div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card mb-4">
            <div class="card-header"><h5>Aksi Cepat</h5></div>
            <div class="card-body">
                <div class="quick-actions">
                    <a href="../kependudukan/warga.php" class="quick-action-btn">
                        <i class="mdi mdi-account-plus text-primary"></i> Tambah Warga
                    </a>
                    <a href="../kesehatan/rekam_medis.php" class="quick-action-btn">
                        <i class="mdi mdi-medical-bag text-success"></i> Rekam Medis
                    </a>
                    <a href="../bantuan_sosial/pengajuan.php" class="quick-action-btn">
                        <i class="mdi mdi-sack text-warning"></i> Bantuan Sosial
                    </a>
                    <a href="../spk/klasifikasi.php" class="quick-action-btn">
                        <i class="mdi mdi-lightbulb-on text-info"></i> Jalankan SPK
                    </a>
                    <a href="../analitik/dashboard.php" class="quick-action-btn">
                        <i class="mdi mdi-chart-bar text-danger"></i> Lihat Analitik
                    </a>
                    <a href="../admin/settings.php" class="quick-action-btn">
                        <i class="mdi mdi-settings text-secondary"></i> Pengaturan
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Warga -->
        <div class="card">
            <div class="card-header"><h5>Warga Terbaru</h5></div>
            <div class="card-body" style="padding: 0;">
                <?php if (empty($recentWarga)): ?>
                    <p class="text-muted text-center p-4">Belum ada data warga</p>
                <?php else: ?>
                <div class="list-group">
                    <?php foreach ($recentWarga as $w): ?>
                    <div class="list-group-item">
                        <div style="width:42px;height:42px;border-radius:50%;background:var(--primary-100);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="mdi mdi-account text-primary" style="font-size:1.3rem;"></i>
                        </div>
                        <div class="flex-grow-1">
                            <strong style="font-size:0.9rem;"><?= sanitize($w->nama_lengkap) ?></strong>
                            <br><small class="text-muted"><?= formatDate($w->created_at, 'd M Y H:i') ?></small>
                        </div>
                        <span class="badge badge-primary">Baru</span>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <?php include '../../includes/footer.php'; ?>
    </div>

    <?php include '../../includes/foot.php'; ?>
    <script>
    $(document).ready(function(){
        new Chart(document.getElementById('chartKecamatan').getContext('2d'), {
            type: 'bar',
            data: {
                labels: <?= json_encode(array_column($chartKecamatan, 'nama_kecamatan')) ?>,
                datasets: [{
                    label: 'Jumlah',
                    backgroundColor: 'rgba(79,70,229,0.8)',
                    borderColor: '#4f46e5',
                    borderWidth: 1,
                    borderRadius: 6,
                    data: <?= json_encode(array_column($chartKecamatan, 'jumlah')) ?>
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.04)' } }, x: { grid: { display: false } } },
                plugins: { legend: { display: false } }
            }
        });
        new Chart(document.getElementById('chartKemiskinan').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: <?= json_encode(array_column($chartKemiskinan, 'kategori')) ?>,
                datasets: [{
                    data: <?= json_encode(array_column($chartKemiskinan, 'jumlah')) ?>,
                    backgroundColor: ['#ef4444','#f59e0b','#eab308','#3b82f6','#10b981'],
                    borderWidth: 0,
                    hoverOffset: 8
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, cutout: '65%' }
        });
    });
    </script>
</body>
</html>
