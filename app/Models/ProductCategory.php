<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'image_data',
        'image_mime_type',
        'image_filename',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'image_data', // Hide binary data from arrays/JSON
    ];

    /**
     * Prevent binary data from being returned when accessing attributes
     */
    protected $appends = [];

    /**
     * Override toArray to remove binary data
     */
    public function toArray()
    {
        $array = parent::toArray();
        // Remove any binary data
        unset($array['image_data']);
        return $array;
    }

    /**
     * Check if category has image data (without exposing binary)
     */
    public function hasImageData()
    {
        return !empty($this->attributes['image_data']);
    }

    public function getImageUrlAttribute()
    {
        // Use file system storage only
        if ($this->image) {
            // Return proper storage URL
            return asset('storage/' . $this->image);
        }

        return null;
    }

    /**
     * Get the public products for this category
     */
    public function publicProducts()
    {
        return $this->hasMany(PublicProduct::class, 'category', 'name');
    }

    /**
     * Get image data as binary for serving (directly from attributes)
     */
    public function getImageDataBinary()
    {
        return $this->attributes['image_data'] ?? null;
    }

    /**
     * Override to prevent accessing binary data directly
     */
    public function getImageDataAttribute()
    {
        // Return null to prevent binary data exposure
        return null;
    }
}
