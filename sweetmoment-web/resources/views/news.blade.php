@extends('layout')

@section('content')
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Amatic+SC:wght@400;700&display=swap"
        rel="stylesheet">

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
            opacity: 0.9;
            color: white;
            padding: 2rem 0;
            margin-bottom: 20px;
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
                            {{-- <div class="mt-4">
                                <div class="d-flex flex-wrap gap-2">
                                    <span class="badge bg-light text-dark">#Wedding</span>
                                    <span class="badge bg-light text-dark">#WeddingTips</span>
                                    <span class="badge bg-light text-dark">#SweetMoments</span>
                                </div>
                            </div> --}}
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
                        {{-- <div class="social-share">
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
                        </div> --}}
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
                                    <a href="https://www.facebook.com/share/19wAE5nSdH/?mibextid=qi2Omg" class="me-2"><i
                                            class="bi bi-facebook"></i></a>
                                    <a href="https://x.com/sweetmomentsofc?t=Q8BfS8nSE-JUuj0Qff24EA&s=09"><i
                                            class="bi bi-twitter"></i></a>
                                </div>
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
                                            <small class="text-muted">{{ $related->created_at->diffForHumans() }}</small>
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
                                            <img src="{{ asset('storage/' . $related->image) }}" class="related-news-image"
                                                alt="{{ $related->title }}">
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
                                            <small class="text-muted">{{ $related->created_at->diffForHumans() }}</small>
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
@endsection

@push('scripts')
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

                            // Show success message toastr
                            toastr.success('Thank you for your feedback!',
                                'Reaction Recorded', {
                                    positionClass: 'toast-top-right',
                                    timeOut: 3000
                                });

                        }
                    } catch (error) {
                        console.error('Error submitting reaction:', error);
                        toastr.error('An error occurred while submitting your reaction.',
                            'Error', {
                                positionClass: 'toast-top-right',
                                timeOut: 3000
                            });
                    }
                });
            });
        });
    </script>
@endpush
