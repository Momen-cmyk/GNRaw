<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\PublicProduct;
use App\Models\ProductCategory;
use App\Models\ClientInquiry;
use App\Models\GeneralSetting;

class UserController extends Controller
{
    public function homePage(Request $request)
    {
        $categories = ProductCategory::where('is_active', true)->get();

        // Get featured products from approved supplier products
        $featuredProducts = \App\Models\SupplierProduct::with(['supplier'])
            ->where('is_approved', true)
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        $recentProducts = \App\Models\SupplierProduct::with(['supplier'])
            ->where('is_approved', true)
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->limit(12)
            ->get();

        // Get featured blog posts for slider
        $featuredBlogPosts = \App\Models\BlogPost::published()
            ->featured()
            ->with(['category', 'author'])
            ->latest()
            ->limit(5)
            ->get();

        // Get latest blog posts for grid
        $latestBlogPosts = \App\Models\BlogPost::published()
            ->with(['category', 'author'])
            ->latest()
            ->limit(3)
            ->get();

        $data = [
            'pageTitle' => 'Home',
            'categories' => $categories,
            'featuredProducts' => $featuredProducts,
            'recentProducts' => $recentProducts,
            'featuredBlogPosts' => $featuredBlogPosts,
            'latestBlogPosts' => $latestBlogPosts
        ];

        return view('front.pages.home', $data);
    }

    public function productsPage(Request $request)
    {
        // Get approved supplier products with their suppliers
        $query = \App\Models\SupplierProduct::with(['supplier'])
            ->where('is_approved', true)
            ->where('status', 'active');

        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->where('product_category', $request->category);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('product_name', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%')
                    ->orWhere('product_category', 'like', '%' . $request->search . '%')
                    ->orWhere('cas_number', 'like', '%' . $request->search . '%');
            });
        }

        // Sort
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'name':
                $query->orderBy('product_name', 'asc');
                break;
            case 'category':
                $query->orderBy('product_category', 'asc');
                break;
            case 'moq':
                $query->orderBy('moq', 'asc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $products = $query->paginate(20);
        $categories = ProductCategory::where('is_active', true)->get();

        // Get unique categories from approved products
        $productCategories = \App\Models\SupplierProduct::where('is_approved', true)
            ->where('status', 'active')
            ->distinct()
            ->pluck('product_category')
            ->filter()
            ->sort()
            ->values();

        $data = [
            'pageTitle' => 'Products',
            'products' => $products,
            'categories' => $categories,
            'productCategories' => $productCategories,
            'selectedCategory' => $request->category ?? '',
            'search' => $request->search ?? '',
            'sort' => $sort
        ];

        return view('front.pages.products', $data);
    }

    public function productDetail(Request $request, $id)
    {
        $product = \App\Models\SupplierProduct::with(['supplier', 'documents'])
            ->where('is_approved', true)
            ->where('status', 'active')
            ->findOrFail($id);

        // Get related products from the same category
        $relatedProducts = \App\Models\SupplierProduct::with(['supplier'])
            ->where('is_approved', true)
            ->where('status', 'active')
            ->where('product_category', $product->product_category)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();

        $data = [
            'pageTitle' => $product->product_name,
            'product' => $product,
            'relatedProducts' => $relatedProducts
        ];

        return view('front.pages.product-detail', $data);
    }

    public function categoriesPage(Request $request)
    {
        $categories = ProductCategory::where('is_active', true)
            ->withCount(['publicProducts' => function ($query) {
                $query->where('is_active', true);
            }])
            ->get();

        $data = [
            'pageTitle' => 'Categories',
            'categories' => $categories
        ];

        return view('front.pages.categories', $data);
    }

    public function categoryProducts(Request $request, $slug)
    {
        $category = ProductCategory::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $query = PublicProduct::where('is_active', true)
            ->where('category', $category->name);

        // Search within category
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('product_name', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(20);

        $data = [
            'pageTitle' => $category->name . ' Products',
            'category' => $category,
            'products' => $products,
            'search' => $request->search ?? ''
        ];

        return view('front.pages.category-products', $data);
    }

    public function contactPage(Request $request)
    {
        $data = [
            'pageTitle' => 'Contact Us'
        ];

        return view('front.pages.contact', $data);
    }

    public function submitInquiry(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'nullable|string|max:20',
                'company' => 'nullable|string|max:255',
                'subject' => 'required|string|max:255',
                'message' => 'required|string|max:2000',
                'product_id' => 'nullable|exists:public_products,id'
            ]);

            Log::info('Creating contact inquiry', [
                'name' => $request->name,
                'email' => $request->email,
                'subject' => $request->subject
            ]);

            $inquiry = ClientInquiry::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'company' => $request->company,
                'subject' => $request->subject,
                'message' => $request->message,
                'product_id' => $request->product_id,
                'status' => 'pending',
                'inquiry_date' => now()
            ]);

            Log::info('Contact inquiry created successfully', ['inquiry_id' => $inquiry->id]);

            // Send email notification to admin
            try {
                \Illuminate\Support\Facades\Mail::send('email-templates.contact-inquiry', [
                    'inquiry' => $inquiry,
                    'product' => $request->product_id ? \App\Models\PublicProduct::find($request->product_id) : null
                ], function ($message) use ($inquiry) {
                    $message->to('info@gnraw.com')
                        ->subject('New Contact Inquiry: ' . $inquiry->subject);
                });

                Log::info('Contact inquiry email sent successfully', ['inquiry_id' => $inquiry->id]);
            } catch (\Exception $e) {
                Log::error('Failed to send contact inquiry email: ' . $e->getMessage());
                // Continue execution even if email fails
            }

            return redirect()->back()->with('success', 'Your inquiry has been submitted successfully! We will contact you soon.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed for contact inquiry', ['errors' => $e->errors()]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error creating contact inquiry: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'An error occurred while sending your inquiry. Please try again.');
        }
    }

    public function aboutPage(Request $request)
    {
        $data = [
            'pageTitle' => 'About Us'
        ];

        return view('front.pages.about', $data);
    }

    public function userDashboard(Request $request)
    {
        $user = Auth::user();

        // Get user's recent inquiries
        $recentInquiries = ClientInquiry::where('email', $user->email)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get featured products for dashboard
        $featuredProducts = PublicProduct::where('is_featured', true)
            ->where('is_active', true)
            ->limit(6)
            ->get();

        $data = [
            'pageTitle' => 'Dashboard',
            'user' => $user,
            'recentInquiries' => $recentInquiries,
            'featuredProducts' => $featuredProducts
        ];

        return view('front.pages.user.dashboard', $data);
    }

    public function logoutHandler(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'You have been logged out successfully.');
    }

    public function ProfileView(Request $request)
    {
        $user = Auth::user();

        $data = [
            'pageTitle' => 'Profile',
            'user' => $user
        ];

        return view('front.pages.user.profile', $data);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'company_name' => 'nullable|string|max:255'
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'company_name' => $request->company_name
        ]);

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }
}
