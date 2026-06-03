@extends('layouts.user')
@section('container')

 <!-- Gallery Section -->
 <section id="gallery" class="gallery section light-background">

      <div class="container section-title" data-aos="fade-up">
        <h2>User / Home</h2>
        <p><span>Selamat Datang, </span> <span class="description-title">{{ auth()->user()->name }}<br></span></p>
      </div>

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
          "centeredSlides": true,
          "pagination": {
            "el": ".swiper-pagination",
            "type": "bullets",
            "clickable": true
          },
          "breakpoints": {
            "320": {
              "slidesPerView": 1,
              "spaceBetween": 0
            },
            "768": {
              "slidesPerView": 3,
              "spaceBetween": 20
            },
            "1200": {
              "slidesPerView": 5,
              "spaceBetween": 20
            }
          }
        }
      </script>
      <div class="swiper-wrapper align-items-center">
        @foreach($vendor_offers as $vendor_offer)
        <div class="swiper-slide">
        <h3>
          Wedding Organizer Offering by:  
          <span>
              <a href="{{ route('porto.vendor', ['vendorName' => $vendor_offer->user->name]) }}">
                  {{ $vendor_offer->user->name }}
              </a>
          </span>
        </h3>

          <div class="price align-self-start">{{$vendor_offer->jenispenawaran}}</div>
          <div class="price align-self-start">{{$vendor_offer->budget}}</div>
          <p class="description">
            {{$vendor_offer->catatan}}
          </p>
          <a href="{{ route('user.chatbox', $vendor_offer->user_id) }}" class="btn btn-info">Chat {{ $vendor_offer->user->name }}</a>
          <form action="{{ route('user.home.pdf') }}" method="POST">
              @csrf
              <input type="hidden" name="jenispenawaran" value="{{ $vendor_offer->jenispenawaran }}">
              <input type="hidden" name="budget" value="{{ $vendor_offer->budget }}">
              <input type="hidden" name="catatan" value="{{ $vendor_offer->catatan }}">
              <input type="hidden" name="user_id" value="{{ $vendor_offer->user_id }}"> 
              <button type="submit" class="btn btn-primary">Download PDF</button>
          </form>
        </div>
        @endforeach

      </div>
      <div class="swiper-pagination"></div>
    </div>

  <div class="row g-0" data-aos="fade-up" data-aos-delay="100">
    <div class="container d-flex justify-content-center align-items-center reservation-form-bg" data-aos="fade-up" data-aos-delay="200" style="padding:2rem;">
      <form action="{{ route('user.testimony.update') }}" method="post" role="form" class="w-100" enctype="multipart/form-data">
      @csrf  
      <div class="row gy-4">
        <div class="col-12">
          <label for="testimony">Testimony</label>
          <textarea class="form-control" name="testimony" id="testimony" required></textarea>
        </div>
        <div class="col-12">
          <label for="rating">Rating</label>
          <select class="form-control" name="rating" id="rating" required>
            <option value="1">1 Star</option>
            <option value="2">2 Stars</option>
            <option value="3">3 Stars</option>
            <option value="4">4 Stars</option>
            <option value="5">5 Stars</option>
          </select>
        </div>
      </div>
      <div class="text-center mt-3">
        <button class="publisher-btn text-warning" type="submit" style="width: 50%; padding:1rem">Simpan</button>
      </div>
    </form>

    </div><!-- End Reservation Form -->




</section><!-- /Gallery Section -->


@endsection