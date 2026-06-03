{{-- Guest --}}

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>SweetMoments</title>
    <meta name="description" content="">
    <meta name="keywords" content="">

    <!-- Favicons -->
    <link href="{{ asset('img/logo.png') }}" rel="icon">
    <link href="img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Amatic+SC:wght@400;700&display=swap"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
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

            <a href="#home" class="logo d-flex align-items-center me-auto me-xl-0">
                <img src="{{ asset('img/logo.png') }}" alt="">

            </a>

            <nav id="navmenu" class="navmenu">
                <ul>
                    <li><a href="#home" class="active">Home<br></a></li>
                    <li><a href="#news">News</a></li>
                    <li><a href="#testimony">Testimony</a></li>
                    <li><a href="#about">About</a></li>
                    <li><a href="#contact">Contact</a></li>

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
                        <p>Jl Rusunama Tambak Sawah B-302 Waru-Sidoarjo</p>
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
                            <strong>Senin-Jumat:</strong> <span>08.00 - 17.00 WIB</span><br>
                            <strong>Sabtu</strong>: <span>08.00 - 12.00 WIB</span>
                        </p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <h4>Temui Kami</h4>
                    <div class="social-links d-flex">
                        <a href="https://x.com/sweetmomentsofc?t=Q8BfS8nSE-JUuj0Qff24EA&s=09" class="twitter"><i
                                class="bi bi-twitter-x"></i></a>
                        <a href="https://www.facebook.com/share/19wAE5nSdH/?mibextid=qi2Omg" class="facebook"><i
                                class="bi bi-facebook"></i></a>
                        <a href="https://www.instagram.com/sweetmo.official/" class="instagram"><i
                                class="bi bi-instagram"></i></a>
                        <a href="https://www.tiktok.com/@sweetmoment.ofc" class="linkedin"><i
                                class="bi bi-tiktok"></i></a>
                    </div>
                </div>

            </div>
        </div>

        <div class="container copyright text-center mt-4">
            <p>© <span>Copyright</span> <strong class="px-1 sitename">SweetMoments</strong> <span>All Rights
                    Reserved</span></p>
            <div class="credits">

            </div>
        </div>

    </footer>

    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Preloader -->
    <div id="preloader"></div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const buttons = document.querySelectorAll('.btn-reaction');

            // Fungsi untuk memeriksa apakah reaksi sudah diberikan
            const isReacted = sessionStorage.getItem('reacted') === 'true';

            // Disable tombol jika sudah ada reaksi
            if (isReacted) {
                buttons.forEach(button => button.disabled = true);
            }

            buttons.forEach(button => {
                button.addEventListener('click', () => {
                    const reaction = button.getAttribute('data-reaction');
                    const newsId = button.getAttribute('data-id');

                    fetch(`/news/${newsId}/react`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                reaction
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Update jumlah likes/dislikes
                                document.getElementById(`likes-count-${newsId}`).textContent =
                                    data.likes;
                                document.getElementById(`dislikes-count-${newsId}`)
                                    .textContent = data.dislikes;

                                // Tandai bahwa reaksi sudah diberikan
                                sessionStorage.setItem('reacted', 'true');

                                // Disable tombol setelah reaksi
                                buttons.forEach(button => button.disabled = true);
                            } else {
                                alert('Gagal memberikan reaksi.');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                });
            });
        });
    </script>


    <!-- Vendor JS Files -->
    <script src="{{ asset('https://cdn.jsdelivr.net/npm/sweetalert2@11') }}"></script>
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
