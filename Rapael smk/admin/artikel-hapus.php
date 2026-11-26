<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/helpers.php';
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$id = (int)($_POST['id'] ?? 0);
if ($id > 0) {
    $stmt = $pdo->prepare("SELECT gambar FROM artikel WHERE id = ? LIMIT 1");
    $stmt->execute([$id]);
    $art = $stmt->fetch();

    if ($art) {
        if (!empty($art['gambar'])) {
            $filePath = __DIR__ . '/../uploads/' . $art['gambar'];
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
        }
        $del = $pdo->prepare("DELETE FROM artikel WHERE id = ?");
        $del->execute([$id]);
        setFlash('success', 'Artikel berhasil dihapus.');
    } else {
        setFlash('danger', 'Artikel tidak ditemukan.');
    }
}

header('Location: index.php');
exit;
