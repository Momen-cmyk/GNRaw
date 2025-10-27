<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerInquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'supplier_id',
        'product_id',
        'product_name',
        'message',
        'status',
        'admin_notes',
        'is_public',
        'responded_at'
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'responded_at' => 'datetime'
    ];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function product()
    {
        return $this->belongsTo(SupplierProduct::class, 'product_id');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeResponded($query)
    {
        return $query->where('status', 'responded');
    }

    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    // Accessors
    public function getStatusDisplayAttribute()
    {
        return match ($this->status) {
            'pending' => 'Pending',
            'responded' => 'Responded',
            'closed' => 'Closed',
            default => ucfirst($this->status)
        };
    }

    public function getStatusBadgeClassAttribute()
    {
        return match ($this->status) {
            'pending' => 'badge-warning',
            'responded' => 'badge-success',
            'closed' => 'badge-secondary',
            default => 'badge-light'
        };
    }
}
