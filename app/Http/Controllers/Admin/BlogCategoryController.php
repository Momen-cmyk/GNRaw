<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = BlogCategory::with('parent', 'children')
            ->orderBy('level')
            ->orderBy('sort_order')
            ->get();

        $data = [
            'pageTitle' => 'Blog Categories',
            'categories' => $categories
        ];

        return view('back.pages.admin.blog-categories.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $parentCategories = BlogCategory::where('is_active', true)
            ->orderBy('level')
            ->orderBy('name')
            ->get();

        $data = [
            'pageTitle' => 'Create Blog Category',
            'parentCategories' => $parentCategories
        ];

        return view('back.pages.admin.blog-categories.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:blog_categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
        ]);

        $level = 1;
        if ($request->parent_id) {
            $parent = BlogCategory::find($request->parent_id);
            $level = $parent->level + 1;
        }

        $slug = Str::slug($request->name);
        $originalSlug = $slug;
        $counter = 1;
        while (BlogCategory::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        $category = BlogCategory::create([
            'name' => $request->name,
            'slug' => $slug,
            'parent_id' => $request->parent_id,
            'level' => $level,
            'description' => $request->description,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('blog-categories', 'public');
            $category->update(['image' => $imagePath]);
        }

        return redirect()->route('admin.blog-categories.index')
            ->with('success', 'Blog category created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = BlogCategory::findOrFail($id);
        $parentCategories = BlogCategory::where('is_active', true)
            ->where('id', '!=', $id)
            ->orderBy('level')
            ->orderBy('name')
            ->get();

        $data = [
            'pageTitle' => 'Edit Blog Category',
            'category' => $category,
            'parentCategories' => $parentCategories
        ];

        return view('back.pages.admin.blog-categories.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = BlogCategory::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:blog_categories,id|not_in:' . $id,
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
        ]);

        $level = 1;
        if ($request->parent_id) {
            $parent = BlogCategory::find($request->parent_id);
            $level = $parent->level + 1;
        }

        $slug = Str::slug($request->name);
        $originalSlug = $slug;
        $counter = 1;
        while (BlogCategory::where('slug', $slug)->where('id', '!=', $id)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        $category->update([
            'name' => $request->name,
            'slug' => $slug,
            'parent_id' => $request->parent_id,
            'level' => $level,
            'description' => $request->description,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('blog-categories', 'public');
            $category->update(['image' => $imagePath]);
        }

        return redirect()->route('admin.blog-categories.index')
            ->with('success', 'Blog category updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = BlogCategory::findOrFail($id);

        // Check if category has posts
        if ($category->posts()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete category that has blog posts. Please move or delete the posts first.');
        }

        // Check if category has children
        if ($category->children()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete category that has sub-categories. Please delete sub-categories first.');
        }

        $category->delete();

        return redirect()->route('admin.blog-categories.index')
            ->with('success', 'Blog category deleted successfully!');
    }

    /**
     * Toggle category status.
     */
    public function toggleStatus(Request $request, $id)
    {
        $category = BlogCategory::findOrFail($id);
        $category->update(['is_active' => !$category->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $category->is_active
        ]);
    }

    /**
     * Reorder categories.
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'categories' => 'required|array',
            'categories.*.id' => 'required|exists:blog_categories,id',
            'categories.*.sort_order' => 'required|integer',
        ]);

        foreach ($request->categories as $categoryData) {
            BlogCategory::where('id', $categoryData['id'])
                ->update(['sort_order' => $categoryData['sort_order']]);
        }

        return response()->json(['success' => true]);
    }
}
