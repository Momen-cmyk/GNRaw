<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierDocument extends Model
{
    protected $fillable = [
        'supplier_id',
        'document_type',
        'document_name',
        'file_path',
        'expiry_date',
        'is_verified',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
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
