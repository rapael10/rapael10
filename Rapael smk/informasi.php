<?php
require_once __DIR__ . '/config/database.php';

$pageTitle = 'Informasi Sekolah';

$kategoriFilter = trim($_GET['kategori'] ?? '');
if ($kategoriFilter !== '') {
    $stmt = $pdo->prepare("SELECT * FROM informasi WHERE kategori = ? ORDER BY is_pinned DESC, created_at DESC");
    $stmt->execute([$kategoriFilter]);
    $infoList = $stmt->fetchAll();
} else {
    $infoList = $pdo->query("SELECT * FROM informasi ORDER BY is_pinned DESC, created_at DESC")->fetchAll();
}

$kategoriList = $pdo->query("SELECT DISTINCT kategori FROM informasi ORDER BY kategori ASC")->fetchAll(PDO::FETCH_COLUMN);

include __DIR__ . '/includes/header.php';
?>

<section class="page-header">
    <div class="container">
        <h1>Informasi &amp; Profil Sekolah</h1>
        <p>Profil singkat, visi misi, informasi PPDB, serta pengumuman resmi <?= e(SCHOOL_NAME) ?>.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <!-- Profil & Visi Misi -->
        <div class="grid-2" style="margin-bottom:3rem;">
            <div class="card">
                <div class="card-body">
                    <span class="jurusan-badge">Profil Sekolah</span>
                    <h2 style="color:var(--primary); margin-bottom:0.75rem; font-size:1.35rem;">Tentang <?= e(SCHOOL_NAME) ?></h2>
                    <p style="color:var(--text-muted); margin-bottom:1rem;">
                        <?= e(SCHOOL_NAME) ?> adalah lembaga pendidikan kejuruan yang berkomitmen mencetak lulusan kompeten, disiplin, dan berakhlak mulia. Dengan dukungan tenaga pendidik bersertifikat dan kemitraan aktif bersama industri, kami menyiapkan siswa agar siap kerja, siap kuliah, maupun berwirausaha.
                    </p>
                    <ul class="check-list" style="margin-bottom:0;">
                        <li>Status: Terakreditasi A</li>
                        <li>Program Keahlian: TKJ, TKR, dan Akuntansi (AK)</li>
                        <li>Jam Belajar: Senin - Jumat (07.00 - 15.00 WIB)</li>
                        <li>Alamat: Jl. Pendidikan Raya No. 45, Kota Harapan</li>
                    </ul>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <span class="jurusan-badge">Visi &amp; Misi</span>
                    <h2 style="color:var(--primary); margin-bottom:0.75rem; font-size:1.35rem;">Arah Pendidikan Kami</h2>
                    <p style="color:var(--text); font-weight:600; margin-bottom:0.75rem;">
                        "Menjadi SMK unggul yang menghasilkan lulusan profesional, inovatif, dan berdaya saing global."
                    </p>
                    <ul class="check-list" style="margin-bottom:0;">
                        <li>Menyelenggarakan pembelajaran berbasis kompetensi dan teknologi terkini.</li>
                        <li>Mengembangkan karakter disiplin, jujur, dan tanggung jawab.</li>
                        <li>Memperluas kerja sama dengan Dunia Usaha dan Dunia Industri (DUDI).</li>
                        <li>Mendorong jiwa kewirausahaan dan kreativitas peserta didik.</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Pengumuman & Agenda -->
        <div class="section-header" style="margin-bottom:1.25rem;">
            <div>
                <span class="eyebrow">Pengumuman Resmi</span>
                <h2>Informasi &amp; Agenda Terbaru</h2>
            </div>
        </div>

        <form method="GET" class="filter-bar" style="align-items:center;">
            <label for="kategori" style="font-weight:600; font-size:0.9rem;">Filter Kategori:</label>
            <select name="kategori" id="kategori" class="form-control" style="max-width:240px;" onchange="this.form.submit()">
                <option value="">Semua Kategori</option>
                <?php foreach ($kategoriList as $kat): ?>
                    <option value="<?= e($kat) ?>" <?= $kategoriFilter === $kat ? 'selected' : '' ?>><?= e($kat) ?></option>
                <?php endforeach; ?>
            </select>
            <?php if ($kategoriFilter !== ''): ?>
                <a href="informasi.php" class="btn btn-outline btn-sm">Reset</a>
            <?php endif; ?>
        </form>

        <?php if (empty($infoList)): ?>
            <div class="alert alert-danger">Belum ada informasi untuk kategori yang dipilih.</div>
        <?php else: ?>
            <div style="display:flex; flex-direction:column; gap:1rem;">
                <?php foreach ($infoList as $info): ?>
                    <div class="info-item <?= $info['is_pinned'] ? 'pinned' : '' ?>">
                        <div class="article-meta">
                            <span class="badge <?= $info['is_pinned'] ? 'badge-warning' : '' ?>"><?= e($info['kategori']) ?></span>
                            <?php if ($info['is_pinned']): ?>
                                <span class="badge badge-success">Penting</span>
                            <?php endif; ?>
                            <?php if ($info['tanggal_acara']): ?>
                                <span>Tanggal: <strong><?= formatTanggal($info['tanggal_acara']) ?></strong></span>
                            <?php endif; ?>
                            <?php if ($info['lokasi']): ?>
                                <span>&bull; Lokasi: <?= e($info['lokasi']) ?></span>
                            <?php endif; ?>
                        </div>
                        <h3 style="font-size:1.15rem; margin-bottom:0.4rem; color:var(--primary);"><?= e($info['judul']) ?></h3>
                        <p style="color:var(--text-muted); font-size:0.95rem;"><?= nl2br(e($info['isi'])) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
