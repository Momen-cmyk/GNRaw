<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlogPostImage extends Model
{
    protected $fillable = [
        'blog_post_id',
        'image_path',
        'caption',
        'sort_order',
    ];

    /**
     * Get the blog post that owns the image.
     */
    public function blogPost(): BelongsTo
    {
        return $this->belongsTo(BlogPost::class);
    }
}
