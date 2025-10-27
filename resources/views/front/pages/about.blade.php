@extends('front.layout.app')

@section('content')
    <!-- Jumbotron Header -->
    <div class="jumbotron">
        <div class="jumbotron-content">
            <div class="jumbotron-heading">
                <h1>About GNRAW</h1>
            </div>
            <div class="jumbotron-breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span class="separator">/</span>
                <span>About</span>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-12">
                <!-- Content moved below jumbotron -->
            </div>
        </div>

        <div class="row mb-5">
            <div class="col-md-6">
                <h2>Our Mission</h2>
                <p class="lead">To provide premium, science-backed nutritional supplements that support your health and
                    wellness journey.</p>
                <p>At GNRAW, we believe that everyone deserves access to high-quality nutritional supplements. Our
                    team of experts carefully selects and tests every product to ensure it meets our strict standards for
                    purity, potency, and effectiveness.</p>
            </div>
            <div class="col-md-6">
                <img src="/images/about/mission.jpg" class="img-fluid rounded" alt="Our Mission">
            </div>
        </div>

        <div class="row mb-5">
            <div class="col-md-6 order-md-2">
                <h2>Quality Assurance</h2>
                <p>Every product in our catalog undergoes rigorous testing and quality control processes. We work with
                    certified laboratories and follow Good Manufacturing Practices (GMP) to ensure the highest standards.
                </p>
                <ul>
                    <li>Third-party tested for purity</li>
                    <li>GMP certified facilities</li>
                    <li>No artificial fillers or preservatives</li>
                    <li>Sustainable sourcing practices</li>
                </ul>
            </div>
            <div class="col-md-6 order-md-1">
                <img src="/images/about/quality.jpg" class="img-fluid rounded" alt="Quality Assurance">
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <h2 class="text-center mb-4">Our Values</h2>
            </div>
            <div class="col-md-3 text-center mb-4">
                <i class="fa fa-heart fa-3x text-danger mb-3"></i>
                <h5>Health First</h5>
                <p>Your health and wellness are our top priorities in everything we do.</p>
            </div>
            <div class="col-md-3 text-center mb-4">
                <i class="fa fa-shield fa-3x text-success mb-3"></i>
                <h5>Trust & Safety</h5>
                <p>We maintain the highest safety standards and transparent practices.</p>
            </div>
            <div class="col-md-3 text-center mb-4">
                <i class="fa fa-leaf fa-3x text-success mb-3"></i>
                <h5>Natural & Pure</h5>
                <p>We source only the finest natural ingredients for our products.</p>
            </div>
            <div class="col-md-3 text-center mb-4">
                <i class="fa fa-users fa-3x text-primary mb-3"></i>
                <h5>Customer Care</h5>
                <p>Exceptional customer service and support for your wellness journey.</p>
            </div>
        </div>
    </div>

    <style>
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
@endsection
