<?php
require_once '../../includes/auth.php';
check_login();
if (!hasRole('Admin')) { setFlash('danger', 'Akses ditolak'); redirect('../dashboard/index.php'); }

if (isset($_POST['submit_settings'])) {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) die('Token tidak valid');
    foreach ($_POST['setting'] as $key => $val) {
        $stmt = $dbh->prepare("UPDATE tbl_pengaturan SET nilai = :val WHERE nama_pengaturan = :key");
        $stmt->execute([':val' => $val, ':key' => $key]);
    }
    logAudit($dbh, $_SESSION['user_id'], 'UPDATE', 'tbl_pengaturan');
    setFlash('success', 'Pengaturan berhasil disimpan');
    redirect('settings.php');
}

$settings = $dbh->query("SELECT * FROM tbl_pengaturan ORDER BY kategori, nama_pengaturan")->fetchAll();
$grouped = [];
foreach ($settings as $s) { $grouped[$s->kategori][] = $s; }
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
                <span class="icon bg-secondary"><i class="mdi mdi-settings"></i></span>
                Pengaturan Sistem
            </h3>
            <div class="breadcrumb"><a href="../dashboard/index.php">Beranda</a> / Pengaturan</div>
        </div>

        <?php $flash = getFlash(); if ($flash): ?>
            <div class="alert alert-<?= $flash['type'] ?>"><?= sanitize($flash['message']) ?></div>
        <?php endif; ?>

        <div class="card" style="max-width:800px;">
            <div class="card-body">
                <form method="post">
                    <?= csrfField() ?>
                    <?php foreach ($grouped as $kategori => $items): ?>
                    <div class="card mb-3">
                        <div class="card-header"><h5 class="mb-0"><?= sanitize($kategori === 'spk' ? 'SPK' : ucfirst($kategori)) ?></h5></div>
                        <div class="card-body">
                            <?php foreach ($items as $item): ?>
                            <div class="form-group">
                                <label><?= sanitize($item->deskripsi ?? $item->nama_pengaturan) ?></label>
                                <input type="text" class="form-control" name="setting[<?= $item->nama_pengaturan ?>]" value="<?= sanitize($item->nilai) ?>">
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <button type="submit" name="submit_settings" class="btn btn-primary"><i class="mdi mdi-content-save"></i> Simpan Pengaturan</button>
                </form>
            </div>
        </div>

        <?php include '../../includes/footer.php'; ?>
    </div>

    <?php include '../../includes/foot.php'; ?>
</body>
</html>
