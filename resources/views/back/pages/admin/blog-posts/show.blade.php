@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'View Blog Post')

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="title">
                    <h4>View Blog Post</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.blog-posts.index') }}">Blog Posts</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            View
                        </li>
                    </ol>
                </nav>
            </div>
            <div class="col-md-6 col-sm-12 text-right">
                <a href="{{ route('admin.blog-posts.edit', $post->id) }}" class="btn btn-primary">
                    <i class="fa fa-edit"></i> Edit Post
                </a>
                <a href="{{ route('blog.show', $post->slug) }}" class="btn btn-info" target="_blank">
                    <i class="fa fa-eye"></i> View Public
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-8 col-lg-10 col-md-12 col-sm-12">
            <div class="card-box height-100-p pd-20">
                <!-- Post Header -->
                <div class="post-header mb-4">
                    <h2 class="post-title">{{ $post->title }}</h2>
                    <div class="post-meta mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <span class="badge bg-info me-2">{{ $post->category->name }}</span>
                            <span
                                class="badge bg-{{ $post->status === 'published' ? 'success' : ($post->status === 'draft' ? 'warning' : 'info') }} me-2">
                                {{ ucfirst($post->status) }}
                            </span>
                            @if ($post->is_featured)
                                <span class="badge bg-primary me-2">Featured</span>
                            @endif
                        </div>
                        <div class="d-flex align-items-center text-muted">
                            <small class="me-3">
                                <i class="fa fa-user me-1"></i> {{ $post->author->name }}
                            </small>
                            <small class="me-3">
                                <i class="fa fa-calendar me-1"></i> {{ $post->created_at->format('M d, Y H:i') }}
                            </small>
                            @if ($post->published_at)
                                <small class="me-3">
                                    <i class="fa fa-clock me-1"></i> Published:
                                    {{ $post->published_at->format('M d, Y H:i') }}
                                </small>
                            @endif
                            <small>
                                <i class="fa fa-eye me-1"></i> {{ $post->views_count }} views
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Featured Image -->
                @if ($post->featured_image)
                    <div class="post-featured-image mb-4">
                        <img src="{{ asset('storage/' . $post->featured_image) }}" class="img-fluid rounded"
                            alt="{{ $post->title }}">
                    </div>
                @endif

                <!-- Excerpt -->
                @if ($post->excerpt)
                    <div class="post-excerpt mb-4">
                        <h5>Excerpt</h5>
                        <p class="text-muted">{{ $post->excerpt }}</p>
                    </div>
                @endif

                <!-- Content -->
                <div class="post-content mb-4">
                    <h5>Content</h5>
                    <div class="content-preview">
                        {!! $post->content !!}
                    </div>
                </div>

                <!-- SEO Information -->
                <div class="post-seo mb-4">
                    <h5>SEO Information</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Meta Title:</strong>
                            <p class="text-muted">{{ $post->meta_title ?: 'Not set' }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Meta Keywords:</strong>
                            <p class="text-muted">{{ $post->meta_keywords ?: 'Not set' }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <strong>Meta Description:</strong>
                            <p class="text-muted">{{ $post->meta_description ?: 'Not set' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Post Images Gallery -->
                @if ($post->images && $post->images->count() > 0)
                    <div class="post-gallery mb-4">
                        <h5>Gallery Images</h5>
                        <div class="row">
                            @foreach ($post->images as $image)
                                <div class="col-md-4 mb-3">
                                    <img src="{{ asset('storage/' . $image->image_path) }}" class="img-fluid rounded"
                                        alt="{{ $image->caption ?? 'Gallery image' }}">
                                    @if ($image->caption)
                                        <p class="text-muted small mt-1">{{ $image->caption }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Actions -->
                <div class="post-actions">
                    <a href="{{ route('admin.blog-posts.edit', $post->id) }}" class="btn btn-primary">
                        <i class="fa fa-edit"></i> Edit Post
                    </a>
                    <a href="{{ route('blog.show', $post->slug) }}" class="btn btn-info" target="_blank">
                        <i class="fa fa-eye"></i> View Public
                    </a>
                    <a href="{{ route('admin.blog-posts.index') }}" class="btn btn-secondary">
                        <i class="fa fa-arrow-left"></i> Back to Posts
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
