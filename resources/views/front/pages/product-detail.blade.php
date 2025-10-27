@extends('front.layout.app')
@section('title', 'Product Details - ' . ($product->product_name ?? 'Product'))

@section('content')
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        @if ($product)
                            <div class="row">
                                <div class="col-md-6">
                                    @if ($product->product_images && count($product->product_images) > 0)
                                        <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                                            <div class="carousel-inner">
                                                @foreach ($product->product_images as $index => $image)
                                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                                        <img src="{{ asset('storage/' . $image) }}" class="d-block w-100"
                                                            alt="Product Image {{ $index + 1 }}"
                                                            style="height: 400px; object-fit: cover;">
                                                    </div>
                                                @endforeach
                                            </div>
                                            @if (count($product->product_images) > 1)
                                                <button class="carousel-control-prev" type="button"
                                                    data-bs-target="#productCarousel" data-bs-slide="prev">
                                                    <span class="carousel-control-prev-icon"></span>
                                                </button>
                                                <button class="carousel-control-next" type="button"
                                                    data-bs-target="#productCarousel" data-bs-slide="next">
                                                    <span class="carousel-control-next-icon"></span>
                                                </button>
                                            @endif
                                        </div>
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center"
                                            style="height: 400px;">
                                            <i class="fa fa-image fa-3x text-muted"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <h2 class="card-title">{{ $product->product_name }}</h2>
                                    <p class="text-muted">Category: {{ $product->product_category }}</p>

                                    @if ($product->cas_number)
                                        <p><strong>CAS Number:</strong> {{ $product->cas_number }}</p>
                                    @endif

                                    @if ($product->molecular_formula)
                                        <p><strong>Molecular Formula:</strong> {{ $product->molecular_formula }}</p>
                                    @endif

                                    @if ($product->molecular_weight)
                                        <p><strong>Molecular Weight:</strong> {{ $product->molecular_weight }}</p>
                                    @endif

                                    @if ($product->moq)
                                        <p><strong>Minimum Order Quantity:</strong> {{ $product->moq }} Kg</p>
                                    @endif

                                    <div class="mt-4">
                                        <h5>Specifications</h5>
                                        <p class="text-muted">{{ $product->specifications }}</p>
                                    </div>

                                    @if ($product->description)
                                        <div class="mt-3">
                                            <h5>Description</h5>
                                            <p class="text-muted">{{ $product->description }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fa fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                                <h4>Product Not Found</h4>
                                <p class="text-muted">The product you're looking for doesn't exist or has been removed.</p>
                                <a href="{{ route('home') }}" class="btn btn-primary">Back to Home</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                @if ($product)
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Request Information</h5>
                        </div>
                        <div class="card-body">
                            <form id="inquiryForm" action="{{ route('product.inquiry') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="supplier_id" value="{{ $product->supplier_id }}">
                                <input type="hidden" name="product_name" value="{{ $product->product_name }}">

                                <div class="mb-3">
                                    <label for="customer_name" class="form-label">Your Name *</label>
                                    <input type="text" class="form-control" id="customer_name" name="customer_name"
                                        required>
                                </div>

                                <div class="mb-3">
                                    <label for="customer_email" class="form-label">Email Address *</label>
                                    <input type="email" class="form-control" id="customer_email" name="customer_email"
                                        required>
                                </div>

                                <div class="mb-3">
                                    <label for="customer_phone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control" id="customer_phone" name="customer_phone">
                                </div>

                                <div class="mb-3">
                                    <label for="message" class="form-label">Your Message *</label>
                                    <textarea class="form-control" id="message" name="message" rows="4"
                                        placeholder="Please describe your requirements, quantity needed, and any specific questions about this product..."
                                        required></textarea>
                                </div>

                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fa fa-paper-plane"></i> Send Inquiry
                                </button>
                            </form>

                            <div class="mt-3">
                                <small class="text-muted">
                                    <i class="fa fa-info-circle"></i>
                                    Your inquiry will be reviewed by our team. We will contact you directly with product
                                    information and pricing.
                                </small>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('inquiryForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;

            // Show loading state
            submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Sending...';
            submitBtn.disabled = true;

            fetch('{{ route('product.inquiry') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        const alertDiv = document.createElement('div');
                        alertDiv.className = 'alert alert-success alert-dismissible fade show';
                        alertDiv.innerHTML = `
                <i class="fa fa-check-circle"></i> ${data.message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;

                        // Insert before the form
                        this.parentNode.insertBefore(alertDiv, this);

                        // Reset form
                        this.reset();
                    } else {
                        // Show error message
                        const alertDiv = document.createElement('div');
                        alertDiv.className = 'alert alert-danger alert-dismissible fade show';
                        alertDiv.innerHTML = `
                <i class="fa fa-exclamation-triangle"></i> ${data.message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;

                        this.parentNode.insertBefore(alertDiv, this);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'alert alert-danger alert-dismissible fade show';
                    alertDiv.innerHTML = `
            <i class="fa fa-exclamation-triangle"></i> An error occurred while sending your inquiry. Please try again.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

                    this.parentNode.insertBefore(alertDiv, this);
                })
                .finally(() => {
                    // Reset button
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                });
        });
    </script>
@endpush
