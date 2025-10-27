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
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->string('featured_image')->nullable();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('author_id');
            $table->enum('status', ['draft', 'published', 'scheduled'])->default('draft');
            $table->boolean('is_featured')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->integer('views_count')->default(0);
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('slug');
            $table->index('status');
            $table->index('is_featured');
            $table->index('published_at');
            $table->index('category_id');

            // Foreign keys
            $table->foreign('category_id')
                ->references('id')
                ->on('blog_categories')
                ->onDelete('cascade');
            $table->foreign('author_id')
                ->references('id')
                ->on('admins')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_posts');
    }
};
