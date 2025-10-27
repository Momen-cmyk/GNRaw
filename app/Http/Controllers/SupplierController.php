<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\Supplier;
use App\Models\SupplierProduct;
use App\Models\ProductCategory;
use App\Models\SupplierDocument;
use App\Models\ProductComment;

class SupplierController extends Controller
{
    public function supplierDashboard(Request $request)
    {
        $supplier = Auth::guard('supplier')->user();

        $stats = [
            'total_products' => SupplierProduct::where('supplier_id', $supplier->id)->active()->count(),
            'active_products' => SupplierProduct::where('supplier_id', $supplier->id)->active()->where('is_available', true)->count(),
            'pending_products' => SupplierProduct::where('supplier_id', $supplier->id)->active()->where('is_approved', false)->count()
        ];

        $recentProducts = SupplierProduct::where('supplier_id', $supplier->id)
            ->active()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get recent unread comments for supplier's products
        $recentComments = ProductComment::whereHas('product', function ($query) use ($supplier) {
            $query->where('supplier_id', $supplier->id);
        })
            ->with(['product', 'admin'])
            ->where('is_read_by_supplier', false)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Get unread comments count
        $unreadCommentsCount = ProductComment::whereHas('product', function ($query) use ($supplier) {
            $query->where('supplier_id', $supplier->id);
        })
            ->where('is_read_by_supplier', false)
            ->count();

        // Get urgent unread comments count
        $urgentCommentsCount = ProductComment::whereHas('product', function ($query) use ($supplier) {
            $query->where('supplier_id', $supplier->id);
        })
            ->where('is_read_by_supplier', false)
            ->where('is_urgent', true)
            ->count();

        $data = [
            'pageTitle' => 'Dashboard',
            'stats' => $stats,
            'recentProducts' => $recentProducts,
            'recentComments' => $recentComments,
            'unreadCommentsCount' => $unreadCommentsCount,
            'urgentCommentsCount' => $urgentCommentsCount,
        ];

        return view('back.pages.supplier.dashboard', $data);
    }

    public function logoutHandler(Request $request)
    {
        Auth::guard('supplier')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('supplier.login')->with('fail', 'You are now logged out!');
    }

    public function ProfileView(Request $request)
    {
        $supplier = Auth::guard('supplier')->user();

        $data = [
            'pageTitle' => 'Profile',
            'supplier' => $supplier
        ];

        return view('back.pages.supplier.profile', $data);
    }

    public function updateProfile(Request $request)
    {
        $supplier = Auth::guard('supplier')->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:suppliers,email,' . $supplier->id,
            'phone' => 'nullable|string|max:20',
            'company_name' => 'required|string|max:255',
            'company_activity' => 'nullable|in:manufacturing,trading,manufacturing_trading',
            'company_description' => 'nullable|string|max:1000'
        ]);

        $supplier->update($request->only([
            'name',
            'email',
            'phone',
            'company_name',
            'company_activity',
            'company_description'
        ]));

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    public function uploadProfilePicture(Request $request)
    {
        $request->validate([
            'profilePictureFile' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $supplier = Auth::guard('supplier')->user();

        if ($request->hasFile('profilePictureFile')) {
            $file = $request->file('profilePictureFile');
            $filename = 'supplier_' . $supplier->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('images/suppliers', $filename, 'public');

            $supplier->update(['picture' => $path]);

            return response()->json([
                'status' => 1,
                'image_path' => asset('storage/' . $path),
                'message' => 'Profile picture updated successfully.'
            ]);
        }

        return response()->json([
            'status' => 0,
            'message' => 'Failed to upload profile picture.'
        ]);
    }

    public function generalSettings(Request $request)
    {
        $supplier = Auth::guard('supplier')->user();

        $data = [
            'pageTitle' => 'Settings',
            'user' => $supplier
        ];
        return view('back.pages.supplier.settings', $data);
    }

    // Product Management
    public function productsPage(Request $request)
    {
        $supplier = Auth::guard('supplier')->user();

        $query = SupplierProduct::where('supplier_id', $supplier->id)->active();

        // Search
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('product_name', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            if ($request->status === 'active') {
                $query->where('is_available', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_available', false);
            } elseif ($request->status === 'pending') {
                $query->where('is_approved', false);
            }
        }

        // Filter by category
        if ($request->has('category') && $request->category !== '') {
            $query->where('product_category', $request->category);
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(20);
        $categories = ProductCategory::where('is_active', true)->get();

        $data = [
            'pageTitle' => 'Product Management',
            'products' => $products,
            'categories' => $categories,
            'search' => $request->search ?? '',
            'status' => $request->status ?? 'all',
            'category' => $request->category ?? ''
        ];

        return view('back.pages.supplier.products.index', $data);
    }

    public function createProduct(Request $request)
    {
        $categories = ProductCategory::where('is_active', true)->get();

        $data = [
            'pageTitle' => 'Add New Product',
            'categories' => $categories
        ];

        return view('back.pages.supplier.products.create', $data);
    }

    public function storeProduct(Request $request)
    {
        $supplier = Auth::guard('supplier')->user();

        $request->validate([
            'product_name' => 'required|string|max:255',
            'product_category' => 'required|string|max:100',
            'cas_number' => 'nullable|string|max:50',
            'molecular_formula' => 'nullable|string|max:100',
            'molecular_weight' => 'nullable|string|max:50',
            'specifications' => 'required|string|max:5000',
            'moq' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:2000',
            'product_images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
            'coa_document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240'
        ]);

        $product = SupplierProduct::create([
            'supplier_id' => $supplier->id,
            'product_name' => $request->product_name,
            'product_category' => $request->product_category,
            'cas_number' => $request->cas_number,
            'molecular_formula' => $request->molecular_formula,
            'molecular_weight' => $request->molecular_weight,
            'specifications' => $request->specifications,
            'moq' => $request->moq,
            'description' => $request->description,
            'is_approved' => false, // New products need admin approval
            'last_updated' => now()
        ]);

        // Handle product images
        if ($request->hasFile('product_images')) {
            foreach ($request->file('product_images') as $image) {
                $filename = 'product_' . $product->id . '_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('images/products', $filename, 'public');

                // Store image path in product_images JSON field
                $images = $product->product_images ?? [];
                $images[] = $path;
                $product->update(['product_images' => $images]);
            }
        }

        // Handle COA document upload
        if ($request->hasFile('coa_document')) {
            $coaFile = $request->file('coa_document');
            $coaPath = $coaFile->store('product_documents/coa', 'public');

            \App\Models\ProductDocument::create([
                'product_id' => $product->id,
                'document_type' => 'COA',
                'document_name' => $coaFile->getClientOriginalName(),
                'file_path' => $coaPath,
                'is_verified' => false
            ]);
        }

        // Send notification to all admins
        \App\Models\AdminNotification::createProductAddedNotification(
            $supplier->id,
            $product->product_name,
            $supplier->company_name,
            $product->id
        );

        return redirect()->route('supplier.products')->with('success', 'Product added successfully! It will be reviewed by admin before going live.');
    }

    public function editProduct(Request $request, $id)
    {
        $supplier = Auth::guard('supplier')->user();
        $product = SupplierProduct::where('supplier_id', $supplier->id)->with(['comments.admin', 'documents'])->findOrFail($id);
        $categories = ProductCategory::where('is_active', true)->get();

        // Get comments for this product
        $comments = ProductComment::where('product_id', $product->id)
            ->with('admin')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get unread comments count
        $unreadCommentsCount = ProductComment::where('product_id', $product->id)
            ->where('is_read_by_supplier', false)
            ->count();

        // Get urgent comments count
        $urgentCommentsCount = ProductComment::where('product_id', $product->id)
            ->where('is_read_by_supplier', false)
            ->where('is_urgent', true)
            ->count();

        $data = [
            'pageTitle' => 'Edit Product',
            'product' => $product,
            'categories' => $categories,
            'comments' => $comments,
            'unreadCommentsCount' => $unreadCommentsCount,
            'urgentCommentsCount' => $urgentCommentsCount,
        ];

        return view('back.pages.supplier.products.edit', $data);
    }

    public function updateProduct(Request $request, $id)
    {
        $supplier = Auth::guard('supplier')->user();
        $product = SupplierProduct::where('supplier_id', $supplier->id)->active()->findOrFail($id);

        $request->validate([
            'product_name' => 'required|string|max:255',
            'product_category' => 'required|string|max:100',
            'cas_number' => 'nullable|string|max:50',
            'molecular_formula' => 'nullable|string|max:100',
            'molecular_weight' => 'nullable|string|max:50',
            'specifications' => 'required|string|max:5000',
            'moq' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:2000',
            'product_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'coa_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240'
        ]);

        $product->update([
            'product_name' => $request->product_name,
            'product_category' => $request->product_category,
            'cas_number' => $request->cas_number,
            'molecular_formula' => $request->molecular_formula,
            'molecular_weight' => $request->molecular_weight,
            'specifications' => $request->specifications,
            'moq' => $request->moq,
            'description' => $request->description,
            'is_approved' => false, // Changes need re-approval
            'last_updated' => now()
        ]);

        // Handle new product images
        if ($request->hasFile('product_images')) {
            $images = $product->product_images ?? [];
            foreach ($request->file('product_images') as $image) {
                $filename = 'product_' . $product->id . '_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('images/products', $filename, 'public');
                $images[] = $path;
            }
            $product->update(['product_images' => $images]);
        }

        // Handle COA document upload (replace existing if uploaded)
        if ($request->hasFile('coa_document')) {
            $coaFile = $request->file('coa_document');
            $coaPath = $coaFile->store('product_documents/coa', 'public');

            // Delete old COA if exists
            $oldCoa = \App\Models\ProductDocument::where('product_id', $product->id)
                ->where('document_type', 'COA')
                ->first();
            if ($oldCoa) {
                // Delete old file from storage
                Storage::disk('public')->delete($oldCoa->file_path);
                $oldCoa->delete();
            }

            // Create new COA record
            \App\Models\ProductDocument::create([
                'product_id' => $product->id,
                'document_type' => 'COA',
                'document_name' => $coaFile->getClientOriginalName(),
                'file_path' => $coaPath,
                'is_verified' => false
            ]);
        }

        // Send notification to all admins
        \App\Models\AdminNotification::createProductUpdatedNotification(
            $supplier->id,
            $product->product_name,
            $supplier->company_name,
            $product->id
        );

        return redirect()->route('supplier.products')->with('success', 'Product updated successfully! Changes will be reviewed by admin.');
    }

    public function deleteProduct(Request $request, $id)
    {
        $supplier = Auth::guard('supplier')->user();
        $product = SupplierProduct::where('supplier_id', $supplier->id)->active()->findOrFail($id);

        // Store product info before deletion for notification
        $productName = $product->product_name;

        // Soft delete the product (make it a draft)
        $product->delete();

        // Send notification to all admins about product being moved to draft
        \App\Models\AdminNotification::createProductDeletedNotification(
            $supplier->id,
            $productName,
            $supplier->company_name,
            $id
        );

        return redirect()->route('supplier.products')->with('success', 'Product moved to draft successfully!');
    }

    // Certificate Management
    public function certificatesPage(Request $request)
    {
        $supplier = Auth::guard('supplier')->user();
        $certificates = \App\Models\SupplierCertificate::where('supplier_id', $supplier->id)->get();

        $data = [
            'pageTitle' => 'Certificates',
            'certificates' => $certificates
        ];

        return view('back.pages.supplier.certificates.index', $data);
    }

    public function storeCertificate(Request $request)
    {
        $supplier = Auth::guard('supplier')->user();

        $request->validate([
            'certificate_type' => 'required|in:iso,manufacturing,gmp,wc',
            'certificate_name' => 'required|string|max:255',
            'certificate_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'issue_date' => 'nullable|date',
            'expiry_date' => 'nullable|date',
            'issuing_authority' => 'nullable|string|max:255',
            'certificate_number' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $file = $request->file('certificate_file');
        $path = $file->store('certificates', 'public');

        \App\Models\SupplierCertificate::create([
            'supplier_id' => $supplier->id,
            'certificate_type' => $request->certificate_type,
            'certificate_name' => $request->certificate_name,
            'file_path' => $path,
            'original_filename' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'description' => $request->description,
            'issue_date' => $request->issue_date,
            'expiry_date' => $request->expiry_date,
            'issuing_authority' => $request->issuing_authority,
            'certificate_number' => $request->certificate_number,
            'status' => 'pending_review',
        ]);

        return redirect()->route('supplier.certificates')->with('success', 'Certificate uploaded successfully!');
    }

    public function deleteCertificate($id)
    {
        $supplier = Auth::guard('supplier')->user();
        $certificate = \App\Models\SupplierCertificate::where('supplier_id', $supplier->id)->findOrFail($id);

        Storage::disk('public')->delete($certificate->file_path);
        $certificate->delete();

        return redirect()->route('supplier.certificates')->with('success', 'Certificate deleted successfully!');
    }

    // Orders Management
    public function ordersPage(Request $request)
    {
        $supplier = Auth::guard('supplier')->user();
        $orders = \App\Models\Order::where('supplier_id', $supplier->id)
            ->with(['customer', 'items'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $data = [
            'pageTitle' => 'Orders',
            'orders' => $orders
        ];

        return view('back.pages.supplier.orders.index', $data);
    }

    public function viewOrder($id)
    {
        $supplier = Auth::guard('supplier')->user();
        $order = \App\Models\Order::where('supplier_id', $supplier->id)
            ->with(['customer', 'items.product'])
            ->findOrFail($id);

        $data = [
            'pageTitle' => 'Order Details',
            'order' => $order
        ];

        return view('back.pages.supplier.orders.view', $data);
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $supplier = Auth::guard('supplier')->user();
        $order = \App\Models\Order::where('supplier_id', $supplier->id)->findOrFail($id);

        $request->validate([
            'status' => 'required|in:confirmed,processing,shipped,delivered,cancelled',
            'tracking_number' => 'nullable|string',
            'carrier' => 'nullable|string',
        ]);

        $order->update([
            'status' => $request->status,
            'tracking_number' => $request->tracking_number,
            'carrier' => $request->carrier,
            'shipped_at' => $request->status === 'shipped' ? now() : $order->shipped_at,
            'delivered_at' => $request->status === 'delivered' ? now() : $order->delivered_at,
        ]);

        return redirect()->back()->with('success', 'Order status updated successfully!');
    }

    // Inventory Management
    public function inventoryPage(Request $request)
    {
        $supplier = Auth::guard('supplier')->user();
        $inventory = \App\Models\Inventory::where('supplier_id', $supplier->id)
            ->with('product')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $lowStockItems = \App\Models\Inventory::where('supplier_id', $supplier->id)
            ->lowStock()
            ->count();

        $outOfStockItems = \App\Models\Inventory::where('supplier_id', $supplier->id)
            ->outOfStock()
            ->count();

        $data = [
            'pageTitle' => 'Inventory',
            'inventory' => $inventory,
            'lowStockItems' => $lowStockItems,
            'outOfStockItems' => $outOfStockItems
        ];

        return view('back.pages.supplier.inventory.index', $data);
    }

    public function updateInventory(Request $request)
    {
        $supplier = Auth::guard('supplier')->user();

        $request->validate([
            'inventory_id' => 'required|exists:inventory,id',
            'quantity' => 'required|integer',
            'movement_type' => 'required|in:purchase,adjustment,damage,expired',
            'notes' => 'nullable|string|max:500',
        ]);

        $inventory = \App\Models\Inventory::where('supplier_id', $supplier->id)
            ->findOrFail($request->inventory_id);

        // Record the movement
        \App\Models\InventoryMovement::recordMovement(
            $inventory,
            $request->movement_type,
            $request->quantity,
            [
                'notes' => $request->notes,
                'user_id' => $supplier->id,
                'user_type' => 'supplier',
            ]
        );

        return redirect()->back()->with('success', 'Inventory updated successfully!');
    }

    public function inventoryMovements(Request $request)
    {
        $supplier = Auth::guard('supplier')->user();
        $movements = \App\Models\InventoryMovement::where('supplier_id', $supplier->id)
            ->with(['product', 'inventory'])
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        $data = [
            'pageTitle' => 'Inventory Movements',
            'movements' => $movements
        ];

        return view('back.pages.supplier.inventory.movements', $data);
    }

    // Sales & Reports
    public function salesPage(Request $request)
    {
        $supplier = Auth::guard('supplier')->user();

        // Get sales data
        $totalSales = \App\Models\Order::where('supplier_id', $supplier->id)
            ->where('payment_status', 'paid')
            ->sum('total');

        $totalOrders = \App\Models\Order::where('supplier_id', $supplier->id)->count();

        $recentOrders = \App\Models\Order::where('supplier_id', $supplier->id)
            ->with('customer')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $data = [
            'pageTitle' => 'Sales',
            'totalSales' => $totalSales,
            'totalOrders' => $totalOrders,
            'recentOrders' => $recentOrders
        ];

        return view('back.pages.supplier.sales.index', $data);
    }

    public function reportsPage(Request $request)
    {
        $supplier = Auth::guard('supplier')->user();

        $data = [
            'pageTitle' => 'Reports',
        ];

        return view('back.pages.supplier.reports.index', $data);
    }

    // Customer Management
    public function customersPage(Request $request)
    {
        $supplier = Auth::guard('supplier')->user();

        // Get all customers who have ordered from this supplier
        $customers = \App\Models\User::whereHas('orders', function ($query) use ($supplier) {
            $query->where('supplier_id', $supplier->id);
        })->with(['orders' => function ($query) use ($supplier) {
            $query->where('supplier_id', $supplier->id);
        }])->paginate(20);

        $data = [
            'pageTitle' => 'Customers',
            'customers' => $customers
        ];

        return view('back.pages.supplier.customers.index', $data);
    }

    // Shipping Management
    public function shippingPage(Request $request)
    {
        $supplier = Auth::guard('supplier')->user();

        // Get orders that need shipping
        $pendingShipments = \App\Models\Order::where('supplier_id', $supplier->id)
            ->whereIn('status', ['confirmed', 'processing'])
            ->with(['customer', 'items'])
            ->orderBy('created_at', 'asc')
            ->paginate(20);

        $shippedOrders = \App\Models\Order::where('supplier_id', $supplier->id)
            ->where('status', 'shipped')
            ->with(['customer'])
            ->orderBy('shipped_at', 'desc')
            ->limit(10)
            ->get();

        $data = [
            'pageTitle' => 'Shipping',
            'pendingShipments' => $pendingShipments,
            'shippedOrders' => $shippedOrders
        ];

        return view('back.pages.supplier.shipping.index', $data);
    }

    // Notifications
    public function notificationsPage(Request $request)
    {
        $supplier = Auth::guard('supplier')->user();

        $notifications = $supplier->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $data = [
            'pageTitle' => 'Notifications',
            'notifications' => $notifications,
        ];

        return view('back.pages.supplier.notifications.index', $data);
    }

    // Settings Update
    public function updateSettings(Request $request)
    {
        $supplier = Auth::guard('supplier')->user();

        $request->validate([
            'email_notifications' => 'nullable|boolean',
            'language' => 'nullable|string|in:en,ar,fr,es',
            'timezone' => 'nullable|string',
        ]);

        // Prepare the data to update
        $updateData = [];

        // Handle email notifications (checkbox)
        if ($request->has('email_notifications')) {
            $updateData['email_notifications'] = true;
        } else {
            $updateData['email_notifications'] = false;
        }

        // Handle language
        if ($request->has('language')) {
            $updateData['language'] = $request->language;
        }

        // Handle timezone
        if ($request->has('timezone')) {
            $updateData['timezone'] = $request->timezone;
        }

        // Update the supplier settings
        $supplier->update($updateData);

        return redirect()->back()->with('success', 'Settings updated successfully!');
    }

    public function changePassword(Request $request)
    {
        $supplier = Auth::guard('supplier')->user();

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8',
            'new_password_confirmation' => 'required|same:new_password',
        ]);

        // Check if current password is correct
        if (!Hash::check($request->current_password, $supplier->password)) {
            return redirect()->back()->with('error', 'Current password is incorrect!');
        }

        // Update password
        $supplier->update([
            'password' => Hash::make($request->new_password)
        ]);

        return redirect()->back()->with('success', 'Password changed successfully!');
    }

    public function getProductDetails(Request $request, $id)
    {
        try {
            $supplier = Auth::guard('supplier')->user();
            $product = SupplierProduct::with(['comments.admin', 'documents'])
                ->where('supplier_id', $supplier->id)
                ->findOrFail($id);

            // Mark unread comments as read
            $product->comments()->where('is_read_by_supplier', false)->update([
                'is_read_by_supplier' => true,
                'read_at' => now()
            ]);

            // Refresh the product to get updated comments
            $product->refresh();

            return response()->json([
                'success' => true,
                'product' => $product,
                'comments' => $product->comments->map(function ($comment) {
                    return [
                        'id' => $comment->id,
                        'comment' => $comment->comment,
                        'is_urgent' => $comment->is_urgent,
                        'admin_name' => $comment->admin ? $comment->admin->name : 'Unknown Admin',
                        'created_at' => $comment->created_at->format('M d, Y H:i'),
                        'is_read' => $comment->is_read_by_supplier,
                        'read_at' => $comment->read_at ? $comment->read_at->format('M d, Y H:i') : null
                    ];
                }),
                'documents' => $product->documents->map(function ($document) {
                    return [
                        'id' => $document->id,
                        'document_type' => $document->document_type,
                        'document_name' => $document->document_name,
                        'file_path' => $document->file_path,
                        'is_verified' => $document->is_verified,
                        'view_url' => route('document.viewer', $document->file_path),
                        'download_url' => asset('storage/' . $document->file_path)
                    ];
                })
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getProductDetails: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error loading product details: ' . $e->getMessage()
            ], 500);
        }
    }

    // Notification Management
    public function getUnreadNotificationCount(Request $request)
    {
        $supplier = Auth::guard('supplier')->user();
        $count = $supplier->unreadNotifications()->count();

        return response()->json(['count' => $count]);
    }

    public function markNotificationAsRead(Request $request, $id)
    {
        $supplier = Auth::guard('supplier')->user();
        $notification = $supplier->notifications()->findOrFail($id);

        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    public function markAllNotificationsAsRead(Request $request)
    {
        $supplier = Auth::guard('supplier')->user();

        // Get count before update for debugging
        $unreadCount = $supplier->unreadNotifications()->count();

        // Update all unread notifications
        $updatedCount = $supplier->unreadNotifications()->update([
            'is_read' => true,
            'read_at' => now()
        ]);

        Log::info('Mark all notifications as read', [
            'supplier_id' => $supplier->id,
            'unread_count_before' => $unreadCount,
            'updated_count' => $updatedCount
        ]);

        return response()->json([
            'success' => true,
            'updated_count' => $updatedCount,
            'unread_count_before' => $unreadCount
        ]);
    }

    public function markCommentRead(Request $request, $id)
    {
        try {
            $supplier = Auth::guard('supplier')->user();

            // Find the comment and verify it belongs to one of this supplier's products
            $comment = ProductComment::whereHas('product', function ($query) use ($supplier) {
                $query->where('supplier_id', $supplier->id);
            })
                ->findOrFail($id);

            // Mark the comment as read
            $comment->update([
                'is_read_by_supplier' => true,
                'read_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Comment marked as read'
            ]);
        } catch (\Exception $e) {
            Log::error('Error marking comment as read: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark comment as read'
            ], 500);
        }
    }
}
