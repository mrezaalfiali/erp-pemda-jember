<?php
require_once '../../includes/auth.php';
check_login();

// Get criteria
$criteriaList = $dbh->query("SELECT * FROM tbl_kriteria_kemiskinan ORDER BY id")->fetchAll();

// Handle update criteria
if (isset($_POST['update_criteria'])) {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) die('Token tidak valid');
    $bobot = $_POST['bobot'];
    $totalBobot = array_sum($bobot);
    if (abs($totalBobot - 1.0) > 0.01) {
        setFlash('danger', 'Total bobot harus 100% (1.0). Saat ini: ' . round($totalBobot * 100, 1) . '%');
        redirect('klasifikasi.php');
    }
    foreach ($bobot as $id => $val) {
        $stmt = $dbh->prepare("UPDATE tbl_kriteria_kemiskinan SET bobot = :bobot WHERE id = :id");
        $stmt->execute([':bobot' => $val, ':id' => $id]);
    }
    logAudit($dbh, $_SESSION['user_id'], 'UPDATE', 'tbl_kriteria_kemiskinan');
    setFlash('success', 'Bobot kriteria berhasil diupdate');
    redirect('klasifikasi.php');
}

// Run TOPSIS Classification
if (isset($_POST['run_classification'])) {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) die('Token tidak valid');
    
    $tahun = $_POST['tahun'] ?? date('Y');
    
    // Get all active warga
    $wargaList = $dbh->query("SELECT id, penghasilan, status_kawin, pekerjaan FROM tbl_warga WHERE status_aktif = 1")->fetchAll();
    
    // Get criteria
    $criteria = $dbh->query("SELECT * FROM tbl_kriteria_kemiskinan ORDER BY id")->fetchAll();
    
    if (empty($wargaList) || empty($criteria)) {
        setFlash('danger', 'Data warga atau kriteria belum tersedia');
        redirect('klasifikasi.php');
    }
    
    // Build decision matrix
    $n = count($wargaList);
    $m = count($criteria);
    $matrix = [];
    
    foreach ($wargaList as $i => $w) {
        foreach ($criteria as $j => $c) {
            // Calculate value for each criterion
            switch ($c->nama_kriteria) {
                case 'Penghasilan':
                    $matrix[$i][$j] = $w->penghasilan > 0 ? $w->penghasilan : 1;
                    break;
                case 'Jumlah Tanggungan':
                    // Estimate from family data
                    $stmt = $dbh->prepare("SELECT COUNT(*) FROM tbl_keluarga_anggota ka JOIN tbl_keluarga kk ON kk.id = ka.keluarga_id WHERE kk.kepala_keluarga_id = :id");
                    $stmt->execute([':id' => $w->id]);
                    $matrix[$i][$j] = max(1, $stmt->fetchColumn());
                    break;
                case 'Status Pekerjaan':
                    $pekerjaan = strtolower($w->pekerjaan ?? '');
                    $matrix[$i][$j] = in_array($pekerjaan, ['pengusaha', 'pns', 'tni', 'polri', 'guru', 'dokter']) ? 5 : (in_array($pekerjaan, ['buruh', 'petani', 'nelayan', 'pedagang']) ? 3 : 1);
                    break;
                case 'Kondisi Rumah':
                    // Default score based on data availability
                    $matrix[$i][$j] = 3;
                    break;
                case 'Akses Pendidikan':
                    $matrix[$i][$j] = 3;
                    break;
                case 'Akses Kesehatan':
                    $matrix[$i][$j] = 3;
                    break;
                default:
                    $matrix[$i][$j] = 1;
            }
        }
    }
    
    // TOPSIS Algorithm
    // 1. Normalize
    $normalized = [];
    for ($j = 0; $j < $m; $j++) {
        $sumSq = 0;
        for ($i = 0; $i < $n; $i++) {
            $sumSq += pow($matrix[$i][$j], 2);
        }
        $sqrtSumSq = sqrt($sumSq);
        for ($i = 0; $i < $n; $i++) {
            $normalized[$i][$j] = $sqrtSumSq > 0 ? $matrix[$i][$j] / $sqrtSumSq : 0;
        }
    }
    
    // 2. Weighted normalized
    $weighted = [];
    for ($i = 0; $i < $n; $i++) {
        for ($j = 0; $j < $m; $j++) {
            $weighted[$i][$j] = $normalized[$i][$j] * $criteria[$j]->bobot;
        }
    }
    
    // 3. Ideal solutions
    $idealPos = [];
    $idealNeg = [];
    for ($j = 0; $j < $m; $j++) {
        $max = -INF;
        $min = INF;
        for ($i = 0; $i < $n; $i++) {
            $max = max($max, $weighted[$i][$j]);
            $min = min($min, $weighted[$i][$j]);
        }
        if ($criteria[$j]->tipe === 'Benefit') {
            $idealPos[$j] = $max;
            $idealNeg[$j] = $min;
        } else {
            $idealPos[$j] = $min;
            $idealNeg[$j] = $max;
        }
    }
    
    // 4. Distance to ideal
    $scores = [];
    for ($i = 0; $i < $n; $i++) {
        $dPos = 0;
        $dNeg = 0;
        for ($j = 0; $j < $m; $j++) {
            $dPos += pow($weighted[$i][$j] - $idealPos[$j], 2);
            $dNeg += pow($weighted[$i][$j] - $idealNeg[$j], 2);
        }
        $dPos = sqrt($dPos);
        $dNeg = sqrt($dNeg);
        $total = $dPos + $dNeg;
        $scores[$i] = $total > 0 ? $dNeg / $total : 0;
    }
    
    // 5. Classify
    // Delete previous classification for this year
    $stmt = $dbh->prepare("DELETE FROM tbl_hasil_klasifikasi WHERE tahun = :tahun");
    $stmt->execute([':tahun' => $tahun]);
    
    foreach ($wargaList as $i => $w) {
        $skor = round($scores[$i] * 100, 2);
        
        if ($skor <= 20) $kategori = 'Sangat Miskin';
        elseif ($skor <= 40) $kategori = 'Miskin';
        elseif ($skor <= 60) $kategori = 'Rentan';
        elseif ($skor <= 80) $kategori = 'Layak Hidup';
        else $kategori = 'Sejahtera';
        
        $stmt = $dbh->prepare("INSERT INTO tbl_hasil_klasifikasi (warga_id, skor, kategori, tahun, bulan, metode) VALUES (:warga, :skor, :kategori, :tahun, :bulan, 'TOPSIS')");
        $stmt->execute([':warga' => $w->id, ':skor' => $skor, ':kategori' => $kategori, ':tahun' => $tahun, ':bulan' => date('m')]);
    }
    
    logAudit($dbh, $_SESSION['user_id'], 'CLASSIFY', 'tbl_hasil_klasifikasi', null, null, ['tahun' => $tahun, 'jumlah' => $n]);
    setFlash('success', "Klasifikasi selesai! $n warga telah diklasifikasikan menggunakan metode TOPSIS tahun $tahun");
    redirect('klasifikasi.php');
}

// Get results
$tahunFilter = $_GET['tahun'] ?? date('Y');
$results = $dbh->prepare("
    SELECT hk.*, w.nama_lengkap, w.nik, d.nama_desa, k.nama_kecamatan
    FROM tbl_hasil_klasifikasi hk
    JOIN tbl_warga w ON w.id = hk.warga_id
    LEFT JOIN tbl_desa d ON d.id = w.desa_id
    LEFT JOIN tbl_kecamatan k ON k.id = d.kecamatan_id
    WHERE hk.tahun = :tahun
    ORDER BY hk.skor ASC
");
$results->execute([':tahun' => $tahunFilter]);
$hasilList = $results->fetchAll();

// Stats
$stats = $dbh->prepare("SELECT kategori, COUNT(*) as jumlah FROM tbl_hasil_klasifikasi WHERE tahun = :tahun GROUP BY kategori");
$stats->execute([':tahun' => $tahunFilter]);
$statList = $stats->fetchAll();
$statMap = [];
foreach ($statList as $s) $statMap[$s->kategori] = $s->jumlah;
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
                <span class="icon bg-danger"><i class="mdi mdi-lightbulb-on"></i></span>
                SPK Klasifikasi Kemiskinan (TOPSIS)
            </h3>
            <div class="breadcrumb"><a href="../dashboard/index.php">Beranda</a> / Klasifikasi</div>
        </div>

        <?php $flash = getFlash(); if ($flash): ?>
            <div class="alert alert-<?= $flash['type'] ?>"><?= sanitize($flash['message']) ?></div>
        <?php endif; ?>

        <?php
        $colors = ['Sangat Miskin' => 'danger', 'Miskin' => 'warning', 'Rentan' => 'info', 'Layak Hidup' => 'primary', 'Sejahtera' => 'success'];
        ?>
        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap:20px;" class="mb-3">
            <?php foreach ($colors as $kat => $color): ?>
            <div class="card stat-card <?= $color ?>">
                <div class="card-body text-center">
                    <h3 class="mb-0"><?= $statMap[$kat] ?? 0 ?></h3>
                    <small class="text-muted"><?= $kat ?></small>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap:20px;" class="mb-3">
            <!-- Settings -->
            <div class="card">
                <div class="card-header"><h5 class="mb-0">Pengaturan Kriteria & Bobot</h5></div>
                <div class="card-body">
                    <form method="post">
                        <?= csrfField() ?>
                        <table class="table table-sm">
                            <thead><tr><th>Kriteria</th><th>Bobot</th><th>Tipe</th></tr></thead>
                            <tbody>
                                <?php foreach ($criteriaList as $c): ?>
                                <tr>
                                    <td><?= sanitize($c->nama_kriteria) ?></td>
                                    <td><input type="number" class="form-control form-control-sm" name="bobot[<?= $c->id ?>]" value="<?= $c->bobot ?>" step="0.01" min="0" max="1" required></td>
                                    <td><span class="badge badge-<?= $c->tipe === 'Benefit' ? 'success' : 'warning' ?>"><?= $c->tipe ?></span></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <button type="submit" name="update_criteria" class="btn btn-primary btn-sm"><i class="mdi mdi-content-save"></i> Update Bobot</button>
                    </form>
                </div>
            </div>

            <!-- Run Classification -->
            <div class="card">
                <div class="card-header"><h5 class="mb-0">Jalankan Klasifikasi</h5></div>
                <div class="card-body">
                    <form method="post">
                        <?= csrfField() ?>
                        <div class="form-group">
                            <label>Tahun Analisis</label>
                            <input type="number" class="form-control" name="tahun" value="<?= date('Y') ?>" min="2020" max="2030">
                        </div>
                        <div class="form-group">
                            <label>Metode</label>
                            <input type="text" class="form-control" value="TOPSIS (Technique for Order of Preference by Similarity to Ideal Solution)" readonly>
                        </div>
                        <p class="text-muted small">Klasifikasi akan menghitung skor kemiskinan untuk semua warga aktif berdasarkan kriteria yang telah ditetapkan.</p>
                        <button type="submit" name="run_classification" class="btn btn-danger btn-lg" onclick="return confirm('Jalankan klasifikasi kemiskinan? Proses ini akan mengupdate semua data klasifikasi.')">
                            <i class="mdi mdi-play-circle mr-1"></i> Jalankan Klasifikasi
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Results -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Hasil Klasifikasi Tahun <?= $tahunFilter ?></h5>
                <form method="get" class="form-inline">
                    <select name="tahun" class="form-control form-control-sm mr-2">
                        <?php for ($y = date('Y'); $y >= 2020; $y--): ?>
                            <option value="<?= $y ?>" <?= $y == $tahunFilter ? 'selected' : '' ?>><?= $y ?></option>
                        <?php endfor; ?>
                    </select>
                    <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                </form>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Nama Warga</th>
                                <th>NIK</th>
                                <th>Desa</th>
                                <th>Kecamatan</th>
                                <th class="text-center">Skor</th>
                                <th class="text-center">Kategori</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $cnt = 1; foreach ($hasilList as $row): ?>
                            <tr>
                                <td class="text-center"><?= $cnt++ ?></td>
                                <td><?= sanitize($row->nama_lengkap) ?></td>
                                <td><code><?= sanitize($row->nik) ?></code></td>
                                <td><?= sanitize($row->nama_desa ?? '-') ?></td>
                                <td><?= sanitize($row->nama_kecamatan ?? '-') ?></td>
                                <td class="text-center"><strong><?= $row->skor ?></strong></td>
                                <td class="text-center">
                                    <span class="badge badge-<?= $colors[$row->kategori] ?? 'secondary' ?>"><?= $row->kategori ?></span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
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
