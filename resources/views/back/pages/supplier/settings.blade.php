@extends('back.layout.supplier-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Settings')

@section('content')
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="supplier-stats-card">
                <h3 class="mb-2">{{ __('common.settings') }}</h3>
                <p class="text-muted mb-0">Manage your account settings and preferences</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-8 col-lg-10 col-md-12 mb-3">
            <div class="supplier-quick-actions">
                <h4 class="mb-4">Account Settings</h4>

                <form action="{{ route('supplier.settings.update') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('common.email_notifications') }}</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="emailNotifications"
                                    name="email_notifications" {{ $user->email_notifications ? 'checked' : '' }}>
                                <label class="form-check-label" for="emailNotifications">
                                    Receive email notifications
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('common.language') }}</label>
                            <select class="form-control" name="language">
                                <option value="en" {{ $user->language == 'en' ? 'selected' : '' }}>English</option>
                                <option value="ar" {{ $user->language == 'ar' ? 'selected' : '' }}>العربية</option>
                                <option value="fr" {{ $user->language == 'fr' ? 'selected' : '' }}>Français</option>
                                <option value="es" {{ $user->language == 'es' ? 'selected' : '' }}>Español</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('common.timezone') }}</label>
                            <select class="form-control" name="timezone">
                                <option value="UTC" {{ $user->timezone == 'UTC' ? 'selected' : '' }}>UTC</option>
                                <option value="America/New_York"
                                    {{ $user->timezone == 'America/New_York' ? 'selected' : '' }}>Eastern Time</option>
                                <option value="America/Chicago"
                                    {{ $user->timezone == 'America/Chicago' ? 'selected' : '' }}>Central Time</option>
                                <option value="America/Denver" {{ $user->timezone == 'America/Denver' ? 'selected' : '' }}>
                                    Mountain Time</option>
                                <option value="America/Los_Angeles"
                                    {{ $user->timezone == 'America/Los_Angeles' ? 'selected' : '' }}>Pacific Time</option>
                                <option value="Europe/London" {{ $user->timezone == 'Europe/London' ? 'selected' : '' }}>
                                    London</option>
                                <option value="Europe/Paris" {{ $user->timezone == 'Europe/Paris' ? 'selected' : '' }}>
                                    Paris</option>
                                <option value="Asia/Dubai" {{ $user->timezone == 'Asia/Dubai' ? 'selected' : '' }}>Dubai
                                </option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('common.theme') }}</label>
                            <div class="d-flex gap-2">
                                <button type="button" class="supplier-action-btn outline" onclick="toggleTheme()">
                                    <i class="icon-copy dw dw-moon" id="theme-icon"></i> Toggle Theme
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="supplier-action-btn">
                            <i class="icon-copy dw dw-save"></i> {{ __('common.save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Security Settings -->
    <div class="row">
        <div class="col-xl-8 col-lg-10 col-md-12 mb-3">
            <div class="supplier-quick-actions">
                <h4 class="mb-4">Security Settings</h4>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="security-option">
                            <h6>Change Password</h6>
                            <p class="text-muted small">Update your account password</p>
                            <button class="supplier-action-btn outline" data-bs-toggle="modal"
                                data-bs-target="#changePasswordModal">
                                <i class="icon-copy dw dw-lock"></i> Change Password
                            </button>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="security-option">
                            <h6>Two-Factor Authentication</h6>
                            <p class="text-muted small">Add extra security to your account</p>
                            <button class="supplier-action-btn outline">
                                <i class="icon-copy dw dw-shield"></i> Enable 2FA
                            </button>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="security-option">
                            <h6>Login Sessions</h6>
                            <p class="text-muted small">Manage active login sessions</p>
                            <button class="supplier-action-btn outline">
                                <i class="icon-copy dw dw-monitor"></i> View Sessions
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Change Password Modal -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Change Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('supplier.settings.change_password') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Current Password</label>
                            <input type="password" class="form-control" name="current_password" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" class="form-control" name="new_password" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" name="new_password_confirmation" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="supplier-action-btn">Change Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .theme-option {
            cursor: pointer;
            padding: 15px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            transition: all 0.3s ease;
            text-align: center;
        }

        .theme-option:hover {
            border-color: #667eea;
            transform: translateY(-2px);
        }

        .theme-option.active {
            border-color: #667eea;
            background: rgba(102, 126, 234, 0.1);
        }

        .theme-preview {
            width: 100%;
            height: 80px;
            border-radius: 8px;
            position: relative;
            overflow: hidden;
        }

        .light-theme {
            background: #f8f9fa;
        }

        .light-theme .preview-header {
            height: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .light-theme .preview-sidebar {
            position: absolute;
            left: 0;
            top: 20px;
            width: 30%;
            height: 60px;
            background: #2c3e50;
        }

        .light-theme .preview-content {
            position: absolute;
            right: 0;
            top: 20px;
            width: 70%;
            height: 60px;
            background: white;
        }

        .dark-theme {
            background: #1a1a1a;
        }

        .dark-theme .preview-header {
            height: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .dark-theme .preview-sidebar {
            position: absolute;
            left: 0;
            top: 20px;
            width: 30%;
            height: 60px;
            background: #2c3e50;
        }

        .dark-theme .preview-content {
            position: absolute;
            right: 0;
            top: 20px;
            width: 70%;
            height: 60px;
            background: #343a40;
        }

        .security-option {
            padding: 20px;
            border: 1px solid #e9ecef;
            border-radius: 10px;
            text-align: center;
            height: 100%;
        }

        .security-option h6 {
            margin-bottom: 10px;
            color: #2c3e50;
        }

        .security-option p {
            margin-bottom: 15px;
        }

        /* Dark theme styles */
        [data-theme="dark"] .supplier-stats-card {
            background: #2c3e50;
            color: #ecf0f1;
            border: 1px solid #34495e;
        }

        [data-theme="dark"] .supplier-quick-actions {
            background: #2c3e50;
            color: #ecf0f1;
            border: 1px solid #34495e;
        }

        [data-theme="dark"] .form-control {
            background: #34495e;
            border-color: #4a5f7a;
            color: #ecf0f1;
        }

        [data-theme="dark"] .form-control:focus {
            background: #34495e;
            border-color: #667eea;
            color: #ecf0f1;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        [data-theme="dark"] .form-label {
            color: #ecf0f1;
        }

        [data-theme="dark"] .text-muted {
            color: #bdc3c7 !important;
        }

        [data-theme="dark"] .security-option {
            background: #34495e;
            border-color: #4a5f7a;
            color: #ecf0f1;
        }

        [data-theme="dark"] .security-option h6 {
            color: #ecf0f1;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Theme management
        function initTheme() {
            const savedTheme = localStorage.getItem('supplier-theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
            updateThemeIcon(savedTheme);
        }

        function toggleTheme() {
            const currentTheme = document.documentElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('supplier-theme', newTheme);
            updateThemeIcon(newTheme);
        }

        function updateThemeIcon(theme) {
            const themeIcon = document.getElementById('theme-icon');
            if (themeIcon) {
                if (theme === 'dark') {
                    themeIcon.className = 'icon-copy dw dw-sun';
                } else {
                    themeIcon.className = 'icon-copy dw dw-moon';
                }
            }
        }

        // Initialize theme on page load
        document.addEventListener('DOMContentLoaded', function() {
            initTheme();
        });
    </script>
@endpush
