<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/x-icon">

    <title>Sweet Moments - Your Perfect Wedding Awaits</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css"
        rel="stylesheet">

    <!-- Toastr CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
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
            --shadow-hover: 0 8px 30px rgba(0, 0, 0, 0.15);
            --danger: #dc3545;
            --success: #28a745;
            --warning: #ffc107;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: var(--text-dark);
            background-color: var(--bg-light);
            padding-top: 80px;
        }

        /* Navigation */
        .navbar {
            background: var(--white);
            box-shadow: var(--shadow);
            padding: 1rem 0;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .navbar-brand {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary-color) !important;
            text-decoration: none;
        }

        .nav-link {
            color: var(--text-dark) !important;
            font-weight: 500;
            margin: 0 0.5rem;
            transition: color 0.3s ease;
        }

        .nav-link:hover {
            color: var(--primary-color) !important;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            border: none;
            border-radius: 25px;
            padding: 0.5rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-hover);
        }

        .navbar-nav-icons {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .icon-btn {
            position: relative;
            background: none;
            border: none;
            font-size: 1.2rem;
            color: var(--text-dark);
            padding: 0.5rem;
            border-radius: 50%;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .icon-btn:hover {
            background: var(--bg-light);
            color: var(--primary-color);
        }

        .badge-notification {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--primary-color);
            color: white;
            border-radius: 50%;
            min-width: 20px;
            height: 20px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        .badge-notification.hidden {
            display: none;
        }

        /* Search Bar */
        .search-container {
            position: relative;
            max-width: 500px;
            margin: 0 auto;
        }

        .search-input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 3rem;
            border: 2px solid #e0e0e0;
            border-radius: 25px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(17, 109, 110, 0.1);
        }

        .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--accent-color) 100%);
            color: white;
            padding: 4rem 0;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hero-content h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
            animation: fadeInUp 0.8s ease;
        }

        .hero-content p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            opacity: 0.9;
            animation: fadeInUp 0.8s ease 0.2s both;
        }

        /* Banner Carousel */
        .banner-section {
            margin: 2rem 0;
        }

        .banner-card {
            background: var(--white);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
            position: relative;
        }

        .banner-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-hover);
        }

        .banner-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .banner-content {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(transparent, rgba(0, 0, 0, 0.7));
            color: white;
            padding: 2rem 1rem 1rem;
        }

        /* Categories Grid */
        .categories-section {
            padding: 3rem 0;
        }

        .category-card {
            background: var(--white);
            border-radius: 15px;
            padding: 2rem 1rem;
            text-align: center;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
            cursor: pointer;
            height: 100%;
        }

        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-hover);
            background: var(--primary-color);
            color: white;
        }

        .category-icon {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
            transition: color 0.3s ease;
        }

        .category-card:hover .category-icon {
            color: white;
        }

        /* Vendor Recommendations */
        .vendor-card {
            background: var(--white);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
            margin-bottom: 1.5rem;
            cursor: pointer;
        }

        .vendor-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-hover);
        }

        .vendor-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .vendor-image-container {
            position: relative;
        }

        .vendor-badges {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 10;
        }

        .vendor-badges .badge {
            margin-right: 5px;
        }

        .btn-favorite {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(255, 255, 255, 0.9);
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            z-index: 15;
        }

        .btn-favorite:hover {
            background: var(--primary-color);
            color: white;
        }

        .btn-favorite.favorited {
            background: var(--danger);
            color: white;
        }

        .btn-favorite.favorited:hover {
            background: #c82333;
        }

        .vendor-content {
            padding: 1.5rem;
        }

        .vendor-name {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--text-dark);
        }

        .vendor-category {
            color: var(--text-light);
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .vendor-location {
            color: var(--text-light);
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .vendor-rating {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .star-rating {
            color: #ffc107;
        }

        .vendor-price {
            font-weight: 600;
            color: var(--primary-color);
            font-size: 1.1rem;
        }

        /* Bottom Navigation (Mobile) */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: var(--white);
            box-shadow: 0 -2px 20px rgba(0, 0, 0, 0.1);
            display: none;
            z-index: 1000;
        }

        .bottom-nav-item {
            flex: 1;
            text-align: center;
            padding: 0.75rem 0.5rem;
            color: var(--text-light);
            text-decoration: none;
            font-size: 0.8rem;
            transition: color 0.3s ease;
        }

        .bottom-nav-item.active,
        .bottom-nav-item:hover {
            color: var(--primary-color);
        }

        .bottom-nav-item i {
            display: block;
            font-size: 1.2rem;
            margin-bottom: 0.25rem;
        }

        /* Modal Styles */
        .modal-content {
            border-radius: 15px;
            border: none;
        }

        .modal-header {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            color: white;
            border-radius: 15px 15px 0 0;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(17, 109, 110, 0.25);
        }

        /* Notification Panel */
        .notification-panel {
            position: fixed;
            top: 70px;
            right: 20px;
            width: 350px;
            max-height: 500px;
            background: var(--white);
            border-radius: 15px;
            box-shadow: var(--shadow-hover);
            z-index: 9999;
            display: none;
            overflow: hidden;
        }

        .notification-header {
            background: var(--primary-color);
            color: white;
            padding: 1rem;
            display: flex;
            justify-content: between;
            align-items: center;
        }

        .notification-item {
            padding: 1rem;
            border-bottom: 1px solid #eee;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .notification-item:hover {
            background: var(--bg-light);
        }

        .notification-item.unread {
            background: rgba(17, 109, 110, 0.1);
        }

        /* Messages Panel */
        .messages-panel {
            position: fixed;
            top: 70px;
            right: 20px;
            width: 400px;
            max-height: 600px;
            background: var(--white);
            border-radius: 15px;
            box-shadow: var(--shadow-hover);
            z-index: 9999;
            display: none;
            overflow: hidden;
        }

        .messages-header {
            background: var(--primary-color);
            color: white;
            padding: 1rem;
            display: flex;
            justify-content: between;
            align-items: center;
        }

        .message-item {
            padding: 1rem;
            border-bottom: 1px solid #eee;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .message-item:hover {
            background: var(--bg-light);
        }

        .message-item.unread {
            background: rgba(17, 109, 110, 0.1);
        }

        /* Loading Spinner */
        .loading-spinner {
            text-align: center;
            padding: 2rem;
        }

        .spinner-border {
            color: var(--primary-color);
        }

        /* Responsive */
        @media (max-width: 768px) {
            body {
                padding-bottom: 100px;
            }

            .bottom-nav {
                display: flex;
            }

            .hero-content h1 {
                font-size: 2rem;
            }

            .search-container {
                margin: 1rem;
            }

            .category-card {
                padding: 1.5rem 0.5rem;
            }

            .category-icon {
                font-size: 2rem;
            }

            .notification-panel,
            .messages-panel {
                right: 10px;
                left: 10px;
                width: auto;
            }
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeInUp 0.6s ease;
        }

        /* Footer */
        .footer {
            color: var(--white);
            background-color: #1f1f24;
            font-size: 14px;
            position: relative;
            padding: 40px 0px;
        }

        .footer .icon {
            color: var(--accent-color);
            margin-right: 15px;
            font-size: 24px;
            line-height: 0;
        }

        .footer .copyright {
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Toastr custom styling */
        .toast-top-right {
            top: 100px;
        }
    </style>

    <style>
        /* Order Detail Styles */
        .order-detail-header {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            color: white;
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
        }

        .order-status-badge {
            font-size: 0.875rem;
            padding: 0.5rem 1rem;
            border-radius: 20px;
        }

        .order-timeline {
            position: relative;
            padding-left: 2rem;
        }

        .order-timeline::before {
            content: '';
            position: absolute;
            left: 0.5rem;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #dee2e6;
        }

        .timeline-item {
            position: relative;
            padding-bottom: 1.5rem;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -0.5rem;
            top: 0.25rem;
            width: 1rem;
            height: 1rem;
            border-radius: 50%;
            background: var(--primary-color);
            border: 3px solid white;
            box-shadow: 0 0 0 2px #dee2e6;
        }

        .timeline-item.active::before {
            background: var(--success-color);
        }

        .vendor-detail-card {
            border: 1px solid #dee2e6;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .vendor-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
        }

        .package-features {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1rem;
        }

        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .feature-item:last-child {
            margin-bottom: 0;
        }

        .payment-info {
            background: #e3f2fd;
            border-radius: 8px;
            padding: 1rem;
        }

        /* Rating Styles */
        .rating-stars {
            font-size: 2rem;
            cursor: pointer;
            user-select: none;
        }

        .rating-star {
            color: #dee2e6;
            margin: 0 0.25rem;
            transition: all 0.2s ease;
        }

        .rating-star:hover,
        .rating-star.hover {
            color: #ffc107;
            transform: scale(1.1);
        }

        .rating-star.selected {
            color: #ffc107;
        }

        .existing-review {
            background: #f8f9fa;
            border-left: 4px solid var(--primary-color);
            padding: 1rem;
            border-radius: 0 8px 8px 0;
            margin-top: 1rem;
        }

        .review-actions {
            margin-top: 1.5rem;
            padding-top: 1rem;
            border-top: 1px solid #dee2e6;
        }

        @media (max-width: 768px) {
            .modal-dialog {
                margin: 0.5rem;
            }

            .order-detail-header {
                padding: 1rem;
            }

            .vendor-detail-card {
                padding: 1rem;
            }
        }
    </style>

    <style>
        .dropdown-menu-end[data-bs-popper] {
            right: auto;
            left: auto;
        }

        @if (Auth::check() && (Auth::user()->role == 'admin' || Auth::user()->role == 'vendor'))
            .btn-add-to-cart {
                display: none;
            }

            .btn-cart,
            .btn-notification {
                display: none;
            }
        @endif
    </style>
</head>

<body>
    <!-- Desktop Navigation -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset('img/logo.png') }}" style="max-height: 50px" alt="Logo">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home', ['route' => 'vendor']) }}">Vendors</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            Categories
                        </a>
                        <ul class="dropdown-menu" id="categoriesDropdown">
                            @foreach ($categories as $category)
                                <li><a class="dropdown-item"
                                        href="{{ route('home', ['route' => 'category', 'slug' => $category->slug]) }}"><i
                                            class="bi {{ $category->icon ?? 'bi-star' }} me-2"></i>{{ $category->name }}</a>
                                </li>

                                {{-- <div class="col-6 col-md-3 col-lg-2" onclick="showCategory('{{ $category->slug }}')">
                                    <div class="category-card">
                                        <i class="bi {{ $category->icon ?? 'bi-star' }} category-icon"></i>
                                        <h6>{{ $category->name }}</h6>
                                        <small class="text-muted">{{ $category->vendor_profiles_count ?? 0 }}
                                            vendors</small>
                                    </div>
                                </div> --}}
                            @endforeach
                            <!-- Categories will be populated dynamically -->
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home', ['route' => 'news']) }}">News</a>
                    </li>
                </ul>

                <!-- Search Bar -->
                <div class="search-container me-3">
                    <i class="bi bi-search search-icon"></i>
                    <input type="text" class="search-input" placeholder="Search vendors, services..."
                        id="searchInput">
                </div>

                <!-- Navigation Icons -->
                <div class="navbar-nav-icons">
                    @if (Auth::check())
                        <button class="icon-btn btn-cart" onclick="toggleCart()" title="Cart">
                            <i class="bi bi-bag"></i>
                            <span class="badge-notification" id="cartCount">0</span>
                        </button>

                        <button class="icon-btn btn-notification" onclick="toggleNotifications()" title="Notifications">
                            <i class="bi bi-bell"></i>
                            <span class="badge-notification" id="notifCount"></span>
                        </button>

                        {{-- <button class="icon-btn" onclick="toggleMessages()" title="Messages">
                            <i class="bi bi-chat"></i>
                            <span class="badge-notification" id="msgCount">0</span>
                        </button> --}}
                    @endif

                    <div class="dropdown">
                        <button class="icon-btn" data-bs-toggle="dropdown" title="Profile">
                            <i class="bi bi-person-circle"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            @if (Auth::check())
                                <li><a class="dropdown-item" href="{{ route('home', ['route' => 'profile']) }}">
                                        <i class="bi bi-person me-2"></i>Profile
                                    </a></li>
                                <li><a class="dropdown-item" href="{{ route('home', ['route' => 'orders']) }}">
                                        <i class="bi bi-bag me-2"></i>My Orders
                                    </a></li>
                                @if (Auth::user()->role == 'admin' || Auth::user()->role == 'vendor')
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                            <i class="bi bi-gear me-2"></i>Dashboard
                                        </a></li>
                                @else
                                    <li><a class="dropdown-item" href="#" onclick="becomeVendor()">
                                            <i class="bi bi-shop me-2"></i>Become Vendor
                                        </a></li>
                                @endif
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                                        </button>
                                    </form>
                                </li>
                            @else
                                <li><a class="dropdown-item" href="{{ route('login') }}">
                                        <i class="bi bi-box-arrow-in-right me-2"></i>Login
                                    </a></li>
                                <li><a class="dropdown-item" href="#" onclick="showRegister()">
                                        <i class="bi bi-person-plus me-2"></i>Register
                                    </a></li>
                            @endif
                        </ul>

                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Toast Container for Notifications -->
    <div class="toast-container" id="toastContainer"></div>

    <!-- Notification Panel -->
    <div class="notification-panel" id="notificationPanel">
        <div class="notification-header">
            <h6 class="mb-0">Notifications</h6>
            <button class="btn btn-sm btn-light ms-auto" onclick="markAllNotificationsRead()">Mark All Read</button>
        </div>
        <div class="notification-body" id="notificationBody">
            <!-- Notifications will be loaded here -->
        </div>
    </div>

    <!-- Messages Panel -->
    <div class="messages-panel" id="messagesPanel">
        <div class="messages-header">
            <h6 class="mb-0">Messages</h6>
            <button class="btn btn-sm btn-light ms-auto" onclick="closeMessages()">
                <i class="bi bi-x"></i>
            </button>
        </div>
        <div class="messages-body" id="messagesBody">
            <!-- Messages will be loaded here -->
        </div>
    </div>

    <div class="main-content">
        @yield('content')
    </div>

    <!-- Main Content -->


    <!-- Bottom Navigation (Mobile) -->
    <nav class="bottom-nav">
        <a href="#" class="bottom-nav-item active" onclick="showHome()">
            <i class="bi bi-house"></i>
            <span>Home</span>
        </a>
        <a href="#" class="bottom-nav-item" onclick="showVendors()">
            <i class="bi bi-search"></i>
            <span>Explore</span>
        </a>
        <a href="#" class="bottom-nav-item" onclick="toggleCart()">
            <i class="bi bi-bag"></i>
            <span>Cart</span>
        </a>
        <a href="#" class="bottom-nav-item" onclick="toggleMessages()">
            <i class="bi bi-chat"></i>
            <span>Messages</span>
        </a>
        <a href="#" class="bottom-nav-item" onclick="showProfile()">
            <i class="bi bi-person"></i>
            <span>Profile</span>
        </a>
    </nav>

    <!-- Modals -->
    <!-- Cart Modal -->
    <div class="modal fade" id="cartModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Shopping Cart</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="cartItems">
                        <p class="text-center text-muted">Your cart is empty</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Continue
                        Shopping</button>
                    <button type="button" class="btn btn-primary" onclick="proceedCheckout()">Checkout</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Become Vendor Modal -->
    <div class="modal fade" id="vendorModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-shop me-2"></i>Become a Vendor
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        Join our vendor community and start showcasing your services to couples planning their special
                        day!
                    </div>

                    <form id="vendorApplicationForm">
                        <!-- Business Information Section -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="businessName" class="form-label">Business Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="businessName"
                                        name="business_name" required>
                                    <div class="form-text">This will be displayed publicly</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="vendorCategory" class="form-label">Service Category <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="vendorCategory" name="vendor_category_id"
                                        required>
                                        <option value="">Select a category</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="businessDesc" class="form-label">Business Description <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control" id="businessDesc" name="description" rows="4"
                                placeholder="Describe your services, experience, and what makes your business special..." required minlength="50"
                                maxlength="1000"></textarea>
                            <div class="form-text">
                                <span id="descCharCount">0</span>/1000 characters (minimum 50 required)
                            </div>
                        </div>

                        <!-- Contact Information Section -->
                        <h6 class="mb-3"><i class="bi bi-telephone me-2"></i>Contact Information</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="vendorLocation" class="form-label">Business Location <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="vendorLocation" name="location"
                                        placeholder="e.g., Jakarta, Indonesia" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="vendorPhone" class="form-label">Phone Number <span
                                            class="text-danger">*</span></label>
                                    <input type="tel" class="form-control" id="vendorPhone" name="phone"
                                        placeholder="e.g., +62 812 3456 7890" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="vendorWhatsapp" class="form-label">WhatsApp Number</label>
                                    <input type="tel" class="form-control" id="vendorWhatsapp" name="whatsapp"
                                        placeholder="e.g., +62 812 3456 7890">
                                    <div class="form-text">Leave empty to use phone number</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="vendorInstagram" class="form-label">Instagram Username</label>
                                    <div class="input-group">
                                        <span class="input-group-text">@</span>
                                        <input type="text" class="form-control" id="vendorInstagram"
                                            name="instagram" placeholder="your_business_name">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="vendorWebsite" class="form-label">Website URL</label>
                            <input type="url" class="form-control" id="vendorWebsite" name="website"
                                placeholder="https://your-website.com">
                        </div>

                        <!-- Pricing Section -->
                        <h6 class="mb-3"><i class="bi bi-currency-dollar me-2"></i>Price Range</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="priceMin" class="form-label">Minimum Price (IDR) <span
                                            class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="priceMin" name="price_range_min"
                                        min="0" step="50000" placeholder="e.g., 500000" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="priceMax" class="form-label">Maximum Price (IDR) <span
                                            class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="priceMax" name="price_range_max"
                                        min="0" step="50000" placeholder="e.g., 5000000" required>
                                </div>
                            </div>
                        </div>

                        <!-- Search Tags Section -->
                        <div class="mb-3">
                            <label for="searchTags" class="form-label">Search Tags</label>
                            <input type="text" class="form-control" id="searchTags" name="search_tags"
                                placeholder="e.g., outdoor wedding, traditional, modern, budget-friendly">
                            <div class="form-text">Keywords that help customers find your services (comma-separated)
                            </div>
                        </div>

                        <!-- Terms and Conditions -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="termsAccepted"
                                    name="terms_accepted" required>
                                <label class="form-check-label" for="termsAccepted">
                                    I agree to the <a href="#" class="text-decoration-none">Terms and
                                        Conditions</a>
                                    and <a href="#" class="text-decoration-none">Vendor Policies</a> <span
                                        class="text-danger">*</span>
                                </label>
                            </div>
                        </div>

                        <!-- Benefits Info -->
                        <div class="alert alert-success">
                            <h6><i class="bi bi-check-circle me-2"></i>Vendor Benefits:</h6>
                            <ul class="mb-0">
                                <li>Free registration and listing</li>
                                <li>Direct communication with customers</li>
                                <li>Portfolio showcase</li>
                                <li>Customer reviews and ratings</li>
                                <li>Order management system</li>
                            </ul>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="submitVendorApplication()">
                        <i class="bi bi-send me-2"></i>Submit Application (Free)
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Detail Modal -->
    <div class="modal fade" id="orderDetailModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-receipt me-2"></i>Order Details
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="orderDetailContent">
                        <!-- Order details will be loaded here -->
                        <div class="text-center p-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2">Loading order details...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Rating Modal -->
    <div class="modal fade" id="ratingModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-star me-2"></i>Rate Your Experience
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="ratingForm">
                        <input type="hidden" id="ratingOrderId" name="order_id">
                        <input type="hidden" id="ratingVendorId" name="vendor_profile_id">

                        <div class="text-center mb-4">
                            <img id="ratingVendorImage" src="" alt="Vendor" class="rounded-circle mb-3"
                                style="width: 80px; height: 80px; object-fit: cover;">
                            <h6 id="ratingVendorName">Vendor Name</h6>
                            <p class="text-muted" id="ratingPackageName">Package Name</p>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">How would you rate this service?</label>
                            <div class="rating-stars text-center">
                                <i class="bi bi-star rating-star" data-rating="1"></i>
                                <i class="bi bi-star rating-star" data-rating="2"></i>
                                <i class="bi bi-star rating-star" data-rating="3"></i>
                                <i class="bi bi-star rating-star" data-rating="4"></i>
                                <i class="bi bi-star rating-star" data-rating="5"></i>
                            </div>
                            <input type="hidden" id="ratingValue" name="rating" required>
                            <div class="text-center mt-2">
                                <small class="text-muted" id="ratingText">Click stars to rate</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="reviewText" class="form-label">Share your experience (optional)</label>
                            <textarea class="form-control" id="reviewText" name="review" rows="4"
                                placeholder="Tell others about your experience with this vendor..."></textarea>
                            <div class="form-text">Your review will help other couples make better decisions.</div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="recommendVendor"
                                    name="recommend">
                                <label class="form-check-label" for="recommendVendor">
                                    I would recommend this vendor to others
                                </label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="submitRating()">
                        <i class="bi bi-send me-2"></i>Submit Review
                    </button>
                </div>
            </div>
        </div>
    </div>

    <footer id="footer" class="footer">
        <div class="container">
            <div class="row gy-3">
                <div class="col-lg-3 col-md-6 d-flex">
                    <i class="bi bi-geo-alt icon"></i>
                    <div class="address">
                        <h4>Alamat</h4>
                        <p>Jl Rusunama Tambak Sawah B-302 Waru-Sidoarjo</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 d-flex">
                    <i class="bi bi-telephone icon"></i>
                    <div>
                        <h4>Kontak</h4>
                        <p>
                            <strong>WA:</strong> <span>082140034110</span><br>
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
                    <style>
                        .social-links a {
                            color: var(--white);
                            font-size: 1.5rem;
                            transition: color 0.3s ease;
                        }

                        .social-links a:hover {
                            color: var(--accent-color);
                        }

                        .social-links i {
                            margin-right: 10px;
                        }
                    </style>
                    <div class="social-links d-flex gap-2 ">
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
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.0/axios.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Pass Laravel data to JavaScript -->
    <script>
        // Pass initial data from Laravel to JavaScript
        window.initialData = {
            vendors: @json($vendors ?? []),
            categories: @json($categories ?? []),
            news: @json($news ?? []),
            testimonials: @json($testimony ?? []),
            banners: @json($banners ?? []),
            currentUser: @json(auth()->user() ?? null),
            csrfToken: '{{ csrf_token() }}',
            apiBase: '{{ url('/') }}'
        };

        // Configure Toastr
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        // Set global variables for backward compatibility
        let vendors = window.initialData.vendors;
        let categories = window.initialData.categories;
        let news = window.initialData.news;
        let testimonials = window.initialData.testimonials;
        let currentUser = window.initialData.currentUser;
        let cartItems = [];
        let notifications = [];
        let messages = [];
        let favorites = [];

        // Handle Laravel flash messages
        @if (session('success'))
            toastr.success('{{ session('success') }}');
        @endif

        @if (session('error'))
            toastr.error('{{ session('error') }}');
        @endif

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                toastr.error('{{ $error }}');
            @endforeach
        @endif
    </script>

    <script>
        let isLogin = false;
        @if (Auth::check())
            isLogin = true;
        @endif
    </script>

    <!-- Enhanced JavaScript -->
    <script src="{{ asset('js/script.js') }}"></script>

    @stack('scripts')
</body>
