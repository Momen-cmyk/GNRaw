<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_number',
        'customer_id',
        'supplier_id',
        'status',
        'subtotal',
        'tax',
        'shipping_cost',
        'discount',
        'total',
        'currency',
        'shipping_name',
        'shipping_email',
        'shipping_phone',
        'shipping_address',
        'shipping_city',
        'shipping_state',
        'shipping_postal_code',
        'shipping_country',
        'billing_same_as_shipping',
        'billing_name',
        'billing_email',
        'billing_phone',
        'billing_address',
        'billing_city',
        'billing_state',
        'billing_postal_code',
        'billing_country',
        'payment_method',
        'payment_status',
        'payment_date',
        'transaction_id',
        'tracking_number',
        'carrier',
        'shipped_at',
        'delivered_at',
        'customer_notes',
        'admin_notes',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
        'billing_same_as_shipping' => 'boolean',
        'payment_date' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    public function scopeShipped($query)
    {
        return $query->where('status', 'shipped');
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', 'delivered');
    }

    // Helper Methods
    public function getStatusLabelAttribute()
    {
        $statuses = [
            'pending' => '<span class="badge bg-warning">Pending</span>',
            'confirmed' => '<span class="badge bg-info">Confirmed</span>',
            'processing' => '<span class="badge bg-primary">Processing</span>',
            'shipped' => '<span class="badge bg-success">Shipped</span>',
            'delivered' => '<span class="badge bg-success">Delivered</span>',
            'cancelled' => '<span class="badge bg-danger">Cancelled</span>',
            'refunded' => '<span class="badge bg-secondary">Refunded</span>',
        ];

        return $statuses[$this->status] ?? '<span class="badge bg-secondary">Unknown</span>';
    }

    public function getPaymentStatusLabelAttribute()
    {
        $statuses = [
            'pending' => '<span class="badge bg-warning">Pending</span>',
            'paid' => '<span class="badge bg-success">Paid</span>',
            'failed' => '<span class="badge bg-danger">Failed</span>',
            'refunded' => '<span class="badge bg-secondary">Refunded</span>',
        ];

        return $statuses[$this->payment_status] ?? '<span class="badge bg-secondary">Unknown</span>';
    }

    // Boot method to auto-generate order number
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = 'ORD-' . date('Ymd') . '-' . strtoupper(uniqid());
            }
        });
    }
}
