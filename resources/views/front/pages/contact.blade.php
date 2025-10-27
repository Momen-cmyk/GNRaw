@extends('front.layout.app')

@section('content')
    <!-- Jumbotron Header -->
    <div class="jumbotron">
        <div class="jumbotron-content">
            <div class="jumbotron-heading">
                <h1>Contact Us</h1>
            </div>
            <div class="jumbotron-breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span class="separator">/</span>
                <span>Contact</span>
            </div>
        </div>
    </div>

    <div class="container" style="padding-bottom: 80px;">
        <div class="row">
            <div class="col-12">
                <p class="lead">{{ settings()->site_description ?? 'Get in touch with our team. We\'re here to help!' }}
                </p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5>Send us a Message</h5>
                    </div>
                    <div class="card-body">
                        <form>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="firstName" class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="firstName" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="lastName" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="lastName" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" required>
                            </div>
                            <div class="dropdown-menu mb-3">
                                <label for="subject" class="dropdown-header">Subject</label>
                                <select class="dropdown-item" id="subject" required>
                                    <option value="">Select a subject</option>
                                    <option value="general">General Inquiry</option>
                                    <option value="product">Product Question</option>
                                    <option value="order">Order Support</option>
                                    <option value="complaint">Complaint</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">Message</label>
                                <textarea class="form-control" id="message" rows="5" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Send Message</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Contact Information</h5>
                    </div>
                    <div class="card-body">
                        @if (settings()->site_address)
                            <div class="mb-3">
                                <i class="fa fa-map-marker text-primary me-2"></i>
                                <strong>Address</strong><br>
                                {{ settings()->site_address }}
                                @if (settings()->site_city)
                                    <br>{{ settings()->site_city }}
                                @endif
                                @if (settings()->site_state)
                                    , {{ settings()->site_state }}
                                @endif
                                @if (settings()->site_country)
                                    <br>{{ settings()->site_country }}
                                @endif
                                @if (settings()->site_zip_code)
                                    {{ settings()->site_zip_code }}
                                @endif
                            </div>
                        @endif
                        @if (settings()->site_phone)
                            <div class="mb-3">
                                <i class="fa fa-phone text-primary me-2"></i>
                                <strong>Phone</strong><br>
                                <a href="tel:{{ settings()->site_phone }}"
                                    class="text-decoration-none">{{ settings()->site_phone }}</a>
                            </div>
                        @endif
                        @if (settings()->site_email)
                            <div class="mb-3">
                                <i class="fa fa-envelope text-primary me-2"></i>
                                <strong>Email</strong><br>
                                <a href="mailto:{{ settings()->site_email }}"
                                    class="text-decoration-none">{{ settings()->site_email }}</a>
                            </div>
                        @endif
                        @if (settings()->site_website)
                            <div class="mb-3">
                                <i class="fa fa-globe text-primary me-2"></i>
                                <strong>Website</strong><br>
                                <a href="{{ settings()->site_website }}" target="_blank"
                                    class="text-decoration-none">{{ settings()->site_website }}</a>
                            </div>
                        @endif
                        <div class="mb-3">
                            <i class="fa fa-clock-o text-primary me-2"></i>
                            <strong>Business Hours</strong><br>
                            24 Hours<br>
                            7 Days a Week<br>
                        </div>
                    </div>
                </div>

                @if (settings()->facebook_url ||
                        settings()->twitter_url ||
                        settings()->instagram_url ||
                        settings()->linkedin_url ||
                        settings()->youtube_url ||
                        settings()->whatsapp_number ||
                        settings()->telegram_username)
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5>Follow Us</h5>
                        </div>
                        <div class="card-body text-center">
                            @if (settings()->facebook_url)
                                <a href="{{ settings()->facebook_url }}" target="_blank"
                                    class="btn btn-outline-primary me-2 mb-2" title="Facebook"><i
                                        class="fab fa-facebook"></i></a>
                            @endif
                            @if (settings()->twitter_url)
                                <a href="{{ settings()->twitter_url }}" target="_blank"
                                    class="btn btn-outline-info me-2 mb-2" title="X"><i
                                        class="fab fa-x-twitter"></i></a>
                            @endif
                            @if (settings()->instagram_url)
                                <a href="{{ settings()->instagram_url }}" target="_blank"
                                    class="btn btn-outline-danger me-2 mb-2" title="Instagram"><i
                                        class="fab fa-instagram"></i></a>
                            @endif
                            @if (settings()->linkedin_url)
                                <a href="{{ settings()->linkedin_url }}" target="_blank"
                                    class="btn btn-outline-primary me-2 mb-2" title="LinkedIn"><i
                                        class="fab fa-linkedin"></i></a>
                            @endif
                            @if (settings()->youtube_url)
                                <a href="{{ settings()->youtube_url }}" target="_blank"
                                    class="btn btn-outline-danger me-2 mb-2" title="YouTube"><i
                                        class="fab fa-youtube"></i></a>
                            @endif
                            @if (settings()->whatsapp_number)
                                <a href="https://wa.me/{{ str_replace(['+', ' ', '-', '(', ')'], '', settings()->whatsapp_number) }}"
                                    target="_blank" class="btn btn-outline-success me-2 mb-2" title="WhatsApp"><i
                                        class="fab fa-whatsapp"></i></a>
                            @endif
                            @if (settings()->telegram_username)
                                <a href="https://t.me/{{ str_replace('@', '', settings()->telegram_username) }}"
                                    target="_blank" class="btn btn-outline-info mb-2" title="Telegram"><i
                                        class="fab fa-telegram-plane"></i></a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .contact-page {
            padding: 60px 0 80px 0;
        }

        .card {
            border: none;
            background: #FFFFFF;
            box-shadow: 0px 8px 28px -6px rgba(24, 39, 75, 0.12), 0px 18px 88px -4px rgba(24, 39, 75, 0.14);
            border-radius: 10px;
        }

        .card-header {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            border-radius: 10px 10px 0 0 !important;
            border: none;
        }

        .card-header h5 {
            margin: 0;
            font-weight: 600;
        }

        .btn-outline-primary:hover,
        .btn-outline-info:hover,
        .btn-outline-danger:hover,
        .btn-outline-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .contact-info .fas {
            width: 20px;
            text-align: center;
        }

        .contact-info a {
            color: #007bff;
            transition: color 0.3s ease;
        }

        .contact-info a:hover {
            color: #0056b3;
            text-decoration: none;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .btn-primary {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            border: none;
            padding: 12px 30px;
            font-weight: 600;
            border-radius: 25px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 123, 255, 0.3);
        }

        /* Jumbotron Header */
        .jumbotron {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 80px 0;
            margin-bottom: 40px;
            position: relative;
            overflow: hidden;
        }

        .jumbotron::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.1);
            z-index: 1;
        }

        .jumbotron-content {
            position: relative;
            z-index: 2;
            text-align: center;
        }

        .jumbotron-heading h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .jumbotron-breadcrumb {
            font-size: 1.1rem;
            margin-top: 20px;
        }

        .jumbotron-breadcrumb a {
            color: #ffffff;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .jumbotron-breadcrumb a:hover {
            color: #f8f9fa;
        }

        .jumbotron-breadcrumb span {
            color: #e9ecef;
        }

        .jumbotron-breadcrumb .separator {
            color: #adb5bd;
            margin: 0 10px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .jumbotron {
                padding: 60px 0;
            }

            .jumbotron-heading h1 {
                font-size: 2.5rem;
            }
        }

        @media (max-width: 480px) {
            .jumbotron {
                padding: 40px 0;
            }

            .jumbotron-heading h1 {
                font-size: 2rem;
            }
        }
    </style>
@endpush
