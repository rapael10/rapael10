<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/helpers.php';
requireAdmin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$isEdit = $id > 0;
$artikel = [
    'judul' => '',
    'kategori' => 'Berita',
    'ringkasan' => '',
    'konten' => '',
    'penulis' => $_SESSION['admin_nama'] ?? 'Admin SMK BNB',
    'status' => 'published',
    'gambar' => null,
];

if ($isEdit) {
    $stmt = $pdo->prepare("SELECT * FROM artikel WHERE id = ? LIMIT 1");
    $stmt->execute([$id]);
    $found = $stmt->fetch();
    if (!$found) {
        setFlash('danger', 'Artikel tidak ditemukan.');
        header('Location: index.php');
        exit;
    }
    $artikel = $found;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = trim($_POST['judul'] ?? '');
    $kategori = trim($_POST['kategori'] ?? 'Berita');
    $ringkasan = trim($_POST['ringkasan'] ?? '');
    $konten = trim($_POST['konten'] ?? '');
    $penulis = trim($_POST['penulis'] ?? 'Admin SMK BNB');
    $status = ($_POST['status'] ?? 'published') === 'draft' ? 'draft' : 'published';

    if ($judul === '') $errors[] = 'Judul artikel wajib diisi.';
    if ($ringkasan === '') $errors[] = 'Ringkasan artikel wajib diisi.';
    if ($konten === '') $errors[] = 'Konten artikel wajib diisi.';

    $gambarName = $artikel['gambar'];

    // Handle upload gambar opsional
    if (!empty($_FILES['gambar']['name'])) {
        $allowedExt = ['jpg', 'jpeg', 'png', 'webp'];
        $ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
        $maxSize = 2 * 1024 * 1024; // 2MB

        if (!in_array($ext, $allowedExt, true)) {
            $errors[] = 'Format gambar harus JPG, JPEG, PNG, atau WEBP.';
        } elseif ($_FILES['gambar']['size'] > $maxSize) {
            $errors[] = 'Ukuran gambar maksimal 2 MB.';
        } elseif ($_FILES['gambar']['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'Terjadi kesalahan saat mengunggah gambar.';
        } else {
            $uploadDir = __DIR__ . '/../uploads';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $newFileName = 'artikel-' . time() . '-' . bin2hex(random_bytes(4)) . '.' . $ext;
            if (move_uploaded_file($_FILES['gambar']['tmp_name'], $uploadDir . '/' . $newFileName)) {
                // Hapus gambar lama jika edit
                if ($isEdit && !empty($artikel['gambar']) && file_exists($uploadDir . '/' . $artikel['gambar'])) {
                    @unlink($uploadDir . '/' . $artikel['gambar']);
                }
                $gambarName = $newFileName;
            } else {
                $errors[] = 'Gagal menyimpan file gambar.';
            }
        }
    }

    if (empty($errors)) {
        $baseSlug = createSlug($judul);
        $slug = $baseSlug;
        $counter = 1;

        // Pastikan slug unik
        while (true) {
            $checkSql = $isEdit
                ? "SELECT id FROM artikel WHERE slug = ? AND id != ? LIMIT 1"
                : "SELECT id FROM artikel WHERE slug = ? LIMIT 1";
            $checkStmt = $pdo->prepare($checkSql);
            $isEdit ? $checkStmt->execute([$slug, $id]) : $checkStmt->execute([$slug]);
            if (!$checkStmt->fetch()) {
                break;
            }
            $slug = $baseSlug . '-' . (++$counter);
        }

        if ($isEdit) {
            $stmt = $pdo->prepare(
                "UPDATE artikel SET judul = ?, slug = ?, kategori = ?, ringkasan = ?, konten = ?, gambar = ?, penulis = ?, status = ? WHERE id = ?"
            );
            $stmt->execute([$judul, $slug, $kategori, $ringkasan, $konten, $gambarName, $penulis, $status, $id]);
            setFlash('success', 'Artikel berhasil diperbarui.');
        } else {
            $stmt = $pdo->prepare(
                "INSERT INTO artikel (judul, slug, kategori, ringkasan, konten, gambar, penulis, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
            );
            $stmt->execute([$judul, $slug, $kategori, $ringkasan, $konten, $gambarName, $penulis, $status]);
            setFlash('success', 'Artikel baru berhasil ditambahkan.');
        }

        header('Location: index.php');
        exit;
    }

    // Pertahankan input saat error
    $artikel = array_merge($artikel, [
        'judul' => $judul,
        'kategori' => $kategori,
        'ringkasan' => $ringkasan,
        'konten' => $konten,
        'penulis' => $penulis,
        'status' => $status,
    ]);
}

$pageTitle = $isEdit ? 'Edit Artikel' : 'Tambah Artikel';
include __DIR__ . '/../includes/header.php';
?>

<section class="page-header" style="padding:1.75rem 0;">
    <div class="container" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem;">
        <div>
            <h1><?= $isEdit ? 'Edit Artikel' : 'Tambah Artikel Baru' ?></h1>
            <p>Lengkapi formulir di bawah ini untuk mempublikasikan artikel sekolah.</p>
        </div>
        <a href="index.php" class="btn btn-outline-light">&larr; Kembali ke Dashboard</a>
    </div>
</section>

<section class="section" style="padding:2.5rem 0 4rem;">
    <div class="container">
        <div class="card" style="max-width:820px; margin:0 auto;">
            <div class="card-body" style="padding:2rem;">
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul style="margin-left:1.2rem;">
                            <?php foreach ($errors as $err): ?>
                                <li><?= e($err) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="judul">Judul Artikel</label>
                        <input type="text" id="judul" name="judul" class="form-control" required value="<?= e($artikel['judul']) ?>">
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label for="kategori">Kategori</label>
                            <select id="kategori" name="kategori" class="form-control">
                                <?php foreach (['Berita', 'Prestasi', 'Kegiatan', 'Akademik', 'Edukasi'] as $kat): ?>
                                    <option value="<?= e($kat) ?>" <?= $artikel['kategori'] === $kat ? 'selected' : '' ?>><?= e($kat) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="status">Status Publikasi</label>
                            <select id="status" name="status" class="form-control">
                                <option value="published" <?= $artikel['status'] === 'published' ? 'selected' : '' ?>>Published (Tampil di Publik)</option>
                                <option value="draft" <?= $artikel['status'] === 'draft' ? 'selected' : '' ?>>Draft (Simpan Sementara)</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="penulis">Nama Penulis</label>
                        <input type="text" id="penulis" name="penulis" class="form-control" required value="<?= e($artikel['penulis']) ?>">
                    </div>

                    <div class="form-group">
                        <label for="ringkasan">Ringkasan Singkat</label>
                        <textarea id="ringkasan" name="ringkasan" rows="2" class="form-control" required><?= e($artikel['ringkasan']) ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="konten">Isi Artikel Lengkap</label>
                        <textarea id="konten" name="konten" rows="8" class="form-control" required><?= e($artikel['konten']) ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="gambarInput">Gambar Sampul (Opsional, JPG/PNG/WEBP maks 2MB)</label>
                        <input type="file" id="gambarInput" name="gambar" accept="image/*" class="form-control">
                        <?php if (!empty($artikel['gambar']) && file_exists(__DIR__ . '/../uploads/' . $artikel['gambar'])): ?>
                            <img id="gambarPreview" src="../uploads/<?= e($artikel['gambar']) ?>" alt="Preview" style="margin-top:0.75rem; max-height:180px; border-radius:8px;">
                        <?php else: ?>
                            <img id="gambarPreview" src="" alt="Preview" style="display:none; margin-top:0.75rem; max-height:180px; border-radius:8px;">
                        <?php endif; ?>
                    </div>

                    <div style="display:flex; gap:0.75rem; margin-top:1.5rem;">
                        <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Simpan Perubahan' : 'Publikasikan Artikel' ?></button>
                        <a href="index.php" class="btn btn-outline">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
