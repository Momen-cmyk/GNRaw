<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupplierCertificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'certificate_type',
        'certificate_name',
        'file_path',
        'original_filename',
        'file_size',
        'mime_type',
        'description',
        'issue_date',
        'expiry_date',
        'issuing_authority',
        'certificate_number',
        'status',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the supplier that owns the certificate.
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    /**
     * Scope a query to only include active certificates.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include certificates for a specific supplier.
     */
    public function scopeForSupplier($query, $supplierId)
    {
        return $query->where('supplier_id', $supplierId);
    }

    /**
     * Scope a query to only include certificates of a specific type.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('certificate_type', $type);
    }

    /**
     * Get the file size in human readable format.
     */
    public function getFileSizeHumanAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Check if certificate is expired.
     */
    public function isExpired()
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }

    /**
     * Get certificate type display name.
     * Note: COA is now per product, not per supplier
     */
    public function getTypeDisplayNameAttribute()
    {
        $types = [
            'iso' => 'ISO Certificate',
            'manufacturing' => 'Manufacturing Certificate',
            'gmp' => 'GMP Certificate',
            'wc' => 'Worldwide Compliance Certificate',
        ];

        return $types[$this->certificate_type] ?? ucfirst($this->certificate_type);
    }
}
