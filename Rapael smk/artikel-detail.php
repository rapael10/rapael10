<?php
require_once __DIR__ . '/config/database.php';

$slug = trim($_GET['slug'] ?? '');
$stmt = $pdo->prepare("SELECT * FROM artikel WHERE slug = ? AND status = 'published' LIMIT 1");
$stmt->execute([$slug]);
$artikel = $stmt->fetch();

if (!$artikel) {
    $pageTitle = 'Artikel Tidak Ditemukan';
    include __DIR__ . '/includes/header.php';
    echo '<section class="section"><div class="container"><div class="alert alert-danger">Artikel tidak ditemukan atau belum dipublikasikan.</div><a href="artikel.php" class="btn btn-primary">&larr; Kembali ke Daftar Artikel</a></div></section>';
    include __DIR__ . '/includes/footer.php';
    exit;
}

$pageTitle = $artikel['judul'];
include __DIR__ . '/includes/header.php';
?>

<section class="section">
    <div class="container">
        <div style="max-width:820px; margin:0 auto 1.25rem;">
            <a href="artikel.php" class="btn btn-outline btn-sm">&larr; Kembali ke Artikel</a>
        </div>

        <article class="article-detail">
            <div class="article-meta">
                <span class="badge"><?= e($artikel['kategori']) ?></span>
                <span>Dipublikasikan: <?= formatTanggal($artikel['created_at']) ?></span>
                <span>&bull; Penulis: <strong><?= e($artikel['penulis']) ?></strong></span>
            </div>

            <h1 style="font-size:clamp(1.6rem, 3vw, 2.2rem); color:var(--primary); margin-bottom:0.75rem;">
                <?= e($artikel['judul']) ?>
            </h1>

            <p style="font-size:1.05rem; color:var(--text-muted); font-weight:500; margin-bottom:1.25rem;">
                <?= e($artikel['ringkasan']) ?>
            </p>

            <?php if (!empty($artikel['gambar']) && file_exists(__DIR__ . '/uploads/' . $artikel['gambar'])): ?>
                <img src="uploads/<?= e($artikel['gambar']) ?>" alt="<?= e($artikel['judul']) ?>" class="article-detail-cover">
            <?php endif; ?>

            <div class="article-content">
                <?php
                $paragraphs = preg_split('/\r\n|\r|\n/', trim($artikel['konten']));
                foreach ($paragraphs as $p) {
                    $p = trim($p);
                    if ($p !== '') {
                        echo '<p>' . e($p) . '</p>';
                    }
                }
                ?>
            </div>
        </article>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
