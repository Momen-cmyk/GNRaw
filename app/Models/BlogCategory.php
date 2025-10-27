<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BlogCategory extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'parent_id',
        'level',
        'image',
        'is_active',
        'sort_order',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the parent category.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'parent_id');
    }

    /**
     * Get the child categories.
     */
    public function children(): HasMany
    {
        return $this->hasMany(BlogCategory::class, 'parent_id');
    }

    /**
     * Get the blog posts for this category.
     */
    public function posts(): HasMany
    {
        return $this->hasMany(BlogPost::class, 'category_id');
    }

    /**
     * Get the full path of the category hierarchy.
     */
    public function getFullPathAttribute(): string
    {
        $path = [$this->name];
        $parent = $this->parent;

        while ($parent) {
            array_unshift($path, $parent->name);
            $parent = $parent->parent;
        }

        return implode(' > ', $path);
    }

    /**
     * Scope a query to only include active categories.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include parent categories (level 1).
     */
    public function scopeParents($query)
    {
        return $query->where('level', 1);
    }

    /**
     * Scope a query to only include root categories (no parent).
     */
    public function scopeRootCategories($query)
    {
        return $query->whereNull('parent_id');
    }
}
