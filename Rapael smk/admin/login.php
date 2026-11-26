<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/helpers.php';

if (isAdminLoggedIn()) {
    header('Location: index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Username dan password wajib diisi.';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['admin_username'] = $user['username'];
            $_SESSION['admin_nama'] = $user['nama_lengkap'];
            setFlash('success', 'Selamat datang kembali, ' . $user['nama_lengkap'] . '!');
            header('Location: index.php');
            exit;
        } else {
            $error = 'Username atau password salah.';
        }
    }
}

$pageTitle = 'Login Admin';
include __DIR__ . '/../includes/header.php';
?>

<section class="section">
    <div class="container">
        <div class="card" style="max-width:440px; margin:1.5rem auto;">
            <div class="card-body" style="padding:2rem;">
                <span class="jurusan-badge">Panel Administrator</span>
                <h1 style="font-size:1.5rem; color:var(--primary); margin-bottom:0.4rem;">Masuk ke Dashboard</h1>
                <p style="color:var(--text-muted); font-size:0.9rem; margin-bottom:1.5rem;">
                    Kelola artikel (tambah, edit, hapus) dan informasi sekolah <?= e(SCHOOL_NAME) ?>.
                </p>

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= e($error) ?></div>
                <?php endif; ?>

                <form method="POST" autocomplete="off">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" class="form-control" required autofocus value="<?= e($_POST['username'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width:100%;">Masuk</button>
                </form>

                <div style="margin-top:1.25rem; padding-top:1rem; border-top:1px solid var(--border); font-size:0.82rem; color:var(--text-muted);">
                    Akun default: <code>admin</code> / <code>password123</code>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
