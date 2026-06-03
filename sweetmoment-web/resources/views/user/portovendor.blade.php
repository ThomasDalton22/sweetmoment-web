@extends('layouts.user')
@section('container')

 <!-- Events Section -->
    <section id="events" class="events section">

      <div class="container-fluid" data-aos="fade-up" data-aos-delay="100">

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
              },
              "breakpoints": {
                "320": {
                  "slidesPerView": 1,
                  "spaceBetween": 40
                },
                "1200": {
                  "slidesPerView": 3,
                  "spaceBetween": 1
                }
              }
            }
          </script>
          <div class="swiper-wrapper">

            @foreach ($portfolios as $portfolio)
                <div class="swiper-slide event-item d-flex flex-column justify-content-end" 
                    style="background-image: url({{ asset($portfolio->image) }}); 
                        background-position: center; 
                        background-repeat: no-repeat; 
                        background-size: contain;">
                    <h3>Portofolio by: {{ $portfolio->vendor ?? '$0' }} </h3>
                    <p class="description">
                        {{ $portfolio->description ?? 'No details available.' }}
                    </p>
                </div>
            @endforeach

          </div>
          <div class="swiper-pagination"></div>
        </div>

      </div>

    </section><!-- /Events Section -->

@endsection