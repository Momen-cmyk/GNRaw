<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BlogCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization is handled by middleware
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $categoryId = $this->route('blog_category') ?? $this->route('id');
        
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('blog_categories', 'name')->ignore($categoryId)
            ],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('blog_categories', 'slug')->ignore($categoryId)
            ],
            'description' => 'nullable|string|max:1000',
            'parent_id' => 'nullable|exists:blog_categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0|max:999',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The category name is required.',
            'name.unique' => 'A category with this name already exists.',
            'name.max' => 'The category name may not be greater than 255 characters.',
            'slug.regex' => 'The slug may only contain lowercase letters, numbers, and hyphens.',
            'slug.unique' => 'A category with this slug already exists.',
            'description.max' => 'The description may not be greater than 1000 characters.',
            'parent_id.exists' => 'The selected parent category does not exist.',
            'image.image' => 'The file must be an image.',
            'image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif, webp.',
            'image.max' => 'The image may not be greater than 2MB.',
            'sort_order.integer' => 'The sort order must be an integer.',
            'sort_order.min' => 'The sort order must be at least 0.',
            'sort_order.max' => 'The sort order may not be greater than 999.',
            'meta_title.max' => 'The meta title may not be greater than 255 characters.',
            'meta_description.max' => 'The meta description may not be greater than 500 characters.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'category name',
            'slug' => 'URL slug',
            'description' => 'category description',
            'parent_id' => 'parent category',
            'image' => 'category image',
            'is_active' => 'active status',
            'sort_order' => 'sort order',
            'meta_title' => 'meta title',
            'meta_description' => 'meta description',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Auto-generate slug if not provided
        if (!$this->has('slug') && $this->has('name')) {
            $this->merge([
                'slug' => \Str::slug($this->name)
            ]);
        }

        // Ensure boolean values are properly cast
        if ($this->has('is_active')) {
            $this->merge([
                'is_active' => (bool) $this->is_active
            ]);
        }
    }
}