@extends('front.layout.app')
@section('title', 'Dashboard - ' . ($user->name ?? 'User'))

@section('content')
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Welcome back, {{ $user->name }}!</h4>
                        <p class="text-muted mb-0">Here's what's happening with your account</p>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- User Info Card -->
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card bg-primary text-white">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <i class="fa fa-user fa-2x"></i>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h5 class="card-title mb-1">Profile</h5>
                                                <p class="card-text mb-0">{{ $user->email }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Inquiries Card -->
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card bg-info text-white">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <i class="fa fa-envelope fa-2x"></i>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h5 class="card-title mb-1">Inquiries</h5>
                                                <p class="card-text mb-0">{{ $recentInquiries->count() }} recent inquiries
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Quick Actions Card -->
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card bg-success text-white">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <i class="fa fa-cog fa-2x"></i>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h5 class="card-title mb-1">Quick Actions</h5>
                                                <p class="card-text mb-0">Manage your account</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Recent Inquiries -->
                            <div class="col-lg-6 mb-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">Recent Inquiries</h5>
                                    </div>
                                    <div class="card-body">
                                        @if ($recentInquiries->count() > 0)
                                            @foreach ($recentInquiries as $inquiry)
                                                <div class="d-flex align-items-center mb-3 p-3 border rounded">
                                                    <div class="flex-shrink-0">
                                                        <i class="fa fa-envelope text-primary"></i>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <h6 class="mb-1">{{ $inquiry->subject }}</h6>
                                                        <p class="text-muted mb-1">{{ Str::limit($inquiry->message, 50) }}
                                                        </p>
                                                        <small
                                                            class="text-muted">{{ $inquiry->created_at->format('M d, Y') }}</small>
                                                    </div>
                                                    <div class="flex-shrink-0">
                                                        <span
                                                            class="badge badge-{{ $inquiry->status === 'pending' ? 'warning' : 'success' }}">
                                                            {{ ucfirst($inquiry->status) }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="text-center py-4">
                                                <i class="fa fa-envelope fa-3x text-muted mb-3"></i>
                                                <p class="text-muted">No inquiries yet</p>
                                                <a href="{{ route('contact') }}" class="btn btn-primary">Submit an
                                                    Inquiry</a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Featured Products -->
                            <div class="col-lg-6 mb-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">Featured Products</h5>
                                    </div>
                                    <div class="card-body">
                                        @if ($featuredProducts->count() > 0)
                                            <div class="row">
                                                @foreach ($featuredProducts as $product)
                                                    <div class="col-md-6 mb-3">
                                                        <div class="card h-100">
                                                            @if ($product->images && count($product->images) > 0)
                                                                <img src="{{ asset('storage/' . $product->images[0]) }}"
                                                                    class="card-img-top" alt="{{ $product->product_name }}"
                                                                    style="height: 120px; object-fit: cover;">
                                                            @else
                                                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                                                    style="height: 120px;">
                                                                    <i class="fa fa-image fa-2x text-muted"></i>
                                                                </div>
                                                            @endif
                                                            <div class="card-body p-2">
                                                                <h6 class="card-title mb-1">
                                                                    {{ Str::limit($product->product_name, 30) }}</h6>
                                                                <p class="card-text small text-muted">
                                                                    {{ Str::limit($product->description, 50) }}</p>
                                                            </div>
                                                            <div class="card-footer p-2">
                                                                <a href="{{ route('product.detail', $product->id) }}"
                                                                    class="btn btn-sm btn-primary w-100">View Details</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="text-center py-4">
                                                <i class="fa fa-box fa-3x text-muted mb-3"></i>
                                                <p class="text-muted">No featured products available</p>
                                                <a href="{{ route('products') }}" class="btn btn-primary">Browse All
                                                    Products</a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">Quick Actions</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3 mb-3">
                                                <a href="{{ route('user.profile') }}"
                                                    class="btn btn-outline-primary w-100">
                                                    <i class="fa fa-user me-2"></i>Edit Profile
                                                </a>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <a href="{{ route('products') }}" class="btn btn-outline-success w-100">
                                                    <i class="fa fa-search me-2"></i>Browse Products
                                                </a>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <a href="{{ route('contact') }}" class="btn btn-outline-info w-100">
                                                    <i class="fa fa-envelope me-2"></i>Contact Us
                                                </a>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <form action="{{ route('user.logout') }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-danger w-100">
                                                        <i class="fa fa-sign-out me-2"></i>Logout
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
