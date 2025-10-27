@extends('front.layout.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/blog.css') }}">
    <style>
        .blog-wrap {
            background: #f8f9fa;
        }

        .blog-list ul {
            list-style: none;
            padding: 0;
        }

        .blog-list li {
            margin-bottom: 30px;
            background: #FFFFFF;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0px 8px 28px -6px rgba(24, 39, 75, 0.12), 0px 18px 88px -4px rgba(24, 39, 75, 0.14);
        }

        .blog-img {
            height: 200px;
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .blog-caption {
            padding: 20px;
        }

        .blog-caption h4 a {
            color: #333;
            text-decoration: none;
            font-weight: 600;
        }

        .blog-caption h4 a:hover {
            color: #007bff;
        }

        .blog-by p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .blog-pagination {
            margin-top: 40px;
        }

        .btn-group .btn {
            margin: 0 2px;
        }

        .btn-group .btn.current {
            background: #007bff;
            color: white;
            border-color: #007bff;
        }


        .list-group-item {
            border: none;
            border-bottom: 1px solid #f1f1f1;
            padding: 12px 20px;
        }

        .list-group-item:last-child {
            border-bottom: none;
        }

        .list-group-item.active {
            background: #007bff;
            color: white;
        }

        .badge {
            font-size: 0.75em;
        }

        .latest-post ul {
            list-style: none;
            padding: 0;
        }

        .latest-post li {
            padding: 15px 20px;
            border-bottom: 1px solid #f1f1f1;
        }

        .latest-post li:last-child {
            border-bottom: none;
        }

        .latest-post h4 {
            font-size: 14px;
            margin-bottom: 5px;
        }

        .latest-post h4 a {
            color: #333;
            text-decoration: none;
        }

        .latest-post h4 a:hover {
            color: #007bff;
        }

        .latest-post span {
            color: #007bff;
            font-size: 12px;
            font-weight: 500;
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
    </style>
@endpush

@section('content')

    <!-- Jumbotron Header -->
    <div class="jumbotron">
        <div class="jumbotron-content">
            <div class="jumbotron-heading">
                <h1>{{ $currentCategory->name }}</h1>
            </div>
            <div class="jumbotron-breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span class="separator">/</span>
                <a href="{{ route('blog.index') }}">Blog</a>
                <span class="separator">/</span>
                <span>{{ $currentCategory->name }}</span>
            </div>
        </div>
    </div>

    <div class="blog-wrap">
        <div class="container pd-0">
            <div class="row">
                <div class="col-md-8 col-sm-12">
                    <div class="blog-list">
                        <ul>
                            @forelse($posts as $post)
                                <li>
                                    <div class="row no-gutters">
                                        <div class="col-lg-4 col-md-12 col-sm-12">
                                            <div class="blog-img"
                                                style="background: url('{{ asset('storage/' . $post->featured_image) }}') center center no-repeat;">
                                                <img src="{{ asset('storage/' . $post->featured_image) }}"
                                                    alt="{{ $post->title }}" class="bg_img" style="display: none;">
                                            </div>
                                        </div>
                                        <div class="col-lg-8 col-md-12 col-sm-12">
                                            <div class="blog-caption">
                                                <h4>
                                                    <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                                                </h4>
                                                <div class="blog-by">
                                                    <p>{{ Str::limit($post->excerpt, 200) }}</p>
                                                    <div class="pt-10">
                                                        <a href="{{ route('blog.show', $post->slug) }}"
                                                            class="btn btn-outline-primary">Read More</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @empty
                                <li>
                                    <div class="row no-gutters">
                                        <div class="col-12">
                                            <div class="blog-caption text-center py-5">
                                                <h4>No posts found in this category</h4>
                                                <p>Check back later for new articles in
                                                    {{ $currentCategory->name }}.</p>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforelse
                        </ul>
                    </div>
                    @if ($posts->hasPages())
                        <div class="blog-pagination">
                            <div class="btn-toolbar justify-content-center mb-15">
                                <div class="btn-group">
                                    @if ($posts->currentPage() > 1)
                                        <a href="{{ $posts->previousPageUrl() }}" class="btn btn-outline-primary prev"><i
                                                class="fa fa-angle-double-left"></i></a>
                                    @endif

                                    @for ($i = max(1, $posts->currentPage() - 2); $i <= min($posts->lastPage(), $posts->currentPage() + 2); $i++)
                                        @if ($i == $posts->currentPage())
                                            <span class="btn btn-primary current">{{ $i }}</span>
                                        @else
                                            <a href="{{ $posts->url($i) }}"
                                                class="btn btn-outline-primary">{{ $i }}</a>
                                        @endif
                                    @endfor

                                    @if ($posts->currentPage() < $posts->lastPage())
                                        <a href="{{ $posts->nextPageUrl() }}" class="btn btn-outline-primary next"><i
                                                class="fa fa-angle-double-right"></i></a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="col-md-4 col-sm-12">
                    <div class="card-box mb-30">
                        <h5 class="pd-20 h5 mb-0">Categories</h5>
                        <div class="list-group">
                            <a href="{{ route('blog.index') }}"
                                class="list-group-item d-flex align-items-center justify-content-between">
                                All Categories
                                <span class="badge badge-primary badge-pill">{{ $totalPostsCount }}</span>
                            </a>
                            @foreach ($blogCategories as $category)
                                @include('front.pages.blog.partials.category-tree', [
                                    'category' => $category,
                                    'selectedCategory' => $currentCategory->slug,
                                    'level' => 1,
                                ])
                            @endforeach
                        </div>
                    </div>
                    <div class="card-box mb-30">
                        <h5 class="pd-20 h5 mb-0">Latest Post</h5>
                        <div class="latest-post">
                            <ul>
                                @foreach ($posts->take(5) as $latestPost)
                                    <li>
                                        <h4>
                                            <a
                                                href="{{ route('blog.show', $latestPost->slug) }}">{{ Str::limit($latestPost->title, 50) }}</a>
                                        </h4>
                                        <span>{{ $latestPost->category->name }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="card-box overflow-hidden">
                        <h5 class="pd-20 h5 mb-0">Archives</h5>
                        <div class="list-group">
                            @php
                                $archives = $posts
                                    ->groupBy(function ($post) {
                                        return $post->published_at->format('F Y');
                                    })
                                    ->take(5);
                            @endphp
                            @foreach ($archives as $archive => $archivePosts)
                                <a href="#"
                                    class="list-group-item d-flex align-items-center justify-content-between">{{ $archive }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Blog category script loaded');

            // Handle dropdown toggle ONLY for card-box categories
            const buttons = document.querySelectorAll('.card-box .dropdown-toggle');
            console.log('Found buttons:', buttons.length);

            buttons.forEach(function(button) {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    console.log('Button clicked!');

                    const targetId = this.getAttribute('data-toggle');
                    const dropdown = document.getElementById(targetId);

                    console.log('Target ID:', targetId);
                    console.log('Dropdown found:', dropdown);

                    // Toggle the show class for smooth animation
                    if (dropdown.classList.contains('show')) {
                        dropdown.classList.remove('show');
                        dropdown.style.display = 'none';
                        this.setAttribute('aria-expanded', 'false');
                        console.log('Hiding dropdown');
                    } else {
                        dropdown.classList.add('show');
                        dropdown.style.display = 'block';
                        this.setAttribute('aria-expanded', 'true');
                        console.log('Showing dropdown');
                    }
                });
            });
        });
    </script>
@endpush
