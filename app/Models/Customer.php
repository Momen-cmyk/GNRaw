<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Customer extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'picture',
        'bio',
        'phone',
        'date_of_birth',
        'gender',
        'is_active',
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
            'email_notifications' => 'boolean',
            'date_of_birth' => 'date',
        ];
    }

    public function getPictureAttribute($value)
    {
        if ($value) {
            return asset($value);
        }
        return asset('images/users/default-avatar.png');
    }
}
