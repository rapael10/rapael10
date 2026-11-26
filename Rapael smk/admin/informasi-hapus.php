<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/helpers.php';
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    if ($id > 0) {
        $stmt = $pdo->prepare("DELETE FROM informasi WHERE id = ?");
        $stmt->execute([$id]);
        setFlash('success', 'Informasi berhasil dihapus.');
    }
}

header('Location: informasi.php');
exit;
