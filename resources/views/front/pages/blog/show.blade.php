@extends('front.layout.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/blog.css') }}">
    <style>
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

    <!-- Blog Post Header -->
    <div class="jumbotron">
        <div class="jumbotron-content">
            <div class="jumbotron-heading">
                <h1>{{ Str::limit($post->title, 50) }}</h1>
            </div>
            <div class="jumbotron-breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span class="separator">/</span>
                <a href="{{ route('blog.index') }}">Blog</a>
                <span class="separator">/</span>
                <a href="{{ route('blog.category', $post->category->slug) }}">{{ $post->category->name }}</a>
                <span class="separator">/</span>
                <span>{{ Str::limit($post->title, 30) }}</span>
            </div>
        </div>
    </div>

    <!-- Blog Post Content -->
    <section class="blog-post-content py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <article class="blog-post">
                        <!-- Featured Image -->
                        <div class="post-featured-image mb-4">
                            <img src="{{ asset('storage/' . $post->featured_image) }}" class="img-fluid rounded"
                                alt="{{ $post->title }}">
                        </div>

                        <!-- Post Meta -->
                        <div class="post-meta mb-4">
                            <div class="d-flex align-items-center mb-3">
                                <span class="badge bg-primary me-3">{{ $post->category->name }}</span>
                                <small class="text-muted me-3">
                                    <i class="fa fa-user me-1"></i> {{ $post->author->name }}
                                </small>
                                <small class="text-muted me-3">
                                    <i class="fa fa-calendar me-1"></i> {{ $post->published_at->format('M d, Y') }}
                                </small>
                                <small class="text-muted">
                                    <i class="fa fa-eye me-1"></i> {{ $post->views_count }} views
                                </small>
                            </div>
                            <div class="d-flex align-items-center">
                                <small class="text-muted me-3">
                                    <i class="fa fa-clock me-1"></i> {{ $post->reading_time }} min read
                                </small>
                                <div class="social-share">
                                    <small class="text-muted me-2">Share:</small>
                                    <a href="#" class="btn btn-sm btn-outline-primary me-1">
                                        <i class="fa fa-facebook"></i>
                                    </a>
                                    <a href="#" class="btn btn-sm btn-outline-info me-1">
                                        <i class="fa fa-twitter"></i>
                                    </a>
                                    <a href="#" class="btn btn-sm btn-outline-success me-1">
                                        <i class="fa fa-whatsapp"></i>
                                    </a>
                                    <a href="#" class="btn btn-sm btn-outline-secondary">
                                        <i class="fa fa-link"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Post Title -->
                        <h1 class="post-title mb-4">{{ $post->title }}</h1>

                        <!-- Post Excerpt -->
                        @if ($post->excerpt)
                            <div class="post-excerpt mb-4">
                                <p class="lead text-muted">{{ $post->excerpt }}</p>
                            </div>
                        @endif

                        <!-- Post Content -->
                        <div class="post-content">
                            {!! $post->content !!}
                        </div>

                        <!-- Post Images Gallery -->
                        @if ($post->images && $post->images->count() > 0)
                            <div class="post-gallery mt-5">
                                <h5 class="mb-3">Gallery</h5>
                                <div class="row">
                                    @foreach ($post->images as $image)
                                        <div class="col-md-4 mb-3">
                                            <img src="{{ asset('storage/' . $image->image_path) }}"
                                                class="img-fluid rounded" alt="{{ $image->caption ?? 'Gallery image' }}"
                                                data-bs-toggle="modal" data-bs-target="#imageModal{{ $image->id }}">
                                            @if ($image->caption)
                                                <p class="text-muted small mt-2">{{ $image->caption }}</p>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </article>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <div class="blog-sidebar">
                        <!-- Related Posts -->
                        @if ($relatedPosts && $relatedPosts->count() > 0)
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">Related Articles</h5>
                                </div>
                                <div class="card-body">
                                    @foreach ($relatedPosts as $relatedPost)
                                        <div class="d-flex mb-3">
                                            <img src="{{ asset('storage/' . $relatedPost->featured_image) }}"
                                                class="flex-shrink-0 me-3 rounded" alt="{{ $relatedPost->title }}"
                                                style="width: 80px; height: 60px; object-fit: cover;">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1">
                                                    <a href="{{ route('blog.show', $relatedPost->slug) }}"
                                                        class="text-decoration-none">
                                                        {{ Str::limit($relatedPost->title, 50) }}
                                                    </a>
                                                </h6>
                                                <small class="text-muted">
                                                    {{ $relatedPost->published_at->format('M d, Y') }}
                                                </small>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Category Info -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">Category</h5>
                            </div>
                            <div class="card-body">
                                <h6>{{ $post->category->name }}</h6>
                                @if ($post->category->description)
                                    <p class="text-muted small">{{ $post->category->description }}</p>
                                @endif
                                <a href="{{ route('blog.category', $post->category->slug) }}"
                                    class="btn btn-sm btn-outline-primary">
                                    View All in {{ $post->category->name }}
                                </a>
                            </div>
                        </div>

                        <!-- Author Info -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">About the Author</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="avatar me-3">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                            style="width: 50px; height: 50px;">
                                            {{ substr($post->author->name, 0, 1) }}
                                        </div>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">{{ $post->author->name }}</h6>
                                        <small class="text-muted">Admin</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Image Modal -->
    @if ($post->images && $post->images->count() > 0)
        @foreach ($post->images as $image)
            <div class="modal fade" id="imageModal{{ $image->id }}" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ $image->caption ?? 'Gallery Image' }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body text-center">
                            <img src="{{ asset('storage/' . $image->image_path) }}" class="img-fluid"
                                alt="{{ $image->caption ?? 'Gallery image' }}">
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif

@endsection
