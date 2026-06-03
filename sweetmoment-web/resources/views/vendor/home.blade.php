@extends('layouts.vendor')

@section('container')
<!-- Gallery Section -->
<section id="gallery" class="gallery section light-background">
    <div class="container section-title" data-aos="fade-up">
        <h2>Vendor / Home</h2>
        <p><span>Selamat Datang, </span> <span class="description-title">{{ auth()->user()->name }}<br></span></p>
    </div>

    <!-- Wedding Section -->
    <div class="container" data-aos="fade-up" data-aos-delay="100">
      <div class="centered-heading">
          <h2>Wedding Organizer</h2>
      </div>
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
                @foreach($weddings as $wedding)
                    <div class="swiper-slide">
                        <h3>Wedding Organizer Offering by: <span>{{ $wedding->user->name }}</span></h3>
                        <div class="price align-self-start">{{ $wedding->budget }}</div>
                        <p class="description">{{ $wedding->catatan }}</p>
                        <a href="{{ route('user.chatbox', $wedding->user_id) }}" class="btn btn-info">Chat {{ $wedding->user->name }}</a>
                        <form action="{{ route('vendor.home.wopdf') }}" method="POST">
                            @csrf
                            <input type="hidden" name="date" value="{{ $wedding->date }}">
                            <input type="hidden" name="budget" value="{{ $wedding->budget }}">
                            <input type="hidden" name="catatan" value="{{ $wedding->catatan }}">
                            <input type="hidden" name="user_id" value="{{ $wedding->user_id }}"> 
                            <button type="submit" class="btn btn-primary">Download PDF</button>
                        </form>
                    </div>
                @endforeach
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>

    <!-- Party Section -->
    <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="centered-heading">
            <h2>Party Organizer</h2>
        </div>
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
                @foreach($parties as $party)
                    <div class="swiper-slide">
                        <h3>Party Organizer Offering by: <span>{{ $party->user->name }}</span></h3>
                        <div class="price align-self-start">{{ $party->budget }}</div>
                        <p class="description">{{ $party->catatan }}</p>
                        <a href="{{ route('user.chatbox', $party->user_id) }}" class="btn btn-info">Chat {{ $party->user->name }}</a>
                        <form action="{{ route('vendor.home.popdf') }}" method="POST">
                            @csrf
                            <input type="hidden" name="date" value="{{ $party->date }}">
                            <input type="hidden" name="budget" value="{{ $party->budget }}">    
                            <input type="hidden" name="catatan" value="{{ $party->catatan }}">
                            <input type="hidden" name="user_id" value="{{ $party->user_id }}"> 
                            <button type="submit" class="btn btn-primary">Download PDF</button>
                        </form>
                    </div>
                @endforeach
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>

</section><!-- /Gallery Section -->
@endsection
