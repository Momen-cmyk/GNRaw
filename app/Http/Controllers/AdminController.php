<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Support\Facades\File;
use SawaStacks\Utils\Kropify;
use App\Models\GeneralSetting;
use App\Models\Supplier;
use App\Models\SupplierProduct;
use App\Models\ProductCategory;
use App\Models\ClientInquiry;
use App\Helpers\CMail;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function adminDashboard(Request $request)
    {
        // Get dashboard statistics
        $totalSuppliers = \App\Models\Supplier::count();
        $pendingSuppliers = \App\Models\Supplier::where('approval_status', 'pending')->count();
        $approvedSuppliers = \App\Models\Supplier::where('approval_status', 'approved')->count();
        $totalInquiries = \App\Models\ClientInquiry::count();

        // Get recent supplier applications
        $recentSuppliers = \App\Models\Supplier::with('documents')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Get recent client inquiries
        $recentInquiries = \App\Models\ClientInquiry::orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $data = [
            'pageTitle' => 'Dashboard',
            'totalSuppliers' => $totalSuppliers,
            'pendingSuppliers' => $pendingSuppliers,
            'approvedSuppliers' => $approvedSuppliers,
            'totalInquiries' => $totalInquiries,
            'recentSuppliers' => $recentSuppliers,
            'recentInquiries' => $recentInquiries
        ];
        return view('back.pages.dashboard', $data);
    } //End Method

    public function logoutHandler(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login')->with('fail', 'You are now logged out!');
    } //End Method

    public function ProfileView(Request $request)
    {
        $data = [
            'pageTitle' => 'Profile',
            // 'adminData' => User::find(Auth::user()->id),
        ];
        return view('back.pages.profile', $data);
    } //End Method

    public function uploadProfilePicture(Request $request)
    {
        $request->validate([
            'profilePictureFile' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $admin = Auth::guard('admin')->user();

        if ($request->hasFile('profilePictureFile')) {
            try {
                $file = $request->file('profilePictureFile');
                $filename = 'admin_' . $admin->id . '_' . time() . '.' . $file->getClientOriginalExtension();

                // Direct upload to public directory (works best on Hostinger)
                $publicPath = 'images/admins/';
                $fullPath = public_path($publicPath);

                // Create directory if it doesn't exist
                if (!is_dir($fullPath)) {
                    if (!mkdir($fullPath, 0755, true)) {
                        throw new \Exception('Failed to create directory: ' . $fullPath);
                    }
                }

                // Delete old profile picture if exists
                if ($admin->getRawOriginal('picture')) {
                    $oldPicture = $admin->getRawOriginal('picture');
                    // Remove storage/ prefix if present
                    $oldPicture = str_replace('storage/', '', $oldPicture);
                    $oldPicturePath = public_path($oldPicture);
                    if (file_exists($oldPicturePath)) {
                        @unlink($oldPicturePath);
                    }
                }

                // Move file directly to public directory
                if (!$file->move($fullPath, $filename)) {
                    throw new \Exception('Failed to move uploaded file to: ' . $fullPath . $filename);
                }

                // Update admin with relative path
                $relativePath = $publicPath . $filename;
                $admin->picture = $relativePath;
                $admin->save();

                // Return success with the full URL
                return response()->json([
                    'status' => 1,
                    'image_path' => asset($relativePath),
                    'message' => 'Profile picture updated successfully.'
                ]);
            } catch (\Exception $e) {
                \Log::error('Profile picture upload failed: ' . $e->getMessage());
                return response()->json([
                    'status' => 0,
                    'message' => 'Failed to upload profile picture. Error: ' . $e->getMessage()
                ]);
            }
        }

        return response()->json([
            'status' => 0,
            'message' => 'Failed to upload profile picture.'
        ]);
    } //End Method

    public function generalSettings(Request $request)
    {
        $data = [
            'pageTitle' => 'General Settings'
        ];
        return view('back.pages.general_settings', $data);
    } //End Method
    public function updateLogo(Request $request)
    {
        $settings = GeneralSetting::take(1)->first();

        if (!is_null($settings)) {
            $path = 'images/site/';
            $old_logo = $settings->site_logo;
            $file = $request->file('site_logo');

            if ($request->hasFile('site_logo')) {
                // Validate file type
                $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png', 'image/svg+xml'];
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'svg'];

                $fileExtension = strtolower($file->getClientOriginalExtension());
                $fileMimeType = $file->getMimeType();

                if (!in_array($fileExtension, $allowedExtensions) || !in_array($fileMimeType, $allowedMimes)) {
                    return response()->json([
                        'status' => 0,
                        'message' => 'Invalid file type. Please upload JPG, PNG, or SVG files only.'
                    ]);
                }

                // Generate filename with correct extension
                $filename = 'logo_' . uniqid() . '.' . $fileExtension;

                $upload = $file->move(public_path($path), $filename);
                if ($upload) {
                    if ($old_logo != null && File::exists(public_path($path . $old_logo))) {
                        File::delete(public_path($path . $old_logo));
                    }
                    $settings->update(['site_logo' => $filename]);

                    return response()->json([
                        'status' => 1,
                        'image_path' => $path . $filename,
                        'message' => 'Site logo has been updated successfully.'
                    ]);
                } else {
                    return response()->json([
                        'status' => 0,
                        'message' => 'Something went wrong. Please try again later.'
                    ]);
                }
            }
        } else {
            return response()->json(['status' => 0, 'message' => 'Make sure you updated general settings form first.']);
        }
    } //End method

    public function updateFavicon(Request $request)
    {
        $settings = GeneralSetting::take(1)->first();
        if (!is_null($settings)) {
            $path = 'images/site/';
            $old_favicon = $settings->site_favicon;
            $file = $request->file('site_favicon');
            $filename = 'favicon_' . uniqid() . '.png';
            if ($request->hasFile('site_favicon')) {
                $upload = $file->move(public_path($path), $filename);
                if ($upload) {
                    if ($old_favicon != null && File::exists(public_path($path . $old_favicon))) {
                        File::delete(public_path($path . $old_favicon));
                    }
                    $settings->update(['site_favicon' => $filename]);

                    return response()->json([
                        'status' => 1,
                        'image_path' => $path . $filename,
                        'message' => 'Site favicon has been updated successfully.'
                    ]);
                } else {
                    return response()->json([
                        'status' => 0,
                        'Something went wrong in upload new favicon. Please try again later.'
                    ]);
                }
            }
        } else {
            return response()->json([
                'status' => 0,
                'message' => 'Make sure you updated general settings form first.'
            ]);
        }
    } //End Method

    // Supplier Management Methods
    public function suppliersIndex(Request $request)
    {
        $query = Supplier::with('documents');

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('approval_status', $request->status);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('company_name', 'like', '%' . $request->search . '%')
                    ->orWhere('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $suppliers = $query->orderBy('created_at', 'desc')->paginate(20);

        $data = [
            'pageTitle' => 'Supplier Management',
            'suppliers' => $suppliers,
            'status' => $request->status ?? 'all',
            'search' => $request->search ?? ''
        ];

        return view('back.pages.suppliers.index', $data);
    }

    public function showSupplier(Request $request, $id)
    {
        $supplier = Supplier::with(['documents', 'products'])->findOrFail($id);

        $data = [
            'pageTitle' => 'Supplier Details - ' . $supplier->company_name,
            'supplier' => $supplier
        ];

        return view('back.pages.suppliers.show', $data);
    }

    public function getSupplierProducts(Request $request, $id)
    {
        try {
            $supplier = Supplier::findOrFail($id);
            $products = $supplier->products()
                ->select([
                    'id',
                    'product_name',
                    'description',
                    'product_category',
                    'subcategory',
                    'is_approved',
                    'is_available',
                    'created_at',
                    'updated_at'
                ])
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'products' => $products
            ]);
        } catch (\Exception $e) {
            \Log::error('Error getting supplier products: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error loading products: ' . $e->getMessage()
            ], 500);
        }
    }

    public function approveSupplier(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);

        $supplier->update([
            'approval_status' => 'approved',
            'is_active' => true,
            'approved_at' => now(),
            'approved_by' => Auth::id()
        ]);

        // Send approval email to supplier
        try {
            $mail_body = view('email-templates.supplier-approval', [
                'supplier' => $supplier
            ])->render();

            $mail_config = [
                'recipient_address' => $supplier->email,
                'recipient_name' => $supplier->name,
                'subject' => 'Welcome to GNRAW - Your Application Has Been Approved!',
                'body' => $mail_body,
            ];

            $email_sent = CMail::send($mail_config);

            if ($email_sent) {
                \Log::info('✅ Supplier approval email sent successfully to: ' . $supplier->email);
                $email_status = 'Email sent successfully';
            } else {
                Log::error('❌ Failed to send approval email to: ' . $supplier->email);
                $email_status = 'Email failed to send';
            }
        } catch (\Exception $e) {
            Log::error('Error sending supplier approval email: ' . $e->getMessage());
        }

        $email_message = isset($email_status) ? " and {$email_status}" : '';
        return redirect()->back()->with('success', 'Supplier approved successfully' . $email_message . '!');
    }

    public function testEmail(Request $request)
    {
        try {
            $test_email = $request->input('email', 'momentarek904@gmail.com');

            $mail_config = [
                'recipient_address' => $test_email,
                'recipient_name' => 'Test User',
                'subject' => 'Test Email from GNRAW - ' . now(),
                'body' => '<h1>Test Email</h1><p>This is a test email sent at ' . now() . '</p><p>If you receive this, the email system is working correctly!</p>',
            ];

            $result = CMail::send($mail_config);

            if ($result) {
                return response()->json([
                    'status' => 1,
                    'message' => 'Test email sent successfully to ' . $test_email
                ]);
            } else {
                return response()->json([
                    'status' => 0,
                    'message' => 'Failed to send test email to ' . $test_email
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    public function rejectSupplier(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $supplier = Supplier::findOrFail($id);

        $supplier->update([
            'approval_status' => 'rejected',
            'is_active' => false,
            'rejection_reason' => $request->rejection_reason,
            'approved_at' => now(),
            'approved_by' => Auth::id()
        ]);

        // Send rejection email to supplier
        try {
            $mail_body = view('email-templates.supplier-rejection', [
                'supplier' => $supplier,
                'rejection_reason' => $request->rejection_reason
            ])->render();

            $mail_config = [
                'recipient_address' => $supplier->email,
                'recipient_name' => $supplier->name,
                'subject' => 'Supplier Application Status Update - GNRAW',
                'body' => $mail_body,
            ];

            $email_sent = CMail::send($mail_config);

            if ($email_sent) {
                \Log::info('✅ Supplier rejection email sent successfully to: ' . $supplier->email);
                $email_status = 'Email sent successfully';
            } else {
                Log::error('❌ Failed to send rejection email to: ' . $supplier->email);
                $email_status = 'Email failed to send';
            }
        } catch (\Exception $e) {
            Log::error('Error sending supplier rejection email: ' . $e->getMessage());
        }

        $email_message = isset($email_status) ? " and {$email_status}" : '';
        return redirect()->back()->with('success', 'Supplier rejected successfully' . $email_message . '!');
    }

    // Client Inquiries Management
    public function inquiriesIndex(Request $request)
    {
        $query = ClientInquiry::with('product');

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $inquiries = $query->orderBy('created_at', 'desc')->paginate(20);

        $data = [
            'pageTitle' => 'Client Inquiries',
            'inquiries' => $inquiries,
            'status' => $request->status ?? 'all'
        ];

        return view('back.pages.inquiries.index', $data);
    }

    public function showInquiry(Request $request, $id)
    {
        $inquiry = ClientInquiry::with('product')->findOrFail($id);

        $data = [
            'pageTitle' => 'Inquiry Details',
            'inquiry' => $inquiry
        ];

        return view('back.pages.inquiries.show', $data);
    }

    public function updateInquiryStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'notes' => 'nullable|string|max:1000'
        ]);

        $inquiry = ClientInquiry::findOrFail($id);

        $inquiry->update([
            'status' => $request->status,
            'notes' => $request->notes,
            'assigned_to' => Auth::id()
        ]);

        return redirect()->back()->with('success', 'Inquiry status updated successfully!');
    }

    // Category Management Methods
    public function categoriesPage(Request $request)
    {
        $categories = \App\Models\ProductCategory::orderBy('sort_order', 'asc')
            ->orderBy('name', 'asc')
            ->paginate(20);

        $data = [
            'pageTitle' => 'Product Categories',
            'categories' => $categories
        ];

        return view('back.pages.admin.categories.index', $data);
    }

    public function createCategory(Request $request)
    {
        $data = [
            'pageTitle' => 'Create Category'
        ];

        return view('back.pages.admin.categories.create', $data);
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:product_categories,name',
            'slug' => 'nullable|string|max:255|unique:product_categories,slug',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'nullable|in:0,1',
            'sort_order' => 'nullable|integer|min:0'
        ]);

        $data = $request->only(['name', 'slug', 'description', 'sort_order']);

        // Handle boolean fields properly
        $data['is_active'] = $request->has('is_active') ? (bool) $request->input('is_active') : true;

        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        // Handle image upload - Store to file system
        if ($request->hasFile('image')) {
            // Ensure the categories folder exists
            $categoriesPath = storage_path('app/public/categories');
            if (!file_exists($categoriesPath)) {
                mkdir($categoriesPath, 0755, true);
                Log::info('Created categories folder', ['path' => $categoriesPath]);
            }

            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = $image->storeAs('categories', $imageName, 'public');
            // Normalize path to use forward slashes for URLs
            $data['image'] = str_replace('\\', '/', $imagePath);

            // Log the upload for debugging
            Log::info('Category image uploaded', [
                'original_name' => $image->getClientOriginalName(),
                'stored_path' => $data['image'],
                'file_size' => $image->getSize()
            ]);
        } else {
            Log::info('No image file provided for category creation');
        }

        // Log the data being saved (without binary data for safety)
        $logData = $data;
        unset($logData['image_data']); // Don't log binary data
        Log::info('Creating category with data', $logData);

        $category = \App\Models\ProductCategory::create($data);

        Log::info('Category created successfully', ['id' => $category->id, 'image' => $category->image]);

        return redirect()->route('admin.categories')->with('success', 'Category created successfully!');
    }

    public function editCategory(Request $request, $id)
    {
        $category = \App\Models\ProductCategory::findOrFail($id);

        $data = [
            'pageTitle' => 'Edit Category',
            'category' => $category
        ];

        return view('back.pages.admin.categories.edit', $data);
    }

    public function updateCategory(Request $request, $id)
    {
        Log::info('updateCategory method called', ['id' => $id, 'method' => $request->method()]);

        $category = \App\Models\ProductCategory::findOrFail($id);

        // Log the request data for debugging
        Log::info('Category update request received', [
            'category_id' => $id,
            'has_file' => $request->hasFile('image'),
            'all_files' => $request->allFiles(),
            'form_data' => $request->except(['_token', '_method']),
            'content_type' => $request->header('Content-Type'),
            'method' => $request->method()
        ]);

        // Temporarily disable validation to test upload
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:product_categories,name,' . $id,
                'slug' => 'nullable|string|max:255|unique:product_categories,slug,' . $id,
                'description' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'is_active' => 'nullable|in:0,1',
                'sort_order' => 'nullable|integer|min:0'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed for category update', [
                'errors' => $e->errors(),
                'category_id' => $id
            ]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        }

        $data = $request->only(['name', 'slug', 'description', 'sort_order']);

        // Handle boolean fields properly
        $data['is_active'] = $request->has('is_active') ? (bool) $request->input('is_active') : false;

        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        // Handle image upload - Store to file system
        if ($request->hasFile('image')) {
            // Ensure the categories folder exists
            $categoriesPath = storage_path('app/public/categories');
            if (!file_exists($categoriesPath)) {
                mkdir($categoriesPath, 0755, true);
                Log::info('Created categories folder on update', ['path' => $categoriesPath]);
            }

            // Delete old image
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }

            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = $image->storeAs('categories', $imageName, 'public');
            // Normalize path to use forward slashes for URLs
            $data['image'] = str_replace('\\', '/', $imagePath);

            Log::info('Category image updated', [
                'category_id' => $id,
                'original_name' => $image->getClientOriginalName(),
                'stored_path' => $imagePath,
                'file_size' => $image->getSize()
            ]);
        } else {
            Log::info('No new image file provided for category update', ['category_id' => $id]);
        }

        $category->update($data);

        return redirect()->route('admin.categories')->with('success', 'Category updated successfully!');
    }

    public function deleteCategory(Request $request, $id)
    {
        $category = \App\Models\ProductCategory::findOrFail($id);

        // Check if category has products
        if ($category->publicProducts()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete category that has products. Please move or delete the products first.');
        }

        // Delete image if exists
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return redirect()->route('admin.categories')->with('success', 'Category deleted successfully!');
    }

    // Products Management Methods
    public function productsPage(Request $request)
    {
        $query = \App\Models\SupplierProduct::with(['supplier', 'approvedBy'])->active();

        // Filter by approval status
        if ($request->has('status') && $request->status !== 'all') {
            if ($request->status === 'pending') {
                $query->where('is_approved', false);
            } elseif ($request->status === 'approved') {
                $query->where('is_approved', true);
            } elseif ($request->status === 'active') {
                $query->where('status', 'active');
            } elseif ($request->status === 'inactive') {
                $query->where('status', 'inactive');
            }
        }

        // Filter by supplier
        if ($request->has('supplier') && $request->supplier) {
            $query->where('supplier_id', $request->supplier);
        }

        // Search functionality
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('product_name', 'like', '%' . $request->search . '%')
                    ->orWhere('product_category', 'like', '%' . $request->search . '%')
                    ->orWhere('cas_number', 'like', '%' . $request->search . '%')
                    ->orWhereHas('supplier', function ($supplierQuery) use ($request) {
                        $supplierQuery->where('company_name', 'like', '%' . $request->search . '%');
                    });
            });
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(20);
        $suppliers = \App\Models\Supplier::where('is_active', true)->get();

        $data = [
            'pageTitle' => 'Products Management',
            'products' => $products,
            'suppliers' => $suppliers,
            'status' => $request->status ?? 'all',
            'supplier_filter' => $request->supplier ?? '',
            'search' => $request->search ?? ''
        ];

        return view('back.pages.admin.products.index', $data);
    }

    public function showProduct(Request $request, $id)
    {
        $product = \App\Models\SupplierProduct::withTrashed()->with(['supplier', 'approvedBy', 'documents', 'comments.admin'])->findOrFail($id);

        $data = [
            'pageTitle' => 'Product Details - ' . $product->product_name,
            'product' => $product
        ];

        return view('back.pages.admin.products.show', $data);
    }

    public function approveProduct(Request $request, $id)
    {
        $product = \App\Models\SupplierProduct::findOrFail($id);

        $product->update([
            'is_approved' => true,
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'status' => 'active'
        ]);

        return redirect()->back()->with('success', 'Product approved successfully!');
    }

    public function rejectProduct(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $product = \App\Models\SupplierProduct::findOrFail($id);

        $product->update([
            'is_approved' => false,
            'status' => 'inactive',
            'rejection_reason' => $request->rejection_reason
        ]);

        return redirect()->back()->with('success', 'Product rejected successfully!');
    }

    public function toggleProductStatus(Request $request, $id)
    {
        $product = \App\Models\SupplierProduct::findOrFail($id);

        $newStatus = $product->status === 'active' ? 'inactive' : 'active';
        $product->update(['status' => $newStatus]);

        $message = $newStatus === 'active' ? 'Product activated successfully!' : 'Product deactivated successfully!';
        return redirect()->back()->with('success', $message);
    }

    public function customersPage(Request $request)
    {
        // Get all customers with their inquiries count
        $customers = \App\Models\User::withCount('inquiries')
            ->orderBy('created_at', 'desc')
            ->get();

        $data = [
            'pageTitle' => 'Customers Management',
            'customers' => $customers
        ];
        return view('back.pages.admin.customers.index', $data);
    }

    public function getCustomerDetails(Request $request, $id)
    {
        try {
            $customer = \App\Models\User::with(['inquiries.supplier', 'inquiries.product'])->findOrFail($id);

            $data = [
                'success' => true,
                'customer' => [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'email' => $customer->email,
                    'phone' => $customer->phone,
                    'registration_date' => $customer->created_at->format('M d, Y'),
                    'last_activity' => $customer->last_login_at ? $customer->last_login_at->diffForHumans() : 'Never'
                ],
                'inquiries' => $customer->inquiries->map(function ($inquiry) {
                    return [
                        'id' => $inquiry->id,
                        'product_name' => $inquiry->product_name,
                        'message' => $inquiry->message,
                        'status' => $inquiry->status,
                        'created_at' => $inquiry->created_at->format('M d, Y H:i'),
                        'supplier_name' => $inquiry->supplier->company_name ?? 'Unknown'
                    ];
                })
            ];

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found or error occurred'
            ], 404);
        }
    }


    public function shippingPage(Request $request)
    {
        $data = [
            'pageTitle' => 'Shipping Management',
        ];
        return view('back.pages.admin.shipping.index', $data);
    }

    public function certificatesPage(Request $request)
    {
        $data = [
            'pageTitle' => 'Certificates Management',
        ];
        return view('back.pages.admin.certificates.index', $data);
    }

    public function reportsPage(Request $request)
    {
        // Get date range for filtering (default to last 30 days)
        $startDate = $request->get('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        // Previous period for comparison
        $previousStartDate = \Carbon\Carbon::parse($startDate)->subDays(30)->format('Y-m-d');
        $previousEndDate = \Carbon\Carbon::parse($endDate)->subDays(30)->format('Y-m-d');

        // Total Suppliers
        $totalSuppliers = \App\Models\Supplier::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->count();

        $previousSuppliers = \App\Models\Supplier::whereBetween('created_at', [$previousStartDate . ' 00:00:00', $previousEndDate . ' 23:59:59'])
            ->count();

        $suppliersGrowth = $previousSuppliers > 0 ? (($totalSuppliers - $previousSuppliers) / $previousSuppliers) * 100 : 0;

        // Approved Suppliers
        $approvedSuppliers = \App\Models\Supplier::where('approval_status', 'approved')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->count();

        $previousApprovedSuppliers = \App\Models\Supplier::where('approval_status', 'approved')
            ->whereBetween('created_at', [$previousStartDate . ' 00:00:00', $previousEndDate . ' 23:59:59'])
            ->count();

        $approvedSuppliersGrowth = $previousApprovedSuppliers > 0 ? (($approvedSuppliers - $previousApprovedSuppliers) / $previousApprovedSuppliers) * 100 : 0;

        // Total Customers
        $totalCustomers = \App\Models\User::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->count();

        $previousCustomers = \App\Models\User::whereBetween('created_at', [$previousStartDate . ' 00:00:00', $previousEndDate . ' 23:59:59'])
            ->count();

        $customersGrowth = $previousCustomers > 0 ? (($totalCustomers - $previousCustomers) / $previousCustomers) * 100 : 0;

        // Total Products
        $totalProducts = \App\Models\SupplierProduct::where('is_approved', true)
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->count();

        $previousProducts = \App\Models\SupplierProduct::where('is_approved', true)
            ->whereBetween('created_at', [$previousStartDate . ' 00:00:00', $previousEndDate . ' 23:59:59'])
            ->count();

        $productsGrowth = $previousProducts > 0 ? (($totalProducts - $previousProducts) / $previousProducts) * 100 : 0;

        // Monthly Products Data for Chart (last 12 months)
        $monthlyProducts = \App\Models\SupplierProduct::where('is_approved', true)
            ->where('created_at', '>=', now()->subMonths(12))
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as total_products')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('total_products', 'month');

        // Top Categories (by product count)
        $topCategories = \App\Models\ProductCategory::withCount(['publicProducts' => function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }])
            ->having('public_products_count', '>', 0)
            ->orderBy('public_products_count', 'desc')
            ->limit(5)
            ->get();

        // Recent Inquiries
        $recentInquiries = \App\Models\ClientInquiry::with(['product', 'customer'])
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Supplier Performance (by product count and approval status)
        $supplierPerformance = \App\Models\Supplier::withCount(['products' => function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }])
            ->withCount(['products as approved_products_count' => function ($query) use ($startDate, $endDate) {
                $query->where('is_approved', true)
                    ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
            }])
            ->having('products_count', '>', 0)
            ->orderBy('approved_products_count', 'desc')
            ->limit(5)
            ->get();

        // Inquiry Statistics
        $totalInquiries = \App\Models\ClientInquiry::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->count();

        $previousInquiries = \App\Models\ClientInquiry::whereBetween('created_at', [$previousStartDate . ' 00:00:00', $previousEndDate . ' 23:59:59'])
            ->count();

        $inquiriesGrowth = $previousInquiries > 0 ? (($totalInquiries - $previousInquiries) / $previousInquiries) * 100 : 0;

        $data = [
            'pageTitle' => 'Reports & Analytics',
            'startDate' => $startDate,
            'endDate' => $endDate,
            'totalSuppliers' => $totalSuppliers,
            'suppliersGrowth' => $suppliersGrowth,
            'approvedSuppliers' => $approvedSuppliers,
            'approvedSuppliersGrowth' => $approvedSuppliersGrowth,
            'totalCustomers' => $totalCustomers,
            'customersGrowth' => $customersGrowth,
            'totalProducts' => $totalProducts,
            'productsGrowth' => $productsGrowth,
            'totalInquiries' => $totalInquiries,
            'inquiriesGrowth' => $inquiriesGrowth,
            'monthlyProducts' => $monthlyProducts,
            'topCategories' => $topCategories,
            'recentInquiries' => $recentInquiries,
            'supplierPerformance' => $supplierPerformance,
        ];

        return view('back.pages.admin.reports.index', $data);
    }

    public function notificationsPage(Request $request)
    {
        $admin = Auth::user();
        $notifications = $admin->notifications()->paginate(20);

        $data = [
            'pageTitle' => 'Notifications',
            'notifications' => $notifications
        ];
        return view('back.pages.admin.notifications.index', $data);
    }

    public function supplierCertificatesPage(Request $request)
    {
        // Get all suppliers with their certificates and products count
        $suppliers = \App\Models\Supplier::with(['certificates', 'products'])
            ->selectRaw('suppliers.id, suppliers.name, suppliers.company_name, suppliers.email, suppliers.phone, suppliers.approval_status, suppliers.created_at,
                    COUNT(DISTINCT supplier_products.id) as products_count,
                    COUNT(DISTINCT supplier_certificates.id) as certificates_count')
            ->leftJoin('supplier_products', 'suppliers.id', '=', 'supplier_products.supplier_id')
            ->leftJoin('supplier_certificates', 'suppliers.id', '=', 'supplier_certificates.supplier_id')
            ->groupBy('suppliers.id', 'suppliers.name', 'suppliers.company_name', 'suppliers.email', 'suppliers.phone', 'suppliers.approval_status', 'suppliers.created_at')
            ->orderBy('suppliers.created_at', 'desc')
            ->get();

        $data = [
            'pageTitle' => 'Supplier Certificates Management',
            'suppliers' => $suppliers
        ];
        return view('back.pages.admin.supplier-certificates', $data);
    }

    public function getSupplierDetails(Request $request, $id)
    {
        try {
            $supplier = \App\Models\Supplier::with(['certificates', 'products'])->findOrFail($id);

            $data = [
                'success' => true,
                'supplier' => [
                    'id' => $supplier->id,
                    'company_name' => $supplier->company_name,
                    'name' => $supplier->name,
                    'email' => $supplier->email,
                    'phone' => $supplier->phone,
                    'approval_status' => $supplier->approval_status,
                    'created_at' => $supplier->created_at->diffForHumans()
                ],
                'certificates' => $supplier->certificates->map(function ($cert) {
                    return [
                        'id' => $cert->id,
                        'certificate_type' => $cert->certificate_type,
                        'certificate_name' => $cert->certificate_name,
                        'file_path' => $cert->file_path,
                        'created_at' => $cert->created_at->format('M d, Y')
                    ];
                }),
                'products' => $supplier->products->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'product_name' => $product->product_name,
                        'product_category' => $product->product_category,
                        'moq' => $product->moq,
                        'is_approved' => $product->is_approved
                    ];
                })
            ];

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Supplier not found or error occurred'
            ], 404);
        }
    }

    // Product Comments Management
    public function addProductComment(Request $request, $id)
    {
        try {
            \Log::info('Add comment request received', [
                'product_id' => $id,
                'comment' => $request->comment,
                'is_urgent' => $request->is_urgent,
                'admin_id' => Auth::id()
            ]);

            $request->validate([
                'comment' => 'required|string|max:1000',
                'is_urgent' => 'boolean'
            ]);

            $product = \App\Models\SupplierProduct::with('supplier')->findOrFail($id);
            $admin = Auth::user();

            if (!$admin) {
                \Log::error('No authenticated admin found');
                return redirect()->back()->with('error', 'You must be logged in as an admin to add comments.');
            }

            $comment = \App\Models\ProductComment::create([
                'product_id' => $product->id,
                'admin_id' => $admin->id,
                'comment' => $request->comment,
                'is_urgent' => $request->boolean('is_urgent', false)
            ]);

            \Log::info('Comment created successfully', ['comment_id' => $comment->id]);

            // Create notification for supplier
            \App\Models\Notification::createCommentAddedNotification(
                $product->supplier_id,
                $product->product_name,
                $admin->name,
                $request->boolean('is_urgent', false),
                $comment->id,
                $product->id
            );

            \Log::info('Notification created successfully');

            return redirect()->back()->with('success', 'Comment added successfully!');
        } catch (\Exception $e) {
            \Log::error('Error adding comment: ' . $e->getMessage(), [
                'product_id' => $id,
                'error' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Error adding comment: ' . $e->getMessage());
        }
    }

    public function deleteProductComment(Request $request, $id)
    {
        $comment = \App\Models\ProductComment::findOrFail($id);

        // Only allow admin who created the comment or super admin to delete
        if ($comment->admin_id !== Auth::id()) {
            return redirect()->back()->with('error', 'You can only delete your own comments.');
        }

        $comment->delete();

        return redirect()->back()->with('success', 'Comment deleted successfully!');
    }

    public function markCommentAsRead(Request $request, $id)
    {
        $comment = \App\Models\ProductComment::findOrFail($id);
        $comment->markAsRead();

        return response()->json(['success' => true]);
    }

    // Admin Notification Management
    public function getUnreadNotificationCount(Request $request)
    {
        $admin = Auth::user();
        $count = $admin->unreadNotifications()->count();

        return response()->json(['count' => $count]);
    }

    public function getRecentNotifications(Request $request)
    {
        $admin = Auth::user();
        $notifications = $admin->notifications()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'notifications' => $notifications,
            'count' => $admin->unreadNotifications()->count()
        ]);
    }

    public function markNotificationAsRead(Request $request, $id)
    {
        $admin = Auth::user();
        $notification = $admin->notifications()->findOrFail($id);
        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    public function markAllNotificationsAsRead(Request $request)
    {
        try {
            $admin = Auth::user();

            // Get count of unread notifications before marking as read
            $unreadCountBefore = $admin->unreadNotifications()->count();

            // Mark all unread notifications as read
            $updatedCount = $admin->unreadNotifications()->update([
                'is_read' => true,
                'read_at' => now()
            ]);

            \Log::info('Admin marked all notifications as read', [
                'admin_id' => $admin->id,
                'updated_count' => $updatedCount,
                'unread_count_before' => $unreadCountBefore
            ]);

            return response()->json([
                'success' => true,
                'updated_count' => $updatedCount,
                'unread_count_before' => $unreadCountBefore
            ]);
        } catch (\Exception $e) {
            \Log::error('Error marking all admin notifications as read: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error marking notifications as read: ' . $e->getMessage()
            ], 500);
        }
    }

    public function draftProductsPage(Request $request)
    {
        $query = SupplierProduct::drafts()->with(['supplier', 'approvedBy']);

        // Search
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('product_name', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%')
                    ->orWhere('product_category', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by supplier
        if ($request->has('supplier') && $request->supplier) {
            $query->where('supplier_id', $request->supplier);
        }

        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->where('product_category', $request->category);
        }

        $draftProducts = $query->orderBy('deleted_at', 'desc')->paginate(15);
        $suppliers = Supplier::orderBy('company_name')->get();
        $categories = ProductCategory::where('is_active', true)->get();

        $data = [
            'pageTitle' => 'Draft Products',
            'draftProducts' => $draftProducts,
            'suppliers' => $suppliers,
            'categories' => $categories,
            'search' => $request->search,
            'selectedSupplier' => $request->supplier,
            'selectedCategory' => $request->category,
        ];

        return view('back.pages.admin.products.drafts', $data);
    }


    public function permanentlyDeleteProduct(Request $request, $id)
    {
        try {
            $product = SupplierProduct::withTrashed()->findOrFail($id);
            $productName = $product->product_name;
            $product->forceDelete();

            \Log::info('Product permanently deleted', [
                'product_id' => $id,
                'product_name' => $productName,
                'admin_id' => Auth::id()
            ]);

            return redirect()->back()->with('success', 'Product permanently deleted!');
        } catch (\Exception $e) {
            \Log::error('Error permanently deleting product: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error permanently deleting product: ' . $e->getMessage());
        }
    }
}
