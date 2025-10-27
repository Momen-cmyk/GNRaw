<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_id',
        'product_id',
        'supplier_id',
        'movement_type',
        'quantity',
        'quantity_before',
        'quantity_after',
        'reference_type',
        'reference_id',
        'reference_number',
        'batch_number',
        'unit_cost',
        'total_value',
        'user_id',
        'user_type',
        'from_location',
        'to_location',
        'notes',
        'reason',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'quantity_before' => 'integer',
        'quantity_after' => 'integer',
        'unit_cost' => 'decimal:2',
        'total_value' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }

    public function product()
    {
        return $this->belongsTo(SupplierProduct::class, 'product_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    // Polymorphic relationship to reference (Order, etc.)
    public function reference()
    {
        return $this->morphTo();
    }

    // Scopes
    public function scopeOfType($query, $type)
    {
        return $query->where('movement_type', $type);
    }

    public function scopePurchases($query)
    {
        return $query->where('movement_type', 'purchase');
    }

    public function scopeSales($query)
    {
        return $query->where('movement_type', 'sale');
    }

    public function scopeAdjustments($query)
    {
        return $query->where('movement_type', 'adjustment');
    }

    // Helper method to create movement record
    public static function recordMovement($inventory, $movementType, $quantity, $details = [])
    {
        $quantityBefore = $inventory->quantity_available;

        // Update inventory quantity based on movement type
        if (in_array($movementType, ['purchase', 'return', 'release'])) {
            $inventory->quantity_available += $quantity;
        } elseif (in_array($movementType, ['sale', 'damage', 'expired', 'reserve'])) {
            $inventory->quantity_available -= $quantity;
        }

        $inventory->save();

        // Create movement record
        return self::create(array_merge([
            'inventory_id' => $inventory->id,
            'product_id' => $inventory->product_id,
            'supplier_id' => $inventory->supplier_id,
            'movement_type' => $movementType,
            'quantity' => $quantity,
            'quantity_before' => $quantityBefore,
            'quantity_after' => $inventory->quantity_available,
        ], $details));
    }
}
