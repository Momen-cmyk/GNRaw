@extends('front.layout.app')
@section('content')
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="hero-title">Welcome to GNRAW</h1>
                    <p class="hero-subtitle">Your trusted partner for premium pharmaceutical and nutraceutical products. We
                        connect you with verified suppliers and quality products.</p>
                    <div class="hero-buttons">
                        <a href="{{ route('products') }}" class="btn btn-primary btn-lg">Browse Products</a>
                        <a href="{{ route('contact') }}" class="btn btn-outline-primary btn-lg">Contact Us</a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="hero-image">
                        <img src="{{ asset('images/hero-pharmaceutical.jpg') }}" alt="Pharmaceutical Products"
                            class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="categories-section py-5">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <h2 class="section-title">Product Categories</h2>
                    <p class="section-subtitle">Explore our wide range of pharmaceutical and nutraceutical categories</p>
                </div>
            </div>
            <div class="row">
                @forelse($categories as $category)
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                        <div class="category-card">
                            <div class="category-image">
                                @if ($category->image)
                                    <img src="{{ asset($category->image_url) }}" alt="{{ $category->name }}"
                                        class="img-fluid"
                                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div class="category-icon" style="display: none;">
                                        @switch($category->name)
                                            @case('Vitamins')
                                                <i class="fa fa-pills"></i>
                                            @break

                                            @case('Minerals')
                                                <i class="fa fa-gem"></i>
                                            @break

                                            @case('Herbal Extracts')
                                                <i class="fa fa-leaf"></i>
                                            @break

                                            @case('Pharmaceuticals')
                                                <i class="fa fa-pills"></i>
                                            @break

                                            @case('Nutraceuticals')
                                                <i class="fa fa-heart"></i>
                                            @break

                                            @case('Medical Devices')
                                                <i class="fa fa-stethoscope"></i>
                                            @break

                                            @default
                                                <i class="fa fa-pills"></i>
                                        @endswitch
                                    </div>
                                @else
                                    <div class="category-icon">
                                        @switch($category->name)
                                            @case('Vitamins')
                                                <i class="fa fa-vitamin"></i>
                                            @break

                                            @case('Minerals')
                                                <i class="fa fa-gem"></i>
                                            @break

                                            @case('Herbal Extracts')
                                                <i class="fa fa-leaf"></i>
                                            @break

                                            @case('Pharmaceuticals')
                                                <i class="fa fa-pills"></i>
                                            @break

                                            @case('Nutraceuticals')
                                                <i class="fa fa-heart"></i>
                                            @break

                                            @case('Medical Devices')
                                                <i class="fa fa-stethoscope"></i>
                                            @break

                                            @default
                                                <i class="fa fa-pills"></i>
                                        @endswitch
                                    </div>
                                @endif
                            </div>
                            <h5 class="category-name">{{ $category->name }}</h5>
                            <p class="category-description">{{ Str::limit($category->description, 80) }}</p>
                            <a href="{{ route('category.products', $category->slug) }}"
                                class="btn btn-outline-primary btn-sm">View Products</a>
                        </div>
                    </div>
                    @empty
                        <div class="col-12 text-center">
                            <p class="text-muted">No categories available at the moment.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        <!-- Featured Products Section -->
        @if ($featuredProducts->count() > 0)
            <section class="featured-products-section py-5 bg-light">
                <div class="container">
                    <div class="row">
                        <div class="col-12 text-center mb-5">
                            <h2 class="section-title">Featured Products</h2>
                            <p class="section-subtitle">Discover our handpicked selection of premium products</p>
                        </div>
                    </div>
                    <div class="row">
                        @foreach ($featuredProducts as $product)
                            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                                <div class="product-card">
                                    <div class="product-image">
                                        @if ($product->product_images && count($product->product_images) > 0)
                                            <img src="{{ asset('storage/' . $product->product_images[0]) }}"
                                                alt="{{ $product->product_name }}" class="img-fluid">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center"
                                                style="height: 200px;">
                                                <i class="fa fa-image fa-3x text-muted"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="product-content">
                                        <h5 class="product-name">{{ $product->product_name }}</h5>
                                        <p class="product-category">{{ $product->product_category }}</p>
                                        <p class="product-description">{{ Str::limit($product->description, 100) }}</p>
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
                    <div class="row">
                        <div class="col-12 text-center">
                            <a href="{{ route('products') }}" class="btn btn-primary">View All Products</a>
                        </div>
                    </div>
                </div>
            </section>
        @endif

        <!-- Recent Products Section -->
        @if ($recentProducts->count() > 0)
            <section class="recent-products-section py-5">
                <div class="container">
                    <div class="row">
                        <div class="col-12 text-center mb-5">
                            <h2 class="section-title">Latest Products</h2>
                            <p class="section-subtitle">Check out our newest additions to the catalog</p>
                        </div>
                    </div>
                    <div class="row">
                        @foreach ($recentProducts as $product)
                            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                                <div class="product-card">
                                    <div class="product-image">
                                        @if ($product->product_images && count($product->product_images) > 0)
                                            <img src="{{ asset('storage/' . $product->product_images[0]) }}"
                                                alt="{{ $product->product_name }}" class="img-fluid">
                                        @else
                                            <img src="{{ asset('images/placeholder-product.jpg') }}"
                                                alt="{{ $product->product_name }}" class="img-fluid">
                                        @endif
                                    </div>
                                    <div class="product-content">
                                        <h5 class="product-name">{{ $product->product_name }}</h5>
                                        <p class="product-category">{{ $product->category }}</p>
                                        <p class="product-description">{{ Str::limit($product->description, 100) }}</p>
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
                </div>
            </section>
        @endif

        <!-- Contact Info Section -->
        @if (settings()->site_email || settings()->site_phone || settings()->site_address)
            <section class="contact-info-section py-5 bg-light">
                <div class="container">
                    <div class="row">
                        <div class="col-12 text-center mb-5">
                            <h2 class="section-title">Contact Information</h2>
                            <p class="section-subtitle">Get in touch with us for any inquiries or support</p>
                        </div>
                    </div>
                    <div class="row">
                        @if (settings()->site_email)
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="contact-info-card">
                                    <div class="contact-icon">
                                        <i class="fa fa-envelope"></i>
                                    </div>
                                    <h5>Email Us</h5>
                                    <p>{{ settings()->site_email }}</p>
                                    <a href="mailto:{{ settings()->site_email }}" class="btn btn-outline-primary btn-sm">Send
                                        Email</a>
                                </div>
                            </div>
                        @endif

                        @if (settings()->site_phone)
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="contact-info-card">
                                    <div class="contact-icon">
                                        <i class="fa fa-phone"></i>
                                    </div>
                                    <h5>Call Us</h5>
                                    <p>{{ settings()->site_phone }}</p>
                                    <a href="tel:{{ settings()->site_phone }}" class="btn btn-outline-primary btn-sm">Call
                                        Now</a>
                                </div>
                            </div>
                        @endif

                        @if (settings()->site_address)
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="contact-info-card">
                                    <div class="contact-icon">
                                        <i class="fa fa-map-marker-alt"></i>
                                    </div>
                                    <h5>Visit Us</h5>
                                    <p>
                                        {{ settings()->site_address }}
                                        @if (settings()->site_city)
                                            <br>{{ settings()->site_city }}
                                        @endif
                                        @if (settings()->site_state)
                                            , {{ settings()->site_state }}
                                        @endif
                                        @if (settings()->site_country)
                                            <br>{{ settings()->site_country }}
                                        @endif
                                        @if (settings()->site_zip_code)
                                            {{ settings()->site_zip_code }}
                                        @endif
                                    </p>
                                    @if (settings()->site_website)
                                        <a href="{{ settings()->site_website }}" target="_blank"
                                            class="btn btn-outline-primary btn-sm">Visit Website</a>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </section>
        @endif

        <!-- Social Media Section -->
        @if (settings()->facebook_url ||
                settings()->twitter_url ||
                settings()->instagram_url ||
                settings()->linkedin_url ||
                settings()->youtube_url ||
                settings()->whatsapp_number ||
                settings()->telegram_username)
            <section class="social-media-section py-5">
                <div class="container">
                    <div class="row">
                        <div class="col-12 text-center mb-5">
                            <h2 class="section-title">Follow Us</h2>
                            <p class="section-subtitle">Stay connected with us on social media</p>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <div class="social-media-links">
                                @if (settings()->facebook_url)
                                    <a href="{{ settings()->facebook_url }}" target="_blank" class="social-link facebook">
                                        <i class="fab fa-facebook-f"></i>
                                        <span>Facebook</span>
                                    </a>
                                @endif

                                @if (settings()->twitter_url)
                                    <a href="{{ settings()->twitter_url }}" target="_blank" class="social-link twitter">
                                        <i class="fab fa-x-twitter"></i>
                                        <span>X</span>
                                    </a>
                                @endif

                                @if (settings()->instagram_url)
                                    <a href="{{ settings()->instagram_url }}" target="_blank" class="social-link instagram">
                                        <i class="fab fa-instagram"></i>
                                        <span>Instagram</span>
                                    </a>
                                @endif

                                @if (settings()->linkedin_url)
                                    <a href="{{ settings()->linkedin_url }}" target="_blank" class="social-link linkedin">
                                        <i class="fab fa-linkedin-in"></i>
                                        <span>LinkedIn</span>
                                    </a>
                                @endif

                                @if (settings()->youtube_url)
                                    <a href="{{ settings()->youtube_url }}" target="_blank" class="social-link youtube">
                                        <i class="fab fa-youtube"></i>
                                        <span>YouTube</span>
                                    </a>
                                @endif

                                @if (settings()->whatsapp_number)
                                    <a href="https://wa.me/{{ str_replace(['+', ' ', '-', '(', ')'], '', settings()->whatsapp_number) }}"
                                        target="_blank" class="social-link whatsapp">
                                        <i class="fab fa-whatsapp"></i>
                                        <span>WhatsApp</span>
                                    </a>
                                @endif

                                @if (settings()->telegram_username)
                                    <a href="https://t.me/{{ str_replace('@', '', settings()->telegram_username) }}"
                                        target="_blank" class="social-link telegram">
                                        <i class="fab fa-telegram-plane"></i>
                                        <span>Telegram</span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endif

        <!-- CTA Section -->
        <section class="cta-section py-5 bg-primary text-white">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h3 class="cta-title">Ready to Find the Right Products?</h3>
                        <p class="cta-subtitle">Contact us today for personalized product recommendations and competitive
                            pricing.</p>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <a href="{{ route('contact') }}" class="btn btn-light btn-lg">Get Started</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Featured Blog Posts Slider -->
        @if ($featuredBlogPosts && $featuredBlogPosts->count() > 0)
            <section class="blog-slider-section py-5 bg-light">
                <div class="container">
                    <div class="row">
                        <div class="col-12 text-center mb-5">
                            <h2 class="section-title">Featured Articles</h2>
                            <p class="section-subtitle">Stay updated with the latest insights and industry news</p>
                        </div>
                    </div>
                    <div id="blogSlider" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach ($featuredBlogPosts as $index => $post)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    <div class="row align-items-center">
                                        <div class="col-md-6">
                                            <img src="{{ asset('storage/' . $post->featured_image) }}"
                                                class="d-block w-100 rounded" alt="{{ $post->title }}"
                                                style="height: 300px; object-fit: cover;">
                                        </div>
                                        <div class="col-md-6">
                                            <div class="carousel-caption text-start text-dark">
                                                <span class="badge bg-primary mb-2">{{ $post->category->name }}</span>
                                                <h3 class="mb-3">{{ $post->title }}</h3>
                                                <p class="mb-3">{{ Str::limit($post->excerpt, 150) }}</p>
                                                <div class="d-flex align-items-center mb-3">
                                                    <small class="text-muted">
                                                        <i class="fa fa-user me-1"></i> {{ $post->author->name }}
                                                        <i class="fa fa-calendar ms-3 me-1"></i>
                                                        {{ $post->published_at->format('M d, Y') }}
                                                    </small>
                                                </div>
                                                <a href="{{ route('blog.show', $post->slug) }}" class="btn btn-primary">
                                                    Read More <i class="fa fa-arrow-right ms-1"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if ($featuredBlogPosts->count() > 1)
                            <button class="carousel-control-prev" type="button" data-bs-target="#blogSlider"
                                data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#blogSlider"
                                data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </button>
                        @endif
                    </div>
                </div>
            </section>
        @endif

        <!-- Latest Blog Posts Grid -->
        @if ($latestBlogPosts && $latestBlogPosts->count() > 0)
            <section class="latest-blog-section py-5">
                <div class="container">
                    <div class="row">
                        <div class="col-12 text-center mb-5">
                            <h2 class="section-title">Latest Articles</h2>
                            <p class="section-subtitle">Discover our most recent blog posts and industry insights</p>
                        </div>
                    </div>
                    <div class="row">
                        @foreach ($latestBlogPosts as $post)
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="card h-100 border-0"
                                    style="background: #FFFFFF; border-radius: 10px; box-shadow: 0px 8px 28px -6px rgba(24, 39, 75, 0.12), 0px 18px 88px -4px rgba(24, 39, 75, 0.14);">
                                    <div class="position-relative">
                                        <img src="{{ asset('storage/' . $post->featured_image) }}" class="card-img-top"
                                            alt="{{ $post->title }}" style="height: 200px; object-fit: cover;">
                                        <div class="position-absolute top-0 start-0 m-2">
                                            <span class="badge bg-primary">{{ $post->category->name }}</span>
                                        </div>
                                    </div>
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title">{{ $post->title }}</h5>
                                        <p class="card-text text-muted flex-grow-1">{{ Str::limit($post->excerpt, 100) }}</p>
                                        <div class="d-flex align-items-center mb-3">
                                            <small class="text-muted">
                                                <i class="fa fa-user me-1"></i> {{ $post->author->name }}
                                                <i class="fa fa-calendar ms-3 me-1"></i>
                                                {{ $post->published_at->format('M d, Y') }}
                                            </small>
                                        </div>
                                        <a href="{{ route('blog.show', $post->slug) }}" class="btn btn-outline-primary">
                                            Read More <i class="fa fa-arrow-right ms-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="row">
                        <div class="col-12 text-center">
                            <a href="{{ route('blog.index') }}" class="btn btn-primary btn-lg">
                                View All Articles <i class="fa fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </section>
        @endif
    @endsection

    @push('styles')
        <style>
            .hero-section {
                padding: 100px 0;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
            }

            .hero-title {
                font-size: 3rem;
                font-weight: 700;
                margin-bottom: 1.5rem;
            }

            .hero-subtitle {
                font-size: 1.2rem;
                margin-bottom: 2rem;
                opacity: 0.9;
            }

            .hero-buttons .btn {
                margin-right: 1rem;
                margin-bottom: 1rem;
            }

            .section-title {
                font-size: 2.5rem;
                font-weight: 600;
                margin-bottom: 1rem;
            }

            .section-subtitle {
                font-size: 1.1rem;
                color: #6c757d;
                margin-bottom: 0;
            }

            .category-card {
                background: #FFFFFF;
                border-radius: 10px;
                padding: 2rem;
                text-align: center;
                box-shadow: 0px 8px 28px -6px rgba(24, 39, 75, 0.12), 0px 18px 88px -4px rgba(24, 39, 75, 0.14);
                transition: transform 0.3s ease;
                height: 100%;
            }

            .category-card:hover {
                transform: translateY(-5px);
            }

            .category-image {
                height: 120px;
                margin-bottom: 1rem;
                display: flex;
                align-items: center;
                justify-content: center;
                overflow: hidden;
                border-radius: 8px;
            }

            .category-image img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .category-icon {
                font-size: 3rem;
                color: #007bff;
                display: flex;
                align-items: center;
                justify-content: center;
                height: 100%;
                background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
                border-radius: 8px;
                transition: all 0.3s ease;
            }

            .category-card:hover .category-icon {
                background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
                color: white;
                transform: scale(1.05);
            }

            .category-name {
                font-weight: 600;
                margin-bottom: 1rem;
            }

            .product-card {
                background: #FFFFFF;
                border-radius: 10px;
                overflow: hidden;
                box-shadow: 0px 8px 28px -6px rgba(24, 39, 75, 0.12), 0px 18px 88px -4px rgba(24, 39, 75, 0.14);
                transition: transform 0.3s ease;
                height: 100%;
            }

            .product-card:hover {
                transform: translateY(-5px);
            }

            .product-image {
                height: 200px;
                overflow: hidden;
            }

            .product-image img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .product-content {
                padding: 1.5rem;
            }

            .product-name {
                font-weight: 600;
                margin-bottom: 0.5rem;
            }

            .product-category {
                color: #007bff;
                font-size: 0.9rem;
                margin-bottom: 0.5rem;
            }

            .product-description {
                color: #6c757d;
                font-size: 0.9rem;
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

            .contact-info-card {
                background: #FFFFFF;
                border-radius: 10px;
                padding: 2rem;
                text-align: center;
                box-shadow: 0px 8px 28px -6px rgba(24, 39, 75, 0.12), 0px 18px 88px -4px rgba(24, 39, 75, 0.14);
                transition: transform 0.3s ease;
                height: 100%;
            }

            .contact-info-card:hover {
                transform: translateY(-5px);
            }

            .contact-icon {
                width: 80px;
                height: 80px;
                background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 1.5rem;
                transition: all 0.3s ease;
            }

            .contact-icon i {
                font-size: 2rem;
                color: white;
            }

            .contact-info-card:hover .contact-icon {
                background: linear-gradient(135deg, #0056b3 0%, #003d82 100%);
                transform: scale(1.1);
            }

            .contact-info-card h5 {
                font-weight: 600;
                margin-bottom: 1rem;
                color: #333;
            }

            .contact-info-card p {
                color: #6c757d;
                margin-bottom: 1.5rem;
                line-height: 1.6;
            }

            .social-media-links {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
                gap: 1rem;
            }

            .social-link {
                display: flex;
                align-items: center;
                padding: 12px 20px;
                border-radius: 50px;
                text-decoration: none;
                color: white;
                font-weight: 500;
                transition: all 0.3s ease;
                min-width: 140px;
                justify-content: center;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            }

            .social-link i {
                font-size: 1.2rem;
                margin-right: 8px;
            }

            .social-link:hover {
                transform: translateY(-3px);
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
                text-decoration: none;
                color: white;
            }

            .social-link.facebook {
                background: linear-gradient(135deg, #1877f2 0%, #0d5bb8 100%);
            }

            .social-link.twitter {
                background: linear-gradient(135deg, #1da1f2 0%, #0d8bd9 100%);
            }

            .social-link.instagram {
                background: linear-gradient(135deg, #e4405f 0%, #c13584 50%, #833ab4 100%);
            }

            .social-link.linkedin {
                background: linear-gradient(135deg, #0077b5 0%, #005885 100%);
            }

            .social-link.youtube {
                background: linear-gradient(135deg, #ff0000 0%, #cc0000 100%);
            }

            .social-link.whatsapp {
                background: linear-gradient(135deg, #25d366 0%, #1ea851 100%);
            }

            .social-link.telegram {
                background: linear-gradient(135deg, #0088cc 0%, #006699 100%);
            }

            @media (max-width: 768px) {
                .social-media-links {
                    flex-direction: column;
                    align-items: center;
                }

                .social-link {
                    width: 100%;
                    max-width: 200px;
                }
            }
        </style>
    @endpush
