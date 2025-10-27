<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Supplier extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'phone',
        'picture',
        'bio',
        'company_name',
        'company_activity',
        'company_description',
        'number_of_employees',
        'is_active',
        'is_verified',
        'approval_status',
        'rejection_reason',
        'approved_at',
        'approved_by',
        'email_notifications',
        'language',
        'timezone',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'is_verified' => 'boolean',
            'email_notifications' => 'boolean',
            'approved_at' => 'datetime',
        ];
    }

    public function getPictureAttribute($value)
    {
        if ($value) {
            return asset('storage/' . $value);
        }
        return asset('images/users/default-avatar.png');
    }

    public function social_links()
    {
        return $this->hasOne(UserSocialLink::class, 'user_id', 'id');
    }

    public function documents()
    {
        return $this->hasMany(SupplierDocument::class);
    }

    public function certificates()
    {
        return $this->hasMany(SupplierCertificate::class);
    }

    public function products()
    {
        return $this->hasMany(SupplierProduct::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(Admin::class, 'approved_by');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'supplier_id');
    }

    public function inventory()
    {
        return $this->hasMany(Inventory::class, 'supplier_id');
    }

    public function inquiries()
    {
        return $this->hasMany(CustomerInquiry::class, 'supplier_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'supplier_id')->orderBy('created_at', 'desc');
    }

    public function unreadNotifications()
    {
        return $this->hasMany(Notification::class, 'supplier_id')->where('is_read', false)->orderBy('created_at', 'desc');
    }

    /**
     * Get the formatted employee range display
     */
    public function getEmployeeRangeDisplayAttribute()
    {
        $ranges = [
            '1-10' => '1–10 employees',
            '11-50' => '11–50 employees',
            '51-200' => '51–200 employees',
            '201-500' => '201–500 employees',
            '501-1000' => '501–1,000 employees',
            '1001-5000' => '1,001–5,000 employees',
            '5001-10000' => '5,001–10,000 employees',
            '10001+' => '10,001+ employees'
        ];

        return $ranges[$this->number_of_employees] ?? $this->number_of_employees ?? 'N/A';
    }
}
