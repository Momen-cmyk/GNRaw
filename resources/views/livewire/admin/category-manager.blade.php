<div>
    {{-- Success Message --}}
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (!$showForm)
        {{-- Categories List --}}
        <div class="card-box">
            <div class="pd-20">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="text-blue h4">Categories</h4>
                    <button wire:click="showCreateForm" class="btn btn-primary">
                        <i class="icon-copy fa fa-plus"></i> Create Category
                    </button>
                </div>
            </div>
            <div class="pd-20">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Slug</th>
                                <th>Products</th>
                                <th>Sort Order</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $category)
                                <tr>
                                    <td>
                                        @if ($category->image)
                                            <img src="{{ $category->image_url }}" alt="{{ $category->name }}"
                                                style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                                        @else
                                            <div style="width: 50px; height: 50px; background: #f8f9fa; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                                                <i class="icon-copy fa fa-image text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td><strong>{{ $category->name }}</strong></td>
                                    <td><code>{{ $category->slug }}</code></td>
                                    <td><span class="badge badge-info">{{ $category->publicProducts()->count() }}</span></td>
                                    <td>{{ $category->sort_order }}</td>
                                    <td>
                                        <span class="badge {{ $category->is_active ? 'badge-success' : 'badge-secondary' }}">
                                            {{ $category->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button wire:click="showEditForm({{ $category->id }})"
                                                    class="btn btn-sm btn-primary"
                                                    title="Edit">
                                                <i class="icon-copy fa fa-edit"></i>
                                            </button>
                                            <button wire:click="toggleStatus({{ $category->id }})"
                                                    class="btn btn-sm {{ $category->is_active ? 'btn-warning' : 'btn-success' }}"
                                                    title="Toggle Status">
                                                <i class="icon-copy fa fa-{{ $category->is_active ? 'eye-slash' : 'eye' }}"></i>
                                            </button>
                                            <button wire:click="delete({{ $category->id }})"
                                                    class="btn btn-sm btn-danger"
                                                    title="Delete"
                                                    onclick="return confirm('Are you sure?')">
                                                <i class="icon-copy fa fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No categories found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        {{-- Category Form --}}
        <div class="card-box">
            <div class="pd-20">
                <h4 class="text-blue h4">{{ $editing_id ? 'Edit' : 'Create' }} Category</h4>
            </div>
            <div class="pd-20">
                <form wire:submit.prevent="save">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Category Name *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    wire:model="name" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Slug</label>
                                <input type="text" class="form-control @error('slug') is-invalid @enderror"
                                    wire:model="slug" placeholder="auto-generated">
                                @error('slug')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Leave empty to auto-generate from name</small>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                            wire:model="description" rows="4" placeholder="Category description..."></textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Category Image</label>

                        {{-- Current Image Preview --}}
                        @if ($showImagePreview && $previewImageUrl)
                            <div class="mb-3">
                                <img src="{{ $previewImageUrl }}" alt="Preview"
                                    style="width: 150px; height: 150px; object-fit: cover; border-radius: 4px; border: 2px solid #ddd;"
                                    onerror="this.style.display='none'">
                                <p class="text-muted mt-1">Current image</p>
                            </div>
                        @endif

                        {{-- New Image Upload --}}
                        <input type="file" class="form-control @error('image') is-invalid @enderror"
                            wire:model="image" accept="image/*">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        {{-- Live Preview of New Image --}}
                        @if ($image)
                            <div class="mt-2">
                                @if ($image->isValid())
                                    <p class="text-success">✓ Image selected: {{ $image->getClientOriginalName() }}</p>
                                    <img src="{{ $image->temporaryUrl() }}" alt="Preview"
                                        style="width: 150px; height: 150px; object-fit: cover; border-radius: 4px; border: 2px solid #0f0;">
                                @else
                                    <p class="text-danger">⚠ {{ $image }}</p>
                                @endif
                            </div>
                        @endif

                        <small class="form-text text-muted">
                            Recommended size: 300x300px, Max size: 2MB
                            @if ($editing_id)
                                . Leave empty to keep current image.
                            @endif
                        </small>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Sort Order</label>
                                <input type="number" class="form-control @error('sort_order') is-invalid @enderror"
                                    wire:model="sort_order" min="0">
                                @error('sort_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Lower numbers appear first</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Status</label>
                                <select class="form-control @error('is_active') is-invalid @enderror" wire:model="is_active">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                                @error('is_active')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                            <span wire:loading.remove>
                                <i class="icon-copy fa fa-save"></i> {{ $editing_id ? 'Update' : 'Create' }} Category
                            </span>
                            <span wire:loading>
                                <i class="fa fa-spinner fa-spin"></i> Saving...
                            </span>
                        </button>
                        <button type="button" wire:click="cancel" class="btn btn-secondary">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>

