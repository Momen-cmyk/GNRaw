<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientInquiry extends Model
{
    protected $fillable = [
        'name',
        'email',
        'company_name',
        'phone',
        'subject',
        'message',
        'inquiry_type',
        'product_id',
        'status',
        'admin_notes',
        'assigned_to',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(SupplierProduct::class, 'product_id');
    }

    public function assignedTo()
    {
        return $this->belongsTo(Admin::class, 'assigned_to');
    }
}
