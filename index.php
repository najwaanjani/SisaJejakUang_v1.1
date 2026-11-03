<?php
session_start();
$is_logged_in = isset($_SESSION['user_id']);
$current_user = $is_logged_in ? [
    'id' => $_SESSION['user_id'],
    'name' => $_SESSION['name'],
    'email' => $_SESSION['email'],
    'role' => $_SESSION['role']
] : null;
?>
<!DOCTYPE html>
<html class="no-js" lang="id">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <title>SisaJejakUang - Kelola Keuangan Pribadi Anda Secara Cerdas</title>
    <meta name="description" content="SisaJejakUang adalah aplikasi pencatatan keuangan dinamis yang membantu Anda mengelola saldo, batas anggaran belanja, dan melacak prioritas kebutuhan vs keinginan." />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png" />

    <!-- ======== CSS here ======== -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/lineicons.css" />
    <link rel="stylesheet" href="assets/css/animate.css" />
    <link rel="stylesheet" href="assets/css/main.css" />

    <style>
      @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');
      body,
      h1, h2, h3, h4, h5, h6 {
        font-family: 'Plus Jakarta Sans', sans-serif;
      }
      .navbar-brand span {
        font-family: 'Plus Jakarta Sans', sans-serif;
        letter-spacing: -0.5px;
      }
      .logo-accent {
        color: #5864FF;
      }
      .header-action .main-btn {
        padding: 12px 26px;
        font-size: 15px;
      }
    </style>
  </head>
  <body>
    <!--[if lte IE 9]>
      <p class="browserupgrade">
        Anda menggunakan browser yang <strong>sudah usang</strong>. Silakan
        <a href="https://browsehappy.com/">perbarui browser Anda</a> untuk meningkatkan
        pengalaman dan keamanan.
      </p>
    <![endif]-->

    <!-- ======== preloader start ======== -->
    <div class="preloader">
      <div class="loader">
        <div class="spinner">
          <div class="spinner-container">
            <div class="spinner-rotator">
              <div class="spinner-left">
                <div class="spinner-circle"></div>
              </div>
              <div class="spinner-right">
                <div class="spinner-circle"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- preloader end -->

    <!-- ======== header start ======== -->
    <header class="header">
      <div class="navbar-area">
        <div class="container">
          <div class="row align-items-center">
            <div class="col-lg-12">
              <nav class="navbar navbar-expand-lg">
                <a class="navbar-brand d-flex align-items-center" href="index.php">
                  <span class="fs-3 fw-bold text-light">SisaJejakUang</span>
                </a>
                <button
                  class="navbar-toggler"
                  type="button"
                  data-bs-toggle="collapse"
                  data-bs-target="#navbarSupportedContent"
                  aria-controls="navbarSupportedContent"
                  aria-expanded="false"
                  aria-label="Toggle navigation"
                >
                  <span class="toggler-icon"></span>
                  <span class="toggler-icon"></span>
                  <span class="toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse sub-menu-bar" id="navbarSupportedContent">
                  <ul id="nav" class="navbar-nav ms-auto">
                    <li class="nav-item">
                      <a class="page-scroll active" href="#home">Beranda</a>
                    </li>
                    <li class="nav-item">
                      <a class="page-scroll" href="#features">Fitur</a>
                    </li>
                    <li class="nav-item">
                      <a class="page-scroll" href="#about">Tentang Kami</a>
                    </li>
                    <li class="nav-item">
                      <a class="page-scroll" href="#why">Kenapa Kami</a>
                    </li>
                  </ul>
                </div>
                <!-- navbar collapse -->

                <div class="header-action d-flex align-items-center gap-2 ms-3">
                  <?php if ($is_logged_in): ?>
                    <span class="me-2 text-muted d-none d-xl-inline">Halo, <b><?= htmlspecialchars($current_user['name']) ?></b></span>
                    <a href="app.php" class="main-btn btn-hover">Ke Dashboard</a>
                  <?php else: ?>
                    <a href="app.php?tab=register" class="main-btn btn-hover">Masuk</a>
                  <?php endif; ?>
                </div>
              </nav>
              <!-- navbar -->
            </div>
          </div>
          <!-- row -->
        </div>
        <!-- container -->
      </div>
      <!-- navbar area -->
    </header>
    <!-- ======== header end ======== -->

    <!-- ======== hero-section start ======== -->
    <section id="home" class="hero-section">
      <div class="container">
        <div class="row align-items-center position-relative">
          <div class="col-lg-6">
            <div class="hero-content">
              <h1 class="wow fadeInUp" data-wow-delay=".4s">
                Kelola Sisa Jejak Keuangan Anda Secara Cerdas
              </h1>
              <p class="wow fadeInUp" data-wow-delay=".6s">
                Pantau saldo dari berbagai dompet, tetapkan anggaran terkendali yang aman,
                catat pengeluaran harian, dan evaluasi kesehatan finansial Anda.
              </p>
              <?php if ($is_logged_in): ?>
                <a href="app.php" class="main-btn border-btn btn-hover wow fadeInUp" data-wow-delay=".6s">Buka Dashboard Saya</a>
              <?php else: ?>
                <a href="app.php?tab=register" class="main-btn border-btn btn-hover wow fadeInUp" data-wow-delay=".6s">Mulai Sekarang</a>
              <?php endif; ?>
              <a href="#features" class="scroll-bottom">
                <i class="lni lni-arrow-down"></i>
              </a>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="hero-img wow fadeInUp" data-wow-delay=".5s">
              <img src="assets/img/hero/hero-img-4.png" alt="Ilustrasi Dashboard Keuangan SisaJejakUang" />
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- ======== hero-section end ======== -->

    <!-- ======== feature-section start ======== -->
    <section id="features" class="feature-section pt-120">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-4 col-md-8 col-sm-10">
            <div class="single-feature">
              <div class="icon">
                <i class="lni lni-wallet"></i>
              </div>
              <div class="content">
                <h3>Multi-Buku Tabungan</h3>
                <p>
                  Pisahkan pencatatan saldo Anda ke beberapa dompet digital, rekening bank,
                  maupun uang tunai secara terstruktur.
                </p>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-8 col-sm-10">
            <div class="single-feature">
              <div class="icon">
                <i class="lni lni-target"></i>
              </div>
              <div class="content">
                <h3>Validasi Saldo & Anggaran</h3>
                <p>
                  Aturan sistem ketat memastikan batas limit anggaran yang Anda buat
                  tidak boleh melebihi saldo aktif tabungan.
                </p>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-8 col-sm-10">
            <div class="single-feature">
              <div class="icon">
                <i class="lni lni-stats-up"></i>
              </div>
              <div class="content">
                <h3>Financial Health Score</h3>
                <p>
                  Pantau kualitas keuangan Anda secara langsung lewat kalkulasi rasio
                  Kebutuhan (Needs) vs Keinginan (Wants).
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- ======== feature-section end ======== -->

    <!-- ======== about-section start ======== -->
    <section id="about" class="about-section pt-150">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-xl-6 col-lg-6">
            <div class="about-img">
              <img src="assets/img/about/about-1.png" alt="" class="w-80" />
              <img src="assets/img/about/about-left-shape.svg" alt="" class="shape shape-1" />
              <img src="assets/img/about/left-dots.svg" alt="" class="shape shape-2" />
            </div>
          </div>
          <div class="col-xl-6 col-lg-6">
            <div class="about-content">
              <div class="section-title mb-30">
                <h2 class="mb-25 wow fadeInUp" data-wow-delay=".2s">
                  Masa Depan Manajemen Keuangan di Tangan Anda
                </h2>
                <p class="wow fadeInUp" data-wow-delay=".4s">
                  Berhentilah menebak ke mana uang Anda pergi. Dapatkan kendali penuh
                  terhadap saku bulanan Anda dengan instan bersama SisaJejakUang —
                  aplikasi pencatatan keuangan dinamis yang membantu Anda mengelola saldo,
                  batas anggaran belanja, dan melacak prioritas kebutuhan vs keinginan.
                </p>
              </div>
              <a href="app.php" class="main-btn btn-hover border-btn wow fadeInUp" data-wow-delay=".6s">Coba Sekarang</a>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- ======== about-section end ======== -->

    <!-- ======== about2-section start ======== -->
    <section class="about-section pt-150">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-xl-6 col-lg-6">
            <div class="about-content">
              <div class="section-title mb-30">
                <h2 class="mb-25 wow fadeInUp" data-wow-delay=".2s">
                  Kontrol Penuh atas Kesehatan Finansial Anda
                </h2>
                <p class="wow fadeInUp" data-wow-delay=".4s">
                  Setiap transaksi tercatat otomatis, setiap anggaran terjaga ketat.
                  Berikut yang Anda dapatkan saat mulai mencatat bersama kami.
                </p>
              </div>
              <ul>
                <li>Pantau saldo riil yang diperbarui secara otomatis per transaksi</li>
                <li>Notifikasi visual instan saat budget pos belanja tersisa kurang dari 10%</li>
                <li>Analisis audit logs yang transparan untuk pemantauan keamanan database</li>
              </ul>
              <a href="app.php" class="main-btn btn-hover border-btn wow fadeInUp" data-wow-delay=".6s">Pelajari Lebih Lanjut</a>
            </div>
          </div>
          <div class="col-xl-6 col-lg-6 order-first order-lg-last">
            <div class="about-img-2">
              <img src="assets/img/about/about-2.png" alt="" class="w-100" />
              <img src="assets/img/about/about-right-shape.svg" alt="" class="shape shape-1" />
              <img src="assets/img/about/right-dots.svg" alt="" class="shape shape-2" />
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- ======== about2-section end ======== -->

    <!-- ======== feature-extended-section start ======== -->
    <section id="why" class="feature-extended-section pt-100">
      <div class="feature-extended-wrapper">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-xxl-5 col-xl-6 col-lg-8 col-md-9">
              <div class="section-title text-center mb-60">
                <h2 class="mb-25 wow fadeInUp" data-wow-delay=".2s">Fitur Unggulan SisaJejakUang</h2>
                <p class="wow fadeInUp" data-wow-delay=".4s">
                  Kami menyediakan modul keuangan esensial yang dirancang secara khusus
                  untuk menjaga stabilitas saku Anda dari risiko pengeluaran berlebih.
                </p>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-lg-4 col-md-6">
              <div class="single-feature-extended">
                <div class="icon">
                  <i class="lni lni-wallet"></i>
                </div>
                <div class="content">
                  <h3>Multi-Buku Tabungan</h3>
                  <p>
                    Pisahkan pencatatan saldo Anda ke beberapa dompet digital, rekening
                    bank, maupun uang tunai secara terstruktur.
                  </p>
                </div>
              </div>
            </div>
            <div class="col-lg-4 col-md-6">
              <div class="single-feature-extended">
                <div class="icon">
                  <i class="lni lni-target"></i>
                </div>
                <div class="content">
                  <h3>Validasi Saldo & Anggaran</h3>
                  <p>
                    Aturan sistem ketat memastikan batas limit anggaran yang Anda buat
                    tidak boleh melebihi saldo aktif tabungan.
                  </p>
                </div>
              </div>
            </div>
            <div class="col-lg-4 col-md-6">
              <div class="single-feature-extended">
                <div class="icon">
                  <i class="lni lni-stats-up"></i>
                </div>
                <div class="content">
                  <h3>Financial Health Score</h3>
                  <p>
                    Pantau kualitas keuangan Anda secara langsung lewat kalkulasi rasio
                    Kebutuhan (Needs) vs Keinginan (Wants).
                  </p>
                </div>
              </div>
            </div>
            <!-- <div class="col-lg-4 col-md-6">
              <div class="single-feature-extended">
                <div class="icon">
                  <i class="lni lni-camera"></i>
                </div>
                <div class="content">
                  <h3>Simulasi Pindai Struk (OCR)</h3>
                  <p>
                    Catat pengeluaran Anda secara kilat melalui sistem simulasi pembaca
                    teks struk digital secara otomatis.
                  </p>
                </div>
              </div>
            </div> -->
            <div class="col-lg-6 col-md-6">
              <div class="single-feature-extended">
                <div class="icon">
                  <i class="lni lni-lock"></i>
                </div>
                <div class="content">
                  <h3>Log Audit Aktivitas</h3>
                  <p>
                    Sistem audit log bawaan yang merekam semua aktivitas finansial
                    krusial demi menjamin transparansi perubahan data.
                  </p>
                </div>
              </div>
            </div>
            <div class="col-lg-6 col-md-6">
              <div class="single-feature-extended">
                <div class="icon">
                  <i class="lni lni-trash-can"></i>
                </div>
                <div class="content">
                  <h3>Privasi Akun Mandiri</h3>
                  <p>
                    Kontrol privasi penuh dengan fitur hapus akun mandiri berantai yang
                    membersihkan semua data Anda dari sistem database.
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- ======== feature-extended-section end ======== -->

    <!-- ======== subscribe-section start ======== -->
    <section id="contact" class="subscribe-section pt-120">
      <div class="container">
        <div class="subscribe-wrapper img-bg">
          <div class="row align-items-center">
            <div class="col-xl-7 col-lg-7">
              <div class="section-title mb-15">
                <h2 class="text-white mb-25">Siap Mengendalikan Keuangan Anda?</h2>
                <p class="text-white pr-5">
                  Buat akun gratis dan mulai catat saldo, anggaran, serta pengeluaran
                  harian Anda hari ini juga.
                </p>
              </div>
            </div>
            <div class="col-xl-5 col-lg-5">
              <?php if ($is_logged_in): ?>
                <a href="app.php" class="main-btn btn-hover">Ke Dashboard Saya</a>
              <?php else: ?>
                <a href="app.php?tab=register" class="main-btn btn-hover">Daftar Gratis Sekarang</a>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- ======== subscribe-section end ======== -->

    <!-- ======== footer start ======== -->
    <footer class="footer">
      <div class="container">
        <div class="widget-wrapper">
          <div class="row">
            <div class="col-xl-4 col-lg-4 col-md-6">
              <div class="footer-widget">
                <div class="logo mb-30">
                  <a href="index.php">
                    <span class="fs-4 fw-bold text-white">SisaJejakUang</span>
                  </a>
                </div>
                <p class="desc mb-30 text-white">
                  Aplikasi pelacakan finansial dinamis dan sistem anggaran saldo
                  untuk mengelola keuangan pribadi Anda secara cerdas.
                </p>
                <ul class="socials">
                  <li><a href="javascript:void(0)"><i class="lni lni-facebook-filled"></i></a></li>
                  <li><a href="javascript:void(0)"><i class="lni lni-twitter-filled"></i></a></li>
                  <li><a href="javascript:void(0)"><i class="lni lni-instagram-filled"></i></a></li>
                  <li><a href="javascript:void(0)"><i class="lni lni-linkedin-original"></i></a></li>
                </ul>
              </div>
            </div>

            <div class="col-xl-2 col-lg-2 col-md-6">
              <div class="footer-widget">
                <h3>Menu Navigasi</h3>
                <ul class="links">
                  <li><a href="#home">Beranda</a></li>
                  <li><a href="#features">Fitur</a></li>
                  <li><a href="#about">Tentang Kami</a></li>
                </ul>
              </div>
            </div>

            <div class="col-xl-3 col-lg-3 col-md-6">
              <div class="footer-widget">
                <h3>Fitur Sistem</h3>
                <ul class="links">
                  <li><a href="app.php">Buku Tabungan</a></li>
                  <li><a href="app.php">Manajemen Anggaran</a></li>
                  <li><a href="app.php">Mutasi Transaksi</a></li>
                  <!-- <li><a href="app.php">Scan Struk (OCR)</a></li> -->
                </ul>
              </div>
            </div>

            <div class="col-xl-3 col-lg-3 col-md-6">
              <div class="footer-widget">
                <h3>Tersedia Untuk</h3>
                <ul class="links">
                  <li><a href="javascript:void(0)">Browser Pilihan Anda</a></li>
                  <!-- <li><a href="javascript:void(0)">Mozilla Firefox</a></li> -->
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </footer>
    <!-- ======== footer end ======== -->

    <!-- ======== scroll-top ======== -->
    <a href="#" class="scroll-top btn-hover">
      <i class="lni lni-chevron-up"></i>
    </a>

    <!-- ======== JS here ======== -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/wow.min.js"></script>
    <script src="assets/js/main.js"></script>
  </body>
</html>
