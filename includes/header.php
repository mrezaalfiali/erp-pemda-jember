<?php
require_once __DIR__ . '/auth.php';
check_login();
$currentUser = getCurrentUser($dbh);
if (!isset($__base)) $__base = '';
?>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<nav class="app-header">
    <button class="menu-toggle" id="menuToggle" aria-label="Toggle menu">
        <i class="mdi mdi-menu"></i>
    </button>
    <span class="brand-text d-none d-md-block">Dashboard</span>
    <div class="header-center"></div>
    <div class="header-right">
        <button class="header-action d-none d-sm-flex" title="Notifikasi">
            <i class="mdi mdi-bell-outline"></i>
            <span class="badge-dot"></span>
        </button>
        <div class="header-profile" id="profileDropdown">
            <img src="<?= $__base ?>uploads/foto_profil/<?= $currentUser->foto ?>" alt="Profile" onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($currentUser->nama_lengkap) ?>&background=4f46e5&color=fff&size=80'" style="object-fit:cover;">
            <div class="profile-info d-none d-sm-flex">
                <span class="name"><?= sanitize($currentUser->nama_lengkap) ?></span>
                <span class="role"><?= sanitize($currentUser->role_name ?? $_SESSION['user_role'] ?? '') ?></span>
            </div>
            <i class="mdi mdi-chevron-down d-none d-sm-flex"></i>
            <div class="dropdown-menu-custom" id="profileMenu">
                <div class="dropdown-header">Akun</div>
                <a class="dropdown-item" href="<?= $__base ?>modul/admin/profile.php">
                    <i class="mdi mdi-account-circle"></i> Profil Saya
                </a>
                <a class="dropdown-item" href="<?= $__base ?>modul/admin/change_password.php">
                    <i class="mdi mdi-key-outline"></i> Ubah Password
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item text-danger" href="<?= $__base ?>logout.php">
                    <i class="mdi mdi-logout"></i> Keluar
                </a>
            </div>
        </div>
    </div>
</nav>
