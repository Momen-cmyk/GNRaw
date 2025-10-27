<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductComment extends Model
{
    protected $fillable = [
        'product_id',
        'admin_id',
        'comment',
        'is_urgent',
        'is_read_by_supplier',
        'read_at',
    ];

    protected $casts = [
        'is_urgent' => 'boolean',
        'is_read_by_supplier' => 'boolean',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the product that owns the comment.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(SupplierProduct::class, 'product_id');
    }

    /**
     * Get the admin who wrote the comment.
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    /**
     * Mark comment as read by supplier.
     */
    public function markAsRead()
    {
        $this->update([
            'is_read_by_supplier' => true,
            'read_at' => now(),
        ]);
    }

    /**
     * Scope a query to only include urgent comments.
     */
    public function scopeUrgent($query)
    {
        return $query->where('is_urgent', true);
    }

    /**
     * Scope a query to only include unread comments.
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read_by_supplier', false);
    }
}
