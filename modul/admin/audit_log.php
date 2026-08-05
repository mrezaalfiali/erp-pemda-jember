<?php
require_once '../../includes/auth.php';
check_login();
if (!hasRole('Admin')) { setFlash('danger', 'Akses ditolak'); redirect('../dashboard/index.php'); }

$logList = $dbh->prepare("
    SELECT al.*, u.username 
    FROM tbl_audit_log al 
    LEFT JOIN tbl_users u ON u.id = al.user_id 
    ORDER BY al.timestamp DESC 
    LIMIT 100
");
$logList->execute();
$logs = $logList->fetchAll();
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
                <span class="icon bg-danger"><i class="mdi mdi-history"></i></span>
                Audit Log
            </h3>
            <div class="breadcrumb"><a href="../dashboard/index.php">Beranda</a> / Audit Log</div>
        </div>

        <?php $flash = getFlash(); if ($flash): ?>
            <div class="alert alert-<?= $flash['type'] ?>"><?= sanitize($flash['message']) ?></div>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr><th>Waktu</th><th>User</th><th>Aksi</th><th>Tabel</th><th>Record ID</th><th>IP Address</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($logs as $row): ?>
                            <tr>
                                <td><small><?= formatDate($row->timestamp, 'd M Y H:i:s') ?></small></td>
                                <td><?= sanitize($row->username ?? '-') ?></td>
                                <td><span class="badge badge-<?= $row->aksi === 'DELETE' ? 'danger' : ($row->aksi === 'INSERT' ? 'success' : 'info') ?>"><?= $row->aksi ?></span></td>
                                <td><?= sanitize($row->tabel) ?></td>
                                <td><?= $row->record_id ?? '-' ?></td>
                                <td><small><?= sanitize($row->ip_address) ?></small></td>
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
