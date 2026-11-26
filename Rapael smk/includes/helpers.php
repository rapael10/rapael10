<?php
if (session_status() === PHP_SESSION_NONE) {
    $localSessionDir = __DIR__ . '/../storage/sessions';
    if (!is_dir($localSessionDir)) {
        @mkdir($localSessionDir, 0755, true);
    }
    if (is_dir($localSessionDir)) {
        session_save_path($localSessionDir);
    }
    session_start();
}

/**
 * Escape output HTML untuk mencegah XSS
 */
function e(?string $str): string
{
    return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8');
}

/**
 * Buat slug URL-friendly dari judul
 */
function createSlug(string $text): string
{
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9-]+/', '-', $text);
    $text = trim($text, '-');
    return $text ?: 'artikel-' . time();
}

/**
 * Format tanggal Indonesia sederhana
 */
function formatTanggal(?string $dateStr): string
{
    if (!$dateStr) {
        return '-';
    }
    $bulan = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    $ts = strtotime($dateStr);
    if (!$ts) {
        return $dateStr;
    }
    $tgl = date('d', $ts);
    $bln = $bulan[(int)date('n', $ts)] ?? date('M', $ts);
    $thn = date('Y', $ts);
    return "$tgl $bln $thn";
}

/**
 * Set & ambil flash message
 */
function setFlash(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function getFlash(): ?array
{
    if (!empty($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

/**
 * Cek login admin
 */
function isAdminLoggedIn(): bool
{
    return !empty($_SESSION['admin_id']);
}

function requireAdmin(): void
{
    if (!isAdminLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

/**
 * Tentukan base path relatif agar link berfungsi di root maupun subfolder admin/
 */
function baseUrl(string $path = ''): string
{
    $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
    $prefix = (str_ends_with($scriptDir, '/admin')) ? '../' : '';
    return $prefix . ltrim($path, '/');
}
