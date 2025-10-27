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
        // Remove unused tables that are not needed for the platform
        $unusedTables = [
            'user_social_links',
            'perant_categories',
            'categories',
            'supplier_certificates',
            'customers'
        ];

        foreach ($unusedTables as $table) {
            if (Schema::hasTable($table)) {
                Schema::dropIfExists($table);
                echo "Dropped table: {$table}\n";
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate the tables if needed (basic structure)
        Schema::create('user_social_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('facebook_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('youtube_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('x_url')->nullable();
            $table->string('github_url')->nullable();
            $table->timestamps();
        });

        Schema::create('perant_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('supplier_certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->string('certificate_type');
            $table->string('certificate_number')->nullable();
            $table->date('issue_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('issuing_authority')->nullable();
            $table->string('file_path')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->timestamps();
        });

        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('username')->unique();
            $table->string('password');
            $table->string('picture')->nullable();
            $table->text('bio')->nullable();
            $table->string('phone')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('email_verified_at')->nullable();
            $table->boolean('email_notifications')->default(true);
            $table->string('language', 5)->default('en');
            $table->string('timezone')->default('UTC');
            $table->timestamps();
        });
    }
};
