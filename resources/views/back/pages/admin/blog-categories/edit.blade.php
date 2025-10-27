@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Edit Blog Category')

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="title">
                    <h4>Edit Blog Category</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.blog-categories.index') }}">Blog Categories</a>
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
                <form method="POST" action="{{ route('admin.blog-categories.update', $category->id) }}"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="name" class="form-label">Category Name *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name', $category->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="parent_id" class="form-label">Parent Category</label>
                                <select class="form-control @error('parent_id') is-invalid @enderror" id="parent_id"
                                    name="parent_id">
                                    <option value="">Select Parent Category (Optional)</option>
                                    @foreach ($parentCategories as $parent)
                                        <option value="{{ $parent->id }}"
                                            {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}
                                            {{ $parent->id == $category->id ? 'disabled' : '' }}>
                                            {{ str_repeat('— ', $parent->level - 1) }}{{ $parent->name }}
                                            @if ($parent->level > 1)
                                                <small class="text-muted">(Level {{ $parent->level }})</small>
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('parent_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                            rows="3">{{ old('description', $category->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="image" class="form-label">Category Image</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image"
                            name="image" accept="image/*">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @if ($category->image)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $category->image) }}" alt="Current category image"
                                    class="img-thumbnail" style="max-width: 200px;">
                                <p class="text-muted small mt-1">Current category image</p>
                            </div>
                        @endif
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="meta_title" class="form-label">Meta Title</label>
                                <input type="text" class="form-control @error('meta_title') is-invalid @enderror"
                                    id="meta_title" name="meta_title"
                                    value="{{ old('meta_title', $category->meta_title) }}">
                                @error('meta_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="meta_description" class="form-label">Meta Description</label>
                                <textarea class="form-control @error('meta_description') is-invalid @enderror" id="meta_description"
                                    name="meta_description" rows="2">{{ old('meta_description', $category->meta_description) }}</textarea>
                                @error('meta_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active Category
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> Update Category
                        </button>
                        <a href="{{ route('admin.blog-categories.index') }}" class="btn btn-secondary">
                            <i class="fa fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
