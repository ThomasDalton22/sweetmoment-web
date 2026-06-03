@extends('layout')

@section('content')
    <main id="mainContent" style="min-height: 70vh">
        <!-- Hero Section -->
        <section class="hero-section">
            <div class="container">
                <div class="hero-content">
                    <h1>Your Perfect Wedding Awaits</h1>
                    <p>Discover the best wedding vendors in Indonesia. From venues to photographers, make your special
                        day unforgettable.</p>
                    <button class="btn btn-light btn-lg" onclick="showVendors()">Explore Vendors</button>
                </div>
            </div>
        </section>

        <!-- Banner Section -->
        <section class="banner-section">
            <div class="container">
                <div id="bannerCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner" id="bannerContent">
                        @if ($banners && count($banners) > 0)
                            @foreach ($banners as $index => $banner)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <div class="banner-card">
                                                <img src="{{ asset('storage/' . $banner->image) }}"
                                                    alt="{{ $banner->title }}" class="banner-image">
                                                <div class="banner-content">
                                                    <h5>{{ $banner->title }}</h5>
                                                    <p>{{ $banner->subtitle }}</p>
                                                    @if ($banner->link)
                                                        <a href="{{ $banner->link }}" class="btn btn-light btn-sm">Learn
                                                            More</a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <!-- Default banners if none in database -->
                            <div class="carousel-item active">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="banner-card">
                                            <img src="https://images.unsplash.com/photo-1519741497674-611481863552?w=800"
                                                alt="Wedding Banner" class="banner-image">
                                            <div class="banner-content">
                                                <h5>Special Wedding Package</h5>
                                                <p>Up to 30% discount for complete wedding services</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="banner-card">
                                            <img src="https://images.unsplash.com/photo-1583939003579-730e3918a45a?w=800"
                                                alt="Photography Banner" class="banner-image">
                                            <div class="banner-content">
                                                <h5>Premium Photography</h5>
                                                <p>Capture your precious moments with top photographers</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        <!-- Categories Section -->
        <section class="categories-section">
            <div class="container">
                <h2 class="text-center mb-4">Wedding Services</h2>
                <div class="row g-3" id="categoriesGrid">
                    @if ($categories && count($categories) > 0)
                        @foreach ($categories as $category)
                            <div class="col-6 col-md-3 col-lg-2" onclick="showCategory('{{ $category->slug }}')">
                                <div class="category-card">
                                    <i class="bi {{ $category->icon ?? 'bi-star' }} category-icon"></i>
                                    <h6>{{ $category->name }}</h6>
                                    <small class="text-muted">{{ $category->vendor_profiles_count ?? 0 }}
                                        vendors</small>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <!-- Default categories if none in database -->
                        <div class="col-6 col-md-3 col-lg-2" onclick="showCategory('venue')">
                            <div class="category-card">
                                <i class="bi bi-buildings category-icon"></i>
                                <h6>Venue</h6>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 col-lg-2" onclick="showCategory('photography')">
                            <div class="category-card">
                                <i class="bi bi-camera category-icon"></i>
                                <h6>Photography</h6>
                            </div>
                        </div>

                        <!-- Add other default categories as needed -->
                    @endif
                </div>
            </div>
        </section>

        <!-- Vendor Recommendations -->
        <section class="vendor-recommendations">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Recommended Vendors</h2>
                    <a href="#" onclick="showVendors()" class="text-decoration-none">View All</a>
                </div>
                <div class="row" id="vendorList">
                    @if ($vendors && count($vendors) > 0)
                        @foreach ($vendors as $vendor)
                            <div class="col-md-6 col-lg-3 mb-4">
                                <div class="vendor-card" onclick="showVendorDetail({{ $vendor->id }})">
                                    <div class="vendor-image-container">
                                        @php
                                            $featuredImage =
                                                $vendor->portfolioImages->where('is_featured', true)->first() ??
                                                $vendor->portfolioImages->first();
                                            $imageSrc = $featuredImage
                                                ? asset('storage/' . $featuredImage->image)
                                                : asset('storage/placeholder.jpg');
                                        @endphp
                                        <img src="{{ $imageSrc }}" alt="{{ $vendor->business_name }}"
                                            class="vendor-image">
                                        <div class="vendor-badges">
                                            @if ($vendor->is_verified)
                                                <span class="badge bg-success">✓ Verified</span>
                                            @endif
                                            @if ($vendor->is_featured)
                                                <span class="badge bg-warning">⭐ Featured</span>
                                            @endif
                                        </div>
                                        {{-- <button class="btn-favorite"
                                            onclick="toggleFavorite({{ $vendor->id }}); event.stopPropagation();"
                                            data-vendor-id="{{ $vendor->id }}">
                                            <i class="bi bi-heart"></i>
                                        </button> --}}
                                    </div>
                                    <div class="vendor-content">
                                        <div class="vendor-name">{{ $vendor->business_name }}</div>
                                        <div class="vendor-category">
                                            <i class="bi bi-tag"></i> {{ $vendor->category->name }}
                                        </div>
                                        <div class="vendor-location">
                                            <i class="bi bi-geo-alt"></i> {{ $vendor->location }}
                                        </div>
                                        <div class="vendor-rating">
                                            <div class="star-rating">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= floor($vendor->rating))
                                                        ★
                                                    @elseif($i - 0.5 <= $vendor->rating)
                                                        ☆
                                                    @else
                                                        ☆
                                                    @endif
                                                @endfor
                                            </div>
                                            <span class="rating-text">{{ number_format($vendor->rating, 1) }}
                                                ({{ $vendor->total_reviews }} reviews)
                                            </span>
                                        </div>
                                        <div class="vendor-price">
                                            Rp {{ number_format($vendor->price_range_min) }} - Rp
                                            {{ number_format($vendor->price_range_max) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="col-12 text-center">
                            <div class="loading-spinner">
                                <div class="spinner-border" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-2">Loading vendors...</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </section>

        <!-- News Section -->
        @if ($news && count($news) > 0)
            <section class="news-section py-5">
                <div class="container">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2>Latest News & Tips</h2>
                        <a href="#" onclick="showNews()" class="text-decoration-none">View All</a>
                    </div>
                    <div class="row">
                        @foreach ($news->take(3) as $newsItem)
                            <div class="col-lg-4 mb-4">
                                <div class="card shadow-sm border-0">
                                    <img src="{{ asset('storage/' . $newsItem->image) }}" class="card-img-top"
                                        style="height: 200px; object-fit: cover;" alt="{{ $newsItem->title }}">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $newsItem->title }}</h5>
                                        <h6 class="card-subtitle mb-2 text-muted">
                                            {{ $newsItem->created_at->diffForHumans() }}</h6>
                                        <p class="card-text">{{ Str::limit($newsItem->description, 100) }}</p>
                                        <a href="{{ route('news', $newsItem->id) }}" class="btn btn-primary">Read
                                            More</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif


    </main>
@endsection
