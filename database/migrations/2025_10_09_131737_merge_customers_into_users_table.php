<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Migrate active customers from customers table to users table
        $customers = DB::table('customers')->where('is_active', true)->get();

        foreach ($customers as $customer) {
            // Check if user already exists in users table
            $existingUser = DB::table('users')->where('email', $customer->email)->first();

            if (!$existingUser) {
                // Insert customer into users table (only fields that exist in users table)
                DB::table('users')->insert([
                    'name' => $customer->name,
                    'email' => $customer->email,
                    'username' => $customer->username,
                    'password' => $customer->password,
                    'picture' => $customer->picture,
                    'bio' => $customer->bio,
                    'email_notifications' => $customer->email_notifications,
                    'language' => $customer->language,
                    'timezone' => $customer->timezone,
                    'status' => 'active',
                    'email_verified_at' => $customer->email_verified_at,
                    'created_at' => $customer->created_at,
                    'updated_at' => $customer->updated_at,
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration is not easily reversible
        // The customers table data would need to be restored manually
    }
};
