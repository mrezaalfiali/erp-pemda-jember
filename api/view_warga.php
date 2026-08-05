<?php
require_once '../includes/dbconnection.php';
require_once '../includes/functions.php';
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_POST['id'])) die('ID tidak valid');

$stmt = $dbh->prepare("SELECT w.*, d.nama_desa, k.nama_kecamatan FROM tbl_warga w LEFT JOIN tbl_desa d ON d.id = w.desa_id LEFT JOIN tbl_kecamatan k ON k.id = d.kecamatan_id WHERE w.id = :id");
$stmt->execute([':id' => $_POST['id']]);
$warga = $stmt->fetch();
if (!$warga) die('Data tidak ditemukan');

$stmt = $dbh->prepare("SELECT kk.no_kk, ka.hubungan FROM tbl_keluarga_anggota ka JOIN tbl_keluarga kk ON kk.id = ka.keluarga_id WHERE ka.warga_id = :id");
$stmt->execute([':id' => $warga->id]);
$kkInfo = $stmt->fetch();
?>
<div class="row">
    <div class="col-md-4 text-center">
        <img src="../uploads/foto_warga/<?= $warga->foto ?>" alt="Foto" class="rounded-circle mb-3" style="width:120px; height:120px; object-fit:cover;" onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode(substr($warga->nama_lengkap,0,1)) ?>&background=4f46e5&color=fff&size=120'">
        <h5><?= sanitize($warga->nama_lengkap) ?></h5>
        <span class="badge badge-<?= $warga->status_aktif ? 'success' : 'danger' ?>"><?= $warga->status_aktif ? 'Aktif' : 'Non-aktif' ?></span>
    </div>
    <div class="col-md-8">
        <table class="table table-sm" style="font-size:0.85rem;">
            <tr><th width="130">NIK</th><td><code><?= sanitize($warga->nik) ?></code></td></tr>
            <tr><th>Tempat, Tgl Lahir</th><td><?= sanitize($warga->tempat_lahir) ?>, <?= formatDate($warga->tanggal_lahir) ?></td></tr>
            <tr><th>Jenis Kelamin</th><td><?= $warga->jenis_kelamin ?></td></tr>
            <tr><th>Gol. Darah</th><td><?= $warga->golongan_darah ?></td></tr>
            <tr><th>Agama</th><td><?= $warga->agama ?></td></tr>
            <tr><th>Status Kawin</th><td><?= $warga->status_kawin ?></td></tr>
            <tr><th>Pekerjaan</th><td><?= sanitize($warga->pekerjaan ?? '-') ?></td></tr>
            <tr><th>Penghasilan</th><td><?= formatRupiah($warga->penghasilan) ?></td></tr>
            <tr><th>Alamat</th><td><?= sanitize($warga->alamat_lengkap) ?></td></tr>
            <tr><th>RT/RW</th><td><?= sanitize($warga->rt) ?>/<?= sanitize($warga->rw) ?></td></tr>
            <tr><th>Desa</th><td><?= sanitize($warga->nama_desa ?? '-') ?></td></tr>
            <tr><th>Kecamatan</th><td><?= sanitize($warga->nama_kecamatan ?? '-') ?></td></tr>
            <?php if ($kkInfo): ?>
            <tr><th>No. KK</th><td><code><?= sanitize($kkInfo->no_kk) ?></code></td></tr>
            <tr><th>Hubungan</th><td><?= sanitize($kkInfo->hubungan) ?></td></tr>
            <?php endif; ?>
        </table>
    </div>
</div>
