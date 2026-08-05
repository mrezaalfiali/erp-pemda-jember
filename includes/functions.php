<?php
// ============================================
// Helper Functions
// ============================================

// Redirect
function redirect($url) {
    header("Location: $url");
    exit();
}

// Sanitize input
function sanitize($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// Generate CSRF Token
function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Verify CSRF Token
function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// CSRF hidden input
function csrfField() {
    return '<input type="hidden" name="csrf_token" value="' . generateCSRFToken() . '">';
}

// Flash messages
function setFlash($type, $message) {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function getFlash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

// Format currency
function formatRupiah($amount) {
    return 'Rp ' . number_format($amount, 0, ',', '.');
}

// Format date
function formatDate($date, $format = 'd-m-Y') {
    return date($format, strtotime($date));
}

// Validate NIK (16 digit)
function validateNIK($nik) {
    return preg_match('/^[0-9]{16}$/', $nik);
}

// Validate No KK (16 digit)
function validateNoKK($noKK) {
    return preg_match('/^[0-9]{16}$/', $noKK);
}

// Generate unique code
function generateCode($prefix, $length = 8) {
    $characters = '0123456789';
    $code = $prefix;
    for ($i = 0; $i < $length; $i++) {
        $code .= $characters[random_int(0, strlen($characters) - 1)];
    }
    return $code;
}

// Get user from session
function getCurrentUser($dbh) {
    if (!isset($_SESSION['user_id'])) return null;
    $stmt = $dbh->prepare("SELECT u.*, r.nama_role, r.level_akses FROM tbl_users u JOIN tbl_roles r ON u.role_id = r.id WHERE u.id = :id");
    $stmt->execute([':id' => $_SESSION['user_id']]);
    return $stmt->fetch();
}

// Check role
function hasRole($roleName) {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === $roleName;
}

// Check level access
function canAccess($minLevel) {
    return isset($_SESSION['user_level']) && $_SESSION['user_level'] <= $minLevel;
}

// Pagination
function paginate($total, $perPage, $currentPage) {
    $totalPages = ceil($total / $perPage);
    $currentPage = max(1, min($currentPage, $totalPages));
    $offset = ($currentPage - 1) * $perPage;
    return [
        'total' => $total,
        'per_page' => $perPage,
        'current_page' => $currentPage,
        'total_pages' => $totalPages,
        'offset' => $offset
    ];
}

// Render pagination HTML
function renderPagination($pagination, $baseUrl) {
    if ($pagination['total_pages'] <= 1) return '';
    
    $html = '<nav><ul class="pagination justify-content-center">';
    
    // Previous
    if ($pagination['current_page'] > 1) {
        $html .= '<li class="page-item"><a class="page-link" href="' . $baseUrl . '?page=' . ($pagination['current_page'] - 1) . '">&laquo;</a></li>';
    }
    
    // Pages
    for ($i = 1; $i <= $pagination['total_pages']; $i++) {
        $active = $i == $pagination['current_page'] ? ' active' : '';
        $html .= '<li class="page-item' . $active . '"><a class="page-link" href="' . $baseUrl . '?page=' . $i . '">' . $i . '</a></li>';
    }
    
    // Next
    if ($pagination['current_page'] < $pagination['total_pages']) {
        $html .= '<li class="page-item"><a class="page-link" href="' . $baseUrl . '?page=' . ($pagination['current_page'] + 1) . '">&raquo;</a></li>';
    }
    
    $html .= '</ul></nav>';
    return $html;
}

// Upload file
function uploadFile($file, $destination, $allowedExts = null) {
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'Upload gagal: ' . $file['error']];
    }
    
    if ($file['size'] > MAX_FILE_SIZE) {
        return ['success' => false, 'message' => 'Ukuran file terlalu besar (maks ' . (MAX_FILE_SIZE / 1024 / 1024) . 'MB)'];
    }
    
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if ($allowedExts && !in_array($ext, $allowedExts)) {
        return ['success' => false, 'message' => 'Ekstensi file tidak diizinkan'];
    }
    
    $filename = uniqid() . '.' . $ext;
    $filepath = $destination . $filename;
    
    if (!is_dir($destination)) {
        mkdir($destination, 0755, true);
    }
    
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return ['success' => true, 'filename' => $filename];
    }
    
    return ['success' => false, 'message' => 'Gagal memindahkan file'];
}

// Log audit
function logAudit($dbh, $userId, $aksi, $tabel, $recordId = null, $dataLama = null, $dataBaru = null) {
    $stmt = $dbh->prepare("INSERT INTO tbl_audit_log (user_id, aksi, tabel, record_id, data_lama, data_baru, ip_address) VALUES (:user_id, :aksi, :tabel, :record_id, :data_lama, :data_baru, :ip_address)");
    $stmt->execute([
        ':user_id' => $userId,
        ':aksi' => $aksi,
        ':tabel' => $tabel,
        ':record_id' => $recordId,
        ':data_lama' => $dataLama ? json_encode($dataLama) : null,
        ':data_baru' => $dataBaru ? json_encode($dataBaru) : null,
        ':ip_address' => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1'
    ]);
}

// Get setting
function getSetting($dbh, $key) {
    $stmt = $dbh->prepare("SELECT nilai FROM tbl_pengaturan WHERE nama_pengaturan = :key");
    $stmt->execute([':key' => $key]);
    $row = $stmt->fetch();
    return $row ? $row->nilai : null;
}
