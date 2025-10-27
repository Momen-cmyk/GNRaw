<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\BlogCategory;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display a listing of blog posts.
     */
    public function index(Request $request)
    {
        $query = BlogPost::published()->with(['category', 'author']);

        // Search functionality
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('excerpt', 'like', '%' . $request->search . '%')
                    ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }

        // Category filter
        if ($request->has('category') && $request->category) {
            $category = BlogCategory::where('slug', $request->category)->with('children')->first();
            if ($category) {
                // Get all descendant category IDs (including the category itself)
                $categoryIds = $this->getAllDescendantIds($category);
                $categoryIds[] = $category->id; // Include the category itself

                $query->whereIn('category_id', $categoryIds);
            }
        }

        $posts = $query->latest()->paginate(12);
        $blogCategories = BlogCategory::active()->with('children')->whereNull('parent_id')->orderBy('name')->get();

        // Add total post count to each category
        $blogCategories->each(function ($category) {
            $category->total_post_count = $this->getTotalPostCount($category);
        });

        $featuredPost = BlogPost::published()->featured()->latest()->first();

        // Get total count of all published posts
        $totalPostsCount = BlogPost::published()->count();

        $data = [
            'pageTitle' => 'Blog',
            'posts' => $posts,
            'blogCategories' => $blogCategories,
            'featuredPost' => $featuredPost,
            'totalPostsCount' => $totalPostsCount,
            'search' => $request->search ?? '',
            'selectedCategory' => $request->category ?? ''
        ];

        return view('front.pages.blog.index', $data);
    }

    /**
     * Display the specified blog post.
     */
    public function show($slug)
    {
        $post = BlogPost::published()
            ->with(['category', 'author', 'images'])
            ->where('slug', $slug)
            ->firstOrFail();

        // Increment view count
        $post->incrementViews();

        // Get related posts (same category, excluding current post)
        $relatedPosts = BlogPost::published()
            ->where('category_id', $post->category_id)
            ->where('id', '!=', $post->id)
            ->latest()
            ->limit(3)
            ->get();

        $data = [
            'pageTitle' => $post->title,
            'post' => $post,
            'relatedPosts' => $relatedPosts
        ];

        return view('front.pages.blog.show', $data);
    }

    /**
     * Display posts by category.
     */
    public function category($slug)
    {
        $category = BlogCategory::where('slug', $slug)->with('children')->firstOrFail();

        // Get all descendant category IDs (including the category itself)
        $categoryIds = $this->getAllDescendantIds($category);
        $categoryIds[] = $category->id; // Include the category itself

        $posts = BlogPost::published()
            ->with(['category', 'author'])
            ->whereIn('category_id', $categoryIds)
            ->latest()
            ->paginate(12);

        $blogCategories = BlogCategory::active()->with('children')->whereNull('parent_id')->orderBy('name')->get();

        // Add total post count to each category
        $blogCategories->each(function ($category) {
            $category->total_post_count = $this->getTotalPostCount($category);
        });

        // Get total count of all published posts
        $totalPostsCount = BlogPost::published()->count();

        $data = [
            'pageTitle' => 'Blog - ' . $category->name,
            'posts' => $posts,
            'blogCategories' => $blogCategories,
            'currentCategory' => $category,
            'totalPostsCount' => $totalPostsCount
        ];

        return view('front.pages.blog.category', $data);
    }

    /**
     * Get all descendant category IDs recursively.
     */
    private function getAllDescendantIds($category)
    {
        $ids = [];

        if ($category->children && $category->children->count() > 0) {
            foreach ($category->children as $child) {
                $ids[] = $child->id;
                // Recursively get children of this child
                $childIds = $this->getAllDescendantIds($child);
                $ids = array_merge($ids, $childIds);
            }
        }

        return $ids;
    }

    /**
     * Get total post count for a category including all descendants.
     */
    private function getTotalPostCount($category)
    {
        $count = $category->posts()->count();

        if ($category->children && $category->children->count() > 0) {
            foreach ($category->children as $child) {
                $count += $this->getTotalPostCount($child);
            }
        }

        return $count;
    }

    /**
     * Search blog posts.
     */
    public function search(Request $request)
    {
        $search = $request->get('q', '');

        if (empty($search)) {
            return redirect()->route('blog.index');
        }

        $posts = BlogPost::published()
            ->with(['category', 'author'])
            ->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('excerpt', 'like', '%' . $search . '%')
                    ->orWhere('content', 'like', '%' . $search . '%');
            })
            ->latest()
            ->paginate(12);

        $blogCategories = BlogCategory::active()->with('children')->whereNull('parent_id')->orderBy('name')->get();

        $data = [
            'pageTitle' => 'Search Results for: ' . $search,
            'posts' => $posts,
            'blogCategories' => $blogCategories,
            'search' => $search
        ];

        return view('front.pages.blog.search', $data);
    }
}
