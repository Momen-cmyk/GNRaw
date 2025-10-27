<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminNotification extends Model
{
    protected $fillable = [
        'admin_id',
        'type',
        'title',
        'message',
        'data',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the admin that owns the notification.
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    /**
     * Mark notification as read.
     */
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    /**
     * Scope for unread notifications.
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope for notifications of a specific type.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Safely get data value with fallback.
     */
    public function getDataValue($key, $default = null)
    {
        return $this->data[$key] ?? $default;
    }

    /**
     * Get action with fallback.
     */
    public function getActionAttribute()
    {
        return $this->getDataValue('action', 'unknown');
    }

    /**
     * Get action icon with fallback.
     */
    public function getActionIconAttribute()
    {
        return $this->getDataValue('action_icon', 'fa-bell');
    }

    /**
     * Get action color with fallback.
     */
    public function getActionColorAttribute()
    {
        return $this->getDataValue('action_color', 'primary');
    }

    /**
     * Create a notification for product added.
     */
    public static function createProductAddedNotification($supplierId, $productName, $supplierName, $productId = null)
    {
        // Get all admins
        $admins = Admin::all();

        foreach ($admins as $admin) {
            self::create([
                'admin_id' => $admin->id,
                'type' => 'product_added',
                'title' => 'New Product Added',
                'message' => "Supplier '{$supplierName}' added a new product: '{$productName}'",
                'data' => [
                    'supplier_id' => $supplierId,
                    'supplier_name' => $supplierName,
                    'product_id' => $productId,
                    'product_name' => $productName,
                    'action' => 'added',
                    'action_icon' => 'fa-plus-circle',
                    'action_color' => 'success'
                ],
            ]);
        }
    }

    /**
     * Create a notification for product updated.
     */
    public static function createProductUpdatedNotification($supplierId, $productName, $supplierName, $productId = null)
    {
        // Get all admins
        $admins = Admin::all();

        foreach ($admins as $admin) {
            self::create([
                'admin_id' => $admin->id,
                'type' => 'product_updated',
                'title' => 'Product Updated',
                'message' => "Supplier '{$supplierName}' updated product: '{$productName}'",
                'data' => [
                    'supplier_id' => $supplierId,
                    'supplier_name' => $supplierName,
                    'product_id' => $productId,
                    'product_name' => $productName,
                    'action' => 'updated',
                    'action_icon' => 'fa-edit',
                    'action_color' => 'warning'
                ],
            ]);
        }
    }

    /**
     * Create a notification for product deleted.
     */
    public static function createProductDeletedNotification($supplierId, $productName, $supplierName, $productId = null)
    {
        // Get all admins
        $admins = Admin::all();

        foreach ($admins as $admin) {
            self::create([
                'admin_id' => $admin->id,
                'type' => 'product_deleted',
                'title' => 'Product Deleted',
                'message' => "Supplier '{$supplierName}' deleted product: '{$productName}'",
                'data' => [
                    'supplier_id' => $supplierId,
                    'supplier_name' => $supplierName,
                    'product_id' => $productId,
                    'product_name' => $productName,
                    'action' => 'deleted',
                    'action_icon' => 'fa-trash',
                    'action_color' => 'danger'
                ],
            ]);
        }
    }
}
