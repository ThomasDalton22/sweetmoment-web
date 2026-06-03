<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>SweetMoments</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <script type="text/javascript"
		src="https://app.stg.midtrans.com/snap/snap.js"
    data-client-key="SET_YOUR_CLIENT_KEY_HERE"></script>

  <!-- Favicons -->
  <link href="{{ asset('img/logo.png') }}" rel="icon">
  <link href="img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Amatic+SC:wght@400;700&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <script src="{{ asset('https://cdn.jsdelivr.net/npm/sweetalert2@11') }}"></script>
  <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('vendor/aos/aos.css') }}" rel="stylesheet">
  <link href="{{ asset('vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
  <link href="{{ asset('vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">

  <link href="{{ asset('css/main.css') }}" rel="stylesheet">

</head>

<body class="index-page">



<header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container position-relative d-flex align-items-center justify-content-between">

      <a href="{{ route('vendor.home') }}" class="logo d-flex align-items-center me-auto me-xl-0">
        <img src="{{ asset('img/logo.png') }}" alt="">

      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="{{ route('vendor.profile') }}" ><i class="bi bi-person-circle"></i><br></a></li>
          <li><a href="{{ route('vendor.chat') }}" ><i class="bi bi-chat-square-text"></i><br></a></li>
          <li><a href="{{ route('vendor.home') }}">Home<br></a></li>
          <li><a href="{{ route('vendor.customer') }}">Penawaran Customer<br></a></li>
          <li><a href="{{ route('vendor.riwayat') }}">Riwayat </a></li>
          <li><a href="{{ route('vendor.portfolio') }}">Portofolio </a></li>
          <li><a href="{{ route('logout') }}" ><i class="bi bi-box-arrow-right"></i><br></a></li>

        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>
    </div>
  </header>

  <main class="main">

@yield ('container')
@include('sweetalert::alert')





</main>

<footer id="footer" class="footer dark-background">

  <div class="container">
    <div class="row gy-3">
      <div class="col-lg-3 col-md-6 d-flex">
        <i class="bi bi-geo-alt icon"></i>
        <div class="address">
          <h4>Alamat</h4>
          <p>Jl Ikan Arwana J Nomor 02</p>
          <p></p>
          <p></p>
        </div>

      </div>

      <div class="col-lg-3 col-md-6 d-flex">
        <i class="bi bi-telephone icon"></i>
        <div>
          <h4>Kontak</h4>
          <p>
            <strong>WA:</strong> <span>082140034110</span><br>
            <strong></strong> <span></span><br>
          </p>
        </div>
      </div>

      <div class="col-lg-3 col-md-6 d-flex">
        <i class="bi bi-clock icon"></i>
        <div>
          <h4>Jam Operasional</h4>
          <p>
            <strong>Senin-Sabtu:</strong> <span>11AM - 23PM</span><br>
            <strong>Minggu</strong>: <span>Tutup</span>
          </p>
        </div>
      </div>

      <div class="col-lg-3 col-md-6">
        <h4>Temui Kami</h4>
        <div class="social-links d-flex">
          <a href="#" class="twitter"><i class="bi bi-twitter-x"></i></a>
          <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
          <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
          <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a>
        </div>
      </div>

    </div>
  </div>

  <div class="container copyright text-center mt-4">
    <p>© <span>Copyright</span> <strong class="px-1 sitename">SweetMoments</strong> <span>All Rights Reserved</span></p>
    <div class="credits">

    </div>
  </div>

</footer>

<!-- Scroll Top -->
<a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

<!-- Preloader -->
<div id="preloader"></div>

<!-- Vendor JS Files -->
<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('vendor/php-email-form/validate.js') }}"></script>
<script src="{{ asset('vendor/aos/aos.js') }}"></script>
<script src="{{ asset('vendor/glightbox/js/glightbox.min.js') }}"></script>
<script src="{{ asset('vendor/purecounter/purecounter_vanilla.js') }}"></script>
<script src="{{ asset('vendor/swiper/swiper-bundle.min.js') }}"></script>

<!-- Main JS File -->
<script src="{{ asset('js/main.js') }}"></script>

</body>

</html>



