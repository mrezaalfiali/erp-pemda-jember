<?php
// ============================================
// ERP PEMERINTAHAN DAERAH JEMBER
// Database Installer
// ============================================
require_once __DIR__ . '/includes/config.php';

$host = 'localhost';
$user = 'root';
$pass = '';
$dbName = 'erp_jember';
$success = true;
$message = '';

if (isset($_POST['install'])) {
    try {
        // Connect without database
        $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
        
        // Create database
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
        $pdo->exec("USE `$dbName`");
        
        // Read and execute SQL file
        $sqlFile = __DIR__ . '/database/schema.sql';
        if (file_exists($sqlFile)) {
            $sql = file_get_contents($sqlFile);
            // Remove USE statement since we already selected the database
            $sql = str_replace('CREATE DATABASE IF NOT EXISTS erp_jember CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;', '', $sql);
            $sql = str_replace('USE erp_jember;', '', $sql);
            
            // Split by semicolons and execute each statement
            $statements = array_filter(array_map('trim', explode(';', $sql)));
            foreach ($statements as $statement) {
                if (!empty($statement)) {
                    try {
                        $pdo->exec($statement);
                    } catch (PDOException $e) {
                        // Skip duplicate key errors
                        if (strpos($e->getMessage(), 'Duplicate') === false) {
                            $message .= "Error: " . $e->getMessage() . "<br>";
                        }
                    }
                }
            }
            $message = "Instalasi berhasil! Database '$dbName' telah dibuat.";
        } else {
            $message = "File schema.sql tidak ditemukan!";
            $success = false;
        }
    } catch (PDOException $e) {
        $message = "Koneksi gagal: " . $e->getMessage();
        $success = false;
    }
}

// Check if already installed
$installed = false;
try {
    $testPdo = new PDO("mysql:host=$host;dbname=$dbName;charset=utf8mb4", $user, $pass);
    $installed = true;
} catch (PDOException $e) {
    $installed = false;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instalasi - ERP Pemerintahan Daerah Jember</title>
    <link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; }
        .install-card { max-width: 600px; margin: 0 auto; }
    </style>
</head>
<body>
    <div class="container">
        <div class="install-card">
            <div class="card">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <img src="uploads/logo/logo.png" alt="Logo" style="width: 80px;" class="mb-3">
                        <h3>Instalasi Database</h3>
                        <p class="text-muted"><?= APP_NAME ?? 'ERP Pemerintahan Daerah Jember' ?></p>
                    </div>
                    
                    <?php if ($message): ?>
                        <div class="alert alert-<?= $success ? 'success' : 'danger' ?>">
                            <?= $message ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($installed): ?>
                        <div class="alert alert-success">
                            <i class="mdi mdi-check-circle mr-1"></i>
                            Database sudah terinstal. <a href="index.php">Login sekarang</a>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">
                            <i class="mdi mdi-information mr-1"></i>
                            Database belum tersedia. Klik tombol di bawah untuk menginstal.
                        </div>
                        
                        <form method="post">
                            <div class="form-group">
                                <label>Host</label>
                                <input type="text" class="form-control" value="<?= $host ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label>Database Name</label>
                                <input type="text" class="form-control" value="<?= $dbName ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" class="form-control" value="<?= $user ?>" readonly>
                            </div>
                            <button type="submit" name="install" class="btn btn-primary btn-block btn-lg">
                                <i class="mdi mdi-database-plus mr-1"></i> Instal Database
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
