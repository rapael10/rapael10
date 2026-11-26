<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/helpers.php';
requireAdmin();

$pageTitle = 'Kelola Informasi Sekolah';
$flash = getFlash();
$infoList = $pdo->query("SELECT * FROM informasi ORDER BY is_pinned DESC, created_at DESC")->fetchAll();

include __DIR__ . '/../includes/header.php';
?>

<section class="page-header" style="padding:1.75rem 0;">
    <div class="container" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem;">
        <div>
            <h1>Kelola Informasi Sekolah</h1>
            <p>Tambah, edit, atau hapus pengumuman PPDB dan agenda sekolah.</p>
        </div>
        <div style="display:flex; gap:0.65rem;">
            <a href="informasi-form.php" class="btn btn-accent">+ Tambah Informasi</a>
            <a href="index.php" class="btn btn-outline-light">&larr; Dashboard</a>
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

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Judul Informasi</th>
                        <th>Kategori</th>
                        <th>Tanggal Acara</th>
                        <th>Prioritas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($infoList)): ?>
                        <tr>
                            <td colspan="6" style="text-align:center; color:var(--text-muted); padding:2rem;">Belum ada data informasi.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($infoList as $idx => $info): ?>
                            <tr>
                                <td><?= $idx + 1 ?></td>
                                <td>
                                    <strong><?= e($info['judul']) ?></strong>
                                    <?php if ($info['lokasi']): ?>
                                        <div style="font-size:0.8rem; color:var(--text-muted);">Lokasi: <?= e($info['lokasi']) ?></div>
                                    <?php endif; ?>
                                </td>
                                <td><span class="badge"><?= e($info['kategori']) ?></span></td>
                                <td><?= formatTanggal($info['tanggal_acara']) ?></td>
                                <td>
                                    <?= $info['is_pinned'] ? '<span class="badge badge-warning">Disematkan</span>' : '<span style="color:var(--text-muted);">Normal</span>' ?>
                                </td>
                                <td>
                                    <div style="display:flex; gap:0.45rem;">
                                        <a href="informasi-form.php?id=<?= (int)$info['id'] ?>" class="btn btn-outline btn-sm">Edit</a>
                                        <form method="POST" action="informasi-hapus.php" onsubmit="return confirm('Hapus informasi ini?');" style="display:inline;">
                                            <input type="hidden" name="id" value="<?= (int)$info['id'] ?>">
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
