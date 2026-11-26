<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/icons.php';

$pageTitle = 'Beranda';

// Ambil 3 jurusan
$jurusanList = $pdo->query("SELECT * FROM jurusan ORDER BY id ASC")->fetchAll();

// Ambil 3 artikel terbaru yang published
$artikelList = $pdo->query("SELECT * FROM artikel WHERE status = 'published' ORDER BY created_at DESC LIMIT 3")->fetchAll();

// Ambil 3 informasi terbaru (utamakan yang pinned)
$infoList = $pdo->query("SELECT * FROM informasi ORDER BY is_pinned DESC, created_at DESC LIMIT 3")->fetchAll();

include __DIR__ . '/includes/header.php';
?>

<section class="hero">
    <div class="container hero-grid">
        <div>
            <div class="hero-badge">Akreditasi A &bull; Sekolah Pusat Keunggulan Vokasi</div>
            <h1>Mencetak Generasi Terampil, Berkarakter &amp; Siap Kerja</h1>
            <p>
                Selamat datang di <?= e(SCHOOL_NAME) ?>. Kami memadukan kurikulum berbasis industri, fasilitas praktik modern, dan pembinaan karakter untuk mempersiapkan lulusan unggul di era digital dan industri 4.0.
            </p>
            <div class="hero-actions">
                <a href="jurusan.php" class="btn btn-accent">Jelajahi Jurusan</a>
                <a href="informasi.php" class="btn btn-outline-light">Informasi &amp; PPDB</a>
            </div>
        </div>
        <div class="hero-stats">
            <div class="stat-card">
                <div class="num">3</div>
                <div class="label">Program Keahlian Unggulan (TKJ, TKR, AK)</div>
            </div>
            <div class="stat-card">
                <div class="num">94%</div>
                <div class="label">Lulusan Terserap Kerja &amp; Wirausaha</div>
            </div>
            <div class="stat-card">
                <div class="num">45+</div>
                <div class="label">Mitra Industri &amp; Perusahaan (DUDI)</div>
            </div>
            <div class="stat-card">
                <div class="num">100%</div>
                <div class="label">Praktik Lab &amp; Bengkel Standar Industri</div>
            </div>
        </div>
    </div>
</section>

<!-- Program Keahlian -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <div>
                <span class="eyebrow">Program Keahlian</span>
                <h2>Jurusan Unggulan Kami</h2>
                <p>Pilih kompetensi keahlian yang sesuai dengan minat dan bakatmu untuk masa depan karier yang cemerlang.</p>
            </div>
            <a href="jurusan.php" class="btn btn-outline">Detail Semua Jurusan &rarr;</a>
        </div>

        <div class="grid-3">
            <?php foreach ($jurusanList as $j): ?>
                <?php $kompetensi = array_slice(explode('|', $j['kompetensi']), 0, 3); ?>
                <article class="card">
                    <div class="card-body">
                        <div class="jurusan-icon"><?= renderJurusanIcon($j['ikon']) ?></div>
                        <span class="jurusan-badge"><?= e($j['singkatan']) ?></span>
                        <h3 class="card-title"><?= e($j['nama']) ?></h3>
                        <p class="card-text"><?= e($j['deskripsi']) ?></p>
                        <ul class="check-list">
                            <?php foreach ($kompetensi as $komp): ?>
                                <li><?= e($komp) ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <a href="jurusan.php#<?= e($j['kode']) ?>" class="btn btn-outline btn-sm" style="width:fit-content;">Lihat Kurikulum &rarr;</a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Informasi & Pengumuman Terbaru -->
<section class="section section-muted">
    <div class="container">
        <div class="section-header">
            <div>
                <span class="eyebrow">Papan Informasi</span>
                <h2>Pengumuman &amp; Agenda Sekolah</h2>
                <p>Informasi resmi terkait PPDB, jadwal akademik, dan kegiatan siswa.</p>
            </div>
            <a href="informasi.php" class="btn btn-outline">Semua Informasi &rarr;</a>
        </div>

        <div style="display:flex; flex-direction:column; gap:1rem;">
            <?php foreach ($infoList as $info): ?>
                <div class="info-item <?= $info['is_pinned'] ? 'pinned' : '' ?>">
                    <div class="article-meta">
                        <span class="badge <?= $info['is_pinned'] ? 'badge-warning' : '' ?>"><?= e($info['kategori']) ?></span>
                        <?php if ($info['tanggal_acara']): ?>
                            <span>Tanggal: <strong><?= formatTanggal($info['tanggal_acara']) ?></strong></span>
                        <?php endif; ?>
                        <?php if ($info['lokasi']): ?>
                            <span>&bull; <?= e($info['lokasi']) ?></span>
                        <?php endif; ?>
                    </div>
                    <h3 style="font-size:1.12rem; margin-bottom:0.35rem; color:var(--primary);"><?= e($info['judul']) ?></h3>
                    <p style="color:var(--text-muted); font-size:0.94rem;"><?= e($info['isi']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Artikel Terbaru -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <div>
                <span class="eyebrow">Berita &amp; Artikel</span>
                <h2>Kabar Terbaru SMK BNB</h2>
                <p>Liputan prestasi siswa, kegiatan praktik industri, dan informasi edukatif.</p>
            </div>
            <a href="artikel.php" class="btn btn-outline">Semua Artikel &rarr;</a>
        </div>

        <?php if (empty($artikelList)): ?>
            <div class="alert alert-danger">Belum ada artikel yang dipublikasikan.</div>
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
                            <a href="artikel-detail.php?slug=<?= urlencode($art['slug']) ?>" class="btn btn-outline btn-sm" style="width:fit-content;">Baca Selengkapnya &rarr;</a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
