<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $fillable = [
        'supplier_id',
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
     * Get the supplier that owns the notification.
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
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
     * Scope a query to only include unread notifications.
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope a query to only include notifications of a specific type.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Create a notification for comment added.
     */
    public static function createCommentAddedNotification($supplierId, $productName, $adminName, $isUrgent = false, $commentId = null, $productId = null)
    {
        return self::create([
            'supplier_id' => $supplierId,
            'type' => 'comment_added',
            'title' => $isUrgent ? 'Urgent Comment Added' : 'New Comment Added',
            'message' => $isUrgent
                ? "Admin {$adminName} added an urgent comment on your product '{$productName}'"
                : "Admin {$adminName} added a comment on your product '{$productName}'",
            'data' => [
                'product_name' => $productName,
                'admin_name' => $adminName,
                'is_urgent' => $isUrgent,
                'comment_id' => $commentId,
                'product_id' => $productId,
            ],
        ]);
    }
}
