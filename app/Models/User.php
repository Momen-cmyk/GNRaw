<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\UserSocialLink;
use App\UserStatus;

use function PHPSTORM_META\type;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'phone',
        'picture',
        'bio',
        'status',
        'company_name',
        'company_activity',
        'partnership_name',
        'business_type',
        'company_description',
        'email_notifications',
        'language',
        'timezone',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'status' => UserStatus::class,
            'last_login_at' => 'datetime',
        ];
    }

    public function getPictureAttribute($value)
    {
        if ($value) {
            return asset($value);
        }

        return asset('images/users/default-avatar.png');
    } //End Method

    public function social_links()
    {
        return $this->hasOne(UserSocialLink::class, 'user_id', 'id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }

    public function inquiries()
    {
        return $this->hasMany(CustomerInquiry::class, 'customer_id');
    }
}
