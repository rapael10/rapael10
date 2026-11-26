<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/helpers.php';
requireAdmin();

$pageTitle = 'Dashboard Admin';
$flash = getFlash();

$totalArtikel = (int)$pdo->query("SELECT COUNT(*) FROM artikel")->fetchColumn();
$totalPublished = (int)$pdo->query("SELECT COUNT(*) FROM artikel WHERE status = 'published'")->fetchColumn();
$totalInfo = (int)$pdo->query("SELECT COUNT(*) FROM informasi")->fetchColumn();
$totalJurusan = (int)$pdo->query("SELECT COUNT(*) FROM jurusan")->fetchColumn();

$artikelList = $pdo->query("SELECT * FROM artikel ORDER BY created_at DESC")->fetchAll();

include __DIR__ . '/../includes/header.php';
?>

<section class="page-header" style="padding:2rem 0;">
    <div class="container" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem;">
        <div>
            <h1>Dashboard Admin</h1>
            <p>Login sebagai <strong><?= e($_SESSION['admin_nama'] ?? 'Admin') ?></strong></p>
        </div>
        <div style="display:flex; gap:0.65rem; flex-wrap:wrap;">
            <a href="artikel-form.php" class="btn btn-accent">+ Tambah Artikel</a>
            <a href="informasi.php" class="btn btn-outline-light">Kelola Informasi</a>
            <a href="logout.php" class="btn btn-danger">Keluar</a>
        </div>
    </div>
</section>

<section class="section" style="padding:2.5rem 0 4rem;">
    <div class="container">
        <?php if ($flash): ?>
            <div class="alert alert-<?= $flash['type'] === 'success' ? 'success' : 'danger' ?>">
                <?= e($flash['message']) ?>
            </div>
        <?php endif; ?>

        <!-- Ringkasan -->
        <div class="grid-3" style="margin-bottom:2.25rem;">
            <div class="card">
                <div class="card-body">
                    <span style="font-size:0.85rem; color:var(--text-muted);">Total Artikel</span>
                    <div style="font-size:2rem; font-weight:800; color:var(--primary);"><?= $totalArtikel ?></div>
                    <span style="font-size:0.82rem; color:var(--success);"><?= $totalPublished ?> dipublikasikan</span>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <span style="font-size:0.85rem; color:var(--text-muted);">Informasi &amp; Pengumuman</span>
                    <div style="font-size:2rem; font-weight:800; color:var(--primary);"><?= $totalInfo ?></div>
                    <a href="informasi.php" style="font-size:0.82rem; color:var(--primary-light); font-weight:600;">Kelola Informasi &rarr;</a>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <span style="font-size:0.85rem; color:var(--text-muted);">Program Keahlian</span>
                    <div style="font-size:2rem; font-weight:800; color:var(--primary);"><?= $totalJurusan ?></div>
                    <span style="font-size:0.82rem; color:var(--text-muted);">TKJ, TKR, AK</span>
                </div>
            </div>
        </div>

        <div class="section-header" style="margin-bottom:1.25rem;">
            <div>
                <h2>Daftar Artikel</h2>
                <p>Tambah, edit, atau hapus artikel berita dan kegiatan sekolah.</p>
            </div>
            <a href="artikel-form.php" class="btn btn-primary">+ Tambah Artikel Baru</a>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Judul Artikel</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($artikelList)): ?>
                        <tr>
                            <td colspan="6" style="text-align:center; color:var(--text-muted); padding:2rem;">Belum ada artikel. Klik "+ Tambah Artikel Baru" untuk memulai.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($artikelList as $idx => $art): ?>
                            <tr>
                                <td><?= $idx + 1 ?></td>
                                <td>
                                    <strong><?= e($art['judul']) ?></strong>
                                    <div style="font-size:0.8rem; color:var(--text-muted);">Oleh: <?= e($art['penulis']) ?></div>
                                </td>
                                <td><span class="badge"><?= e($art['kategori']) ?></span></td>
                                <td>
                                    <?php if ($art['status'] === 'published'): ?>
                                        <span class="badge badge-success">Published</span>
                                    <?php else: ?>
                                        <span class="badge badge-warning">Draft</span>
                                    <?php endif; ?>
                                </td>
                                <td style="white-space:nowrap;"><?= formatTanggal($art['created_at']) ?></td>
                                <td>
                                    <div style="display:flex; gap:0.45rem; flex-wrap:wrap;">
                                        <a href="artikel-form.php?id=<?= (int)$art['id'] ?>" class="btn btn-outline btn-sm">Edit</a>
                                        <form method="POST" action="artikel-hapus.php" onsubmit="return confirm('Yakin ingin menghapus artikel ini?');" style="display:inline;">
                                            <input type="hidden" name="id" value="<?= (int)$art['id'] ?>">
                                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
