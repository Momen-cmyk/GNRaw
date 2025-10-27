@extends('back.layout.auth-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Create an Account')

@section('content')
    <div class="d-flex align-items-center justify-content-center min-vh-100 bg-light">
        <div class="card shadow border-0" style="max-width: 700px; width: 100%;">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <img src="/images/site/{{ isset(settings()->site_logo) ? settings()->site_logo : 'logo.svg' }}"
                        alt="GNRAW Logo" class="mb-3" style="height:50px;">
                    <h3 class="fw-bold">Supplier Registration</h3>
                    <p class="text-muted">Join our network of verified suppliers</p>
                </div>

                <!-- Progress Steps -->
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="step-item text-center" data-step="1">
                            <div class="step-number bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center"
                                style="width: 40px; height: 40px;">1</div>
                            <div class="step-title mt-2">Personal Info</div>
                        </div>
                        <div class="step-line flex-grow-1 mx-3" style="height: 2px; background: #dee2e6;"></div>
                        <div class="step-item text-center" data-step="2">
                            <div class="step-number bg-secondary text-white rounded-circle d-inline-flex align-items-center justify-content-center"
                                style="width: 40px; height: 40px;">2</div>
                            <div class="step-title mt-2">Company Info</div>
                        </div>
                        <div class="step-line flex-grow-1 mx-3" style="height: 2px; background: #dee2e6;"></div>
                        <div class="step-item text-center" data-step="3">
                            <div class="step-number bg-secondary text-white rounded-circle d-inline-flex align-items-center justify-content-center"
                                style="width: 40px; height: 40px;">3</div>
                            <div class="step-title mt-2">Documents</div>
                        </div>
                    </div>
                </div>

                <form action="{{ route('supplier.signup_handler') }}" method="POST" enctype="multipart/form-data"
                    id="signupForm">
                    @csrf
                    <x-form-alerts></x-form-alerts>

                    <!-- Step 1: Personal Information -->
                    <div class="step-content" id="step1">
                        <h5 class="text-primary mb-4">Step 1: Personal Information</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Full Name *</label>
                                <input type="text" name="name" class="form-control" placeholder="John Doe" required
                                    value="{{ old('name') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email Address *</label>
                                <input type="email" name="email" class="form-control" placeholder="john@example.com"
                                    required value="{{ old('email') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Username *</label>
                                <input type="text" name="username" class="form-control" placeholder="johndoe" required
                                    value="{{ old('username') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone Number</label>
                                <input type="tel" name="phone" class="form-control"
                                    placeholder="+1234567890 (optional)" value="{{ old('phone') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Password *</label>
                                <input type="password" name="password" class="form-control" placeholder="********" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Confirm Password *</label>
                                <input type="password" name="password_confirmation" class="form-control"
                                    placeholder="********" required>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-primary" onclick="nextStep()">Next Step</button>
                        </div>
                    </div>

                    <!-- Step 2: Company Information -->
                    <div class="step-content d-none" id="step2">
                        <h5 class="text-success mb-4">Step 2: Company Information</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Company Name *</label>
                                <input type="text" name="company_name" class="form-control" placeholder="Company Inc."
                                    required value="{{ old('company_name') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Company Activity *</label>
                                <select name="company_activity" class="form-select" required>
                                    <option value="">Select Activity</option>
                                    <option value="manufacturing"
                                        {{ old('company_activity') == 'manufacturing' ? 'selected' : '' }}>Manufacturing
                                    </option>
                                    <option value="trading" {{ old('company_activity') == 'trading' ? 'selected' : '' }}>
                                        Trading</option>
                                    <option value="manufacturing_trading"
                                        {{ old('company_activity') == 'manufacturing_trading' ? 'selected' : '' }}>
                                        Manufacturing & Trading</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Number of Employees</label>
                                <select name="number_of_employees" class="form-select" required>
                                    <option value="">Select Employee Range</option>
                                    <option value="1-10" {{ old('number_of_employees') == '1-10' ? 'selected' : '' }}>
                                        1–10 employees</option>
                                    <option value="11-50" {{ old('number_of_employees') == '11-50' ? 'selected' : '' }}>
                                        11–50 employees</option>
                                    <option value="51-200" {{ old('number_of_employees') == '51-200' ? 'selected' : '' }}>
                                        51–200 employees</option>
                                    <option value="201-500"
                                        {{ old('number_of_employees') == '201-500' ? 'selected' : '' }}>201–500 employees
                                    </option>
                                    <option value="501-1000"
                                        {{ old('number_of_employees') == '501-1000' ? 'selected' : '' }}>501–1,000
                                        employees</option>
                                    <option value="1001-5000"
                                        {{ old('number_of_employees') == '1001-5000' ? 'selected' : '' }}>1,001–5,000
                                        employees</option>
                                    <option value="5001-10000"
                                        {{ old('number_of_employees') == '5001-10000' ? 'selected' : '' }}>5,001–10,000
                                        employees</option>
                                    <option value="10001+" {{ old('number_of_employees') == '10001+' ? 'selected' : '' }}>
                                        10,001+ employees</option>
                                </select>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Company Description *</label>
                                <textarea name="company_description" class="form-control" rows="3" placeholder="Describe your company..."
                                    required>{{ old('company_description') }}</textarea>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary" onclick="prevStep()">Previous</button>
                            <button type="button" class="btn btn-primary" onclick="nextStep()">Next Step</button>
                        </div>
                    </div>

                    <!-- Step 3: Optional Documents -->
                    <div class="step-content d-none" id="step3">
                        <h5 class="text-warning mb-4">Step 3: Documents (Optional)</h5>
                        <p class="text-muted mb-4"><small>You can upload your certificates now or add them later from your
                                dashboard. COA (Certificate of Analysis) documents will be uploaded per product when you add
                                products to your catalog.</small></p>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">ISO Certificate</label>
                                <input type="file" name="iso_document" class="form-control"
                                    accept=".pdf,.jpg,.jpeg,.png">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">GMP Certificate</label>
                                <input type="file" name="gmp_document" class="form-control"
                                    accept=".pdf,.jpg,.jpeg,.png">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">WC Certificate</label>
                                <input type="file" name="wc_document" class="form-control"
                                    accept=".pdf,.jpg,.jpeg,.png">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Manufacturing Certificate</label>
                                <input type="file" name="manufacturing_certificate" class="form-control"
                                    accept=".pdf,.jpg,.jpeg,.png">
                            </div>
                        </div>

                        <!-- Terms and Conditions -->
                        <div class="mb-4">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="terms" id="terms" required>
                                <label class="form-check-label" for="terms">
                                    I agree to the <a href="#" class="text-primary">Terms and Conditions</a> and <a
                                        href="#" class="text-primary">Privacy Policy</a> *
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="data_accuracy" id="data_accuracy"
                                    required>
                                <label class="form-check-label" for="data_accuracy">
                                    I confirm that all provided information is accurate and up-to-date *
                                </label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary" onclick="prevStep()">Previous</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-user-plus me-2"></i>Register as Supplier
                            </button>
                        </div>
                    </div>
                </form>

                <div class="text-center mt-4">
                    <small>Already have an account? <a href="{{ route('supplier.login') }}">Sign In</a></small>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        let currentStep = 1;
        const totalSteps = 3;

        function showStep(step) {
            // Hide all steps
            document.querySelectorAll('.step-content').forEach(el => {
                el.classList.add('d-none');
            });

            // Show current step
            document.getElementById('step' + step).classList.remove('d-none');

            // Update progress indicators
            document.querySelectorAll('.step-item').forEach((item, index) => {
                const stepNumber = item.querySelector('.step-number');
                const stepTitle = item.querySelector('.step-title');

                if (index + 1 <= step) {
                    stepNumber.classList.remove('bg-secondary');
                    stepNumber.classList.add('bg-primary');
                    stepTitle.classList.add('text-primary');
                    stepTitle.classList.remove('text-muted');
                } else {
                    stepNumber.classList.remove('bg-primary');
                    stepNumber.classList.add('bg-secondary');
                    stepTitle.classList.remove('text-primary');
                    stepTitle.classList.add('text-muted');
                }
            });

            // Update step lines
            document.querySelectorAll('.step-line').forEach((line, index) => {
                if (index < step - 1) {
                    line.style.background = '#0d6efd';
                } else {
                    line.style.background = '#dee2e6';
                }
            });
        }

        function nextStep() {
            if (validateCurrentStep()) {
                if (currentStep < totalSteps) {
                    currentStep++;
                    showStep(currentStep);
                }
            }
        }


        function prevStep() {
            if (currentStep > 1) {
                currentStep--;
                showStep(currentStep);
            }
        }

        function validateCurrentStep() {
            const currentStepEl = document.getElementById('step' + currentStep);
            const requiredFields = currentStepEl.querySelectorAll('input[required], select[required], textarea[required]');
            let isValid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    isValid = false;
                } else {
                    field.classList.remove('is-invalid');
                }
            });

            // Special validation for step 1 (password confirmation)
            if (currentStep === 1) {
                const password = document.querySelector('input[name="password"]');
                const confirmPassword = document.querySelector('input[name="password_confirmation"]');

                if (password.value !== confirmPassword.value) {
                    confirmPassword.classList.add('is-invalid');
                    confirmPassword.setCustomValidity('Passwords do not match');
                    isValid = false;
                } else {
                    confirmPassword.classList.remove('is-invalid');
                    confirmPassword.setCustomValidity('');
                }
            }

            return isValid;
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize first step
            showStep(1);

            // Password confirmation validation
            const password = document.querySelector('input[name="password"]');
            const confirmPassword = document.querySelector('input[name="password_confirmation"]');

            function validatePassword() {
                if (password.value !== confirmPassword.value) {
                    confirmPassword.setCustomValidity('Passwords do not match');
                } else {
                    confirmPassword.setCustomValidity('');
                }
            }

            if (password && confirmPassword) {
                password.addEventListener('input', validatePassword);
                confirmPassword.addEventListener('input', validatePassword);
            }

            // Form submission loading state
            const form = document.querySelector('form');
            const submitBtn = document.querySelector('button[type="submit"]');

            form.addEventListener('submit', function() {
                submitBtn.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Creating Account...';
                submitBtn.disabled = true;
            });
        });
    </script>
@endpush
