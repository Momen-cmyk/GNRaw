<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PublicProduct extends Model
{
    protected $fillable = [
        'supplier_product_id',
        'display_name',
        'short_description',
        'full_description',
        'category',
        'subcategory',
        'images',
        'is_featured',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'images' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function supplierProduct()
    {
        return $this->belongsTo(SupplierProduct::class, 'supplier_product_id');
    }

    /**
     * Get the display name for the product
     */
    public function getDisplayNameAttribute()
    {
        return $this->display_name ?? $this->supplierProduct->product_name ?? 'Unknown Product';
    }

    /**
     * Get the product name (alias for display_name)
     */
    public function getProductNameAttribute()
    {
        return $this->display_name;
    }

    /**
     * Get the description (short or full)
     */
    public function getDescriptionAttribute()
    {
        return $this->full_description ?: $this->short_description;
    }

    /**
     * Get the product images
     */
    public function getProductImagesAttribute()
    {
        return $this->images ?? [];
    }
}
