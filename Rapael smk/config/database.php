<?php
/**
 * Konfigurasi koneksi database PDO (MySQL / MariaDB)
 */
define('DB_HOST', 'localhost');
define('DB_NAME', 'smk_bnb');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

define('SCHOOL_NAME', 'SMK Bangun Nusa Bangsa');
define('SCHOOL_SHORT', 'SMK BNB');
define('SCHOOL_TAGLINE', 'Unggul dalam Vokasi, Berkarakter, dan Siap Kerja');

try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    die(
        '<div style="font-family:sans-serif;max-width:600px;margin:4rem auto;padding:2rem;border:1px solid #fecaca;background:#fef2f2;border-radius:12px;color:#991b1b;">' .
        '<h2 style="margin-top:0;">Koneksi Database Gagal</h2>' .
        '<p>Pastikan server MySQL/MariaDB berjalan dan database <code>' . htmlspecialchars(DB_NAME) . '</code> sudah diimpor dari file <code>database.sql</code>.</p>' .
        '<p style="font-size:0.875rem;color:#7f1d1d;">Detail: ' . htmlspecialchars($e->getMessage()) . '</p>' .
        '</div>'
    );
}
