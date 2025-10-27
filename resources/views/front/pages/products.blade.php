@extends('front.layout.app')

@section('content')
    <!-- Jumbotron Header -->
    <div class="jumbotron">
        <div class="jumbotron-content">
            <div class="jumbotron-heading">
                <h1>Our Products</h1>
            </div>
            <div class="jumbotron-breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span class="separator">/</span>
                <span>Products</span>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-12">
                <p class="lead">Discover our wide range of premium nutritional supplements from verified suppliers</p>
            </div>
        </div>

        <!-- Search and Filter Bar -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card"
                    style="background: #FFFFFF; border-radius: 10px; box-shadow: 0px 8px 28px -6px rgba(24, 39, 75, 0.12), 0px 18px 88px -4px rgba(24, 39, 75, 0.14);">
                    <div class="card-body">
                        <form method="GET" action="{{ route('products') }}" class="row g-3">
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="search" value="{{ $search }}"
                                    placeholder="Search products or CAS numbers...">
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" name="category">
                                    <option value="">All Categories</option>
                                    @foreach ($productCategories as $category)
                                        <option value="{{ $category }}"
                                            {{ $selectedCategory === $category ? 'selected' : '' }}>
                                            {{ $category }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" name="sort">
                                    <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>Newest First</option>
                                    <option value="oldest" {{ $sort === 'oldest' ? 'selected' : '' }}>Oldest First</option>
                                    <option value="name" {{ $sort === 'name' ? 'selected' : '' }}>Name A-Z</option>
                                    <option value="category" {{ $sort === 'category' ? 'selected' : '' }}>Category</option>
                                    <option value="moq" {{ $sort === 'moq' ? 'selected' : '' }}>MOQ (Low to High)
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">Search</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                @if ($products->count() > 0)
                    <div class="row">
                        @foreach ($products as $product)
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="card h-100 product-card">
                                    <div class="product-image">
                                        @if ($product->product_images && count($product->product_images) > 0)
                                            <img src="{{ asset('storage/' . $product->product_images[0]) }}"
                                                alt="{{ $product->product_name }}" class="card-img-top"
                                                style="height: 250px; object-fit: cover;">
                                        @else
                                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                                style="height: 250px;">
                                                <i class="fa fa-image fa-3x text-muted"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title">{{ $product->product_name }}</h5>
                                        <p class="text-muted mb-2">
                                            <strong>Category:</strong> {{ $product->product_category }}
                                        </p>
                                        @if ($product->cas_number)
                                            <p class="text-muted mb-2">
                                                <strong>CAS:</strong> {{ $product->cas_number }}
                                            </p>
                                        @endif
                                        <p class="text-muted mb-2">
                                            <strong>MOQ:</strong> {{ $product->moq }} kg
                                        </p>
                                        @if ($product->description)
                                            <p class="card-text flex-grow-1">
                                                {{ Str::limit($product->description, 120) }}
                                            </p>
                                        @endif
                                        <div class="mt-auto">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="text-muted">
                                                    Added {{ $product->created_at->diffForHumans() }}
                                                </small>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('product.detail', $product->id) }}"
                                                        class="btn btn-primary btn-sm">View Details</a>
                                                    <a href="{{ route('contact') }}?product={{ $product->id }}"
                                                        class="btn btn-outline-primary btn-sm">Inquire</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if ($products->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $products->appends(request()->query())->links() }}
                        </div>
                    @endif
                @else
                    <div class="text-center py-5">
                        <div class="text-muted">
                            <i class="fa fa-box fa-4x mb-3"></i>
                            <h4>No Products Found</h4>
                            <p>No products match your search criteria. Try adjusting your filters or search terms.</p>
                            <a href="{{ route('products') }}" class="btn btn-primary">View All Products</a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        .product-card {
            background: #FFFFFF;
            border-radius: 10px;
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
            box-shadow: 0px 8px 28px -6px rgba(24, 39, 75, 0.12), 0px 18px 88px -4px rgba(24, 39, 75, 0.14);
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0px 12px 35px -6px rgba(24, 39, 75, 0.15), 0px 25px 100px -4px rgba(24, 39, 75, 0.18);
        }

        .product-image img {
            border-radius: 0.375rem 0.375rem 0 0;
        }

        .card-title {
            color: #2c3e50;
            font-weight: 600;
        }

        .text-muted {
            font-size: 0.9rem;
        }

        /* Jumbotron Header */
        .jumbotron {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 80px 0;
            margin-bottom: 40px;
            position: relative;
            overflow: hidden;
        }

        .jumbotron::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.1);
            z-index: 1;
        }

        .jumbotron-content {
            position: relative;
            z-index: 2;
            text-align: center;
        }

        .jumbotron-heading h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .jumbotron-breadcrumb {
            font-size: 1.1rem;
            margin-top: 20px;
        }

        .jumbotron-breadcrumb a {
            color: #ffffff;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .jumbotron-breadcrumb a:hover {
            color: #f8f9fa;
        }

        .jumbotron-breadcrumb span {
            color: #e9ecef;
        }

        .jumbotron-breadcrumb .separator {
            color: #adb5bd;
            margin: 0 10px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .jumbotron {
                padding: 60px 0;
            }

            .jumbotron-heading h1 {
                font-size: 2.5rem;
            }
        }

        @media (max-width: 480px) {
            .jumbotron {
                padding: 40px 0;
            }

            .jumbotron-heading h1 {
                font-size: 2rem;
            }
        }
    </style>
@endsection
