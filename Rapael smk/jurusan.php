<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/icons.php';

$pageTitle = 'Program Keahlian';
$jurusanList = $pdo->query("SELECT * FROM jurusan ORDER BY id ASC")->fetchAll();

include __DIR__ . '/includes/header.php';
?>

<section class="page-header">
    <div class="container">
        <h1>Program Keahlian (Jurusan)</h1>
        <p>SMK Bangun Nusa Bangsa menyelenggarakan 3 kompetensi keahlian terakreditasi yang dirancang sesuai kebutuhan dunia usaha dan industri.</p>
    </div>
</section>

<section class="section">
    <div class="container" style="display:flex; flex-direction:column; gap:2rem;">
        <?php foreach ($jurusanList as $j): ?>
            <?php
            $kompetensi = explode('|', $j['kompetensi']);
            $karir = explode('|', $j['prospek_karir']);
            ?>
            <article id="<?= e($j['kode']) ?>" class="card" style="scroll-margin-top:95px;">
                <div class="card-body" style="padding:2rem;">
                    <div style="display:flex; align-items:center; gap:1rem; flex-wrap:wrap; margin-bottom:1.25rem;">
                        <div class="jurusan-icon" style="margin-bottom:0;"><?= renderJurusanIcon($j['ikon']) ?></div>
                        <div>
                            <span class="jurusan-badge" style="margin-bottom:0.25rem;">Kode Jurusan: <?= e($j['kode']) ?></span>
                            <h2 style="color:var(--primary); font-size:1.5rem;"><?= e($j['nama']) ?></h2>
                        </div>
                    </div>

                    <p style="color:var(--text-muted); margin-bottom:1.5rem; font-size:1rem;">
                        <?= e($j['deskripsi']) ?>
                    </p>

                    <div class="grid-2" style="margin-bottom:1.25rem;">
                        <div style="background:var(--surface-muted); padding:1.25rem; border-radius:var(--radius-sm);">
                            <h3 style="font-size:1rem; color:var(--primary); margin-bottom:0.75rem;">Kompetensi yang Dipelajari</h3>
                            <ul class="check-list" style="margin-bottom:0;">
                                <?php foreach ($kompetensi as $item): ?>
                                    <li><?= e($item) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <div style="background:var(--surface-muted); padding:1.25rem; border-radius:var(--radius-sm);">
                            <h3 style="font-size:1rem; color:var(--primary); margin-bottom:0.75rem;">Prospek Karier Lulusan</h3>
                            <ul class="check-list" style="margin-bottom:0;">
                                <?php foreach ($karir as $item): ?>
                                    <li><?= e($item) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>

                    <div style="border-top:1px solid var(--border); padding-top:1rem; font-size:0.92rem; color:var(--text-muted);">
                        <strong style="color:var(--primary);">Fasilitas Praktik:</strong> <?= e($j['fasilitas']) ?>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
