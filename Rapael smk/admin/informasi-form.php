<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/helpers.php';
requireAdmin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$isEdit = $id > 0;
$info = [
    'judul' => '',
    'kategori' => 'Pengumuman',
    'isi' => '',
    'tanggal_acara' => '',
    'lokasi' => '',
    'is_pinned' => 0,
];

if ($isEdit) {
    $stmt = $pdo->prepare("SELECT * FROM informasi WHERE id = ? LIMIT 1");
    $stmt->execute([$id]);
    $found = $stmt->fetch();
    if (!$found) {
        setFlash('danger', 'Informasi tidak ditemukan.');
        header('Location: informasi.php');
        exit;
    }
    $info = $found;
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = trim($_POST['judul'] ?? '');
    $kategori = trim($_POST['kategori'] ?? 'Pengumuman');
    $isi = trim($_POST['isi'] ?? '');
    $tanggal_acara = trim($_POST['tanggal_acara'] ?? '') ?: null;
    $lokasi = trim($_POST['lokasi'] ?? '') ?: null;
    $is_pinned = isset($_POST['is_pinned']) ? 1 : 0;

    if ($judul === '') $errors[] = 'Judul informasi wajib diisi.';
    if ($isi === '') $errors[] = 'Isi informasi wajib diisi.';

    if (empty($errors)) {
        if ($isEdit) {
            $stmt = $pdo->prepare("UPDATE informasi SET judul = ?, kategori = ?, isi = ?, tanggal_acara = ?, lokasi = ?, is_pinned = ? WHERE id = ?");
            $stmt->execute([$judul, $kategori, $isi, $tanggal_acara, $lokasi, $is_pinned, $id]);
            setFlash('success', 'Informasi berhasil diperbarui.');
        } else {
            $stmt = $pdo->prepare("INSERT INTO informasi (judul, kategori, isi, tanggal_acara, lokasi, is_pinned) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$judul, $kategori, $isi, $tanggal_acara, $lokasi, $is_pinned]);
            setFlash('success', 'Informasi baru berhasil ditambahkan.');
        }
        header('Location: informasi.php');
        exit;
    }

    $info = compact('judul', 'kategori', 'isi', 'tanggal_acara', 'lokasi', 'is_pinned');
}

$pageTitle = $isEdit ? 'Edit Informasi' : 'Tambah Informasi';
include __DIR__ . '/../includes/header.php';
?>

<section class="page-header" style="padding:1.75rem 0;">
    <div class="container" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem;">
        <div>
            <h1><?= $isEdit ? 'Edit Informasi' : 'Tambah Informasi Baru' ?></h1>
            <p>Publikasikan pengumuman atau agenda sekolah terbaru.</p>
        </div>
        <a href="informasi.php" class="btn btn-outline-light">&larr; Kembali</a>
    </div>
</section>

<section class="section" style="padding:2.5rem 0 4rem;">
    <div class="container">
        <div class="card" style="max-width:740px; margin:0 auto;">
            <div class="card-body" style="padding:2rem;">
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger"><?= e(implode(' ', $errors)) ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="form-group">
                        <label for="judul">Judul Informasi</label>
                        <input type="text" id="judul" name="judul" class="form-control" required value="<?= e($info['judul']) ?>">
                    </div>
                    <div class="grid-2">
                        <div class="form-group">
                            <label for="kategori">Kategori</label>
                            <select id="kategori" name="kategori" class="form-control">
                                <?php foreach (['Pengumuman', 'PPDB', 'Akademik', 'Kegiatan'] as $kat): ?>
                                    <option value="<?= e($kat) ?>" <?= $info['kategori'] === $kat ? 'selected' : '' ?>><?= e($kat) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="tanggal_acara">Tanggal Acara (Opsional)</label>
                            <input type="date" id="tanggal_acara" name="tanggal_acara" class="form-control" value="<?= e($info['tanggal_acara']) ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="lokasi">Lokasi (Opsional)</label>
                        <input type="text" id="lokasi" name="lokasi" class="form-control" value="<?= e($info['lokasi']) ?>">
                    </div>
                    <div class="form-group">
                        <label for="isi">Isi Informasi</label>
                        <textarea id="isi" name="isi" rows="5" class="form-control" required><?= e($info['isi']) ?></textarea>
                    </div>
                    <div class="form-group" style="display:flex; align-items:center; gap:0.5rem;">
                        <input type="checkbox" id="is_pinned" name="is_pinned" value="1" <?= !empty($info['is_pinned']) ? 'checked' : '' ?>>
                        <label for="is_pinned" style="margin-bottom:0;">Sematkan di bagian atas (Penting)</label>
                    </div>
                    <div style="display:flex; gap:0.75rem;">
                        <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Simpan Perubahan' : 'Tambah Informasi' ?></button>
                        <a href="informasi.php" class="btn btn-outline">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
