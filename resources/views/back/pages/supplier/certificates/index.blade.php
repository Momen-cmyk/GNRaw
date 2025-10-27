@extends('back.layout.supplier-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Certificate Management')

@section('content')
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="supplier-stats-card">
                <h3 class="mb-2">Certificate Management</h3>
                <p class="text-muted mb-0">Upload and manage your business certificates and compliance documents</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12 mb-3">
            <div class="supplier-quick-actions">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="mb-0">Certificates & Documents</h4>
                    <button class="supplier-action-btn" data-bs-toggle="modal" data-bs-target="#uploadCertificateModal">
                        <i class="icon-copy dw dw-upload"></i> Upload Certificate
                    </button>
                </div>
                <div class="alert alert-info">
                    <i class="icon-copy dw dw-info"></i> <strong>Note:</strong> COA (Certificate of Analysis) documents are uploaded per product. Please add COAs when creating or editing products.
                </div>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Certificate Type Tabs -->
                <ul class="nav nav-tabs mb-4" id="certificateTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all"
                            type="button" role="tab">
                            All Certificates
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="iso-tab" data-bs-toggle="tab" data-bs-target="#iso" type="button"
                            role="tab">
                            ISO Certificates
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="manufacturing-tab" data-bs-toggle="tab" data-bs-target="#manufacturing"
                            type="button" role="tab">
                            Manufacturing
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="gmp-tab" data-bs-toggle="tab" data-bs-target="#gmp" type="button"
                            role="tab">
                            GMP Certificates
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="wc-tab" data-bs-toggle="tab" data-bs-target="#wc" type="button"
                            role="tab">
                            WC Certificates
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="certificateTabsContent">
                    @foreach (['all', 'iso', 'manufacturing', 'gmp', 'wc'] as $type)
                        <div class="tab-pane fade {{ $type === 'all' ? 'show active' : '' }}" id="{{ $type }}"
                            role="tabpanel">
                            <div class="row">
                                @php
                                    $filteredCertificates =
                                        $type === 'all'
                                            ? $certificates
                                            : $certificates->where('certificate_type', $type);
                                @endphp

                                @forelse($filteredCertificates as $certificate)
                                    <div class="col-md-6 col-lg-4 mb-4">
                                        <div class="supplier-stats-card h-100">
                                            <div class="d-flex align-items-start mb-3">
                                                <div class="flex-shrink-0">
                                                    @if ($certificate->certificate_type === 'coa')
                                                        <i class="icon-copy dw dw-file-text text-primary"
                                                            style="font-size: 24px;"></i>
                                                    @elseif($certificate->certificate_type === 'iso')
                                                        <i class="icon-copy dw dw-certificate text-success"
                                                            style="font-size: 24px;"></i>
                                                    @elseif($certificate->certificate_type === 'manufacturing')
                                                        <i class="icon-copy dw dw-factory text-warning"
                                                            style="font-size: 24px;"></i>
                                                    @elseif($certificate->certificate_type === 'gmp')
                                                        <i class="icon-copy dw dw-shield text-info"
                                                            style="font-size: 24px;"></i>
                                                    @elseif($certificate->certificate_type === 'wc')
                                                        <i class="icon-copy dw dw-globe text-danger"
                                                            style="font-size: 24px;"></i>
                                                    @else
                                                        <i class="icon-copy dw dw-file text-secondary"
                                                            style="font-size: 24px;"></i>
                                                    @endif
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h6 class="card-title mb-1">{{ $certificate->certificate_name }}
                                                    </h6>
                                                    <small class="text-muted">{{ $certificate->type_display_name }}</small>
                                                </div>
                                            </div>

                                            <p class="card-text small text-muted mb-2">
                                                {{ Str::limit($certificate->description, 80) }}
                                            </p>

                                            <div class="row small text-muted mb-3">
                                                <div class="col-6">
                                                    <strong>File Size:</strong><br>
                                                    {{ $certificate->file_size_human }}
                                                </div>
                                                <div class="col-6">
                                                    <strong>Status:</strong><br>
                                                    @if ($certificate->status === 'active')
                                                        <span class="badge bg-success">Active</span>
                                                    @elseif($certificate->status === 'expired')
                                                        <span class="badge bg-danger">Expired</span>
                                                    @else
                                                        <span class="badge bg-warning">Pending Review</span>
                                                    @endif
                                                </div>
                                            </div>

                                            @if ($certificate->expiry_date)
                                                <div class="small text-muted mb-3">
                                                    <strong>Expires:</strong>
                                                    {{ $certificate->expiry_date->format('M d, Y') }}
                                                    @if ($certificate->isExpired())
                                                        <span class="text-danger">(Expired)</span>
                                                    @endif
                                                </div>
                                            @endif

                                            <div class="d-flex justify-content-between">
                                                <a href="{{ Storage::url($certificate->file_path) }}" target="_blank"
                                                    class="supplier-action-btn outline"
                                                    style="padding: 8px 12px; font-size: 12px;">
                                                    <i class="icon-copy dw dw-eye"></i> View
                                                </a>
                                                <a href="{{ Storage::url($certificate->file_path) }}"
                                                    download="{{ $certificate->original_filename }}"
                                                    class="supplier-action-btn outline"
                                                    style="padding: 8px 12px; font-size: 12px;">
                                                    <i class="icon-copy dw dw-download"></i> Download
                                                </a>
                                                <button class="supplier-action-btn outline"
                                                    style="padding: 8px 12px; font-size: 12px; background: #dc3545; border-color: #dc3545; color: white;"
                                                    onclick="deleteCertificate({{ $certificate->id }})">
                                                    <i class="icon-copy dw dw-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <div class="text-center py-5">
                                            <i class="icon-copy dw dw-file-text"
                                                style="font-size: 64px; opacity: 0.3;"></i>
                                            <h5 class="mt-3 text-muted">No {{ $type === 'all' ? '' : $type }} certificates
                                                found</h5>
                                            <p class="text-muted">Upload your first certificate to get started.</p>
                                            <button class="supplier-action-btn" data-bs-toggle="modal"
                                                data-bs-target="#uploadCertificateModal">
                                                <i class="icon-copy dw dw-upload"></i> Upload Certificate
                                            </button>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Upload Certificate Modal -->
    <div class="modal fade" id="uploadCertificateModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Upload Certificate</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('supplier.certificates.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Certificate Type *</label>
                                <select class="form-control" name="certificate_type" required>
                                    <option value="">Select Type</option>
                                    <option value="iso">ISO Certificate</option>
                                    <option value="manufacturing">Manufacturing Certificate</option>
                                    <option value="gmp">GMP Certificate</option>
                                    <option value="wc">Worldwide Compliance Certificate</option>
                                </select>
                                <div class="form-text"><small>COA documents are uploaded per product</small></div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Certificate Name *</label>
                                <input type="text" class="form-control" name="certificate_name" required
                                    placeholder="e.g., COA-2024-001">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Issue Date</label>
                                <input type="date" class="form-control" name="issue_date">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Expiry Date</label>
                                <input type="date" class="form-control" name="expiry_date">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Issuing Authority</label>
                                <input type="text" class="form-control" name="issuing_authority"
                                    placeholder="e.g., ISO, FDA, WHO">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Certificate Number</label>
                                <input type="text" class="form-control" name="certificate_number"
                                    placeholder="e.g., ISO-9001-2024">
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="description" rows="3" placeholder="Brief description of the certificate"></textarea>
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label">Upload File *</label>
                                <input type="file" class="form-control" name="certificate_file" required
                                    accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                <div class="form-text">Supported formats: PDF, DOC, DOCX, JPG, PNG. Max size: 10MB</div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="supplier-action-btn">Upload Certificate</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteCertificateModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Certificate</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this certificate? This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteCertificateForm" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="supplier-action-btn"
                            style="background: #dc3545; border-color: #dc3545;">Delete Certificate</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function deleteCertificate(certificateId) {
            document.getElementById('deleteCertificateForm').action = `/supplier/certificates/${certificateId}`;
            new bootstrap.Modal(document.getElementById('deleteCertificateModal')).show();
        }
    </script>
@endpush
