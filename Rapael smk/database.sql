-- Database: smk_bnb
CREATE DATABASE IF NOT EXISTS `smk_bnb` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `smk_bnb`;

-- Tabel Admin / Users
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `nama_lengkap` VARCHAR(100) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Tabel Jurusan (TKJ, TKR, AK)
CREATE TABLE IF NOT EXISTS `jurusan` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `kode` VARCHAR(10) NOT NULL UNIQUE,
    `nama` VARCHAR(120) NOT NULL,
    `singkatan` VARCHAR(20) NOT NULL,
    `deskripsi` TEXT NOT NULL,
    `kompetensi` TEXT NOT NULL,
    `prospek_karir` TEXT NOT NULL,
    `fasilitas` VARCHAR(255) NOT NULL,
    `ikon` VARCHAR(30) DEFAULT 'code'
) ENGINE=InnoDB;

-- Tabel Artikel
CREATE TABLE IF NOT EXISTS `artikel` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `judul` VARCHAR(200) NOT NULL,
    `slug` VARCHAR(220) NOT NULL UNIQUE,
    `kategori` VARCHAR(50) NOT NULL DEFAULT 'Berita',
    `ringkasan` TEXT NOT NULL,
    `konten` LONGTEXT NOT NULL,
    `gambar` VARCHAR(255) DEFAULT NULL,
    `penulis` VARCHAR(100) NOT NULL DEFAULT 'Admin SMK BNB',
    `status` ENUM('published', 'draft') NOT NULL DEFAULT 'published',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Tabel Informasi / Pengumuman Sekolah
CREATE TABLE IF NOT EXISTS `informasi` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `judul` VARCHAR(200) NOT NULL,
    `kategori` VARCHAR(50) NOT NULL DEFAULT 'Pengumuman',
    `isi` TEXT NOT NULL,
    `tanggal_acara` DATE DEFAULT NULL,
    `lokasi` VARCHAR(150) DEFAULT NULL,
    `is_pinned` TINYINT(1) NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Seed Admin Default (username: admin, password: password123)
INSERT IGNORE INTO `users` (`username`, `password`, `nama_lengkap`) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator SMK Bangun Nusa Bangsa');

-- Seed Jurusan: TKJ, TKR, AK
INSERT IGNORE INTO `jurusan` (`kode`, `nama`, `singkatan`, `deskripsi`, `kompetensi`, `prospek_karir`, `fasilitas`, `ikon`) VALUES
(
    'TKJ',
    'Teknik Komputer dan Jaringan',
    'TKJ',
    'Program keahlian yang membekali peserta didik dengan keterampilan merakit, mengkonfigurasi, mengelola jaringan komputer, server, keamanan siber dasar, serta layanan komputasi awan untuk kebutuhan industri digital modern.',
    'Perakitan & Perawatan PC|Administrasi Server Linux & Windows|Konfigurasi Router & Mikrotik|Jaringan Fiber Optik|Keamanan Jaringan Dasar',
    'Network Engineer|IT Support Specialist|System Administrator|Teknisi Fiber Optik|Cloud & DevOps Junior',
    'Lab Komputer Dual-OS, Lab Mikrotik & Cisco, Lab Fiber Optik, Jaringan Internet Fiber 1 Gbps',
    'network'
),
(
    'TKR',
    'Teknik Kendaraan Ringan',
    'TKR',
    'Program keahlian otomotif yang berfokus pada perawatan, perbaikan, dan diagnosa sistem kendaraan ringan modern mulai dari mesin EFI, sasis, pemindah tenaga, hingga sistem kelistrikan dan AC otomotif.',
    'Perawatan Mesin Bensin & Diesel|Sistem Injeksi Elektronik (EFI)|Sistem Rem, Suspensi & Kemudi|Kelistrikan Bodi & AC Mobil|Diagnosa OBD-II Scanner',
    'Mekanik Otomotif Profesional|Service Advisor Bengkel Resmi|Quality Control Industri Otomotif|Teknisi Kelistrikan Mobil|Wirausaha Bengkel Mandiri',
    'Bengkel Praktik Standar Industri, Unit Mobil Praktik EFI, Engine Stand, OBD-II Diagnostic Tool, Car Lift',
    'wrench'
),
(
    'AK',
    'Akuntansi dan Keuangan Lembaga',
    'AK',
    'Program keahlian bisnis dan manajemen yang mencetak tenaga profesional di bidang pembukuan, penyusunan laporan keuangan, perpajakan, audit dasar, dan pengoperasian software akuntansi berbasis komputer.',
    'Siklus Akuntansi Perusahaan Jasa & Dagang|Akuntansi Keuangan & Perpajakan|Aplikasi Pengolah Angka (Excel Lanjutan)|Komputer Akuntansi (MYOB / Accurate)|Administrasi Kas & Perbankan',
    'Staf Akuntansi & Keuangan|Kasir & Teller Perbankan|Staf Administrasi Pajak|Junior Auditor|Wirausaha Jasa Pembukuan UMKM',
    'Lab Komputer Akuntansi, Lab Mini Bank Sekolah, Software MYOB & Accurate Terbaru, Perpustakaan Bisnis',
    'calculator'
);

-- Seed Artikel Awal
INSERT IGNORE INTO `artikel` (`judul`, `slug`, `kategori`, `ringkasan`, `konten`, `penulis`, `status`) VALUES
(
    'Siswa TKJ SMK Bangun Nusa Bangsa Raih Juara 1 LKS Jaringan Tingkat Kota',
    'siswa-tkj-raih-juara-1-lks-jaringan-tingkat-kota',
    'Prestasi',
    'Tim kompetensi keahlian Teknik Komputer dan Jaringan berhasil menyabet juara pertama pada Lomba Kompetensi Siswa (LKS) bidang IT Network Systems Administration.',
    'Prestasi membanggakan kembali ditorehkan oleh siswa SMK Bangun Nusa Bangsa. Pada ajang Lomba Kompetensi Siswa (LKS) tingkat kota tahun ini, perwakilan dari jurusan Teknik Komputer dan Jaringan (TKJ) berhasil meraih Juara 1 pada mata lomba IT Network Systems Administration.\n\nDalam kompetisi yang berlangsung selama dua hari tersebut, peserta ditantang untuk merancang topologi jaringan multi-site, mengkonfigurasi server Linux, membangun firewall, serta melakukan troubleshooting jaringan secara cepat dan tepat.\n\nKepala Sekolah menyampaikan apresiasi setinggi-tingginya kepada siswa dan guru pembimbing yang telah berlatih intensif di Laboratorium Mikrotik & Server sekolah. Keberhasilan ini menjadi bukti komitmen SMK Bangun Nusa Bangsa dalam mencetak lulusan yang siap bersaing di dunia industri.',
    'Humas SMK BNB',
    'published'
),
(
    'Workshop Tune-Up Kendaraan Injeksi Bersama Bengkel Mitra Industri',
    'workshop-tune-up-kendaraan-injeksi-bersama-mitra-industri',
    'Kegiatan',
    'Jurusan Teknik Kendaraan Ringan (TKR) menggelar pelatihan praktis diagnosa mesin EFI menggunakan scanner OBD-II bersama instruktur dari industri otomotif.',
    'Untuk menyelaraskan kompetensi siswa dengan kebutuhan bengkel modern, jurusan Teknik Kendaraan Ringan (TKR) SMK Bangun Nusa Bangsa menyelenggarakan Workshop Tune-Up dan Diagnosa Mesin Injeksi (EFI).\n\nKegiatan yang berlangsung di Bengkel Praktik Otomotif sekolah ini menghadirkan teknisi senior dari mitra industri otomotif nasional. Para siswa kelas XI dan XII mendapatkan kesempatan langsung mempraktikkan pembacaan sensor mesin menggunakan scanner OBD-II, pengecekan tekanan bahan bakar, hingga kalibrasi sistem pengapian.\n\nDengan metode pembelajaran berbasis proyek nyata (teaching factory), lulusan TKR diharapkan langsung siap kerja begitu menyelesaikan masa PKL maupun setelah lulus.',
    'Tim TKR',
    'published'
),
(
    'Pelatihan Software Akuntansi Digital & Pelaporan Pajak untuk Kelas XII AK',
    'pelatihan-software-akuntansi-digital-dan-pajak-kelas-xii-ak',
    'Akademik',
    'Menghadapi era digitalisasi keuangan, siswa Akuntansi dibekali sertifikasi pengoperasian software akuntansi modern dan e-Filing perpajakan.',
    'Jurusan Akuntansi dan Keuangan Lembaga (AK) SMK Bangun Nusa Bangsa mengadakan pelatihan intensif penggunaan software akuntansi digital dan simulasi pelaporan pajak elektronik bagi siswa tingkat akhir.\n\nMateri pelatihan mencakup pencatatan transaksi harian, rekonsiliasi bank otomatis, penyusunan neraca dan laporan laba rugi, hingga penghitungan PPh dan PPN. Selain keterampilan teknis, siswa juga dilatih ketelitian serta etika profesi akuntan.\n\nProgram ini merupakan bagian dari persiapan Uji Kompetensi Keahlian (UKK) sekaligus bekal menghadapi dunia kerja di perusahaan jasa, dagang, maupun lembaga perbankan.',
    'Kaprodi Akuntansi',
    'published'
);

-- Seed Informasi / Pengumuman Sekolah
INSERT IGNORE INTO `informasi` (`judul`, `kategori`, `isi`, `tanggal_acara`, `lokasi`, `is_pinned`) VALUES
(
    'Penerimaan Peserta Didik Baru (PPDB) Tahun Ajaran 2026/2027 Dibuka!',
    'PPDB',
    'SMK Bangun Nusa Bangsa membuka pendaftaran siswa baru untuk program keahlian TKJ, TKR, dan AK. Tersedia jalur prestasi akademik/non-akademik serta beasiswa potongan biaya masuk bagi pendaftar gelombang pertama.',
    '2026-12-15',
    'Kampus SMK Bangun Nusa Bangsa & Online',
    1
),
(
    'Jadwal Pelaksanaan Uji Kompetensi Keahlian (UKK) Kelas XII',
    'Akademik',
    'Pelaksanaan Uji Kompetensi Keahlian (UKK) bersama penguji eksternal dari Dunia Usaha dan Dunia Industri (DUDI) akan dilaksanakan secara bertahap di masing-masing laboratorium dan bengkel jurusan.',
    '2027-02-10',
    'Lab TKJ, Bengkel TKR, Lab AK',
    0
),
(
    'Pembekalan Praktik Kerja Lapangan (PKL) Siswa Kelas XI',
    'Pengumuman',
    'Seluruh siswa kelas XI wajib mengikuti kegiatan pembekalan etos kerja, keselamatan kerja (K3), dan penyusunan jurnal PKL sebelum diterjunkan ke perusahaan mitra.',
    '2026-12-05',
    'Aula Utama SMK Bangun Nusa Bangsa',
    0
);
