    <?php

    use Illuminate\Support\Facades\Route;
    use Illuminate\Support\Facades\Auth;
    use App\Http\Controllers\AuthController;
    use App\Http\Controllers\AdminController;
    use App\Http\Controllers\SupplierController;
    use App\Http\Controllers\UserController;
    use App\Http\Controllers\LanguageController;

    /**
     * Language Switching Route
     */
    Route::get('/language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');

    /**
     *
     * User Portal Routes (Main Website) - website.test/
     */

    // Public routes
    // Public Routes
    Route::get('/', [UserController::class, 'homePage'])->middleware('handleAuthRedirects')->name('home');

    // Test route for category upload debugging
    Route::post('/test-category-upload', function (Request $request) {
        \Log::info('Test upload route called', [
            'has_file' => $request->hasFile('image'),
            'all_files' => $request->allFiles(),
            'form_data' => $request->all()
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = $image->storeAs('categories', $imageName, 'public');

            return response()->json([
                'success' => true,
                'message' => 'File uploaded successfully',
                'path' => $imagePath,
                'url' => \Storage::disk('public')->url($imagePath)
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No file received'
        ]);
    });

    Route::get('/test-category-image', function () {
        $category = \App\Models\ProductCategory::find(7);
        if ($category) {
            return response()->json([
                'category_id' => $category->id,
                'category_name' => $category->name,
                'image_path' => $category->image,
                'image_url' => $category->image_url,
                'file_exists' => \Storage::disk('public')->exists($category->image),
                'direct_url' => url('storage/' . $category->image)
            ]);
        }
        return response()->json(['error' => 'Category not found']);
    });

    // Route to serve category images from database
    Route::get('/category-image/{id}', function ($id) {
        $category = \App\Models\ProductCategory::findOrFail($id);

        if (!$category->hasImageData()) {
            abort(404, 'Image not found in database');
        }

        // Get binary data directly from attributes to avoid any UTF-8 issues
        $imageData = $category->attributes['image_data'];

        // Return raw binary image data
        return response($imageData, 200)
            ->header('Content-Type', $category->image_mime_type ?? 'image/png')
            ->header('Content-Disposition', 'inline; filename="' . ($category->image_filename ?? 'image.png') . '"')
            ->header('Cache-Control', 'public, max-age=31536000')
            ->header('Content-Length', strlen($imageData));
    })->name('category.image');

    // Root redirect based on authentication status
    Route::get('/admin', function () {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('admin.login');
    })->name('admin.redirect');

    Route::get('/supplier', function () {
        if (Auth::guard('supplier')->check()) {
            return redirect()->route('supplier.dashboard');
        }
        return redirect()->route('supplier.login');
    })->name('supplier.redirect');

    // Quick access routes for different user types
    Route::get('/dashboard', function () {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        } elseif (Auth::guard('supplier')->check()) {
            return redirect()->route('supplier.dashboard');
        } elseif (Auth::guard('web')->check()) {
            return redirect()->route('user.dashboard');
        }
        return redirect()->route('login');
    })->name('dashboard.redirect');
    Route::get('/products', [UserController::class, 'productsPage'])->name('products');
    Route::get('/product/{id}', [UserController::class, 'productDetail'])->name('product.detail');
    Route::post('/product/inquiry', [App\Http\Controllers\ProductController::class, 'submitInquiry'])->name('product.inquiry');

    // Blog Routes
    Route::get('/blog', [App\Http\Controllers\BlogController::class, 'index'])->name('blog.index');
    Route::get('/blog/{slug}', [App\Http\Controllers\BlogController::class, 'show'])->name('blog.show');
    Route::get('/blog/category/{slug}', [App\Http\Controllers\BlogController::class, 'category'])->name('blog.category');
    Route::get('/blog/search', [App\Http\Controllers\BlogController::class, 'search'])->name('blog.search');



    // Test route for debugging inquiry system
    Route::get('/test-inquiry', function () {
        try {
            // Test database connection
            \Illuminate\Support\Facades\DB::connection()->getPdo();

            // Test CustomerInquiry model
            $testInquiry = new \App\Models\CustomerInquiry();

            // Test if table exists
            $tableExists = \Illuminate\Support\Facades\Schema::hasTable('customer_inquiries');

            return response()->json([
                'success' => true,
                'database_connected' => true,
                'model_instantiated' => true,
                'table_exists' => $tableExists,
                'message' => 'All tests passed'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    });

    // Email testing routes (remove in production)
    Route::get('/test-email', function () {
        try {
            \Illuminate\Support\Facades\Mail::raw('Test email from GNRAW - ' . now(), function ($message) {
                $message->to('info@gnraw.com')
                    ->subject('Test Email from GNRAW');
            });

            return response()->json([
                'success' => true,
                'message' => 'Test email sent successfully to info@gnraw.com! Check your inbox.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'message' => 'Email test failed. Check your mail configuration.'
            ], 500);
        }
    });

    // Advanced email testing routes
    // Route::get('/test-email-basic', [App\Http\Controllers\EmailTestController::class, 'testBasicEmail']);
    // Route::get('/test-email-inquiry', [App\Http\Controllers\EmailTestController::class, 'testInquiryEmail']);
    // Route::get('/test-email-config', [App\Http\Controllers\EmailTestController::class, 'getEmailConfig']);
    // Route::get('/test-email-all', [App\Http\Controllers\EmailTestController::class, 'testAllEmails']);

    // Test contact inquiry email
    Route::get('/test-contact-inquiry', function () {
        try {
            $testInquiry = new \App\Models\ClientInquiry([
                'id' => 999,
                'name' => 'Test Customer',
                'email' => 'test@example.com',
                'phone' => '+1234567890',
                'company' => 'Test Company',
                'subject' => 'Test Inquiry Subject',
                'message' => 'This is a test inquiry message to verify email functionality.',
                'inquiry_date' => now()
            ]);

            \Illuminate\Support\Facades\Mail::send('email-templates.contact-inquiry', [
                'inquiry' => $testInquiry,
                'product' => null
            ], function ($message) use ($testInquiry) {
                $message->to('info@gnraw.com')
                    ->subject('Test Contact Inquiry: ' . $testInquiry->subject);
            });

            return response()->json([
                'success' => true,
                'message' => 'Contact inquiry test email sent to info@gnraw.com!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'message' => 'Contact inquiry email test failed.'
            ], 500);
        }
    });

    // Test contact inquiry form submission
    Route::post('/test-contact-form', function (\Illuminate\Http\Request $request) {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'nullable|string|max:20',
                'company' => 'nullable|string|max:255',
                'subject' => 'required|string|max:255',
                'message' => 'required|string|max:2000'
            ]);

            $inquiry = \App\Models\ClientInquiry::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'company' => $request->company,
                'subject' => $request->subject,
                'message' => $request->message,
                'product_id' => null,
                'status' => 'pending',
                'inquiry_date' => now()
            ]);

            // Send email notification to admin
            \Illuminate\Support\Facades\Mail::send('email-templates.contact-inquiry', [
                'inquiry' => $inquiry,
                'product' => null
            ], function ($message) use ($inquiry) {
                $message->to('info@gnraw.com')
                    ->subject('New Contact Inquiry: ' . $inquiry->subject);
            });

            return response()->json([
                'success' => true,
                'message' => 'Contact inquiry submitted and email sent successfully!',
                'inquiry_id' => $inquiry->id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    });

    // Test password reset email
    Route::get('/test-password-reset-email', function () {
        try {
            // Create a test user
            $testUser = new \App\Models\User([
                'id' => 999,
                'name' => 'Test User',
                'email' => 'test@example.com'
            ]);

            // Create test reset data
            $date = array(
                'actionlink' => 'https://gnraw.com/password/reset/test-token',
                'user' => $testUser,
                'expiry_minutes' => 60
            );

            // Send test email using CMail
            $mail_body = view('email-templates.forgot-template', $date)->render();
            $mail_config = array(
                'recipient_address' => 'info@gnraw.com',
                'recipient_name' => 'Test User',
                'subject' => 'Test Password Reset Email',
                'body' => $mail_body
            );

            $result = \App\Helpers\CMail::send($mail_config);

            return response()->json([
                'success' => $result,
                'message' => $result ? 'Password reset test email sent successfully!' : 'Failed to send password reset test email',
                'mail_config' => [
                    'host' => config('mail.mailers.smtp.host'),
                    'port' => config('mail.mailers.smtp.port'),
                    'username' => config('mail.mailers.smtp.username'),
                    'encryption' => config('mail.mailers.smtp.encryption'),
                    'from_address' => config('mail.from.address'),
                    'from_name' => config('mail.from.name')
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    });

    // Test CMail configuration (without sending email)
    Route::get('/test-cmail-config', function () {
        try {
            $result = \App\Helpers\CMail::testConfiguration();
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    });

    // Test CMail configuration status
    Route::get('/test-cmail-status', function () {
        try {
            $status = \App\Helpers\CMail::getConfigStatus();
            return response()->json([
                'success' => true,
                'config' => $status
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    });

    // Test CMail sendTemplate method
    Route::get('/test-cmail-template', function () {
        try {
            $result = \App\Helpers\CMail::sendTemplate(
                'info@gnraw.com',
                'Test Template Email',
                'email-templates.forgot-template',
                [
                    'name' => 'Test User',
                    'actionlink' => 'https://gnraw.com/test',
                    'expiry_minutes' => 60
                ]
            );

            return response()->json([
                'success' => $result,
                'message' => $result ? 'Template email sent successfully!' : 'Failed to send template email'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    });

    // Test CMail sendText method
    Route::get('/test-cmail-text', function () {
        try {
            $result = \App\Helpers\CMail::sendText(
                'info@gnraw.com',
                'Test Text Email',
                'This is a simple text email test from GNRAW.'
            );

            return response()->json([
                'success' => $result,
                'message' => $result ? 'Text email sent successfully!' : 'Failed to send text email'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    });

    // Test CMail bulk send
    Route::get('/test-cmail-bulk', function () {
        try {
            $recipients = [
                ['name' => 'Test User 1', 'email' => 'info@gnraw.com'],
                ['name' => 'Test User 2', 'email' => 'info@gnraw.com']
            ];

            $result = \App\Helpers\CMail::sendBulk(
                $recipients,
                'Bulk Email Test',
                'email-templates.forgot-template',
                ['actionlink' => 'https://gnraw.com/test', 'expiry_minutes' => 60]
            );

            return response()->json([
                'success' => true,
                'message' => 'Bulk email test completed',
                'results' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    });

    Route::get('/categories', [UserController::class, 'categoriesPage'])->name('categories');
    Route::get('/category/{slug}', [UserController::class, 'categoryProducts'])->name('category.products');
    Route::get('/contact', [UserController::class, 'contactPage'])->name('contact');
    Route::post('/contact', [UserController::class, 'submitInquiry'])->name('contact.submit');
    Route::get('/about', [UserController::class, 'aboutPage'])->name('about');



    // User Authentication Routes
    Route::middleware(['guest', 'preventBackHistory'])->group(function () {
        Route::controller(AuthController::class)->group(function () {
            Route::get('/login', 'userLoginForm')->name('login');
            Route::post('/login', 'userLoginHandler')->name('user.login_handler');
            Route::get('/register', 'userSignupForm')->name('register');
            Route::post('/register', 'userSignupHandler')->name('register_handler');
            Route::get('/forgot-password', 'userForgotForm')->name('user.forgot');
            Route::post('/send-password-reset-link', 'sendPasswordResetLink')->name('user.send_password_reset_link');
            Route::get('/password/reset/{token}', 'resetForm')->name('user.reset_password_form');
            Route::post('/reset-password-handler', 'resetPasswordHandler')->name('user.reset_password_handler');
        });
    });

    // User Dashboard Routes (Authenticated Users)
    Route::middleware(['auth', 'preventBackHistory'])->group(function () {
        Route::controller(UserController::class)->group(function () {
            Route::get('/dashboard', 'userDashboard')->name('user.dashboard');
            Route::post('/logout', 'logoutHandler')->name('user.logout');
            Route::get('/profile', 'ProfileView')->name('user.profile');
            Route::post('/update-profile', 'updateProfile')->name('user.update_profile');
            Route::post('/upload-profile-picture', 'uploadProfilePicture')->name('user.upload_profile_picture');

            // Shopping & Orders
            Route::get('/orders', 'ordersPage')->name('user.orders');
            Route::get('/orders/{id}', 'orderDetails')->name('user.orders.details');
            Route::get('/wishlist', 'wishlistPage')->name('user.wishlist');
            Route::get('/cart', 'cartPage')->name('user.cart');
            Route::get('/checkout', 'checkoutPage')->name('user.checkout');

            // Address Management
            Route::get('/addresses', 'addressesPage')->name('user.addresses');
            Route::post('/addresses', 'addAddress')->name('user.addresses.add');
            Route::put('/addresses/{id}', 'updateAddress')->name('user.addresses.update');
            Route::delete('/addresses/{id}', 'deleteAddress')->name('user.addresses.delete');

            // Account Settings
            Route::get('/settings', 'accountSettings')->name('user.settings');
            Route::post('/change-password', 'changePassword')->name('user.change_password');
        });
    });

    /**
     *
     * Test Routes
     */


    // Document viewer route with favicon
    Route::get('/document/{path}', function ($path) {
        $filePath = storage_path('app/public/' . $path);

        if (!file_exists($filePath)) {
            abort(404);
        }

        $mimeType = mime_content_type($filePath);
        $fileName = basename($filePath);

        // For images, show them in a proper HTML page with favicon
        if (str_starts_with($mimeType, 'image/')) {
            return view('document-viewer', [
                'filePath' => asset('storage/' . $path),
                'fileName' => $fileName,
                'mimeType' => $mimeType
            ]);
        }

        // For other files, serve directly
        return response()->file($filePath);
    })->where('path', '.*')->name('document.viewer');

    /**
     *
     * Admin Routes
     */

    Route::prefix('admin')->name('admin.')->group(function () {

        Route::middleware(['guest', 'preventBackHistory'])->group(function () {
            Route::controller(AuthController::class)->group(function () {

                Route::get('/login', 'loginForm')->name('login');
                Route::post('/login', 'loginHandler')->name('login_handler');
                Route::get('/forgot-password', 'forgotForm')->name('forgot');
                Route::post('/send-password-reset-link', 'sendPasswordResetLink')->name('send_password_reset_link');
                Route::get('/password/reset/{token}', 'resetForm')->name('reset_password_form');
                Route::post('/reset-password-handler', 'resetPasswordHandler')->name('reset_password_handler');
            });
        });

        Route::middleware(['auth:admin', 'adminAccess', 'preventBackHistory'])->group(function () {
            Route::controller(AdminController::class)->group(function () {

                Route::get('/dashboard', 'adminDashboard')->name('dashboard');
                Route::post('/logout', 'logoutHandler')->name('logout');
                Route::get('/profile', 'ProfileView')->name('profile');
                Route::post('/upload-profile-picture', 'uploadProfilePicture')->name('admin.upload_profile_picture');
                Route::post('/test-email', 'testEmail')->name('test_email');
                Route::get('/settings', 'generalSettings')->name('settings');
                Route::post('/update-logo', 'updateLogo')->name('update_logo');
                Route::post('/update-favicon', 'updateFavicon')->name('update_favicon');
                // Category Management Routes
                Route::get('/categories', 'categoriesPage')->name('categories');
                Route::get('/categories/create', 'createCategory')->name('categories.create');
                Route::post('/categories/store', 'storeCategory')->name('categories.store');
                Route::get('/categories/{id}/edit', 'editCategory')->name('categories.edit');
                Route::put('/categories/{id}', 'updateCategory')->name('categories.update');
                Route::delete('/categories/{id}', 'deleteCategory')->name('categories.delete');

                // Supplier Management Routes
                Route::get('/suppliers', 'suppliersIndex')->name('suppliers.index');
                Route::get('/suppliers/{id}', 'showSupplier')->name('suppliers.show');
                Route::get('/suppliers/{id}/products', 'getSupplierProducts')->name('suppliers.products');

                Route::post('/suppliers/{id}/approve', 'approveSupplier')->name('suppliers.approve');
                Route::post('/suppliers/{id}/reject', 'rejectSupplier')->name('suppliers.reject');

                // Client Inquiries Routes
                Route::get('/inquiries', 'inquiriesIndex')->name('inquiries.index');
                Route::get('/inquiries/{id}', 'showInquiry')->name('inquiries.show');
                Route::post('/inquiries/{id}/status', 'updateInquiryStatus')->name('inquiries.status');

                // Products Management Routes
                Route::get('/products', 'productsPage')->name('products');
                Route::get('/products/drafts', 'draftProductsPage')->name('products.drafts');
                Route::get('/products/{id}', 'showProduct')->name('products.show');
                Route::post('/products/{id}/approve', 'approveProduct')->name('products.approve');
                Route::post('/products/{id}/reject', 'rejectProduct')->name('products.reject');
                Route::post('/products/{id}/toggle-status', 'toggleProductStatus')->name('products.toggle_status');
                Route::delete('/products/{id}/permanent-delete', 'permanentlyDeleteProduct')->name('products.permanent_delete');

                // Product Comments Routes
                Route::post('/products/{id}/comments', 'addProductComment')->name('products.comments.add');
                Route::delete('/comments/{id}', 'deleteProductComment')->name('comments.delete');
                Route::post('/comments/{id}/mark-read', 'markCommentAsRead')->name('comments.mark_read');

                // Admin Notification Routes
                Route::get('/notifications/unread-count', 'getUnreadNotificationCount')->name('notifications.unread_count');
                Route::get('/notifications/recent', 'getRecentNotifications')->name('notifications.recent');
                Route::post('/notifications/{id}/mark-read', 'markNotificationAsRead')->name('notifications.mark_read');
                Route::post('/notifications/mark-all-read', 'markAllNotificationsAsRead')->name('notifications.mark_all_read');

                // Customer Management Routes
                Route::get('/customers', 'customersPage')->name('customers');
                Route::get('/customers/{id}/details', 'getCustomerDetails')->name('customers.details');
                Route::get('/shipping', 'shippingPage')->name('shipping');
                Route::get('/certificates', 'certificatesPage')->name('certificates');
                Route::get('/supplier-certificates', 'supplierCertificatesPage')->name('supplier-certificates');
                Route::get('/suppliers/{id}/details', 'getSupplierDetails')->name('suppliers.details');
                Route::get('/reports', 'reportsPage')->name('reports');
                Route::get('/notifications', 'notificationsPage')->name('notifications');

                // Blog Management Routes
                Route::resource('blog-categories', \App\Http\Controllers\Admin\BlogCategoryController::class);
                Route::post('blog-categories/{id}/toggle-status', [\App\Http\Controllers\Admin\BlogCategoryController::class, 'toggleStatus'])->name('blog-categories.toggle-status');
                Route::post('blog-categories/reorder', [\App\Http\Controllers\Admin\BlogCategoryController::class, 'reorder'])->name('blog-categories.reorder');

                Route::resource('blog-posts', \App\Http\Controllers\Admin\BlogPostController::class);
                Route::post('blog-posts/{id}/toggle-featured', [\App\Http\Controllers\Admin\BlogPostController::class, 'toggleFeatured'])->name('blog-posts.toggle-featured');
                Route::post('blog-posts/{id}/update-status', [\App\Http\Controllers\Admin\BlogPostController::class, 'updateStatus'])->name('blog-posts.update-status');
                Route::post('blog-posts/upload-image', [\App\Http\Controllers\Admin\BlogPostController::class, 'uploadImage'])->name('blog-posts.upload-image');
            });
        });
    });

    Route::prefix('supplier')->name('supplier.')->group(function () {

        Route::middleware(['guest', 'preventBackHistory'])->group(function () {
            Route::controller(AuthController::class)->group(function () {

                Route::get('/login', 'loginForm')->name('login');
                Route::post('/login', 'loginHandler')->name('login_handler');
                Route::get('/signup', 'signupForm')->name('signup');
                Route::post('/signup', 'signupHandler')->name('signup_handler');
                Route::get('/forgot-password', 'forgotForm')->name('forgot');
                Route::post('/send-password-reset-link', 'sendPasswordResetLink')->name('send_password_reset_link');
                Route::get('/password/reset/{token}', 'resetForm')->name('reset_password_form');
                Route::post('/reset-password-handler', 'resetPasswordHandler')->name('reset_password_handler');
            });
        });

        Route::middleware(['auth:supplier', 'supplierAccess', 'setUserLanguage', 'preventBackHistory'])->group(function () {
            Route::controller(SupplierController::class)->group(function () {

                Route::get('/dashboard', 'supplierDashboard')->name('dashboard');
                Route::post('/logout', 'logoutHandler')->name('logout');
                Route::get('/profile', 'ProfileView')->name('profile');
                Route::post('/update-profile', 'updateProfile')->name('update_profile');
                Route::post('/upload-profile-picture', 'uploadProfilePicture')->name('upload_profile_picture');
                Route::get('/settings', 'generalSettings')->name('settings');
                Route::post('/update-logo', 'updateLogo')->name('update_logo');
                Route::post('/update-favicon', 'updateFavicon')->name('update_favicon');

                // Product Management Routes
                Route::get('/products', 'productsPage')->name('products');
                Route::get('/products/create', 'createProduct')->name('products.create');
                Route::post('/products/store', 'storeProduct')->name('products.store');
                Route::get('/products/{id}/edit', 'editProduct')->name('products.edit');
                Route::put('/products/{id}', 'updateProduct')->name('products.update');
                Route::delete('/products/{id}', 'deleteProduct')->name('products.delete');
                Route::get('/products/{id}/details', 'getProductDetails')->name('products.details');

                // Certificate Management Routes
                Route::get('/certificates', 'certificatesPage')->name('certificates');
                Route::post('/certificates/store', 'storeCertificate')->name('certificates.store');
                Route::delete('/certificates/{id}', 'deleteCertificate')->name('certificates.delete');

                // Orders Management Routes
                Route::get('/orders', 'ordersPage')->name('orders');
                Route::get('/orders/{id}', 'viewOrder')->name('orders.view');
                Route::post('/orders/{id}/status', 'updateOrderStatus')->name('orders.updateStatus');

                // Inventory Management Routes
                Route::get('/inventory', 'inventoryPage')->name('inventory');
                Route::post('/inventory/update', 'updateInventory')->name('inventory.update');
                Route::get('/inventory/movements', 'inventoryMovements')->name('inventory.movements');

                // Sales/Reports Routes
                Route::get('/sales', 'salesPage')->name('sales');
                Route::get('/reports', 'reportsPage')->name('reports');

                // Customer Management Routes
                Route::get('/customers', 'customersPage')->name('customers');

                // Shipping Management Routes
                Route::get('/shipping', 'shippingPage')->name('shipping');

                // Notifications Routes
                Route::get('/notifications', 'notificationsPage')->name('notifications');
                Route::get('/notifications/unread-count', 'getUnreadNotificationCount')->name('notifications.unread_count');
                Route::post('/notifications/{id}/mark-read', 'markNotificationAsRead')->name('notifications.mark_read');
                Route::post('/notifications/mark-all-read', 'markAllNotificationsAsRead')->name('notifications.mark_all_read');

                // Settings Update Route
                Route::post('/settings/update', 'updateSettings')->name('settings.update');
                Route::post('/settings/change-password', 'changePassword')->name('settings.change_password');
            });
        });
    });
