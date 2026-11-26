</main>
<?php
$isAdminArea = str_contains(str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? ''), '/admin/');
$root = $isAdminArea ? '../' : '';
?>
<footer class="footer">
    <div class="container">
        <div class="footer-grid">
            <div>
                <h4><?= e(SCHOOL_NAME) ?></h4>
                <p style="font-size:0.92rem; max-width:360px; margin-bottom:1rem;">
                    <?= e(SCHOOL_TAGLINE) ?>. Membekali siswa dengan kompetensi industri di bidang TKJ, TKR, dan Akuntansi.
                </p>
                <p style="font-size:0.86rem; color:rgba(255,255,255,0.65);">
                    Jl. Pendidikan Raya No. 45, Kota Harapan<br>
                    Telp: (021) 555-0192 | Email: info@smkbangunnusabangsa.sch.id
                </p>
            </div>
            <div>
                <h4>Navigasi Cepat</h4>
                <ul>
                    <li><a href="<?= $root ?>index.php">Beranda</a></li>
                    <li><a href="<?= $root ?>jurusan.php">Program Keahlian</a></li>
                    <li><a href="<?= $root ?>informasi.php">Informasi &amp; PPDB</a></li>
                    <li><a href="<?= $root ?>artikel.php">Artikel &amp; Berita</a></li>
                </ul>
            </div>
            <div>
                <h4>Program Keahlian</h4>
                <ul>
                    <li><a href="<?= $root ?>jurusan.php#TKJ">Teknik Komputer &amp; Jaringan (TKJ)</a></li>
                    <li><a href="<?= $root ?>jurusan.php#TKR">Teknik Kendaraan Ringan (TKR)</a></li>
                    <li><a href="<?= $root ?>jurusan.php#AK">Akuntansi &amp; Keuangan (AK)</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            &copy; <?= date('Y') ?> <?= e(SCHOOL_NAME) ?>. Seluruh hak cipta dilindungi.
        </div>
    </div>
</footer>
<script src="<?= $root ?>assets/js/main.js"></script>
</body>
</html>
