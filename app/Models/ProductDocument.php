<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductDocument extends Model
{
    protected $fillable = [
        'product_id',
        'document_type',
        'document_name',
        'file_path',
        'is_verified',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(SupplierProduct::class, 'product_id');
    }

    public function verifiedBy()
    {
        return $this->belongsTo(Admin::class, 'verified_by');
    }

    public function getFileUrlAttribute()
    {
        return asset('storage/' . $this->file_path);
    }
}
