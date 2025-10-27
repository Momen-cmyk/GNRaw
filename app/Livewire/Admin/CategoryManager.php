<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\ProductCategory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class CategoryManager extends Component
{
    use WithFileUploads;

    public $categories = [];
    public $name = '';
    public $slug = '';
    public $description = '';
    public $image;
    public $is_active = 1;
    public $sort_order = 0;
    public $editing_id = null;
    public $showForm = false;
    public $showImagePreview = false;
    public $previewImageUrl = null;

    protected $rules = [
        'name' => 'required|string|max:255',
        'slug' => 'nullable|string|max:255',
        'description' => 'nullable|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'is_active' => 'boolean',
        'sort_order' => 'integer|min:0',
    ];

    public function mount()
    {
        $this->loadCategories();
    }

    public function loadCategories()
    {
        $this->categories = ProductCategory::orderBy('sort_order')->orderBy('name')->get();
    }

    public function showCreateForm()
    {
        $this->resetForm();
        $this->showForm = true;
        $this->editing_id = null;
    }

    public function showEditForm($id)
    {
        $category = ProductCategory::findOrFail($id);
        $this->editing_id = $id;
        $this->name = $category->name;
        $this->slug = $category->slug;
        $this->description = $category->description;
        $this->is_active = $category->is_active ? 1 : 0;
        $this->sort_order = $category->sort_order ?? 0;
        $this->previewImageUrl = $category->image_url;
        $this->showImagePreview = !empty($category->image);
        $this->showForm = true;
    }

    public function resetForm()
    {
        $this->name = '';
        $this->slug = '';
        $this->description = '';
        $this->image = null;
        $this->is_active = 1;
        $this->sort_order = 0;
        $this->editing_id = null;
        $this->previewImageUrl = null;
        $this->showImagePreview = false;
        $this->resetValidation();
    }

    public function cancel()
    {
        $this->showForm = false;
        $this->resetForm();
    }

    public function updatedImage()
    {
        // Show preview when image is selected
        $this->showImagePreview = true;
        $this->validate(['image' => 'image|mimes:jpeg,png,jpg,gif|max:2048']);
    }

    public function save()
    {
        // Set validation rules dynamically based on create/edit
        $rules = $this->rules;
        if ($this->editing_id) {
            // Update mode
            $rules['name'] = 'required|string|max:255|unique:product_categories,name,' . $this->editing_id;
            $rules['slug'] = 'nullable|string|max:255|unique:product_categories,slug,' . $this->editing_id;
        } else {
            // Create mode
            $rules['name'] = 'required|string|max:255|unique:product_categories,name';
            $rules['slug'] = 'nullable|string|max:255|unique:product_categories,slug';
        }

        $this->validate($rules);

        $data = [
            'name' => $this->name,
            'slug' => $this->slug ?: Str::slug($this->name),
            'description' => $this->description,
            'is_active' => (bool) $this->is_active,
            'sort_order' => $this->sort_order,
        ];

        // Handle image upload
        if ($this->image) {
            // Delete old image if editing
            if ($this->editing_id) {
                $oldCategory = ProductCategory::find($this->editing_id);
                if ($oldCategory && $oldCategory->image) {
                    Storage::disk('public')->delete($oldCategory->image);
                }
            }

            // Store new image
            $imageName = time() . '_' . $this->image->getClientOriginalName();
            $imagePath = $this->image->storeAs('categories', $imageName, 'public');
            $data['image'] = str_replace('\\', '/', $imagePath);

            // Copy to public/storage for XAMPP compatibility (Windows symlink issues)
            $storagePath = Storage::disk('public')->path($imagePath);
            $publicPath = public_path('storage/' . $imagePath);
            if (!file_exists(dirname($publicPath))) {
                mkdir(dirname($publicPath), 0755, true);
            }
            copy($storagePath, $publicPath);

            Log::info('Category image uploaded', [
                'path' => $data['image'],
                'storage_path' => $storagePath,
                'public_path' => $publicPath,
                'copied' => file_exists($publicPath),
                'category_id' => $this->editing_id
            ]);
        }

        // Save category
        if ($this->editing_id) {
            $category = ProductCategory::findOrFail($this->editing_id);
            $category->update($data);
            // Refresh to get updated image URL
            $category->refresh();
            $this->dispatch('showToastr', [
                'type' => 'success',
                'message' => 'Category updated successfully!'
            ]);
        } else {
            $category = ProductCategory::create($data);
            $this->dispatch('showToastr', [
                'type' => 'success',
                'message' => 'Category created successfully!'
            ]);
        }

        $this->loadCategories();

        // Don't cancel if editing - stay in edit mode
        if (!$this->editing_id) {
            $this->cancel();
        } else {
            // Update preview URL after save
            $this->previewImageUrl = $category->image ? ('/storage/' . str_replace('\\', '/', $category->image)) : null;
            $this->showImagePreview = !empty($category->image);
        }
    }

    public function delete($id)
    {
        $category = ProductCategory::findOrFail($id);

        // Delete image if exists
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();
        $this->dispatch('showToastr', [
            'type' => 'success',
            'message' => 'Category deleted successfully!'
        ]);
        $this->loadCategories();
    }

    public function toggleStatus($id)
    {
        $category = ProductCategory::findOrFail($id);
        $category->is_active = !$category->is_active;
        $category->save();
        $this->loadCategories();
    }

    public function render()
    {
        return view('livewire.admin.category-manager');
    }
}
