@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Blog Posts')

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="title">
                    <h4>Blog Posts</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Blog Posts
                        </li>
                    </ol>
                </nav>
            </div>
            <div class="col-md-6 col-sm-12 text-right">
                <a href="{{ route('admin.blog-posts.create') }}" class="btn btn-primary">
                    <i class="fa fa-plus"></i> Add New Post
                </a>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-xl-12 mb-30">
            <div class="card-box height-100-p pd-20">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Author</th>
                                <th>Status</th>
                                <th>Featured</th>
                                <th>Views</th>
                                <th>Published</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($posts as $post)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if ($post->featured_image)
                                                <img src="{{ asset('storage/' . $post->featured_image) }}"
                                                    class="me-3 rounded" alt="{{ $post->title }}"
                                                    style="width: 50px; height: 40px; object-fit: cover;">
                                            @endif
                                            <div>
                                                <h6 class="mb-1">{{ Str::limit($post->title, 50) }}</h6>
                                                <small class="text-muted">{{ Str::limit($post->excerpt, 60) }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $post->category->name }}</span>
                                    </td>
                                    <td>{{ $post->author->name }}</td>
                                    <td>
                                        @if ($post->status === 'published')
                                            <span class="badge bg-success">Published</span>
                                        @elseif($post->status === 'draft')
                                            <span class="badge bg-warning">Draft</span>
                                        @else
                                            <span class="badge bg-info">Scheduled</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($post->is_featured)
                                            <span class="badge bg-primary">Featured</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $post->views_count }}</td>
                                    <td>
                                        @if ($post->published_at)
                                            {{ $post->published_at->format('M d, Y') }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('blog.show', $post->slug) }}"
                                                class="btn btn-sm btn-outline-info" title="View" target="_blank">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.blog-posts.edit', $post->id) }}"
                                                class="btn btn-sm btn-outline-primary" title="Edit">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.blog-posts.destroy', $post->id) }}"
                                                method="POST" class="d-inline"
                                                onsubmit="return confirm('Are you sure you want to delete this post?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fa fa-newspaper-o" style="font-size: 48px; opacity: 0.3;"></i>
                                            <p class="mt-2">No blog posts found. <a
                                                    href="{{ route('admin.blog-posts.create') }}">Create your first
                                                    post</a></p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($posts->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $posts->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
