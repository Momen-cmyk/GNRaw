<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class BlogPostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = BlogPost::with(['category', 'author'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $data = [
            'pageTitle' => 'Blog Posts',
            'posts' => $posts
        ];

        return view('back.pages.admin.blog-posts.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = BlogCategory::active()->orderBy('name')->get();

        $data = [
            'pageTitle' => 'Create Blog Post',
            'categories' => $categories
        ];

        return view('back.pages.admin.blog-posts.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'category_id' => 'required|exists:blog_categories,id',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:draft,published,scheduled',
            'is_featured' => 'nullable|boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
        ]);

        $slug = Str::slug($request->title);
        $originalSlug = $slug;
        $counter = 1;
        while (BlogPost::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        $post = BlogPost::create([
            'title' => $request->title,
            'slug' => $slug,
            'excerpt' => $request->excerpt,
            'content' => $request->content,
            'category_id' => $request->category_id,
            'author_id' => Auth::guard('admin')->id(),
            'status' => $request->status,
            'is_featured' => $request->has('is_featured'),
            'published_at' => $request->status === 'published' ? now() : null,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
        ]);

        if ($request->hasFile('featured_image')) {
            $imagePath = $request->file('featured_image')->store('blog-posts', 'public');
            $post->update(['featured_image' => $imagePath]);
        }

        return redirect()->route('admin.blog-posts.index')->with('success', 'Blog post created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post = BlogPost::with(['category', 'author', 'images'])->findOrFail($id);

        $data = [
            'pageTitle' => 'View Blog Post',
            'post' => $post
        ];

        return view('back.pages.admin.blog-posts.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $post = BlogPost::findOrFail($id);
        $categories = BlogCategory::active()->orderBy('name')->get();

        $data = [
            'pageTitle' => 'Edit Blog Post',
            'post' => $post,
            'categories' => $categories
        ];

        return view('back.pages.admin.blog-posts.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $post = BlogPost::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'category_id' => 'required|exists:blog_categories,id',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:draft,published,scheduled',
            'is_featured' => 'nullable|boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
        ]);

        $slug = Str::slug($request->title);
        if ($slug !== $post->slug) {
            $originalSlug = $slug;
            $counter = 1;
            while (BlogPost::where('slug', $slug)->where('id', '!=', $id)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }
        }

        $post->update([
            'title' => $request->title,
            'slug' => $slug,
            'excerpt' => $request->excerpt,
            'content' => $request->content,
            'category_id' => $request->category_id,
            'status' => $request->status,
            'is_featured' => $request->has('is_featured'),
            'published_at' => $request->status === 'published' ? ($post->published_at ?? now()) : null,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
        ]);

        if ($request->hasFile('featured_image')) {
            $imagePath = $request->file('featured_image')->store('blog-posts', 'public');
            $post->update(['featured_image' => $imagePath]);
        }

        return redirect()->route('admin.blog-posts.index')->with('success', 'Blog post updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = BlogPost::findOrFail($id);
        $post->delete();

        return redirect()->route('admin.blog-posts.index')->with('success', 'Blog post deleted successfully!');
    }
}
