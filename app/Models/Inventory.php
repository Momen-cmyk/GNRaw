<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $table = 'inventory';

    protected $fillable = [
        'product_id',
        'supplier_id',
        'quantity_available',
        'quantity_reserved',
        'quantity_sold',
        'min_stock_level',
        'reorder_point',
        'max_stock_level',
        'cost_price',
        'selling_price',
        'currency',
        'warehouse_location',
        'bin_location',
        'shelf_number',
        'is_active',
        'is_discontinued',
        'allow_backorder',
        'batch_number',
        'manufacturing_date',
        'expiry_date',
        'notes',
    ];

    protected $casts = [
        'quantity_available' => 'integer',
        'quantity_reserved' => 'integer',
        'quantity_sold' => 'integer',
        'min_stock_level' => 'integer',
        'reorder_point' => 'integer',
        'max_stock_level' => 'integer',
        'cost_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'is_active' => 'boolean',
        'is_discontinued' => 'boolean',
        'allow_backorder' => 'boolean',
        'manufacturing_date' => 'date',
        'expiry_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(SupplierProduct::class, 'product_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function movements()
    {
        return $this->hasMany(InventoryMovement::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeLowStock($query)
    {
        return $query->where('quantity_available', '<=', 'min_stock_level');
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('quantity_available', 0);
    }

    public function scopeExpiringSoon($query, $days = 30)
    {
        return $query->whereNotNull('expiry_date')
            ->where('expiry_date', '<=', now()->addDays($days))
            ->where('expiry_date', '>', now());
    }

    // Helper Methods
    public function getAvailableStockAttribute()
    {
        return $this->quantity_available - $this->quantity_reserved;
    }

    public function isLowStock()
    {
        return $this->quantity_available <= $this->min_stock_level;
    }

    public function isOutOfStock()
    {
        return $this->quantity_available <= 0;
    }

    public function needsReorder()
    {
        return $this->quantity_available <= $this->reorder_point;
    }
}
