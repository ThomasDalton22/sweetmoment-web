<!DOCTYPE html>
<html lang="en">

<head>

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

    <style>
        :root {
            --primary-color: #116d6e;
            --secondary-color: #f8bbd9;
            --accent-color: #bba016;
            --text-dark: #2c3e50;
            --text-light: #7f8c8d;
            --bg-light: #fafafa;
            --white: #ffffff;
            --shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            color: var(--text-dark);
            background-color: var(--bg-light);
        }

        .news-header {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            color: white;
            padding: 2rem 0;
        }

        .news-meta {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
            flex-wrap: wrap;
        }

        .news-date {
            background: rgba(255, 255, 255, 0.2);
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.9rem;
        }

        .news-category {
            background: var(--accent-color);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.9rem;
        }

        .news-image {
            width: 100%;
            max-height: 500px;
            object-fit: cover;
            border-radius: 15px;
            box-shadow: var(--shadow);
        }

        .news-content {
            background: var(--white);
            border-radius: 15px;
            padding: 2rem;
            box-shadow: var(--shadow);
            margin-top: 2rem;
        }

        .news-content p {
            margin-bottom: 1.5rem;
            text-align: justify;
        }

        .reaction-section {
            background: var(--bg-light);
            border-radius: 15px;
            padding: 1.5rem;
            margin-top: 2rem;
            text-align: center;
        }

        .btn-reaction {
            background: var(--white);
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            margin: 0 0.5rem;
            transition: all 0.3s ease;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-reaction:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
        }

        .btn-reaction:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .btn-reaction i {
            font-size: 1.2rem;
        }

        .reaction-count {
            font-weight: 600;
            font-size: 1.1rem;
        }

        .back-btn {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 0.5rem 1rem;
            border-radius: 25px;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .back-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            color: white;
            text-decoration: none;
        }

        .related-news {
            margin-top: 3rem;
        }

        .related-news-card {
            background: var(--white);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
            text-decoration: none;
            color: inherit;
        }

        .related-news-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
            text-decoration: none;
            color: inherit;
        }

        .related-news-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .related-news-content {
            padding: 1.5rem;
        }

        .social-share {
            background: var(--white);
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: var(--shadow);
            margin-top: 2rem;
        }

        .share-btn {
            background: none;
            border: 2px solid #ddd;
            color: var(--text-dark);
            padding: 0.5rem;
            border-radius: 50%;
            width: 45px;
            height: 45px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 0 0.25rem;
            transition: all 0.3s ease;
        }

        .share-btn:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
            transform: translateY(-2px);
        }

        .share-btn.facebook:hover {
            background: #3b5998;
            border-color: #3b5998;
            color: white;
        }

        .share-btn.twitter:hover {
            background: #1da1f2;
            border-color: #1da1f2;
            color: white;
        }

        .share-btn.whatsapp:hover {
            background: #25d366;
            border-color: #25d366;
            color: white;
        }

        .breadcrumb {
            background: transparent;
            padding: 0;
            margin-bottom: 1rem;
        }

        .breadcrumb-item a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
        }

        .breadcrumb-item a:hover {
            color: white;
        }

        .breadcrumb-item.active {
            color: rgba(255, 255, 255, 0.6);
        }

        .reading-time {
            background: rgba(255, 255, 255, 0.2);
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.9rem;
        }

        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
        }

        @media (max-width: 768px) {
            .news-header {
                padding: 1.5rem 0;
            }

            .news-content {
                padding: 1.5rem;
                margin-top: 1rem;
            }

            .btn-reaction {
                margin: 0.25rem;
                padding: 0.5rem 1rem;
                font-size: 0.9rem;
            }

            .news-meta {
                gap: 0.5rem;
            }
        }
    </style>
</head>

<body class="index-page">
    <!-- Header -->
    <header id="header" class="header d-flex align-items-center sticky-top">
        <div class="container position-relative d-flex align-items-center justify-content-between">
            <a href="{{ route('home') }}" class="logo d-flex align-items-center me-auto me-xl-0">
                <img src="{{ asset('img/logo.png') }}" alt="Sweet Moments" style="max-height: 50px;">
            </a>

            <nav id="navmenu" class="navmenu">
                <ul>
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('home') }}#news" class="active">News</a></li>
                    <li><a href="{{ route('home') }}#testimony">Testimony</a></li>
                    <li><a href="{{ route('home') }}#about">About</a></li>
                    <li><a href="{{ route('home') }}#contact">Contact</a></li>
                </ul>
                <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
            </nav>
        </div>
    </header>

    <main class="main">
        <!-- News Header Section -->
        <section class="news-header">
            <div class="container">
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('home') }}#news">News</a></li>
                        <li class="breadcrumb-item active">{{ Str::limit($news->title, 50) }}</li>
                    </ol>
                </nav>

                <div class="row align-items-center">
                    <div class="col-12">
                        <a href="{{ route('home') }}" class="back-btn mb-3">
                            <i class="bi bi-arrow-left"></i>
                            Back to Home
                        </a>

                        <div class="news-meta">
                            <span class="news-date">
                                <i class="bi bi-calendar me-1"></i>
                                {{ $news->created_at->format('M d, Y') }}
                            </span>
                            <span class="news-category">
                                <i class="bi bi-tag me-1"></i>
                                Wedding Tips
                            </span>
                            <span class="reading-time">
                                <i class="bi bi-clock me-1"></i>
                                {{ ceil(str_word_count(strip_tags($news->content ?: $news->description)) / 200) }} min
                                read
                            </span>
                        </div>

                        <h1 class="mb-0">{{ $news->title }}</h1>
                    </div>
                </div>
            </div>
        </section>

        <!-- News Content Section -->
        <section class="section">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        <!-- Featured Image -->
                        @if ($news->image)
                            <img src="{{ asset('storage/' . $news->image) }}" class="news-image mb-4"
                                alt="{{ $news->title }}">
                        @endif

                        <!-- News Content -->
                        <div class="news-content">
                            <!-- Description/Excerpt -->
                            @if ($news->description)
                                <div class="lead mb-4">
                                    {{ $news->description }}
                                </div>
                            @endif

                            <!-- Full Content -->
                            <div class="content">
                                @if ($news->content)
                                    {!! nl2br(e($news->content)) !!}
                                @else
                                    <p>{{ $news->description }}</p>
                                    <p>This article provides valuable insights and tips for planning your perfect
                                        wedding. Our team of experts has compiled the most important information to help
                                        you make informed decisions for your special day.</p>
                                    <p>Whether you're just starting to plan or you're in the final stages of
                                        preparation, these tips will help ensure your wedding day is everything you've
                                        dreamed of and more.</p>
                                @endif
                            </div>

                            <!-- Tags (if any) -->
                            <div class="mt-4">
                                <div class="d-flex flex-wrap gap-2">
                                    <span class="badge bg-light text-dark">#Wedding</span>
                                    <span class="badge bg-light text-dark">#WeddingTips</span>
                                    <span class="badge bg-light text-dark">#SweetMoments</span>
                                </div>
                            </div>
                        </div>

                        <!-- Reaction Section -->
                        <div class="reaction-section">
                            <h5 class="mb-3">Did you find this article helpful?</h5>
                            <div class="reactions">
                                <button class="btn-reaction" data-reaction="like" data-id="{{ $news->id }}">
                                    <i class="bi bi-hand-thumbs-up"></i>
                                    <span>Like</span>
                                    <span class="reaction-count"
                                        id="likes-count-{{ $news->id }}">{{ $news->likes }}</span>
                                </button>
                                <button class="btn-reaction" data-reaction="dislike" data-id="{{ $news->id }}">
                                    <i class="bi bi-hand-thumbs-down"></i>
                                    <span>Dislike</span>
                                    <span class="reaction-count"
                                        id="dislikes-count-{{ $news->id }}">{{ $news->dislikes }}</span>
                                </button>
                            </div>
                            <p class="text-muted mt-3 mb-0">Your feedback helps us create better content for you!</p>
                        </div>

                        <!-- Social Share Section -->
                        <div class="social-share">
                            <h6 class="mb-3">Share this article</h6>
                            <div class="d-flex align-items-center gap-2">
                                <button class="share-btn facebook" onclick="shareOnFacebook()">
                                    <i class="bi bi-facebook"></i>
                                </button>
                                <button class="share-btn twitter" onclick="shareOnTwitter()">
                                    <i class="bi bi-twitter"></i>
                                </button>
                                <button class="share-btn whatsapp" onclick="shareOnWhatsApp()">
                                    <i class="bi bi-whatsapp"></i>
                                </button>
                                <button class="share-btn" onclick="copyLink()">
                                    <i class="bi bi-link-45deg"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="col-lg-4">
                        <!-- Author/Publisher Info -->
                        <div class="card mb-4">
                            <div class="card-body text-center">
                                <img src="{{ asset('img/logo.png') }}" alt="Sweet Moments" class="mb-3"
                                    style="max-height: 80px;">
                                <h6>Sweet Moments</h6>
                                <p class="text-muted small">Your trusted partner in creating perfect wedding moments.
                                    Follow us for more wedding tips and inspiration.</p>
                                <div class="social-links">
                                    <a href="https://www.instagram.com/sweetmo.official/" class="me-2"><i
                                            class="bi bi-instagram"></i></a>
                                    <a href="https://www.facebook.com/share/19wAE5nSdH/?mibextid=qi2Omg"
                                        class="me-2"><i class="bi bi-facebook"></i></a>
                                    <a href="https://x.com/sweetmomentsofc?t=Q8BfS8nSE-JUuj0Qff24EA&s=09"><i
                                            class="bi bi-twitter"></i></a>
                                </div>
                            </div>
                        </div>

                        <!-- Newsletter Signup -->
                        <div class="card mb-4">
                            <div class="card-body">
                                <h6>Get Wedding Tips</h6>
                                <p class="text-muted small">Subscribe to receive the latest wedding tips and vendor
                                    updates.</p>
                                <form>
                                    <div class="input-group">
                                        <input type="email" class="form-control" placeholder="Your email">
                                        <button class="btn btn-primary" type="submit">Subscribe</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Popular Articles -->
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Popular Articles</h6>
                            </div>
                            <div class="card-body">
                                @php
                                    $relatedNews = App\Models\News::where('id', '!=', $news->id)
                                        ->orderBy('likes', 'desc')
                                        ->take(3)
                                        ->get();
                                @endphp
                                @forelse($relatedNews as $related)
                                    <div class="mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                                        <a href="{{ route('news', $related->id) }}" class="text-decoration-none">
                                            <h6 class="mb-1">{{ Str::limit($related->title, 60) }}</h6>
                                            <small
                                                class="text-muted">{{ $related->created_at->diffForHumans() }}</small>
                                        </a>
                                    </div>
                                @empty
                                    <p class="text-muted small">No related articles available.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Related News Section -->
                @if ($relatedNews->count() > 0)
                    <div class="related-news">
                        <h3 class="mb-4">You Might Also Like</h3>
                        <div class="row">
                            @foreach ($relatedNews as $related)
                                <div class="col-md-4 mb-4">
                                    <a href="{{ route('news', $related->id) }}" class="related-news-card d-block">
                                        @if ($related->image)
                                            <img src="{{ asset('storage/' . $related->image) }}"
                                                class="related-news-image" alt="{{ $related->title }}">
                                        @else
                                            <div
                                                class="related-news-image bg-light d-flex align-items-center justify-content-center">
                                                <i class="bi bi-image text-muted" style="font-size: 2rem;"></i>
                                            </div>
                                        @endif
                                        <div class="related-news-content">
                                            <h6>{{ Str::limit($related->title, 60) }}</h6>
                                            <p class="text-muted small mb-2">
                                                {{ Str::limit($related->description, 80) }}</p>
                                            <small
                                                class="text-muted">{{ $related->created_at->diffForHumans() }}</small>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer id="footer" class="footer" style="background-color: #1f1f24; color: white;">
        <div class="container">
            <div class="row gy-3">
                <div class="col-lg-3 col-md-6 d-flex">
                    <i class="bi bi-geo-alt"
                        style="color: var(--accent-color); margin-right: 15px; font-size: 24px;"></i>
                    <div class="address">
                        <h4>Alamat</h4>
                        <p>Jl Rusunama Tambak Sawah B-302 Waru-Sidoarjo</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 d-flex">
                    <i class="bi bi-telephone"
                        style="color: var(--accent-color); margin-right: 15px; font-size: 24px;"></i>
                    <div>
                        <h4>Kontak</h4>
                        <p>
                            <strong>WA:</strong> <span>082140034110</span><br>
                        </p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 d-flex">
                    <i class="bi bi-clock"
                        style="color: var(--accent-color); margin-right: 15px; font-size: 24px;"></i>
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
                        <a href="https://x.com/sweetmomentsofc?t=Q8BfS8nSE-JUuj0Qff24EA&s=09" class="twitter me-2"><i
                                class="bi bi-twitter-x"></i></a>
                        <a href="https://www.facebook.com/share/19wAE5nSdH/?mibextid=qi2Omg" class="facebook me-2"><i
                                class="bi bi-facebook"></i></a>
                        <a href="https://www.instagram.com/sweetmo.official/" class="instagram me-2"><i
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
        </div>
    </footer>

    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"
        style="position: fixed; right: 15px; bottom: 15px; background-color: var(--accent-color); width: 44px; height: 44px; border-radius: 50px; visibility: hidden; opacity: 0; transition: 0.4s;">
        <i class="bi bi-arrow-up-short" style="font-size: 24px; color: white;"></i>
    </a>

    <!-- Toast Container -->
    <div id="toastContainer"></div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.0/axios.min.js"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/php-email-form/validate.js') }}"></script>
    <script src="{{ asset('vendor/aos/aos.js') }}"></script>
    <script src="{{ asset('vendor/glightbox/js/glightbox.min.js') }}"></script>
    <script src="{{ asset('vendor/purecounter/purecounter_vanilla.js') }}"></script>
    <script src="{{ asset('vendor/swiper/swiper-bundle.min.js') }}"></script>

    <!-- Main JS File -->
    <script src="{{ asset('js/main.js') }}"></script>

    <!-- Enhanced News Page Script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Initialize AOS if available
            if (typeof AOS !== 'undefined') {
                AOS.init();
            }

            // Reaction functionality
            const reactionButtons = document.querySelectorAll('.btn-reaction');
            const newsId = {{ $news->id }};

            // Check if user has already reacted
            const hasReacted = sessionStorage.getItem(`reacted_${newsId}`) === 'true';

            // Disable buttons if already reacted
            if (hasReacted) {
                reactionButtons.forEach(button => {
                    button.disabled = true;
                    button.innerHTML = button.innerHTML.replace('Like', 'Liked').replace('Dislike',
                        'Disliked');
                });
            }

            // Add reaction event listeners
            reactionButtons.forEach(button => {
                button.addEventListener('click', async () => {
                    if (button.disabled) return;

                    const reaction = button.getAttribute('data-reaction');
                    const newsId = button.getAttribute('data-id');

                    try {
                        const response = await axios.post(`/news/${newsId}/react`, {
                            reaction: reaction
                        });

                        if (response.data.success) {
                            // Update counts
                            document.getElementById(`likes-count-${newsId}`).textContent =
                                response.data.likes;
                            document.getElementById(`dislikes-count-${newsId}`).textContent =
                                response.data.dislikes;

                            // Mark as reacted
                            sessionStorage.setItem(`reacted_${newsId}`, 'true');

                            // Disable buttons and update text
                            reactionButtons.forEach(btn => {
                                btn.disabled = true;
                                btn.innerHTML = btn.innerHTML.replace('Like', 'Liked')
                                    .replace('Dislike', 'Disliked');
                            });

                            // Show success message
                            showToast('Thank you for your feedback!', 'success');
                        }
                    } catch (error) {
                        console.error('Error submitting reaction:', error);
                        showToast('Error submitting reaction. Please try again.', 'error');
                    }
                });
            });

            // Social sharing functionality
            window.shareOnFacebook = () => {
                const url = encodeURIComponent(window.location.href);
                const title = encodeURIComponent('{{ addslashes($news->title) }}');
                window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}&t=${title}`, '_blank',
                    'width=600,height=400');
            };

            window.shareOnTwitter = () => {
                const url = encodeURIComponent(window.location.href);
                const title = encodeURIComponent('{{ addslashes($news->title) }}');
                const text = encodeURIComponent('Check out this wedding tip: ');
                window.open(`https://twitter.com/intent/tweet?url=${url}&text=${text}${title}`, '_blank',
                    'width=600,height=400');
            };

            window.shareOnWhatsApp = () => {
                const url = encodeURIComponent(window.location.href);
                const title = encodeURIComponent('{{ addslashes($news->title) }}');
                const text = encodeURIComponent(`Check out this wedding tip: ${title} `);
                window.open(`https://wa.me/?text=${text}${url}`, '_blank');
            };

            window.copyLink = async () => {
                try {
                    await navigator.clipboard.writeText(window.location.href);
                    showToast('Link copied to clipboard!', 'success');
                } catch (error) {
                    // Fallback for older browsers
                    const textArea = document.createElement('textarea');
                    textArea.value = window.location.href;
                    document.body.appendChild(textArea);
                    textArea.select();
                    document.execCommand('copy');
                    document.body.removeChild(textArea);
                    showToast('Link copied to clipboard!', 'success');
                }
            };

            // Newsletter subscription
            const newsletterForm = document.querySelector('form');
            if (newsletterForm) {
                newsletterForm.addEventListener('submit', (e) => {
                    e.preventDefault();
                    const email = newsletterForm.querySelector('input[type="email"]').value;
                    if (email) {
                        showToast('Thank you for subscribing! We\'ll send you the latest wedding tips.',
                            'success');
                        newsletterForm.reset();
                    }
                });
            }

            // Scroll to top functionality
            const scrollTop = document.getElementById('scroll-top');
            if (scrollTop) {
                window.addEventListener('scroll', () => {
                    if (window.scrollY > 100) {
                        scrollTop.style.visibility = 'visible';
                        scrollTop.style.opacity = '1';
                    } else {
                        scrollTop.style.visibility = 'hidden';
                        scrollTop.style.opacity = '0';
                    }
                });

                scrollTop.addEventListener('click', (e) => {
                    e.preventDefault();
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                });
            }

            // Reading progress indicator
            createReadingProgress();

            // Smooth scrolling for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth'
                        });
                    }
                });
            });
        });

        // Toast notification function
        function showToast(message, type = 'info') {
            const toastContainer = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className =
                `toast align-items-center text-white bg-${type === 'error' ? 'danger' : type === 'success' ? 'success' : 'primary'} border-0`;
            toast.setAttribute('role', 'alert');
            toast.setAttribute('aria-live', 'assertive');
            toast.setAttribute('aria-atomic', 'true');

            toast.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">${message}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            `;

            toastContainer.appendChild(toast);
            const bsToast = new bootstrap.Toast(toast);
            bsToast.show();

            // Remove toast after it's hidden
            toast.addEventListener('hidden.bs.toast', () => {
                toast.remove();
            });
        }

        // Reading progress indicator
        function createReadingProgress() {
            const progressBar = document.createElement('div');
            progressBar.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                width: 0%;
                height: 3px;
                background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
                z-index: 9999;
                transition: width 0.3s ease;
            `;
            document.body.appendChild(progressBar);

            window.addEventListener('scroll', () => {
                const scrollTop = window.pageYOffset;
                const docHeight = document.body.scrollHeight - window.innerHeight;
                const scrollPercent = (scrollTop / docHeight) * 100;
                progressBar.style.width = scrollPercent + '%';
            });
        }

        // Enhanced mobile navigation
        document.addEventListener('DOMContentLoaded', function() {
            const mobileNavToggle = document.querySelector('.mobile-nav-toggle');
            const navMenu = document.querySelector('#navmenu ul');

            if (mobileNavToggle && navMenu) {
                mobileNavToggle.addEventListener('click', function() {
                    navMenu.classList.toggle('active');
                    this.classList.toggle('bi-list');
                    this.classList.toggle('bi-x');
                });

                // Close mobile nav when clicking on a link
                document.querySelectorAll('#navmenu a').forEach(link => {
                    link.addEventListener('click', () => {
                        navMenu.classList.remove('active');
                        mobileNavToggle.classList.remove('bi-x');
                        mobileNavToggle.classList.add('bi-list');
                    });
                });
            }
        });

        // Image lazy loading
        document.addEventListener('DOMContentLoaded', function() {
            const images = document.querySelectorAll('img[data-src]');
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                        imageObserver.unobserve(img);
                    }
                });
            });

            images.forEach(img => imageObserver.observe(img));
        });

        // Error handling for images
        document.addEventListener('DOMContentLoaded', function() {
            const images = document.querySelectorAll('img');
            images.forEach(img => {
                img.addEventListener('error', function() {
                    this.src =
                        'https://via.placeholder.com/400x300/f8f9fa/6c757d?text=Image+Not+Found';
                    this.alt = 'Image not found';
                });
            });
        });

        // Print functionality
        function printArticle() {
            const printContent = document.querySelector('.news-content');
            const printWindow = window.open('', '', 'height=600,width=800');

            printWindow.document.write(`
                <html>
                    <head>
                        <title>{{ $news->title }}</title>
                        <style>
                            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                            .content { max-width: 800px; margin: 0 auto; padding: 20px; }
                            h1 { color: #116d6e; border-bottom: 2px solid #116d6e; padding-bottom: 10px; }
                            img { max-width: 100%; height: auto; }
                            @media print {
                                body { margin: 0; padding: 20px; }
                                .no-print { display: none; }
                            }
                        </style>
                    </head>
                    <body>
                        <div class="content">
                            <h1>{{ $news->title }}</h1>
                            <p><strong>Published:</strong> {{ $news->created_at->format('M d, Y') }}</p>
                            ${printContent.innerHTML}
                        </div>
                    </body>
                </html>
            `);

            printWindow.document.close();
            printWindow.focus();
            printWindow.print();
            printWindow.close();
        }

        // Add print button functionality if needed
        const addPrintButton = () => {
            const socialShare = document.querySelector('.social-share .d-flex');
            if (socialShare) {
                const printBtn = document.createElement('button');
                printBtn.className = 'share-btn';
                printBtn.onclick = printArticle;
                printBtn.innerHTML = '<i class="bi bi-printer"></i>';
                printBtn.title = 'Print Article';
                socialShare.appendChild(printBtn);
            }
        };

        // Call addPrintButton after DOM is loaded
        document.addEventListener('DOMContentLoaded', addPrintButton);
    </script>

    <!-- Schema.org structured data for SEO -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Article",
        "headline": "{{ $news->title }}",
        "description": "{{ Str::limit($news->description, 160) }}",
        "image": "{{ asset('storage/' . $news->image) }}",
        "author": {
            "@type": "Organization",
            "name": "Sweet Moments",
            "url": "{{ url('/') }}"
        },
        "publisher": {
            "@type": "Organization",
            "name": "Sweet Moments",
            "logo": {
                "@type": "ImageObject",
                "url": "{{ asset('img/logo.png') }}"
            }
        },
        "datePublished": "{{ $news->created_at->toISOString() }}",
        "dateModified": "{{ $news->updated_at->toISOString() }}",
        "mainEntityOfPage": {
            "@type": "WebPage",
            "@id": "{{ url()->current() }}"
        }
    }
    </script>
</body>

</html>
