<?php
require_once __DIR__ . '/helpers.php';
$currentPage = basename($_SERVER['PHP_SELF'] ?? 'index.php');
$isAdminArea = str_contains(str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? ''), '/admin/');
$root = $isAdminArea ? '../' : '';
$pageTitle = $pageTitle ?? SCHOOL_NAME;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?> — <?= e(SCHOOL_NAME) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= $root ?>assets/css/style.css">
</head>
<body>
<header class="navbar">
    <div class="container navbar-inner">
        <a href="<?= $root ?>index.php" class="brand">
            <div class="brand-logo">BNB</div>
            <div class="brand-text">
                <strong><?= e(SCHOOL_NAME) ?></strong>
                <span>Sekolah Menengah Kejuruan</span>
            </div>
        </a>

        <button class="menu-toggle" id="menuToggle" aria-label="Buka menu navigasi" aria-expanded="false">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="12" x2="21" y2="12"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
        </button>

        <ul class="nav-links" id="navLinks">
            <li><a href="<?= $root ?>index.php" class="<?= (!$isAdminArea && $currentPage === 'index.php') ? 'active' : '' ?>">Beranda</a></li>
            <li><a href="<?= $root ?>jurusan.php" class="<?= (!$isAdminArea && $currentPage === 'jurusan.php') ? 'active' : '' ?>">Jurusan</a></li>
            <li><a href="<?= $root ?>informasi.php" class="<?= (!$isAdminArea && $currentPage === 'informasi.php') ? 'active' : '' ?>">Informasi</a></li>
            <li><a href="<?= $root ?>artikel.php" class="<?= (!$isAdminArea && in_array($currentPage, ['artikel.php', 'artikel-detail.php'])) ? 'active' : '' ?>">Artikel</a></li>
            <?php if (isAdminLoggedIn()): ?>
                <li><a href="<?= $root ?>admin/index.php" class="nav-cta">Dashboard Admin</a></li>
            <?php else: ?>
                <li><a href="<?= $root ?>admin/login.php" class="nav-cta">Login Admin</a></li>
            <?php endif; ?>
        </ul>
    </div>
</header>
<main style="flex:1;">
