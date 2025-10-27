<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'picture',
        'bio',
        'is_active',
        'role',
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
        ];
    }

    public function getPictureAttribute($value)
    {
        if ($value) {
            // Always use the value as-is if it starts with 'images/'
            // This is for Hostinger compatibility (direct public directory upload)
            if (strpos($value, 'images/') === 0) {
                return asset($value);
            }
            // Fallback for old storage paths
            return asset('storage/' . $value);
        }
        return asset('images/users/admin_default.png');
    }


    /**
     * Get the admin's notifications.
     */
    public function notifications()
    {
        return $this->hasMany(AdminNotification::class, 'admin_id')->orderBy('created_at', 'desc');
    }

    /**
     * Get the admin's unread notifications.
     */
    public function unreadNotifications()
    {
        return $this->hasMany(AdminNotification::class, 'admin_id')->where('is_read', false)->orderBy('created_at', 'desc');
    }
}
