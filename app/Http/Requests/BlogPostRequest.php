<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BlogPostRequest extends FormRequest
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
        $postId = $this->route('blog_post') ?? $this->route('id');
        
        return [
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('blog_posts', 'title')->ignore($postId)
            ],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('blog_posts', 'slug')->ignore($postId)
            ],
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'category_id' => 'required|exists:blog_categories,id',
            'author_id' => 'nullable|exists:admins,id',
            'status' => 'required|in:draft,published,scheduled',
            'is_featured' => 'boolean',
            'published_at' => 'nullable|date|after_or_equal:now',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
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
            'title.required' => 'The post title is required.',
            'title.unique' => 'A post with this title already exists.',
            'title.max' => 'The post title may not be greater than 255 characters.',
            'slug.regex' => 'The slug may only contain lowercase letters, numbers, and hyphens.',
            'slug.unique' => 'A post with this slug already exists.',
            'excerpt.max' => 'The excerpt may not be greater than 500 characters.',
            'content.required' => 'The post content is required.',
            'featured_image.image' => 'The file must be an image.',
            'featured_image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif, webp.',
            'featured_image.max' => 'The featured image may not be greater than 5MB.',
            'category_id.required' => 'Please select a category for this post.',
            'category_id.exists' => 'The selected category does not exist.',
            'author_id.exists' => 'The selected author does not exist.',
            'status.required' => 'Please select a status for this post.',
            'status.in' => 'The status must be draft, published, or scheduled.',
            'published_at.date' => 'The published date must be a valid date.',
            'published_at.after_or_equal' => 'The published date must be today or in the future.',
            'meta_title.max' => 'The meta title may not be greater than 255 characters.',
            'meta_description.max' => 'The meta description may not be greater than 500 characters.',
            'meta_keywords.max' => 'The meta keywords may not be greater than 500 characters.',
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
            'title' => 'post title',
            'slug' => 'URL slug',
            'excerpt' => 'post excerpt',
            'content' => 'post content',
            'featured_image' => 'featured image',
            'category_id' => 'category',
            'author_id' => 'author',
            'status' => 'post status',
            'is_featured' => 'featured status',
            'published_at' => 'published date',
            'meta_title' => 'meta title',
            'meta_description' => 'meta description',
            'meta_keywords' => 'meta keywords',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Auto-generate slug if not provided
        if (!$this->has('slug') && $this->has('title')) {
            $this->merge([
                'slug' => \Str::slug($this->title)
            ]);
        }

        // Ensure boolean values are properly cast
        if ($this->has('is_featured')) {
            $this->merge([
                'is_featured' => (bool) $this->is_featured
            ]);
        }

        // Set author_id to current admin if not provided
        if (!$this->has('author_id') && auth('admin')->check()) {
            $this->merge([
                'author_id' => auth('admin')->id()
            ]);
        }

        // Handle published_at based on status
        if ($this->status === 'published' && !$this->has('published_at')) {
            $this->merge([
                'published_at' => now()
            ]);
        } elseif ($this->status === 'draft') {
            $this->merge([
                'published_at' => null
            ]);
        }
    }
}