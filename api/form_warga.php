<?php
session_start();
require_once '../includes/dbconnection.php';
require_once '../includes/functions.php';

// Handle form submission
if (isset($_POST['submit_warga'])) {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) die('Token tidak valid');
    $id = $_POST['warga_id'] ?? null;
    $data = [
        ':nik' => $_POST['nik'], ':nama_lengkap' => $_POST['nama_lengkap'], ':tempat_lahir' => $_POST['tempat_lahir'],
        ':tanggal_lahir' => $_POST['tanggal_lahir'], ':jenis_kelamin' => $_POST['jenis_kelamin'],
        ':golongan_darah' => $_POST['golongan_darah'], ':agama' => $_POST['agama'],
        ':status_kawin' => $_POST['status_kawin'], ':pekerjaan' => $_POST['pekerjaan'],
        ':penghasilan' => $_POST['penghasilan'], ':alamat_lengkap' => $_POST['alamat_lengkap'],
        ':rt' => $_POST['rt'], ':rw' => $_POST['rw'], ':desa_id' => $_POST['desa_id']
    ];

    if (!validateNIK($data[':nik'])) { setFlash('danger', 'NIK harus 16 digit angka'); redirect('../modul/kependudukan/warga.php'); }

    $checkSql = "SELECT COUNT(*) FROM tbl_warga WHERE nik = :nik" . ($id ? " AND id != $id" : '');
    $stmt = $dbh->prepare($checkSql); $stmt->execute([':nik' => $data[':nik']]);
    if ($stmt->fetchColumn() > 0) { setFlash('danger', 'NIK sudah terdaftar'); redirect('../modul/kependudukan/warga.php'); }

    $foto = 'default.jpg';
    if (!empty($_FILES['foto']['name'])) {
        $result = uploadFile($_FILES['foto'], '../uploads/foto_warga/', ALLOWED_IMAGE_EXT);
        if ($result['success']) $foto = $result['filename'];
    }
    $data[':foto'] = $foto;

    if ($id) {
        $sql = "UPDATE tbl_warga SET nik=:nik, nama_lengkap=:nama_lengkap, tempat_lahir=:tempat_lahir, tanggal_lahir=:tanggal_lahir, jenis_kelamin=:jenis_kelamin, golongan_darah=:golongan_darah, agama=:agama, status_kawin=:status_kawin, pekerjaan=:pekerjaan, penghasilan=:penghasilan, alamat_lengkap=:alamat_lengkap, rt=:rt, rw=:rw, desa_id=:desa_id, foto=:foto WHERE id = :id";
        $data[':id'] = $id;
    } else {
        $sql = "INSERT INTO tbl_warga (nik, nama_lengkap, tempat_lahir, tanggal_lahir, jenis_kelamin, golongan_darah, agama, status_kawin, pekerjaan, penghasilan, alamat_lengkap, rt, rw, desa_id, foto) VALUES (:nik, :nama_lengkap, :tempat_lahir, :tanggal_lahir, :jenis_kelamin, :golongan_darah, :agama, :status_kawin, :pekerjaan, :penghasilan, :alamat_lengkap, :rt, :rw, :desa_id, :foto)";
    }
    $stmt = $dbh->prepare($sql); $stmt->execute($data);
    logAudit($dbh, $_SESSION['user_id'], $id ? 'UPDATE' : 'INSERT', 'tbl_warga', $id);
    setFlash('success', $id ? 'Data warga berhasil diupdate' : 'Data warga berhasil ditambahkan');
    redirect('../modul/kependudukan/warga.php');
}

// Load form for add/edit
$editData = null;
if (isset($_POST['id']) && !isset($_POST['submit_warga'])) {
    $stmt = $dbh->prepare("SELECT * FROM tbl_warga WHERE id = :id"); $stmt->execute([':id' => $_POST['id']]); $editData = $stmt->fetch();
}

$kecamatanList = $dbh->query("SELECT * FROM tbl_kecamatan ORDER BY nama_kecamatan")->fetchAll();
$desaList = [];
if ($editData && $editData->desa_id) {
    $stmt = $dbh->prepare("SELECT kecamatan_id FROM tbl_desa WHERE id = :id"); $stmt->execute([':id' => $editData->desa_id]); $desaRow = $stmt->fetch();
    if ($desaRow) { $stmt = $dbh->prepare("SELECT * FROM tbl_desa WHERE kecamatan_id = :id ORDER BY nama_desa"); $stmt->execute([':id' => $desaRow->kecamatan_id]); $desaList = $stmt->fetchAll(); }
} elseif (!$editData) {
    $desaList = $dbh->query("SELECT * FROM tbl_desa ORDER BY nama_desa")->fetchAll();
}
?>
<form method="post" enctype="multipart/form-data" action="../../api/form_warga.php">
    <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
    <input type="hidden" name="warga_id" value="<?= $editData->id ?? '' ?>">
    <div class="row">
        <div class="col-md-6"><div class="form-group"><label>NIK *</label><input type="text" class="form-control" name="nik" maxlength="16" pattern="[0-9]{16}" required value="<?= sanitize($editData->nik ?? '') ?>" placeholder="16 digit NIK"></div></div>
        <div class="col-md-6"><div class="form-group"><label>Nama Lengkap *</label><input type="text" class="form-control" name="nama_lengkap" required value="<?= sanitize($editData->nama_lengkap ?? '') ?>"></div></div>
    </div>
    <div class="row">
        <div class="col-md-6"><div class="form-group"><label>Tempat Lahir</label><input type="text" class="form-control" name="tempat_lahir" value="<?= sanitize($editData->tempat_lahir ?? '') ?>"></div></div>
        <div class="col-md-6"><div class="form-group"><label>Tanggal Lahir</label><input type="date" class="form-control" name="tanggal_lahir" value="<?= $editData->tanggal_lahir ?? '' ?>"></div></div>
    </div>
    <div class="row">
        <div class="col-md-4"><div class="form-group"><label>Jenis Kelamin *</label><select class="form-control" name="jenis_kelamin" required><option value="">Pilih</option><option value="Laki-laki" <?= ($editData->jenis_kelamin ?? '') === 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option><option value="Perempuan" <?= ($editData->jenis_kelamin ?? '') === 'Perempuan' ? 'selected' : '' ?>>Perempuan</option></select></div></div>
        <div class="col-md-4"><div class="form-group"><label>Golongan Darah</label><select class="form-control" name="golongan_darah"><option value="-">Tidak Diketahui</option><?php foreach (['A','B','AB','O'] as $gd): ?><option value="<?= $gd ?>" <?= ($editData->golongan_darah ?? '-') === $gd ? 'selected' : '' ?>><?= $gd ?></option><?php endforeach; ?></select></div></div>
        <div class="col-md-4"><div class="form-group"><label>Agama</label><select class="form-control" name="agama"><?php foreach (['Islam','Kristen','Katolik','Hindu','Buddha','Konghucu'] as $ag): ?><option value="<?= $ag ?>" <?= ($editData->agama ?? 'Islam') === $ag ? 'selected' : '' ?>><?= $ag ?></option><?php endforeach; ?></select></div></div>
    </div>
    <div class="row">
        <div class="col-md-4"><div class="form-group"><label>Status Kawin</label><select class="form-control" name="status_kawin"><?php foreach (['Belum Kawin','Kawin','Cerai Hidup','Cerai Mati'] as $sk): ?><option value="<?= $sk ?>" <?= ($editData->status_kawin ?? 'Belum Kawin') === $sk ? 'selected' : '' ?>><?= $sk ?></option><?php endforeach; ?></select></div></div>
        <div class="col-md-4"><div class="form-group"><label>Pekerjaan</label><input type="text" class="form-control" name="pekerjaan" value="<?= sanitize($editData->pekerjaan ?? '') ?>"></div></div>
        <div class="col-md-4"><div class="form-group"><label>Penghasilan/Bulan</label><input type="number" class="form-control" name="penghasilan" min="0" value="<?= $editData->penghasilan ?? 0 ?>"></div></div>
    </div>
    <div class="row">
        <div class="col-md-4"><div class="form-group"><label>Kecamatan *</label><select class="form-control" name="kecamatan_id" id="kecamatan_id" required><option value="">Pilih</option><?php foreach ($kecamatanList as $k): ?><option value="<?= $k->id ?>"><?= sanitize($k->nama_kecamatan) ?></option><?php endforeach; ?></select></div></div>
        <div class="col-md-4"><div class="form-group"><label>Desa *</label><select class="form-control" name="desa_id" id="desa_id" required><option value="">Pilih Desa</option><?php foreach ($desaList as $d): ?><option value="<?= $d->id ?>" <?= ($editData->desa_id ?? '') == $d->id ? 'selected' : '' ?>><?= sanitize($d->nama_desa) ?></option><?php endforeach; ?></select></div></div>
        <div class="col-md-4"><div class="form-group"><label>RT / RW</label><div class="d-flex" style="gap:4px;"><input type="text" class="form-control" name="rt" maxlength="3" placeholder="RT" value="<?= sanitize($editData->rt ?? '') ?>"><input type="text" class="form-control" name="rw" maxlength="3" placeholder="RW" value="<?= sanitize($editData->rw ?? '') ?>"></div></div></div>
    </div>
    <div class="form-group"><label>Alamat Lengkap</label><textarea class="form-control" name="alamat_lengkap" rows="2"><?= sanitize($editData->alamat_lengkap ?? '') ?></textarea></div>
    <div class="form-group"><label>Foto</label><input type="file" class="form-control" name="foto" accept="image/*"></div>
    <div class="modal-footer" style="padding-right:0;">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="submit" name="submit_warga" class="btn btn-primary"><i class="mdi mdi-content-save"></i> <?= $editData ? 'Update' : 'Simpan' ?></button>
    </div>
</form>
<script>
$(document).on('change', '#kecamatan_id', function() {
    var kecId = $(this).val();
    if (kecId) {
        $.get('../get_desa.php?kecamatan_id=' + kecId, function(data) {
            var opts = '<option value="">Pilih Desa</option>';
            data.forEach(function(d) { opts += '<option value="'+d.id+'">'+d.nama_desa+'</option>'; });
            $('#desa_id').html(opts);
        });
    }
});
</script>
