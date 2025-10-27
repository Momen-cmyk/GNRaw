@extends('front.layout.app')

@section('content')
    <!-- Jumbotron Header -->
    <div class="jumbotron">
        <div class="jumbotron-content">
            <div class="jumbotron-heading">
                <h1>Product Categories</h1>
            </div>
            <div class="jumbotron-breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span class="separator">/</span>
                <span>Categories</span>
            </div>
        </div>
    </div>

    <!-- Categories Grid -->
    <section class="categories-grid py-5">
        <div class="container">
            <div class="row">
                @forelse($categories as $category)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="category-card">
                            <div class="category-image">
                                @if ($category->image)
                                    <img src="{{ asset($category->image_url) }}" alt="{{ $category->name }}"
                                        class="img-fluid"
                                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div class="category-icon" style="display: none;">
                                        <i class="fa fa-pills"></i>
                                    </div>
                                @else
                                    <div class="category-icon">
                                        <i class="fa fa-pills"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="category-content">
                                <h3 class="category-name">{{ $category->name }}</h3>
                                <p class="category-description">{{ Str::limit($category->description, 120) }}</p>
                                <div class="category-stats">
                                    <span class="product-count">
                                        <i class="fa fa-box"></i>
                                        {{ $category->public_products_count ?? 0 }} Products
                                    </span>
                                </div>
                                <a href="{{ route('category.products', $category->slug) }}" class="btn btn-primary">
                                    View Products <i class="fa fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center">
                        <div class="empty-state">
                            <i class="fa fa-folder-open empty-icon"></i>
                            <h3>No Categories Available</h3>
                            <p class="text-muted">We're working on adding new categories. Please check back soon!
                            </p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section py-5 bg-primary text-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h3 class="cta-title">Can't Find What You're Looking For?</h3>
                    <p class="cta-subtitle">Contact us for custom product recommendations and specialized solutions.
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="{{ route('contact') }}" class="btn btn-light btn-lg">Contact Us</a>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
    <style>
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

        /* Responsive Design for Jumbotron */
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


        .category-card {
            background: #FFFFFF;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0px 8px 28px -6px rgba(24, 39, 75, 0.12), 0px 18px 88px -4px rgba(24, 39, 75, 0.14);
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0px 12px 35px -6px rgba(24, 39, 75, 0.15), 0px 25px 100px -4px rgba(24, 39, 75, 0.18);
        }

        .category-image {
            height: 200px;
            overflow: hidden;
            position: relative;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }

        .category-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .category-card:hover .category-image img {
            transform: scale(1.05);
        }

        .category-icon {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 4rem;
            color: #007bff;
            opacity: 0.7;
        }

        .category-content {
            padding: 2rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .category-name {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #333;
        }

        .category-description {
            color: #666;
            line-height: 1.6;
            margin-bottom: 1.5rem;
            flex: 1;
        }

        .category-stats {
            margin-bottom: 1.5rem;
        }

        .product-count {
            display: inline-flex;
            align-items: center;
            background: #f8f9fa;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            color: #666;
        }

        .product-count i {
            margin-right: 0.5rem;
            color: #007bff;
        }

        .category-card .btn {
            margin-top: auto;
            width: 100%;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
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

        /* Jumbotron Header - Products Style */
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
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
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

        /* Responsive Design for Jumbotron */
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

        /* Responsive Design */
        @media (max-width: 768px) {
            .page-title {
                font-size: 2rem;
            }

            .page-subtitle {
                font-size: 1rem;
            }

            .category-content {
                padding: 1.5rem;
            }

            .category-name {
                font-size: 1.25rem;
            }
        }
    </style>
@endpush
