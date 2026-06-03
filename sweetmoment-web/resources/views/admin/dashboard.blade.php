@extends('admin.layouts.app')

@section('title', Auth::user()->role === 'admin' ? 'Admin Dashboard' : 'Vendor Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <div class="row">
        @if (Auth::user()->role === 'admin')
            <!-- Admin Stats Cards -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card card-stats">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title text-uppercase text-muted mb-0">Total Users</h5>
                                <span class="h2 font-weight-bold mb-0">{{ $stats['total_users'] }}</span>
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape bg-white text-primary rounded-circle shadow">
                                    <i class="fas fa-users fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card card-stats success">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title text-uppercase text-muted mb-0">Total Vendors</h5>
                                <span class="h2 font-weight-bold mb-0">{{ $stats['total_vendors'] }}</span>
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape bg-white text-success rounded-circle shadow">
                                    <i class="fas fa-store fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card card-stats warning">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title text-uppercase text-muted mb-0">Total Orders</h5>
                                <span class="h2 font-weight-bold mb-0">{{ $stats['total_orders'] }}</span>
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape bg-white text-warning rounded-circle shadow">
                                    <i class="fas fa-shopping-cart fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card card-stats danger">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title text-uppercase text-muted mb-0">Total Revenue</h5>
                                <span class="h2 font-weight-bold mb-0">Rp
                                    {{ number_format($stats['total_revenue'], 0, ',', '.') }}</span>
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape bg-white text-danger rounded-circle shadow">
                                    <i class="fas fa-money-bill-wave fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Vendor Stats Cards -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card card-stats">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title text-uppercase text-muted mb-0">My Packages</h5>
                                <span class="h2 font-weight-bold mb-0">{{ $stats['total_packages'] }}</span>
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape bg-white text-primary rounded-circle shadow">
                                    <i class="fas fa-box fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card card-stats success">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title text-uppercase text-muted mb-0">Total Orders</h5>
                                <span class="h2 font-weight-bold mb-0">{{ $stats['total_orders'] }}</span>
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape bg-white text-success rounded-circle shadow">
                                    <i class="fas fa-shopping-cart fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card card-stats warning">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title text-uppercase text-muted mb-0">Pending Orders</h5>
                                <span class="h2 font-weight-bold mb-0">{{ $stats['pending_orders'] }}</span>
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape bg-white text-warning rounded-circle shadow">
                                    <i class="fas fa-clock fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card card-stats danger">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title text-uppercase text-muted mb-0">My Revenue</h5>
                                <span class="h2 font-weight-bold mb-0">Rp
                                    {{ number_format($stats['total_revenue'], 0, ',', '.') }}</span>
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape bg-white text-danger rounded-circle shadow">
                                    <i class="fas fa-money-bill-wave fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="row">
        <!-- Quick Actions -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bolt text-warning me-2"></i>
                        Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @if (Auth::user()->role === 'admin')
                            <!-- Admin Quick Actions -->
                            <div class="col-md-4 mb-3">
                                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary w-100 py-3">
                                    <i class="fas fa-users fa-2x d-block mb-2"></i>
                                    Manage Users
                                </a>
                            </div>
                            <div class="col-md-4 mb-3">
                                <a href="{{ route('admin.vendor-profile.index') }}"
                                    class="btn btn-outline-success w-100 py-3">
                                    <i class="fas fa-store fa-2x d-block mb-2"></i>
                                    Vendor Profiles
                                </a>
                            </div>
                            <div class="col-md-4 mb-3">
                                <a href="{{ route('admin.banners.index') }}" class="btn btn-outline-warning w-100 py-3">
                                    <i class="fas fa-image fa-2x d-block mb-2"></i>
                                    Manage Banners
                                </a>
                            </div>
                            <div class="col-md-4 mb-3">
                                <a href="{{ route('admin.news.index') }}" class="btn btn-outline-info w-100 py-3">
                                    <i class="fas fa-newspaper fa-2x d-block mb-2"></i>
                                    Manage News
                                </a>
                            </div>
                            <div class="col-md-4 mb-3">
                                <a href="{{ route('admin.vendor-categories.index') }}"
                                    class="btn btn-outline-secondary w-100 py-3">
                                    <i class="fas fa-tags fa-2x d-block mb-2"></i>
                                    Categories
                                </a>
                            </div>
                            <div class="col-md-4 mb-3">
                                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-danger w-100 py-3">
                                    <i class="fas fa-shopping-cart fa-2x d-block mb-2"></i>
                                    View Orders
                                </a>
                            </div>
                        @else
                            <!-- Vendor Quick Actions -->
                            <div class="col-md-4 mb-3">
                                <a href="{{ route('admin.vendor-profile.index') }}"
                                    class="btn btn-outline-primary w-100 py-3">
                                    <i class="fas fa-store fa-2x d-block mb-2"></i>
                                    My Profile
                                </a>
                            </div>
                            <div class="col-md-4 mb-3">
                                <a href="{{ route('admin.vendor-packages.index') }}"
                                    class="btn btn-outline-success w-100 py-3">
                                    <i class="fas fa-box fa-2x d-block mb-2"></i>
                                    My Packages
                                </a>
                            </div>
                            <div class="col-md-4 mb-3">
                                <a href="{{ route('admin.portfolio-images.index') }}"
                                    class="btn btn-outline-warning w-100 py-3">
                                    <i class="fas fa-images fa-2x d-block mb-2"></i>
                                    My Portfolio
                                </a>
                            </div>
                            <div class="col-md-4 mb-3">
                                <a href="{{ route('admin.vendor-availability.index') }}"
                                    class="btn btn-outline-info w-100 py-3">
                                    <i class="fas fa-calendar-check fa-2x d-block mb-2"></i>
                                    My Availability
                                </a>
                            </div>
                            <div class="col-md-4 mb-3">
                                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-danger w-100 py-3">
                                    <i class="fas fa-shopping-cart fa-2x d-block mb-2"></i>
                                    My Orders
                                </a>
                            </div>
                            <div class="col-md-4 mb-3">
                                <a href="{{ route('admin.reviews.index') }}"
                                    class="btn btn-outline-secondary w-100 py-3">
                                    <i class="fas fa-star-half-alt fa-2x d-block mb-2"></i>
                                    My Reviews
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- System Info -->
        <div class="col-lg-4">
            @if (Auth::user()->role === 'admin')
                <!-- Admin System Info -->
                {{-- <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-info-circle text-info me-2"></i>
                            System Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span>Pending Orders</span>
                            <span class="badge bg-warning">{{ $stats['pending_orders'] }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span>Active Banners</span>
                            <span class="badge bg-success">{{ $stats['active_banners'] }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span>Laravel Version</span>
                            <span class="badge bg-info">{{ app()->version() }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span>PHP Version</span>
                            <span class="badge bg-secondary">{{ PHP_VERSION }}</span>
                        </div>
                    </div>
                </div> --}}
            @else
                <!-- Vendor Profile Info -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-user-circle text-info me-2"></i>
                            Profile Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span>Profile Rating</span>
                            <div>
                                @for ($i = 1; $i <= 5; $i++)
                                    <i
                                        class="fas fa-star {{ $i <= $stats['profile_rating'] ? 'text-warning' : 'text-muted' }}"></i>
                                @endfor
                                <span class="ms-2 badge bg-info">{{ number_format($stats['profile_rating'], 1) }}</span>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span>Total Reviews</span>
                            <span class="badge bg-success">{{ $stats['total_reviews'] }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span>Pending Orders</span>
                            <span class="badge bg-warning">{{ $stats['pending_orders'] }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span>Active Packages</span>
                            <span class="badge bg-primary">{{ $stats['total_packages'] }}</span>
                        </div>
                    </div>
                </div>
            @endif

            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-clock text-success me-2"></i>
                        Recent Activity
                    </h5>
                </div>
                <div class="card-body">
                    @if (Auth::user()->role === 'admin')
                        <!-- Admin Recent Activity -->
                        <div class="activity-item d-flex align-items-center mb-3">
                            <div class="activity-icon bg-primary text-white rounded-circle me-3"
                                style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-user fa-sm"></i>
                            </div>
                            <div>
                                <div class="fw-bold">New User Registration</div>
                                <small class="text-muted">5 minutes ago</small>
                            </div>
                        </div>

                        <div class="activity-item d-flex align-items-center mb-3">
                            <div class="activity-icon bg-success text-white rounded-circle me-3"
                                style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-shopping-cart fa-sm"></i>
                            </div>
                            <div>
                                <div class="fw-bold">New Order Received</div>
                                <small class="text-muted">15 minutes ago</small>
                            </div>
                        </div>

                        <div class="activity-item d-flex align-items-center">
                            <div class="activity-icon bg-warning text-white rounded-circle me-3"
                                style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-store fa-sm"></i>
                            </div>
                            <div>
                                <div class="fw-bold">Vendor Profile Updated</div>
                                <small class="text-muted">1 hour ago</small>
                            </div>
                        </div>
                    @else
                        <!-- Vendor Recent Activity -->
                        <div class="activity-item d-flex align-items-center mb-3">
                            <div class="activity-icon bg-success text-white rounded-circle me-3"
                                style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-shopping-cart fa-sm"></i>
                            </div>
                            <div>
                                <div class="fw-bold">New Order Received</div>
                                <small class="text-muted">2 hours ago</small>
                            </div>
                        </div>

                        <div class="activity-item d-flex align-items-center mb-3">
                            <div class="activity-icon bg-warning text-white rounded-circle me-3"
                                style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-star fa-sm"></i>
                            </div>
                            <div>
                                <div class="fw-bold">New Review Received</div>
                                <small class="text-muted">1 day ago</small>
                            </div>
                        </div>

                        <div class="activity-item d-flex align-items-center">
                            <div class="activity-icon bg-info text-white rounded-circle me-3"
                                style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-images fa-sm"></i>
                            </div>
                            <div>
                                <div class="fw-bold">Portfolio Updated</div>
                                <small class="text-muted">2 days ago</small>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
