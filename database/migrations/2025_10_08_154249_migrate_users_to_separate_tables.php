<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Admin;
use App\Models\Supplier;
use App\Models\Customer;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Migrate existing users to appropriate tables based on their type
        $users = User::all();

        foreach ($users as $user) {
            $userData = [
                'name' => $user->name,
                'email' => $user->email,
                'username' => $user->username,
                'password' => $user->password,
                'picture' => $user->picture,
                'bio' => $user->bio,
                'email_verified_at' => $user->email_verified_at,
                'remember_token' => $user->remember_token,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ];

            switch ($user->type) {
                case 'admin':
                case 'super_admin':
                    $userData['role'] = $user->type;
                    $userData['is_active'] = $user->status === 'active';
                    Admin::create($userData);
                    break;

                case 'supplier':
                    $userData['company_name'] = $user->company_name;
                    $userData['company_activity'] = $user->company_activity;
                    $userData['partnership_name'] = $user->partnership_name;
                    $userData['business_type'] = $user->business_type;
                    $userData['company_description'] = $user->company_description;
                    $userData['is_active'] = $user->status === 'active';
                    $userData['is_verified'] = false;
                    $userData['email_notifications'] = $user->email_notifications ?? true;
                    $userData['language'] = $user->language ?? 'en';
                    $userData['timezone'] = $user->timezone ?? 'UTC';
                    Supplier::create($userData);
                    break;

                case 'user':
                default:
                    $userData['phone'] = null; // Add phone if you have it
                    $userData['date_of_birth'] = null; // Add date_of_birth if you have it
                    $userData['gender'] = null; // Add gender if you have it
                    $userData['is_active'] = $user->status === 'active';
                    $userData['email_notifications'] = $user->email_notifications ?? true;
                    $userData['language'] = $user->language ?? 'en';
                    $userData['timezone'] = $user->timezone ?? 'UTC';
                    Customer::create($userData);
                    break;
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration is not easily reversible
        // You would need to manually restore from backups
        // or implement a more complex rollback strategy
    }
};
