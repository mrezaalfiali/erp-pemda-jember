<?php
require_once '../../includes/auth.php';
check_login();

// Get data for charts
$totalWarga = $dbh->query("SELECT COUNT(*) FROM tbl_warga WHERE status_aktif = 1")->fetchColumn();
$totalMiskin = $dbh->query("SELECT COUNT(*) FROM tbl_hasil_klasifikasi WHERE kategori IN ('Sangat Miskin','Miskin') AND tahun = YEAR(NOW())")->fetchColumn();
$totalKecamatan = $dbh->query("SELECT COUNT(*) FROM tbl_kecamatan")->fetchColumn();

// Data per kecamatan
$kecamatanData = $dbh->query("
    SELECT k.nama_kecamatan, 
           COUNT(w.id) as jumlah_penduduk,
           (SELECT COUNT(*) FROM tbl_hasil_klasifikasi hk JOIN tbl_warga w2 ON w2.id = hk.warga_id JOIN tbl_desa d2 ON d2.id = w2.desa_id WHERE d2.kecamatan_id = k.id AND hk.kategori IN ('Sangat Miskin','Miskin') AND hk.tahun = YEAR(NOW())) as jumlah_miskin
    FROM tbl_kecamatan k
    LEFT JOIN tbl_desa d ON d.kecamatan_id = k.id
    LEFT JOIN tbl_warga w ON w.desa_id = d.id AND w.status_aktif = 1
    GROUP BY k.id, k.nama_kecamatan
    ORDER BY jumlah_penduduk DESC
")->fetchAll();

// Klasifikasi
$klasifikasi = $dbh->query("SELECT kategori, COUNT(*) as jumlah FROM tbl_hasil_klasifikasi WHERE tahun = YEAR(NOW()) GROUP BY kategori")->fetchAll();

// Kesehatan per bulan
$kesehatanBulan = $dbh->query("
    SELECT MONTH(tanggal) as bulan, COUNT(*) as jumlah 
    FROM tbl_rekam_medis 
    WHERE YEAR(tanggal) = YEAR(NOW()) 
    GROUP BY MONTH(tanggal) 
    ORDER BY bulan
")->fetchAll();

// Bantuan per kategori
$bantuanKategori = $dbh->query("
    SELECT pr.kategori, COUNT(*) as jumlah 
    FROM tbl_penerima_bantuan pb 
    JOIN tbl_program_bantuan pr ON pr.id = pb.program_id 
    WHERE YEAR(pb.tanggal_terima) = YEAR(NOW()) 
    GROUP BY pr.kategori
")->fetchAll();

// Agama distribution
$agamaData = $dbh->query("SELECT agama, COUNT(*) as jumlah FROM tbl_warga WHERE status_aktif = 1 GROUP BY agama")->fetchAll();
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
                <span class="icon bg-primary"><i class="mdi mdi-chart-bar"></i></span>
                Analitik & Grafik
            </h3>
            <div class="breadcrumb"><a href="../dashboard/index.php">Beranda</a> / Analitik</div>
        </div>

        <?php $flash = getFlash(); if ($flash): ?>
            <div class="alert alert-<?= $flash['type'] ?>"><?= sanitize($flash['message']) ?></div>
        <?php endif; ?>

        <!-- Summary Cards -->
        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap:20px;" class="mb-3">
            <div class="card stat-card primary">
                <div class="card-body text-center">
                    <h3 class="mb-0"><?= number_format($totalWarga, 0, ',', '.') ?></h3>
                    <small>Total Penduduk</small>
                </div>
            </div>
            <div class="card stat-card danger">
                <div class="card-body text-center">
                    <h3 class="mb-0"><?= number_format($totalMiskin, 0, ',', '.') ?></h3>
                    <small>Penduduk Miskin</small>
                </div>
            </div>
            <div class="card stat-card success">
                <div class="card-body text-center">
                    <h3 class="mb-0"><?= $totalKecamatan ?></h3>
                    <small>Kecamatan</small>
                </div>
            </div>
            <div class="card stat-card warning">
                <div class="card-body text-center">
                    <h3 class="mb-0"><?= $totalWarga > 0 ? round($totalMiskin / $totalWarga * 100, 1) : 0 ?>%</h3>
                    <small>Tingkat Kemiskinan</small>
                </div>
            </div>
        </div>

        <!-- Charts Row 1 -->
        <div style="display:grid; grid-template-columns: repeat(2, 1fr); gap:20px;" class="mb-3">
            <div class="card">
                <div class="card-body" style="padding: 20px;">
                    <h5 class="card-title" style="margin-bottom: 15px; font-size: 1rem;">Penduduk & Kemiskinan per Kecamatan</h5>
                    <div style="position: relative; width: 100%; height: 280px;">
                        <canvas id="chartKecamatan"></canvas>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body" style="padding: 20px;">
                    <h5 class="card-title" style="margin-bottom: 15px; font-size: 1rem;">Klasifikasi Kemiskinan</h5>
                    <div style="position: relative; width: 100%; height: 280px;">
                        <canvas id="chartKlasifikasi"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row 2 -->
        <div style="display:grid; grid-template-columns: repeat(3, 1fr); gap:20px; padding-bottom: 60px;">
            <div class="card">
                <div class="card-body" style="padding: 20px;">
                    <h5 class="card-title" style="margin-bottom: 15px; font-size: 1rem;">Distribusi Agama</h5>
                    <div style="position: relative; width: 100%; height: 240px;">
                        <canvas id="chartAgama"></canvas>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body" style="padding: 20px;">
                    <h5 class="card-title" style="margin-bottom: 15px; font-size: 1rem;">Rekam Medis per Bulan</h5>
                    <div style="position: relative; width: 100%; height: 240px;">
                        <canvas id="chartKesehatan"></canvas>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body" style="padding: 20px;">
                    <h5 class="card-title" style="margin-bottom: 15px; font-size: 1rem;">Bantuan per Kategori</h5>
                    <div style="position: relative; width: 100%; height: 240px;">
                        <canvas id="chartBantuan"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <?php include '../../includes/footer.php'; ?>
    </div>

    <?php include '../../includes/foot.php'; ?>
    <script>
    $(document).ready(function(){
        // Chart Kecamatan
        new Chart(document.getElementById('chartKecamatan').getContext('2d'), {
            type: 'bar',
            data: {
                labels: <?= json_encode(array_column($kecamatanData, 'nama_kecamatan')) ?>,
                datasets: [{
                    label: 'Total Penduduk',
                    backgroundColor: '#7c5cfc',
                    data: <?= json_encode(array_column($kecamatanData, 'jumlah_penduduk')) ?>
                }, {
                    label: 'Miskin',
                    backgroundColor: '#f85149',
                    data: <?= json_encode(array_column($kecamatanData, 'jumlah_miskin')) ?>
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: { padding: { bottom: 10 } },
                scales: {
                    xAxes: [{
                        ticks: {
                            autoSkip: false,
                            maxRotation: 45,
                            minRotation: 45,
                            font: { size: 9 }
                        }
                    }],
                    yAxes: [{ ticks: { beginAtZero: true } }]
                },
                legend: { display: true, position: 'bottom', labels: { boxWidth: 12, font: { size: 10 } } }
            }
        });

        // Chart Klasifikasi
        var katColors = ['#f85149', '#d29922', '#e3b341', '#58a6ff', '#3fb950'];
        new Chart(document.getElementById('chartKlasifikasi').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: <?= json_encode(array_column($klasifikasi, 'kategori')) ?>,
                datasets: [{ data: <?= json_encode(array_column($klasifikasi, 'jumlah')) ?>, backgroundColor: katColors }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: { padding: { top: 5, bottom: 5 } },
                legend: { display: true, position: 'bottom', labels: { boxWidth: 12, font: { size: 10 } } }
            }
        });

        // Chart Agama
        var agamaColors = ['#7c5cfc', '#3fb950', '#58a6ff', '#d29922', '#f85149', '#8b949e'];
        new Chart(document.getElementById('chartAgama').getContext('2d'), {
            type: 'pie',
            data: {
                labels: <?= json_encode(array_column($agamaData, 'agama')) ?>,
                datasets: [{ data: <?= json_encode(array_column($agamaData, 'jumlah')) ?>, backgroundColor: agamaColors }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: { padding: { top: 5, bottom: 5 } },
                legend: { display: true, position: 'bottom', labels: { boxWidth: 12, font: { size: 10 } } }
            }
        });

        // Chart Kesehatan
        var bulan = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
        var kesehatanData = new Array(12).fill(0);
        <?php foreach ($kesehatanBulan as $kb): ?>
            kesehatanData[<?= $kb->bulan - 1 ?>] = <?= $kb->jumlah ?>;
        <?php endforeach; ?>
        new Chart(document.getElementById('chartKesehatan').getContext('2d'), {
            type: 'line',
            data: {
                labels: bulan,
                datasets: [{ label: 'Rekam Medis', borderColor: '#3fb950', data: kesehatanData, fill: false, tension: 0.3 }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: { padding: { bottom: 5 } },
                scales: {
                    xAxes: [{ ticks: { font: { size: 10 } } }],
                    yAxes: [{ ticks: { beginAtZero: true } }]
                },
                legend: { display: true, position: 'bottom', labels: { boxWidth: 12, font: { size: 10 } } }
            }
        });

        // Chart Bantuan
        new Chart(document.getElementById('chartBantuan').getContext('2d'), {
            type: 'pie',
            data: {
                labels: <?= json_encode(array_column($bantuanKategori, 'kategori')) ?>,
                datasets: [{ data: <?= json_encode(array_column($bantuanKategori, 'jumlah')) ?>, backgroundColor: ['#7c5cfc','#3fb950','#58a6ff','#d29922','#f85149','#8b949e'] }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: { padding: { top: 5, bottom: 5 } },
                legend: { display: true, position: 'bottom', labels: { boxWidth: 12, font: { size: 10 } } }
            }
        });
    });
    </script>
</body>
</html>
