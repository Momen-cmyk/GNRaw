@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Create Category')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="row">
            <div class="col-md-12">
                <div class="title">
                    <h4>Create New Category</h4>
                </div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.categories') }}">Categories</a></li>
                        <li class="breadcrumb-item active">Create</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card-box">
                <div class="pd-20">
                    <h4 class="text-blue h4">Category Information</h4>
                </div>
                <div class="pd-20">
                    <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Category Name *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Slug</label>
                                    <input type="text" class="form-control @error('slug') is-invalid @enderror"
                                        name="slug" value="{{ old('slug') }}" placeholder="auto-generated">
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
                                placeholder="Category description...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Category Image</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" name="image"
                                accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Recommended size: 300x300px, Max size: 2MB</small>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Sort Order</label>
                                    <input type="number" class="form-control @error('sort_order') is-invalid @enderror"
                                        name="sort_order" value="{{ old('sort_order', 0) }}" min="0">
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
                                        <option value="1" {{ old('is_active', 1) ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Inactive
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
                                <i class="icon-copy fa fa-save"></i> Create Category
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card-box">
                <div class="pd-20">
                    <h4 class="text-blue h4">Tips</h4>
                </div>
                <div class="pd-20">
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="icon-copy fa fa-check text-success"></i>
                            Use descriptive category names
                        </li>
                        <li class="mb-2">
                            <i class="icon-copy fa fa-check text-success"></i>
                            Add high-quality images for better presentation
                        </li>
                        <li class="mb-2">
                            <i class="icon-copy fa fa-check text-success"></i>
                            Use sort order to control display sequence
                        </li>
                        <li class="mb-2">
                            <i class="icon-copy fa fa-check text-success"></i>
                            Categories will appear on the home page
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
