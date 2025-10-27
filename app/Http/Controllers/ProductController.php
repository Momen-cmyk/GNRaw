<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\SupplierProduct;
use App\Models\CustomerInquiry;
use App\Models\User;
use App\Notifications\InquiryReceivedNotification;

class ProductController extends Controller
{
    public function show($id)
    {
        $product = SupplierProduct::with('supplier')->find($id);

        return view('front.pages.product-detail', compact('product'));
    }

    public function submitInquiry(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:supplier_products,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'product_name' => 'required|string|max:255',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'message' => 'required|string|max:2000'
        ]);

        Log::info('Inquiry submission started', [
            'product_id' => $request->product_id,
            'supplier_id' => $request->supplier_id,
            'customer_email' => $request->customer_email
        ]);

        try {
            // Test database connection first
            DB::connection()->getPdo();
            Log::info('Database connection successful');

            // Find or create customer
            $customer = User::firstOrCreate(
                ['email' => $request->customer_email],
                [
                    'name' => $request->customer_name,
                    'phone' => $request->customer_phone,
                    'password' => bcrypt('temporary_password_' . time()), // Temporary password
                    'email_verified_at' => now()
                ]
            );

            Log::info('Customer found/created', ['customer_id' => $customer->id]);

            // Validate supplier exists
            $supplier = \App\Models\Supplier::find($request->supplier_id);
            if (!$supplier) {
                Log::error('Supplier not found', ['supplier_id' => $request->supplier_id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid supplier. Please try again.'
                ], 400);
            }
            Log::info('Supplier found', ['supplier_id' => $supplier->id, 'company_name' => $supplier->company_name]);

            // Validate product exists
            $product = \App\Models\SupplierProduct::find($request->product_id);
            if (!$product) {
                Log::error('Product not found', ['product_id' => $request->product_id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid product. Please try again.'
                ], 400);
            }
            Log::info('Product found', ['product_id' => $product->id, 'product_name' => $product->product_name]);

            // Test if CustomerInquiry model exists and table is accessible
            try {
                $testInquiry = new CustomerInquiry();
                Log::info('CustomerInquiry model instantiated successfully');
            } catch (\Exception $modelError) {
                Log::error('CustomerInquiry model error: ' . $modelError->getMessage());
                throw $modelError;
            }

            // Create inquiry
            $inquiry = CustomerInquiry::create([
                'customer_id' => $customer->id,
                'supplier_id' => $request->supplier_id,
                'product_id' => $request->product_id,
                'product_name' => $request->product_name,
                'message' => $request->message,
                'status' => 'pending'
            ]);

            Log::info('Inquiry created successfully', [
                'inquiry_id' => $inquiry->id,
                'customer_id' => $customer->id,
                'supplier_id' => $request->supplier_id,
                'product_id' => $request->product_id
            ]);

            // Send notification to supplier (optional - inquiry is saved regardless)
            if ($supplier) {
                try {
                    $supplier->notify(new InquiryReceivedNotification($inquiry));
                    Log::info('Notification sent to supplier successfully', ['supplier_id' => $supplier->id]);
                } catch (\Exception $notificationError) {
                    Log::warning('Failed to send notification to supplier: ' . $notificationError->getMessage(), [
                        'supplier_id' => $supplier->id,
                        'inquiry_id' => $inquiry->id,
                        'error' => $notificationError->getMessage()
                    ]);
                    // Continue execution even if notification fails - inquiry is still saved
                }
            } else {
                Log::warning('Supplier not found for inquiry', ['supplier_id' => $request->supplier_id]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Your inquiry has been sent successfully! The supplier will contact you soon.'
            ]);
        } catch (\Exception $e) {
            Log::error('Inquiry submission error: ' . $e->getMessage(), [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while sending your inquiry. Please try again.'
            ], 500);
        }
    }
}
