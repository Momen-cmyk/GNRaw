<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupplierProduct extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'supplier_id',
        'product_name',
        'specifications',
        'product_category',
        'cas_number',
        'molecular_formula',
        'molecular_weight',
        'moq',
        'description',
        'product_images',
        'status',
        'category',
        'subcategory',
        'price',
        'currency',
        'unit_of_measure',
        'minimum_order_quantity',
        'is_available',
        'is_approved',
        'approved_by',
        'approved_at',
        'rejection_reason',
        'last_updated',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'price' => 'decimal:2',
        'moq' => 'decimal:2',
        'product_images' => 'array',
        'is_available' => 'boolean',
        'is_approved' => 'boolean',
        'approved_at' => 'datetime',
        'last_updated' => 'datetime',
    ];

    /**
     * Get the supplier that owns the product.
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function documents()
    {
        return $this->hasMany(ProductDocument::class, 'product_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(Admin::class, 'approved_by');
    }

    public function comments()
    {
        return $this->hasMany(ProductComment::class, 'product_id')->orderBy('created_at', 'desc');
    }

    /**
     * Scope a query to only include products for a specific supplier.
     */
    public function scopeForSupplier($query, $supplierId)
    {
        return $query->where('supplier_id', $supplierId);
    }

    /**
     * Scope a query to only include active (non-deleted) products.
     */
    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }

    /**
     * Scope a query to only include draft (soft deleted) products.
     */
    public function scopeDrafts($query)
    {
        return $query->onlyTrashed();
    }

    /**
     * Scope a query to include both active and draft products.
     */
    public function scopeWithDrafts($query)
    {
        return $query->withTrashed();
    }
}
