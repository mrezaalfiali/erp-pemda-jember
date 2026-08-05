<?php
header('Content-Type: application/json');

require_once '../includes/dbconnection.php';

$kecamatanId = intval($_GET['kecamatan_id'] ?? 0);

if (!$kecamatanId) {
    echo json_encode([]);
    exit();
}

$stmt = $dbh->prepare("SELECT id, nama_desa FROM tbl_desa WHERE kecamatan_id = :id ORDER BY nama_desa");
$stmt->execute([':id' => $kecamatanId]);
$desaList = $stmt->fetchAll();

$result = [];
foreach ($desaList as $d) {
    $result[] = ['id' => $d->id, 'nama_desa' => $d->nama_desa];
}

echo json_encode($result);
