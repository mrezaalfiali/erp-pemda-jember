<?php
if (!isset($__base)) $__base = '';
$currentModule = '';
$currentFile = basename($_SERVER['PHP_SELF']);
if (strpos($_SERVER['PHP_SELF'], '/modul/') !== false) {
    $segments = explode('/', $_SERVER['PHP_SELF']);
    foreach ($segments as $i => $seg) {
        if ($seg === 'modul' && isset($segments[$i + 1])) {
            $currentModule = $segments[$i + 1];
            break;
        }
    }
}
?>
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <img src="<?= $__base ?>uploads/logo/logo.png" alt="Logo" onerror="this.src='https://ui-avatars.com/api/?name=Jember&background=4f46e5&color=fff&size=80'">
        <div>
            <h5>ERP Jember</h5>
            <small>Pemerintahan Daerah</small>
        </div>
    </div>

    <div class="sidebar-nav">
        <div class="nav-category">Menu Utama</div>
        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link <?= $currentModule === '' || $currentModule === 'dashboard' ? 'active' : '' ?>" href="<?= $__base ?>modul/dashboard/index.php">
                    <i class="mdi mdi-view-dashboard"></i><span>Beranda</span>
                </a>
            </li>
        </ul>

        <div class="nav-category">Kependudukan</div>
        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link <?= $currentModule === 'kependudukan' && $currentFile === 'warga.php' ? 'active' : '' ?>" href="<?= $__base ?>modul/kependudukan/warga.php">
                    <i class="mdi mdi-account-group"></i><span>Data Warga</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $currentFile === 'keluarga.php' ? 'active' : '' ?>" href="<?= $__base ?>modul/kependudukan/keluarga.php">
                    <i class="mdi mdi-home-account"></i><span>Kartu Keluarga</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $currentFile === 'dokumen.php' ? 'active' : '' ?>" href="<?= $__base ?>modul/kependudukan/dokumen.php">
                    <i class="mdi mdi-file-document"></i><span>Dokumen</span>
                </a>
            </li>
        </ul>

        <div class="nav-category">Kesehatan</div>
        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link <?= $currentFile === 'rekam_medis.php' ? 'active' : '' ?>" href="<?= $__base ?>modul/kesehatan/rekam_medis.php">
                    <i class="mdi mdi-medical-bag"></i><span>Rekam Medis</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $currentFile === 'vaksinasi.php' ? 'active' : '' ?>" href="<?= $__base ?>modul/kesehatan/vaksinasi.php">
                    <i class="mdi mdi-needle"></i><span>Vaksinasi</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $currentFile === 'rumah_sakit.php' ? 'active' : '' ?>" href="<?= $__base ?>modul/kesehatan/rumah_sakit.php">
                    <i class="mdi mdi-hospital-building"></i><span>RS & Puskesmas</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $currentFile === 'stok_obat.php' ? 'active' : '' ?>" href="<?= $__base ?>modul/kesehatan/stok_obat.php">
                    <i class="mdi mdi-pill"></i><span>Stok Obat</span>
                </a>
            </li>
        </ul>

        <div class="nav-category">Bantuan Sosial</div>
        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link <?= $currentFile === 'program.php' ? 'active' : '' ?>" href="<?= $__base ?>modul/bantuan_sosial/program.php">
                    <i class="mdi mdi-clipboard-text"></i><span>Program Bantuan</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $currentFile === 'pengajuan.php' ? 'active' : '' ?>" href="<?= $__base ?>modul/bantuan_sosial/pengajuan.php">
                    <i class="mdi mdi-file-check"></i><span>Pengajuan</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $currentFile === 'penerima.php' ? 'active' : '' ?>" href="<?= $__base ?>modul/bantuan_sosial/penerima.php">
                    <i class="mdi mdi-account-check"></i><span>Penerima</span>
                </a>
            </li>
        </ul>

        <div class="nav-category">Analitik & SPK</div>
        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link <?= $currentFile === 'klasifikasi.php' ? 'active' : '' ?>" href="<?= $__base ?>modul/spk/klasifikasi.php">
                    <i class="mdi mdi-lightbulb-on"></i><span>SPK Kemiskinan</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $currentModule === 'analitik' ? 'active' : '' ?>" href="<?= $__base ?>modul/analitik/dashboard.php">
                    <i class="mdi mdi-chart-bar"></i><span>Analitik & Grafik</span>
                </a>
            </li>
        </ul>

        <div class="nav-category">Integrasi Data</div>
        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link <?= $currentFile === 'siak.php' ? 'active' : '' ?>" href="<?= $__base ?>modul/integrasi/siak.php">
                    <i class="mdi mdi-cloud-sync"></i><span>Sinkronisasi</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $currentFile === 'log_sinkron.php' ? 'active' : '' ?>" href="<?= $__base ?>modul/integrasi/log_sinkron.php">
                    <i class="mdi mdi-history"></i><span>Log Sinkron</span>
                </a>
            </li>
        </ul>

        <?php if (hasRole('Admin')): ?>
        <div class="nav-category">Pengaturan</div>
        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link <?= $currentFile === 'users.php' ? 'active' : '' ?>" href="<?= $__base ?>modul/admin/users.php">
                    <i class="mdi mdi-account-multiple"></i><span>Manajemen User</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $currentFile === 'roles.php' ? 'active' : '' ?>" href="<?= $__base ?>modul/admin/roles.php">
                    <i class="mdi mdi-shield-key"></i><span>Role & Akses</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $currentFile === 'settings.php' ? 'active' : '' ?>" href="<?= $__base ?>modul/admin/settings.php">
                    <i class="mdi mdi-settings"></i><span>Pengaturan</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $currentFile === 'audit_log.php' ? 'active' : '' ?>" href="<?= $__base ?>modul/admin/audit_log.php">
                    <i class="mdi mdi-history"></i><span>Audit Log</span>
                </a>
            </li>
        </ul>
        <?php endif; ?>
    </div>

    <div class="sidebar-scroll-indicator">
        <div class="version">v<?= APP_VERSION ?> &copy; <?= date('Y') ?></div>
    </div>
</aside>
