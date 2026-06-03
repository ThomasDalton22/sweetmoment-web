<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', Auth::user()->role === 'admin' ? 'Admin Panel' : 'Vendor Panel')</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css"
        rel="stylesheet">

    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <!-- Toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- Summernote -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">

    <style>
        .sidebar {
            min-height: 100vh;
            background: #343a40;
        }

        .sidebar .nav-link {
            color: #adb5bd;
            padding: 0.75rem 1rem;
            border-radius: 0;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: #fff;
            background-color: #495057;
        }

        .sidebar .nav-link i {
            margin-right: 0.5rem;
        }

        .main-content {
            margin-left: 0;
        }

        @media (min-width: 768px) {
            .main-content {
                margin-left: 250px;
            }
        }

        .card-stats {
            background: linear-gradient(45deg, #007bff, #0056b3);
            color: white;
        }

        .card-stats.success {
            background: linear-gradient(45deg, #28a745, #1e7e34);
        }

        .card-stats.warning {
            background: linear-gradient(45deg, #ffc107, #e0a800);
        }

        .card-stats.danger {
            background: linear-gradient(45deg, #dc3545, #c82333);
        }

        .card-stats.info {
            background: linear-gradient(45deg, #17a2b8, #138496);
        }

        .card-stats.secondary {
            background: linear-gradient(45deg, #6c757d, #5a6268);
        }

        .btn-group .btn {
            margin-right: 2px;
        }

        .vendor-only {
            display: {{ Auth::user()->role === 'vendor' ? 'block' : 'none' }};
        }

        .admin-only {
            display: {{ Auth::user()->role === 'admin' ? 'block' : 'none' }};
        }
    </style>

    @stack('styles')
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse" id="sidebarMenu">
                <div class="position-sticky pt-3">
                    <div class="text-center py-3">
                        <h5 class="text-white">{{ Auth::user()->role === 'admin' ? 'Admin Panel' : 'Vendor Panel' }}
                        </h5>
                        <small class="text-white">{{ Auth::user()->name }}</small>
                        <div class="mt-1">
                            <span class="badge bg-{{ Auth::user()->role === 'admin' ? 'danger' : 'success' }}">
                                {{ ucfirst(Auth::user()->role) }}
                            </span>
                        </div>
                    </div>

                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                                href="{{ route('admin.dashboard') }}">
                                <i class="fas fa-tachometer-alt"></i>
                                Dashboard
                            </a>
                        </li>

                        <!-- Admin Only Sections -->
                        @if (Auth::user()->role === 'admin')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
                                    href="{{ route('admin.users.index') }}">
                                    <i class="fas fa-users"></i>
                                    Users Management
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}"
                                    href="{{ route('admin.banners.index') }}">
                                    <i class="fas fa-image"></i>
                                    Banners
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.news.*') ? 'active' : '' }}"
                                    href="{{ route('admin.news.index') }}">
                                    <i class="fas fa-newspaper"></i>
                                    News
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.vendor-categories.*') ? 'active' : '' }}"
                                    href="{{ route('admin.vendor-categories.index') }}">
                                    <i class="fas fa-tags"></i>
                                    Vendor Categories
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.testimonies.*') ? 'active' : '' }}"
                                    href="{{ route('admin.testimonies.index') }}">
                                    <i class="fas fa-star"></i>
                                    Testimonies
                                </a>
                            </li>
                        @endif

                        <!-- Admin & Vendor Sections -->
                        @if (Auth::user()->role === 'admin')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.vendor-profile.*') ? 'active' : '' }}"
                                    href="{{ route('admin.vendor-profile.index') }}">
                                    <i class="fas fa-store"></i>
                                    Vendor Profiles
                                </a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.vendor-profile.*') ? 'active' : '' }}"
                                    href="{{ route('admin.vendor-profile.index') }}">
                                    <i class="fas fa-store"></i>
                                    My Profile
                                </a>
                            </li>
                        @endif

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.vendor-packages.*') ? 'active' : '' }}"
                                href="{{ route('admin.vendor-packages.index') }}">
                                <i class="fas fa-box"></i>
                                {{ Auth::user()->role === 'admin' ? 'All Packages' : 'My Packages' }}
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.portfolio-images.*') ? 'active' : '' }}"
                                href="{{ route('admin.portfolio-images.index') }}">
                                <i class="fas fa-images"></i>
                                {{ Auth::user()->role === 'admin' ? 'All Portfolio' : 'My Portfolio' }}
                            </a>
                        </li>

                        {{-- @if (Auth::user()->role === 'vendor')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.vendor-availability.*') ? 'active' : '' }}"
                                    href="{{ route('admin.vendor-availability.index') }}">
                                    <i class="fas fa-calendar-check"></i>
                                    My Availability
                                </a>
                            </li>
                        @endif --}}

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}"
                                href="{{ route('admin.orders.index') }}">
                                <i class="fas fa-shopping-cart"></i>
                                {{ Auth::user()->role === 'admin' ? 'All Orders' : 'My Orders' }}
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}"
                                href="{{ route('admin.reviews.index') }}">
                                <i class="fas fa-star-half-alt"></i>
                                {{ Auth::user()->role === 'admin' ? 'All Reviews' : 'My Reviews' }}
                            </a>
                        </li>

                        <li class="nav-item mt-3">
                            <a class="nav-link text-danger" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt"></i>
                                Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                <!-- Top navbar -->
                <div
                    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">@yield('page-title', 'Dashboard')</h1>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-{{ Auth::user()->role === 'admin' ? 'danger' : 'success' }} me-3">
                            <i class="fas fa-{{ Auth::user()->role === 'admin' ? 'shield-alt' : 'store' }} me-1"></i>
                            {{ ucfirst(Auth::user()->role) }}
                        </span>
                        <button class="navbar-toggler d-md-none" type="button" data-bs-toggle="collapse"
                            data-bs-target="#sidebarMenu">
                            <i class="fas fa-bars"></i>
                        </button>
                    </div>
                </div>

                <!-- Access Level Info -->
                @if (Auth::user()->role === 'vendor')
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Vendor Access:</strong> You can manage your profile, packages, portfolio, availability,
                        and view your orders & reviews.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Page content -->
                @yield('content')
            </main>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <!-- Bootstrap 5 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <!-- Toastr -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Summernote -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>

    <script>
        // Configure toastr
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": true,
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

        // Configure CSRF token for all AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Global variables for user role
        window.userRole = '{{ Auth::user()->role }}';
        window.isAdmin = userRole === 'admin';
        window.isVendor = userRole === 'vendor';

        // Show success/error messages from session
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

    @stack('scripts')
</body>

</html>
