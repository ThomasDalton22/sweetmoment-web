@extends('layouts.guest')
@section('container')
    <!-- Hero Section -->
    <section id="home" class="hero section light-background">

        <div class="container">
            <div class="row gy-4 justify-content-center justify-content-lg-between">
                <div class="col-lg-5 order-2 order-lg-1 d-flex flex-column justify-content-center">
                    <p data-aos="fade-up" data-aos-delay="100">Halo, Selamat Datang</p>
                    <h1 data-aos="fade-up">Buatlah Acaramu<br>Dengan Meriah</h1>
                    <div class="d-flex" data-aos="fade-up" data-aos-delay="200" style="margin-top:2 rem; gap:1rem">
                        <a href="{{ route('auth') }}" class="btn-get-started">Masuk</a>
                    </div>
                </div>

                <div class="col-lg-5 order-1 order-lg-2 hero-img" data-aos="zoom-out">
                    <img src="/img/home.png" class="img-fluid animated" alt="">
                </div>
            </div>
        </div>

    </section><!-- /Hero Section -->


    <!-- Chefs Section -->
    <section id="news" class="chefs section">

        <!-- Section Title -->
        <div class="container section-title" data-aos="fade-up">
            <h2>News</h2>
            <p><span>Berita</span> <span class="description-title">SweetMoments<br></span></p>
        </div><!-- End Section Title -->

        <div class="container">

            <div class="row gy-4">
                @foreach ($news as $berita)
                    <div class="col-lg-4 d-flex align-items-stretch" data-aos="fade-up" data-aos-delay="100">

                        <div class="team-member">
                            <div class="member-img">
                                <img src="{{ asset($berita->image) }}" class="img-fluid" alt="Image">
                                <div class="social">
                                    <!-- <a href=""><i class="bi bi-twitter-x"></i></a>
                      <a href=""><i class="bi bi-facebook"></i></a>
                      <a href=""><i class="bi bi-instagram"></i></a>
                      <a href=""><i class="bi bi-linkedin"></i></a> -->
                                </div>
                            </div>
                            <div class="member-info">
                                <h4>{{ $berita->title }}</h4> <!-- Menampilkan Judul Berita -->
                                <span>{{ \Carbon\Carbon::parse($berita->created_at)->diffForHumans() }}</span>
                                <!-- Menampilkan waktu relatif -->
                                <p>{{ Str::limit($berita->description, 100) }} <a href="{{ route('news', $berita->id) }}"
                                        style="color:#ffffff">Read More ...</a></p> <!-- Deskripsi singkat dengan link -->
                            </div>
                        </div>
                    </div><!-- End Chef Team Member -->
                @endforeach
            </div>

        </div>

    </section><!-- /Chefs Section -->


    <!-- Testimonials Section -->
    <section id="testimony" class="testimonials section light-background">
        <!-- Section Title -->
        <div class="container section-title" data-aos="fade-up">
            <h2> Testimony</h2>
            <p>Apa Yang Mereka <span class="description-title">Katakan Tentang Kami</span></p>
        </div><!-- End Section Title -->

        <div class="container" data-aos="fade-up" data-aos-delay="100">

            <div class="swiper init-swiper">
                <script type="application/json" class="swiper-config">
            {
                "loop": true,
                "speed": 600,
                "autoplay": {
                "delay": 5000
                },
                "slidesPerView": "auto",
                "pagination": {
                "el": ".swiper-pagination",
                "type": "bullets",
                "clickable": true
                }
            }
            </script>
                <div class="swiper-wrapper">

                    @foreach ($testimony as $test)
                        <div class="swiper-slide">
                            <div class="testimonial-item">
                                <div class="row gy-4 justify-content-center">
                                    <div class="col-lg-6">
                                        <div class="testimonial-content">
                                            <p>
                                                <i class="bi bi-quote quote-icon-left"></i>
                                                <span>{{ $test->testimony }}</span>
                                                <i class="bi bi-quote quote-icon-right"></i>
                                            </p>
                                            <h3>{{ $test->user }}</h3>
                                            <div class="stars">
                                                @for ($i = 1; $i <= $test->rating; $i++)
                                                    <i class="bi bi-star-fill"></i>
                                                @endfor
                                                @for ($i = $test->rating + 1; $i <= 5; $i++)
                                                    <i class="bi bi-star"></i>
                                                @endfor
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="swiper-pagination"></div>
            </div>

        </div>

    </section><!-- /Testimonials Section -->




    <!-- About Section -->
    <section id="about" class="about section">

        <!-- Section Title -->
        <div class="container section-title" data-aos="fade-up">
            <h2>About</h2>
            <p><span>Tentang</span> <span class="description-title">SweetMoments</span></p>
        </div><!-- End Section Title -->

        <div class="container">

            <div class="row gy-4">
                <div class="col-lg-7" data-aos="fade-up" data-aos-delay="100">
                    <img src="img/aout.jpeg" class="img-fluid mb-4" alt="">

                </div>
                <div class="col-lg-5" data-aos="fade-up" data-aos-delay="250">
                    <div class="content ps-0 ps-lg-5">
                        <p>
                            SweetMoments adalah platform digital bagi anda yang ingin mencari paket wedding organizer dengan
                            vendor terlengkap dan penawaran terbaik di Surabaya.
                        </p>
                        <ul>
                            <li><i class="bi bi-check-circle-fill"></i> <span>Penawaran paket dengan harga terbaik.</span>
                            </li>
                            <li><i class="bi bi-check-circle-fill"></i> <span>Vendor yang lengkap dan terpercaya.</span>
                            </li>
                            <li><i class="bi bi-check-circle-fill"></i> <span>Platform yang mudah dan simpel dalam mencari
                                    kebutuhan pernikahan anda.</span></li>
                        </ul>
                        <p>
                            Kami hadir bagi Anda yang ingin melaksanakan pernikahan dan bingung mencari paket pernikahan
                            yang aman, murah, dan terpercaya.
                            Buatlah pernikahanmu menjadi moment terindah yang takan terlupakan !
                        </p>
                    </div>
                </div>
            </div>

        </div>

    </section><!-- /About Section -->

    <!-- Contact Section -->
    <section id="contact" class="contact section light-background">

        <!-- Section Title -->
        <div class="container section-title" data-aos="fade-up">
            <h2>Contact</h2>
            <p><span>Butuh Bantuan?</span> <span class="description-title">Hubungi Kami</span></p>
        </div><!-- End Section Title -->

        <div class="container" data-aos="fade-up" data-aos-delay="100">


            <div class="row gy-4">

                <div class="col-md-6">
                    <div class="info-item d-flex align-items-center" data-aos="fade-up" data-aos-delay="200">
                        <i class="icon bi bi-geo-alt flex-shrink-0"></i>
                        <div>
                            <h3 style="color:#bba016">Alamat</h3>
                            <p style="color:#bba016">Jl Rusunama Tambak Sawah B-302 Waru-Sidoarjo</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="info-item d-flex align-items-center" data-aos="fade-up" data-aos-delay="300">
                        <a href="https://www.instagram.com/sweetmo.official/"><i
                                class="icon bi bi-instagram flex-shrink-0"></i></a>
                        <div>
                            <h3 style="color:#bba016">Instagram</h3>
                            <p style="color:#bba016">SweetMoments</p>
                        </div>
                    </div>
                </div><!-- End Info Item -->

                <div class="col-md-6">
                    <div class="info-item d-flex align-items-center" data-aos="fade-up" data-aos-delay="400">
                        <a href="https://x.com/sweetmomentsofc?t=Q8BfS8nSE-JUuj0Qff24EA&s=09"><i
                                class="icon bi bi-twitter flex-shrink-0"></i></a>
                        <div>
                            <a href="https://x.com/sweetmomentsofc?t=Q8BfS8nSE-JUuj0Qff24EA&s=09">
                                <h3 style="color:#bba016">Twitter / X</h3>
                            </a>
                            <p style="color:#bba016">SweetMoments</p>
                        </div>
                    </div>
                </div><!-- End Info Item -->

                <div class="col-md-6">
                    <div class="info-item d-flex align-items-center" data-aos="fade-up" data-aos-delay="500">
                        <a href="https://www.facebook.com/share/19wAE5nSdH/?mibextid=qi2Omg"><i
                                class="icon bi bi-facebook flex-shrink-0"></i></a>
                        <div>
                            <h3 style="color:#bba016">Facebook</h3>
                            <p style="color:#bba016">SweetMoments</p>
                        </div>
                    </div>
                </div><!-- End Info Item -->

            </div>
        </div>

    </section><!-- /Contact Section -->
@endsection
