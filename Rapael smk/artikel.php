<?php
require_once __DIR__ . '/config/database.php';

$pageTitle = 'Artikel & Berita';

$q = trim($_GET['q'] ?? '');
$kategori = trim($_GET['kategori'] ?? '');

$sql = "SELECT * FROM artikel WHERE status = 'published'";
$params = [];

if ($q !== '') {
    $sql .= " AND (judul LIKE ? OR ringkasan LIKE ? OR konten LIKE ?)";
    $like = "%$q%";
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
}

if ($kategori !== '') {
    $sql .= " AND kategori = ?";
    $params[] = $kategori;
}

$sql .= " ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$artikelList = $stmt->fetchAll();

$kategoriList = $pdo->query("SELECT DISTINCT kategori FROM artikel WHERE status = 'published' ORDER BY kategori ASC")->fetchAll(PDO::FETCH_COLUMN);

include __DIR__ . '/includes/header.php';
?>

<section class="page-header">
    <div class="container">
        <h1>Artikel &amp; Berita Sekolah</h1>
        <p>Kumpulan berita kegiatan, prestasi siswa, serta artikel edukasi dari <?= e(SCHOOL_NAME) ?>.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <form method="GET" class="filter-bar">
            <input type="text" name="q" value="<?= e($q) ?>" placeholder="Cari judul atau kata kunci artikel..." class="form-control" style="flex:1; min-width:220px;">
            <select name="kategori" class="form-control" style="max-width:200px;">
                <option value="">Semua Kategori</option>
                <?php foreach ($kategoriList as $kat): ?>
                    <option value="<?= e($kat) ?>" <?= $kategori === $kat ? 'selected' : '' ?>><?= e($kat) ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-primary">Cari</button>
            <?php if ($q !== '' || $kategori !== ''): ?>
                <a href="artikel.php" class="btn btn-outline">Reset</a>
            <?php endif; ?>
        </form>

        <?php if (empty($artikelList)): ?>
            <div class="alert alert-danger">Artikel tidak ditemukan. Coba kata kunci atau kategori lain.</div>
        <?php else: ?>
            <div class="grid-3">
                <?php foreach ($artikelList as $art): ?>
                    <article class="card">
                        <div class="article-thumb">
                            <?php if (!empty($art['gambar']) && file_exists(__DIR__ . '/uploads/' . $art['gambar'])): ?>
                                <img src="uploads/<?= e($art['gambar']) ?>" alt="<?= e($art['judul']) ?>">
                            <?php else: ?>
                                <span style="font-weight:700; font-size:1.1rem; letter-spacing:0.05em;"><?= e($art['kategori']) ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="card-body">
                            <div class="article-meta">
                                <span class="badge"><?= e($art['kategori']) ?></span>
                                <span><?= formatTanggal($art['created_at']) ?></span>
                            </div>
                            <h3 class="card-title" style="font-size:1.08rem;">
                                <a href="artikel-detail.php?slug=<?= urlencode($art['slug']) ?>"><?= e($art['judul']) ?></a>
                            </h3>
                            <p class="card-text"><?= e($art['ringkasan']) ?></p>
                            <div style="display:flex; justify-content:space-between; align-items:center; gap:0.5rem;">
                                <span style="font-size:0.8rem; color:var(--text-muted);">Oleh: <?= e($art['penulis']) ?></span>
                                <a href="artikel-detail.php?slug=<?= urlencode($art['slug']) ?>" class="btn btn-outline btn-sm">Baca &rarr;</a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
