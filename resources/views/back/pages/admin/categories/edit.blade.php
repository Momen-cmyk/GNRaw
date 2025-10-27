@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Edit Category')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="row">
            <div class="col-md-12">
                <div class="title">
                    <h4>Edit Category</h4>
                </div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.categories') }}">Categories</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card-box">
                <div class="pd-20">
                    <h4 class="text-blue h4">Edit Category: {{ $category->name }}</h4>
                </div>
                <div class="pd-20">
                    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Category Name *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        name="name" value="{{ old('name', $category->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Slug</label>
                                    <input type="text" class="form-control @error('slug') is-invalid @enderror"
                                        name="slug" value="{{ old('slug', $category->slug) }}"
                                        placeholder="auto-generated">
                                    @error('slug')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Leave empty to auto-generate from name</small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="4"
                                placeholder="Category description...">{{ old('description', $category->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Category Image</label>
                            @if ($category->image)
                                <div class="mb-3">
                                    <img src="{{ $category->image_url }}" alt="{{ $category->name }}"
                                        style="width: 100px; height: 100px; object-fit: cover; border-radius: 4px;"
                                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div
                                        style="display: none; width: 100px; height: 100px; background: #f8f9fa; border-radius: 4px; align-items: center; justify-content: center;">
                                        <i class="icon-copy fa fa-image text-muted"></i>
                                    </div>
                                    <p class="text-muted mt-1">Current image</p>
                                </div>
                            @endif
                            <input type="file" class="form-control @error('image') is-invalid @enderror" name="image"
                                accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Recommended size: 300x300px, Max size: 2MB. Leave empty to
                                keep current image.</small>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Sort Order</label>
                                    <input type="number" class="form-control @error('sort_order') is-invalid @enderror"
                                        name="sort_order" value="{{ old('sort_order', $category->sort_order) }}"
                                        min="0">
                                    @error('sort_order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Lower numbers appear first</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Status</label>
                                    <select class="form-control @error('is_active') is-invalid @enderror" name="is_active">
                                        <option value="1"
                                            {{ old('is_active', $category->is_active) ? 'selected' : '' }}>Active</option>
                                        <option value="0"
                                            {{ old('is_active', $category->is_active) == '0' ? 'selected' : '' }}>Inactive
                                        </option>
                                    </select>
                                    @error('is_active')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group text-right">
                            <a href="{{ route('admin.categories') }}" class="btn btn-secondary mr-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="icon-copy fa fa-save"></i> Update Category
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card-box">
                <div class="pd-20">
                    <h4 class="text-blue h4">Category Stats</h4>
                </div>
                <div class="pd-20">
                    <div class="row text-center">
                        <div class="col-12">
                            <h3 class="text-primary">{{ $category->publicProducts()->count() }}</h3>
                            <p class="text-muted">Products in this category</p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-6">
                            <strong>Created:</strong><br>
                            <small class="text-muted">{{ $category->created_at->format('M d, Y') }}</small>
                        </div>
                        <div class="col-6">
                            <strong>Updated:</strong><br>
                            <small class="text-muted">{{ $category->updated_at->format('M d, Y') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
