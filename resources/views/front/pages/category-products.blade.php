@extends('front.layout.app')
@section('content')
    <!-- Page Header -->
    <section class="page-header py-5">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('categories') }}">Categories</a></li>
                            <li class="breadcrumb-item active">{{ $category->name }}</li>
                        </ol>
                    </nav>
                    <h1 class="page-title">{{ $category->name }} Products</h1>
                    <p class="page-subtitle">{{ $category->description }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Search and Filter Section -->
    <section class="search-filter py-4 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <form method="GET" action="{{ route('category.products', $category->slug) }}" class="search-form">
                        <div class="input-group">
                            <input type="text" class="form-control" name="search" value="{{ $search }}"
                                placeholder="Search products in {{ $category->name }}...">
                            <button class="btn btn-primary" type="submit">
                                <i class="fa fa-search"></i> Search
                            </button>
                        </div>
                    </form>
                </div>
                <div class="col-md-4 text-md-end">
                    <span class="text-muted">{{ $products->total() }} products found</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Products Grid -->
    <section class="products-grid py-5">
        <div class="container">
            @if ($products->count() > 0)
                <div class="row">
                    @foreach ($products as $product)
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                            <div class="product-card">
                                <div class="product-image">
                                    @if ($product->images && count($product->images) > 0)
                                        <img src="{{ asset('storage/' . $product->images[0]) }}"
                                            alt="{{ $product->display_name }}" class="img-fluid">
                                    @else
                                        <img src="{{ asset('images/placeholder-product.jpg') }}"
                                            alt="{{ $product->display_name }}" class="img-fluid">
                                    @endif
                                    @if ($product->is_featured)
                                        <div class="featured-badge">Featured</div>
                                    @endif
                                </div>
                                <div class="product-content">
                                    <h5 class="product-name">{{ $product->display_name }}</h5>
                                    <p class="product-category">{{ $product->category }}</p>
                                    <p class="product-description">{{ Str::limit($product->short_description, 100) }}</p>
                                    <div class="product-actions">
                                        <a href="{{ route('product.detail', $product->id) }}"
                                            class="btn btn-primary btn-sm">View Details</a>
                                        <a href="{{ route('contact') }}?product={{ $product->id }}"
                                            class="btn btn-outline-primary btn-sm">Inquire</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if ($products->hasPages())
                    <div class="row">
                        <div class="col-12">
                            <nav aria-label="Products pagination">
                                {{ $products->links() }}
                            </nav>
                        </div>
                    </div>
                @endif
            @else
                <div class="row">
                    <div class="col-12 text-center">
                        <div class="empty-state">
                            <i class="fa fa-box-open empty-icon"></i>
                            <h3>No Products Found</h3>
                            @if ($search)
                                <p class="text-muted">No products found matching "{{ $search }}" in
                                    {{ $category->name }}.</p>
                                <a href="{{ route('category.products', $category->slug) }}" class="btn btn-primary">
                                    View All {{ $category->name }} Products
                                </a>
                            @else
                                <p class="text-muted">No products available in {{ $category->name }} at the moment.</p>
                                <a href="{{ route('categories') }}" class="btn btn-primary">
                                    Browse Other Categories
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section py-5 bg-primary text-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h3 class="cta-title">Need Custom Solutions?</h3>
                    <p class="cta-subtitle">Contact us for personalized product recommendations and competitive pricing.</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="{{ route('contact') }}" class="btn btn-light btn-lg">Get Quote</a>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
    <style>
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .page-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .page-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 0;
        }

        .breadcrumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 25px;
            padding: 0.75rem 1.5rem;
        }

        .breadcrumb-item a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
        }

        .breadcrumb-item a:hover {
            color: white;
        }

        .breadcrumb-item.active {
            color: white;
        }

        .search-form .input-group {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border-radius: 25px;
            overflow: hidden;
        }

        .search-form .form-control {
            border: none;
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
        }

        .search-form .form-control:focus {
            box-shadow: none;
        }

        .product-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            height: 100%;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }

        .product-image {
            height: 200px;
            overflow: hidden;
            position: relative;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .product-card:hover .product-image img {
            transform: scale(1.05);
        }

        .featured-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #ff6b6b;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .product-content {
            padding: 1.5rem;
        }

        .product-name {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #333;
        }

        .product-category {
            color: #007bff;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .product-description {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 1rem;
            line-height: 1.5;
        }

        .product-actions .btn {
            margin-right: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .empty-state {
            padding: 4rem 2rem;
        }

        .empty-icon {
            font-size: 4rem;
            color: #ddd;
            margin-bottom: 1rem;
        }

        .cta-title {
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .cta-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 0;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .page-title {
                font-size: 2rem;
            }

            .page-subtitle {
                font-size: 1rem;
            }

            .search-form .input-group {
                flex-direction: column;
            }

            .search-form .btn {
                border-radius: 0 0 25px 25px;
            }
        }
    </style>
@endpush
