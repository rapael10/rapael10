<?php
/**
 * Script inisialisasi database otomatis dari file database.sql.
 * Aman dijalankan berulang kali (menggunakan IF NOT EXISTS & INSERT IGNORE).
 */
$host = 'localhost';
$user = 'root';
$pass = '';
$sqlFile = __DIR__ . '/database.sql';

if (!file_exists($sqlFile)) {
    die('File database.sql tidak ditemukan.');
}

try {
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

    $sql = file_get_contents($sqlFile);
    $pdo->exec($sql);

    echo '<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><title>Setup Database</title>';
    echo '<link rel="stylesheet" href="assets/css/style.css"></head><body style="padding:3rem 1rem;">';
    echo '<div class="card" style="max-width:560px;margin:0 auto;padding:2rem;">';
    echo '<h2 style="color:var(--primary);margin-bottom:0.75rem;">Database Berhasil Disiapkan!</h2>';
    echo '<p style="color:var(--text-muted);margin-bottom:1.25rem;">Database <code>smk_bnb</code> beserta tabel <code>users</code>, <code>jurusan</code>, <code>artikel</code>, dan <code>informasi</code> telah tersedia dan siap digunakan.</p>';
    echo '<a href="index.php" class="btn btn-primary">Buka Website SMK BNB</a>';
    echo '</div></body></html>';
} catch (PDOException $e) {
    die('Gagal menyiapkan database: ' . htmlspecialchars($e->getMessage()));
}
