@extends('back.layout.supplier-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Edit Product')

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="title">
                    <h4>Edit Product</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('supplier.dashboard') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('supplier.products') }}">Products</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Edit
                        </li>
                </nav>
            </div>
        </div>
    </div>
    </div>

    <div class="row">
        <div class="col-xl-12 mb-30">
            <div class="card-box height-100-p pd-20">
                <h4 class="mb-4">Edit Product Information</h4>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('supplier.products.update', $product->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Basic Information -->
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label">Product Name *</label>
                            <input type="text" class="form-control @error('product_name') is-invalid @enderror"
                                name="product_name" value="{{ old('product_name', $product->product_name) }}" required>
                            @error('product_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Product Category *</label>
                            <select class="form-control @error('product_category') is-invalid @enderror"
                                name="product_category" required>
                                <option value="">Select Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->name }}"
                                        {{ old('product_category', $product->product_category) == $category->name ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('product_category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <small class="text-muted">Categories are managed by admin. Contact admin to add new
                                    categories.</small>
                            </div>
                        </div>
                    </div>

                    <!-- Chemical Information -->
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">CAS Number</label>
                            <input type="text" class="form-control @error('cas_number') is-invalid @enderror"
                                name="cas_number" value="{{ old('cas_number', $product->cas_number) }}"
                                placeholder="e.g., 50-00-0">
                            @error('cas_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Molecular Formula</label>
                            <input type="text" class="form-control @error('molecular_formula') is-invalid @enderror"
                                name="molecular_formula"
                                value="{{ old('molecular_formula', $product->molecular_formula) }}"
                                placeholder="e.g., C6H12O6">
                            @error('molecular_formula')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Molecular Weight</label>
                            <input type="text" class="form-control @error('molecular_weight') is-invalid @enderror"
                                name="molecular_weight" value="{{ old('molecular_weight', $product->molecular_weight) }}"
                                placeholder="e.g., 180.16 g/mol">
                            @error('molecular_weight')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Specifications -->
                    <div class="mb-3">
                        <label class="form-label">Specifications *</label>
                        <textarea class="form-control @error('specifications') is-invalid @enderror" name="specifications" rows="4"
                            required placeholder="Enter detailed product specifications including purity, grade, physical properties, etc.">{{ old('specifications', $product->specifications) }}</textarea>
                        @error('specifications')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Include purity, grade, physical properties, and any relevant technical
                            specifications.</div>
                    </div>

                    <!-- MOQ (Minimum Order Quantity) -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Minimum Order Quantity (MOQ) *</label>
                            <div class="input-group">
                                <input type="number" class="form-control @error('moq') is-invalid @enderror" name="moq"
                                    value="{{ old('moq', $product->moq) }}" required
                                    placeholder="Enter minimum order quantity" step="0.01" min="0">
                                <div class="input-group-append">
                                    <span class="input-group-text">Kg</span>
                                </div>
                            </div>
                            @error('moq')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Minimum order quantity in kilograms.</div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <label class="form-label">Product Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="3"
                            placeholder="Enter a brief description of the product and its applications">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Optional: Brief description of the product and its applications.</div>
                    </div>

                    <!-- Product Images -->
                    <div class="mb-4">
                        <label class="form-label">Product Images *</label>
                        <div class="image-upload-container">
                            <!-- Current Images -->
                            @if ($product->product_images && count($product->product_images) > 0)
                                <div class="current-images mb-3">
                                    <h6>Current Images:</h6>
                                    <div class="image-preview-grid" id="currentImagesGrid">
                                        @foreach ($product->product_images as $index => $image)
                                            <div class="image-preview-item">
                                                <img src="{{ asset('storage/' . $image) }}"
                                                    alt="Current Image {{ $index + 1 }}">
                                                <button type="button" class="remove-btn"
                                                    onclick="removeCurrentImage({{ $index }})">×</button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- New Images Upload -->
                            <div class="new-images">
                                <h6>Add New Images:</h6>
                                <div class="image-preview-grid" id="imagePreviewGrid">
                                    <!-- New images will be previewed here -->
                                </div>
                                <input type="file" class="form-control @error('product_images') is-invalid @enderror"
                                    name="product_images[]" id="productImages" accept="image/*" multiple>
                                @error('product_images')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Upload additional real-time photos of the product (JPG, PNG, max 5MB
                                    each).</div>
                            </div>
                        </div>
                    </div>

                    <!-- COA Document Upload -->
                    <div class="mb-4">
                        <label class="form-label">Certificate of Analysis (COA) *</label>

                        @php
                            $coaDocument = $product->documents()->where('document_type', 'COA')->first();
                        @endphp

                        @if ($coaDocument)
                            <div class="alert alert-info mb-2">
                                <i class="icon-copy dw dw-file-text"></i> Current COA:
                                <a href="{{ Storage::url($coaDocument->file_path) }}" target="_blank"
                                    class="text-primary">
                                    {{ $coaDocument->document_name }}
                                </a>
                            </div>
                        @endif

                        <input type="file" class="form-control @error('coa_document') is-invalid @enderror"
                            name="coa_document" accept=".pdf,.jpg,.jpeg,.png" {{ !$coaDocument ? 'required' : '' }}>
                        @error('coa_document')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            @if ($coaDocument)
                                Upload a new COA to replace the existing one (PDF, JPG, PNG, max 10MB). Leave empty to keep
                                current file.
                            @else
                                Upload Certificate of Analysis for this product (PDF, JPG, PNG, max 10MB). Required.
                            @endif
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="mb-4">
                        <label class="form-label">Product Status</label>
                        <select class="form-control @error('status') is-invalid @enderror" name="status">
                            <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>
                                Active</option>
                            <option value="inactive"
                                {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }}>
                                Inactive</option>
                            <option value="pending_review"
                                {{ old('status', $product->status) == 'pending_review' ? 'selected' : '' }}>Pending Review
                            </option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Form Actions -->
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="icon-copy dw dw-check"></i> Update Product
                            </button>
                            <a href="{{ route('supplier.products') }}" class="btn btn-outline-secondary">
                                <i class="icon-copy dw dw-arrow-left"></i> Cancel
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .form-label {
            font-weight: 500;
            color: #495057;
            margin-bottom: 0.5rem;
        }

        .form-text {
            font-size: 0.875rem;
            color: #6c757d;
        }

        .card-box {
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .image-upload-container {
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            transition: border-color 0.3s ease;
        }

        .image-upload-container:hover {
            border-color: #007bff;
        }

        .image-preview-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 15px;
            margin-bottom: 15px;
        }

        .image-preview-item {
            position: relative;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .image-preview-item img {
            width: 100%;
            height: 120px;
            object-fit: cover;
        }

        .image-preview-item .remove-btn {
            position: absolute;
            top: 5px;
            right: 5px;
            background: rgba(220, 53, 69, 0.8);
            color: white;
            border: none;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            font-size: 12px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .image-preview-item .remove-btn:hover {
            background: rgba(220, 53, 69, 1);
        }

        .current-images h6,
        .new-images h6 {
            color: #495057;
            margin-bottom: 10px;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const imageInput = document.getElementById('productImages');
            const previewGrid = document.getElementById('imagePreviewGrid');
            let selectedFiles = [];
            let currentImages = [];

            // Initialize current images
            const currentImagesElements = document.querySelectorAll('#currentImagesGrid .image-preview-item');
            currentImagesElements.forEach((element, index) => {
                currentImages.push({
                    element: element,
                    index: index
                });
            });

            imageInput.addEventListener('change', function(e) {
                const files = Array.from(e.target.files);

                // Clear previous new image previews
                previewGrid.innerHTML = '';
                selectedFiles = [];

                files.forEach((file, index) => {
                    if (file.type.startsWith('image/')) {
                        selectedFiles.push(file);

                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const previewItem = document.createElement('div');
                            previewItem.className = 'image-preview-item';
                            previewItem.innerHTML = `
                                <img src="${e.target.result}" alt="Preview ${index + 1}">
                                <button type="button" class="remove-btn" onclick="removeNewImage(${index})">×</button>
                            `;
                            previewGrid.appendChild(previewItem);
                        };
                        reader.readAsDataURL(file);
                    }
                });
            });

            window.removeCurrentImage = function(index) {
                const imageElement = currentImages[index].element;
                imageElement.remove();
                currentImages.splice(index, 1);

                // Update indices
                currentImages.forEach((img, newIndex) => {
                    img.index = newIndex;
                    const removeBtn = img.element.querySelector('.remove-btn');
                    removeBtn.setAttribute('onclick', `removeCurrentImage(${newIndex})`);
                });
            };

            window.removeNewImage = function(index) {
                selectedFiles.splice(index, 1);

                // Update the file input
                const dt = new DataTransfer();
                selectedFiles.forEach(file => dt.items.add(file));
                imageInput.files = dt.files;

                // Refresh preview
                previewGrid.innerHTML = '';
                selectedFiles.forEach((file, newIndex) => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const previewItem = document.createElement('div');
                        previewItem.className = 'image-preview-item';
                        previewItem.innerHTML = `
                            <img src="${e.target.result}" alt="Preview ${newIndex + 1}">
                            <button type="button" class="remove-btn" onclick="removeNewImage(${newIndex})">×</button>
                        `;
                        previewGrid.appendChild(previewItem);
                    };
                    reader.readAsDataURL(file);
                });
            };
        });
    </script>
@endpush
