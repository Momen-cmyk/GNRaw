<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Supplier;
use App\Models\SupplierDocument;
use App\UserStatus;
use App\UserType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Helpers\CMail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function loginForm(Request $request)
    {
        $data = [
            'pageTitle' => 'Login'
        ];
        return view('back.pages.auth.login', $data);
    }

    public function signupForm(Request $request)
    {
        $data = [
            'pageTitle' => 'Sign Up'
        ];
        return view('back.pages.auth.signup', $data);
    }

    // User Portal Authentication Methods
    public function userLoginForm(Request $request)
    {
        $data = [
            'pageTitle' => 'Login'
        ];
        return view('front.pages.auth.login', $data);
    }

    public function userSignupForm(Request $request)
    {
        $data = [
            'pageTitle' => 'Register'
        ];
        return view('front.pages.auth.register', $data);
    }

    public function userForgotForm()
    {
        $data = [
            'pageTitle' => 'Forgot Password'
        ];
        return view('front.pages.auth.forgot', $data);
    }

    public function userLoginHandler(Request $request)
    {
        $fieldType = filter_var($request->login_id,  FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if ($fieldType == 'email') {
            $request->validate([
                'login_id' => 'required|email|exists:users,email',
                'password' => 'required|string|min:5'
            ], [
                'login_id.required' => 'Enter your email or username',
                'login_id.email' => 'Invalid email Address',
                'login_id.exists' => 'No account found for this email address',
            ]);
        } else {
            $request->validate([
                'login_id' => 'required|exists:users,username',
                'password' => 'required|string|min:5'
            ], [
                'login_id.required' => 'Enter your email or username',
                'login_id.exists' => 'No account found for this username',
            ]);
        }

        $creds = array(
            $fieldType => $request->login_id,
            'password' => $request->password,
        );

        // Use default web guard for user portal (users table)
        if (Auth::attempt($creds)) {
            /** @var \App\Models\User $user */
            $user = Auth::user();

            // Check if account is active
            if ($user && $user->status !== \App\UserStatus::Active) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('login')->with('fail', 'Your account is inactive. Please contact admin.');
            }

            // Update last login time
            $user->update(['last_login_at' => now()]);

            // Redirect to user dashboard
            return redirect()->route('user.dashboard');
        } else {
            return redirect()->route('login')->withInput()->with('fail', 'Incorrect Password.');
        }
    }

    public function userSignupHandler(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:5|confirmed',
        ], [
            'name.required' => 'Name is required',
            'username.required' => 'Username is required',
            'username.unique' => 'Username already exists',
            'email.required' => 'Email is required',
            'email.unique' => 'Email already exists',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 5 characters',
            'password.confirmed' => 'Password confirmation does not match',
        ]);

        // Create new user
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => UserStatus::Active,
            'email_verified_at' => now(),
        ]);

        if ($user) {
            return redirect()->route('login')->with('success', 'Account created successfully! You can now login.');
        } else {
            return redirect()->back()->withInput()->with('fail', 'Something went wrong. Please try again.');
        }
    }

    public function forgotForm()
    {
        $data = [
            'pageTitle' => 'Forget Password'
        ];
        return view('back.pages.auth.forgot', $data);
    }


    public function loginHandler(Request $request)
    {
        $fieldType = filter_var($request->login_id,  FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // Determine which table to validate against based on the request path
        $table = $request->is('supplier/*') ? 'suppliers' : 'admins';

        if ($fieldType == 'email') {
            $request->validate([
                'login_id' => 'required|email|exists:' . $table . ',email',
                'password' => 'required|string|min:5'
            ], [
                'login_id.required' => 'Enter your email or username',
                'login_id.email' => 'Invalid email Address',
                'login_id.exists' => 'No account found for this email address',
            ]);
        } else {
            $request->validate([
                'login_id' => 'required|exists:' . $table . ',username',
                'password' => 'required|string|min:5'
            ], [
                'login_id.required' => 'Enter your email or username',
                'login_id.exists' => 'No account found for this username',
            ]);
        }

        $creds = array(
            $fieldType => $request->login_id,
            'password' => $request->password,
        );

        // Determine which guard to use based on the request path
        $guard = $request->is('supplier/*') ? 'supplier' : 'admin';
        $loginRoute = $request->is('supplier/*') ? 'supplier.login' : 'admin.login';

        if (Auth::guard($guard)->attempt($creds)) {
            $user = Auth::guard($guard)->user();

            // Check account status based on the guard/model type
            if ($guard === 'supplier') {
                // For suppliers, check is_active field
                if ($user && !$user->is_active) {
                    Auth::guard($guard)->logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                    return redirect()->route($loginRoute)->with('fail', 'Your account is inactive. Please contact admin.');
                }
            } else {
                // For admins, check status field
                if ($user && $user->status == UserStatus::Inactive) {
                    Auth::guard($guard)->logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                    return redirect()->route($loginRoute)->with('fail', 'Your account is inactive. Please contact admin.');
                }
                if ($user && $user->status == UserStatus::Pending) {
                    Auth::guard($guard)->logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                    return redirect()->route($loginRoute)->with('fail', 'Your account is pending. Please contact admin.');
                }
            }
            //redirect user to appropriate dashboard based on guard
            if ($guard === 'supplier') {
                return redirect()->route('supplier.dashboard');
            } elseif ($guard === 'admin') {
                return redirect()->route('admin.dashboard');
            } else {
                // Fallback - should not happen
                Auth::guard($guard)->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route($loginRoute)->with('fail', 'Access denied. Please use the appropriate portal.');
            }
        } else {
            return redirect()->route($loginRoute)->withInput()->with('fail', 'Incorrect Password.');
        }
    } //End Method

    public function signupHandler(Request $request)
    {
        // Determine which table to validate against based on the request path
        $table = $request->is('supplier/*') ? 'suppliers' : 'users';

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:' . $table . ',username',
            'email' => 'required|email|unique:' . $table . ',email',
            'password' => 'required|string|min:5|confirmed',
            'phone' => 'nullable|string|max:20',
            'company_name' => 'required|string|max:255',
            'company_activity' => 'required|in:manufacturing,trading,manufacturing_trading',
            'company_description' => 'required|string|max:1000',
            'number_of_employees' => 'required|in:1-10,11-50,51-200,201-500,501-1000,1001-5000,5001-10000,10001+',
            'iso_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'gmp_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'wc_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'manufacturing_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'terms' => 'required|accepted',
            'data_accuracy' => 'required|accepted',
        ], [
            'name.required' => 'Name is required',
            'username.required' => 'Username is required',
            'username.unique' => 'Username already exists',
            'email.required' => 'Email is required',
            'email.unique' => 'Email already exists',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 5 characters',
            'password.confirmed' => 'Password confirmation does not match',
            'phone.max' => 'Phone number must not exceed 20 characters',
            'company_name.required' => 'Company name is required',
            'company_activity.required' => 'Company activity is required',
            'company_activity.in' => 'Company activity must be either Manufacturing or Trading',
            'number_of_employees.required' => 'Number of employees is required',
            'number_of_employees.in' => 'Please select a valid employee range',
            'company_description.required' => 'Company description is required',
            'terms.required' => 'You must agree to the terms and conditions',
            'data_accuracy.required' => 'You must confirm data accuracy',
        ]);

        // Create new supplier user based on the request path
        if ($request->is('supplier/*')) {
            // Create in suppliers table
            $user = Supplier::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'company_name' => $request->company_name,
                'company_activity' => $request->company_activity,
                'company_description' => $request->company_description,
                'number_of_employees' => $request->number_of_employees,
                'is_active' => false, // Pending approval
                'is_verified' => false,
                'approval_status' => 'pending',
                'email_verified_at' => now(),
            ]);

            // Handle optional file uploads (COA is now per product, not per supplier)
            if ($request->hasFile('iso_document')) {
                $isoPath = $request->file('iso_document')->store('supplier_documents', 'public');
                SupplierDocument::create([
                    'supplier_id' => $user->id,
                    'document_type' => 'ISO',
                    'document_name' => $request->file('iso_document')->getClientOriginalName(),
                    'file_path' => $isoPath,
                ]);
            }

            if ($request->hasFile('gmp_document')) {
                $gmpPath = $request->file('gmp_document')->store('supplier_documents', 'public');
                SupplierDocument::create([
                    'supplier_id' => $user->id,
                    'document_type' => 'GMP',
                    'document_name' => $request->file('gmp_document')->getClientOriginalName(),
                    'file_path' => $gmpPath,
                ]);
            }

            if ($request->hasFile('wc_document')) {
                $wcPath = $request->file('wc_document')->store('supplier_documents', 'public');
                SupplierDocument::create([
                    'supplier_id' => $user->id,
                    'document_type' => 'WC',
                    'document_name' => $request->file('wc_document')->getClientOriginalName(),
                    'file_path' => $wcPath,
                ]);
            }

            if ($request->hasFile('manufacturing_certificate')) {
                $manufacturingPath = $request->file('manufacturing_certificate')->store('supplier_documents', 'public');
                SupplierDocument::create([
                    'supplier_id' => $user->id,
                    'document_type' => 'Manufacturing_Certificate',
                    'document_name' => $request->file('manufacturing_certificate')->getClientOriginalName(),
                    'file_path' => $manufacturingPath,
                ]);
            }
        } else {
            // Create in users table (for regular users)
            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'status' => UserStatus::Active,
            ]);
        }

        if ($user) {
            if ($request->is('supplier/*')) {
                return redirect()->route('supplier.login')->with('success', 'Supplier registration submitted successfully! Your account is pending admin approval. You will be notified once approved.');
            } else {
                return redirect()->route('login')->with('success', 'Account created successfully! You can now login.');
            }
        } else {
            return redirect()->back()->withInput()->with('fail', 'Something went wrong. Please try again.');
        }
    } //End Method

    public function sendPasswordResetLink(Request $request)
    {
        // Determine which table to validate against based on the request path
        $table = $request->is('supplier/*') ? 'suppliers' : ($request->is('admin/*') ? 'admins' : 'users');

        $request->validate([
            'email' => 'required|email|exists:' . $table . ',email',
        ], [
            'email.required' => 'The :attribute is required',
            'email.email' => 'Invalid email Address',
            'email.exists' => 'We can\'t find a user with that email address',
        ]);

        // Get user details based on the table
        if ($request->is('supplier/*')) {
            $user = \App\Models\Supplier::where('email', $request->email)->first();
        } elseif ($request->is('admin/*')) {
            $user = \App\Models\Admin::where('email', $request->email)->first();
        } else {
            $user = User::where('email', $request->email)->first();
        }
        //Generate Token
        $token = base64_encode(Str::random(64));
        //check if there is an existing token
        $oldToken = DB::table('password_reset_tokens')->where('email', $user->email)->first();
        if ($oldToken) {
            //update the existing token
            DB::table('password_reset_tokens')->where('email', $user->email)->update([
                'token' => $token,
                'created_at' => Carbon::now(),
            ]);
        } else {
            // Add new reset password token
            DB::table('password_reset_tokens')->insert([
                'email' => $user->email,
                'token' => $token,
                'created_at' => Carbon::now()
            ]);
        }
        //Create clickable action link based on the request path
        if ($request->is('supplier/*')) {
            $actionLink = route('supplier.reset_password_form', ['token' => $token]);
        } elseif ($request->is('admin/*')) {
            $actionLink = route('admin.reset_password_form', ['token' => $token]);
        } else {
            $actionLink = route('user.reset_password_form', ['token' => $token]);
        }

        $date = array(
            'actionlink' => $actionLink,
            'user' => $user,
            'expiry_minutes' => config('auth.passwords.users.expire')
        );

        //Send Email
        $mail_body = view('email-templates.forgot-template', $date)->render();
        $mail_config = array(
            'recipient_address' => $user->email,
            'recipient_name' => $user->name,
            'subject' => ' Reset Password',
            'body' => $mail_body
        );
        try {
            Log::info("Attempting to send password reset email to: " . $user->email);
            $mailResult = CMail::send($mail_config);

            if ($mailResult) {
                Log::info("Password reset email sent successfully to: " . $user->email);
                $forgotRoute = $request->is('supplier/*') ? 'supplier.forgot' : ($request->is('admin/*') ? 'admin.forgot' : 'user.forgot');
                return redirect()->route($forgotRoute)
                    ->with('success', 'We have emailed your password reset link!');
            } else {
                Log::error("CMail::send() returned false for password reset email to: " . $user->email);
                $forgotRoute = $request->is('supplier/*') ? 'supplier.forgot' : ($request->is('admin/*') ? 'admin.forgot' : 'user.forgot');
                return redirect()->route($forgotRoute)
                    ->with('fail', 'Failed to send reset link. Please check your email configuration or contact support.');
            }
        } catch (\Exception $e) {
            Log::error("Password reset error: " . $e->getMessage(), [
                'user_email' => $user->email,
                'trace' => $e->getTraceAsString()
            ]);
            $forgotRoute = $request->is('supplier/*') ? 'supplier.forgot' : ($request->is('admin/*') ? 'admin.forgot' : 'user.forgot');
            return redirect()->route($forgotRoute)
                ->with('fail', 'An error occurred while sending the reset link. Please try again later.');
        }
    } //End Method

    public function resetForm(Request $request, $token = null)
    {
        //Check if this token is exists
        $isTokenExists = DB::table('password_reset_tokens')
            ->where('token', $token)
            ->first();

        // Determine the correct forgot route based on the request path
        $forgotRoute = $request->is('supplier/*') ? 'supplier.forgot' : ($request->is('admin/*') ? 'admin.forgot' : 'user.forgot');

        if (!$isTokenExists) {
            return redirect()->route($forgotRoute)->with('fail', 'Invalid token. Please request a new Link.');
        } else {
            //check if this token is expired
            $diffMin = Carbon::createFromFormat('Y-m-d H:i:s', $isTokenExists->created_at)
                ->diffInMinutes(Carbon::now());
            if ($diffMin > 15) {
                //When token is older than 15 minutes expired
                return redirect()->route($forgotRoute)->with('fail', 'The password reset link you clicked has expired. Please request a new link.');
            }
            $data = [
                'pageTitle' => 'Reset Password',
                'token' => $token,

            ];
            return view('back.pages.auth.reset', $data);
        }
    } //End Method
    public function resetPasswordHandler(Request $request)
    {
        //validate the form
        $request->validate([
            'new_password' => 'required|min:5|required_with:new_password_confirmation|same:new_password_confirmation',
            'new_password_confirmation' => 'required'
        ]);

        $dbToken = DB::table('password_reset_tokens')
            ->where('token', $request->token)
            ->first();

        //Get User details based on the request path
        if ($request->is('supplier/*')) {
            $user = \App\Models\Supplier::where('email', $dbToken->email)->first();
            //Update Password
            \App\Models\Supplier::where('email', $user->email)->update([
                'password' => Hash::make($request->new_password)
            ]);
        } elseif ($request->is('admin/*')) {
            $user = \App\Models\Admin::where('email', $dbToken->email)->first();
            //Update Password
            \App\Models\Admin::where('email', $user->email)->update([
                'password' => Hash::make($request->new_password)
            ]);
        } else {
            $user = User::where('email', $dbToken->email)->first();
            //Update Password
            User::where('email', $user->email)->update([
                'password' => Hash::make($request->new_password)
            ]);
        }
        //Send Notification Email to this user email address that contains the new password
        $date = array(
            'user' => $user,
            'new_password' => $request->new_password,
        );
        $mail_body = view('email-templates.password-change-template', $date)->render();
        $mailConfig = array(
            'recipient_address' => $user->email,
            'recipient_name' => $user->name,
            'subject' => 'Password Changed',
            'body' => $mail_body
        );
        // Determine the correct routes based on the request path
        $loginRoute = $request->is('supplier/*') ? 'supplier.login' : ($request->is('admin/*') ? 'admin.login' : 'login');
        $resetRoute = $request->is('supplier/*') ? 'supplier.reset_password_form' : ($request->is('admin/*') ? 'admin.reset_password_form' : 'reset_password_form');

        if (CMail::send($mailConfig)) {
            //Delete the token from password_reset_tokens table
            DB::table('password_reset_tokens')->where([
                'email' => $dbToken->email,
                'token' => $dbToken->token
            ])->delete();

            return redirect()->route($loginRoute)->with('success', 'Your password has been changed successfully. You can now login with your new password.');
        } else {
            return redirect()->route($resetRoute, ['token' => $dbToken->token])->with('fail', 'Something went wrong. Please try again later.');
        }
    }
}
