@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Edit Blog Post')

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="title">
                    <h4>Edit Blog Post</h4>
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
                            Edit
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-8 col-lg-10 col-md-12 col-sm-12">
            <div class="card-box height-100-p pd-20">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Validation Errors:</strong>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.blog-posts.update', $post->id) }}"
                    enctype="multipart/form-data" id="blogPostForm">
                    @csrf
                    @method('PUT')

                    <!-- Debug info -->
                    <input type="hidden" name="debug" value="1">

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group mb-3">
                                <label for="title" class="form-label">Post Title *</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                    id="title" name="title" value="{{ old('title', $post->title) }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="category_id" class="form-label">Category *</label>
                                <select class="form-control @error('category_id') is-invalid @enderror" id="category_id"
                                    name="category_id" required>
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('category_id', $post->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="excerpt" class="form-label">Excerpt</label>
                        <textarea class="form-control @error('excerpt') is-invalid @enderror" id="excerpt" name="excerpt" rows="3">{{ old('excerpt', $post->excerpt) }}</textarea>
                        @error('excerpt')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="content" class="form-label">Content</label>
                        <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="10">{{ old('content', $post->content) }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Use the toolbar above to format your content</small>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="featured_image" class="form-label">Featured Image</label>
                                <input type="file" class="form-control @error('featured_image') is-invalid @enderror"
                                    id="featured_image" name="featured_image" accept="image/*">
                                @error('featured_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @if ($post->featured_image)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $post->featured_image) }}"
                                            alt="Current featured image" class="img-thumbnail" style="max-width: 200px;">
                                        <p class="text-muted small mt-1">Current featured image</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="status" class="form-label">Status *</label>
                                <select class="form-control @error('status') is-invalid @enderror" id="status"
                                    name="status" required>
                                    <option value="draft" {{ old('status', $post->status) == 'draft' ? 'selected' : '' }}>
                                        Draft</option>
                                    <option value="published"
                                        {{ old('status', $post->status) == 'published' ? 'selected' : '' }}>Published
                                    </option>
                                    <option value="scheduled"
                                        {{ old('status', $post->status) == 'scheduled' ? 'selected' : '' }}>Scheduled
                                    </option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured"
                                {{ old('is_featured', $post->is_featured) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_featured">
                                Featured Post
                            </label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="meta_title" class="form-label">Meta Title</label>
                                <input type="text" class="form-control @error('meta_title') is-invalid @enderror"
                                    id="meta_title" name="meta_title"
                                    value="{{ old('meta_title', $post->meta_title) }}">
                                @error('meta_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="meta_keywords" class="form-label">Meta Keywords</label>
                                <input type="text" class="form-control @error('meta_keywords') is-invalid @enderror"
                                    id="meta_keywords" name="meta_keywords"
                                    value="{{ old('meta_keywords', $post->meta_keywords) }}">
                                @error('meta_keywords')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="meta_description" class="form-label">Meta Description</label>
                        <textarea class="form-control @error('meta_description') is-invalid @enderror" id="meta_description"
                            name="meta_description" rows="3">{{ old('meta_description', $post->meta_description) }}</textarea>
                        @error('meta_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary" id="updateBtn">
                            <i class="fa fa-save"></i> Update Post
                        </button>
                        <a href="{{ route('admin.blog-posts.index') }}" class="btn btn-secondary">
                            <i class="fa fa-times"></i> Cancel
                        </a>
                        <a href="{{ route('blog.show', $post->slug) }}" class="btn btn-info" target="_blank">
                            <i class="fa fa-eye"></i> View Post
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <!-- TinyMCE CSS -->
    <link rel="stylesheet" href="{{ asset('js/tinymce/skins/ui/oxide/content.min.css') }}">
    <style>
        .tox-tinymce {
            border: 1px solid #ced4da !important;
            border-radius: 0.375rem !important;
        }

        .tox .tox-toolbar__primary {
            background: #f8f9fa !important;
            border-bottom: 1px solid #dee2e6 !important;
        }
    </style>
@endpush

@push('scripts')
    <!-- TinyMCE Script -->
    <script src="{{ asset('js/tinymce/tinymce.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize TinyMCE
            tinymce.init({
                selector: '#content',
                height: 400,
                menubar: false,
                base_url: '{{ asset('js/tinymce') }}',
                suffix: '.min',
                plugins: [
                    'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                    'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                    'insertdatetime', 'media', 'table', 'help', 'wordcount', 'emoticons',
                    'template', 'codesample', 'directionality', 'pagebreak', 'nonbreaking',
                    'save', 'print', 'searchreplace', 'visualchars', 'visualblocks',
                    'template', 'codesample', 'accordion', 'autosave', 'quickbars'
                ],
                toolbar: 'undo redo | blocks fontsize | ' +
                    'bold italic underline strikethrough | forecolor backcolor | ' +
                    'alignleft aligncenter alignright alignjustify | ' +
                    'bullist numlist outdent indent | ' +
                    'link image media table | ' +
                    'code preview fullscreen | ' +
                    'emoticons charmap | ' +
                    'searchreplace | ' +
                    'insertdatetime | ' +
                    'help',
                content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 14px; }',
                fontsize_formats: '8pt 9pt 10pt 11pt 12pt 14pt 16pt 18pt 20pt 22pt 24pt 26pt 28pt 36pt 48pt 72pt',
                block_formats: 'Paragraph=p; Heading 1=h1; Heading 2=h2; Heading 3=h3; Heading 4=h4; Heading 5=h5; Heading 6=h6; Preformatted=pre; Blockquote=blockquote',
                quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
                quickbars_insert_toolbar: 'quickimage quicktable',
                contextmenu: 'link image imagetools table spellchecker configurepermanentpen',
                image_advtab: true,
                image_caption: true,
                image_title: true,
                table_default_attributes: {
                    border: '1'
                },
                table_default_styles: {
                    'border-collapse': 'collapse',
                    'width': '100%'
                },
                branding: false,
                setup: function(editor) {
                    editor.on('change', function() {
                        editor.save();
                    });
                }
            });

            const form = document.querySelector('form');
            const updateBtn = document.getElementById('updateBtn');

            if (form && updateBtn) {
                form.addEventListener('submit', function(e) {
                    // Save TinyMCE content before form submission
                    if (tinymce.get('content')) {
                        tinymce.get('content').save();
                    }

                    // Show loading state
                    updateBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Updating...';
                    updateBtn.disabled = true;
                });
            }
        });
    </script>
@endpush
