<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $fileName }} - Document Viewer</title>

    <!-- Favicon -->
    @if (isset(settings()->site_favicon) && settings()->site_favicon)
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/site/' . settings()->site_favicon) }}">
        <link rel="icon" type="image/png" sizes="16x16"
            href="{{ asset('images/site/' . settings()->site_favicon) }}">
        <link rel="icon" type="image/x-icon" href="{{ asset('images/site/' . settings()->site_favicon) }}">
    @else
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    @endif

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .document-container {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .document-header {
            background: white;
            border-bottom: 1px solid #dee2e6;
            padding: 1rem 0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .document-content {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 0;
        }

        .document-image {
            max-width: 100%;
            max-height: 80vh;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            cursor: zoom-in;
            transition: transform 0.3s ease;
        }

        .document-image:hover {
            transform: scale(1.02);
        }

        .document-image.zoomed {
            cursor: zoom-out;
            transform: scale(1.5);
        }

        .document-actions {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            z-index: 1000;
        }

        .btn-floating {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .loading-spinner {
            display: block;
        }

        .document-image {
            display: none;
        }

        .loading .loading-spinner {
            display: block;
        }

        .loading .document-image {
            display: none;
        }

        .loaded .loading-spinner {
            display: none;
        }

        .loaded .document-image {
            display: block;
        }
    </style>
</head>

<body>
    <div class="document-container">
        <!-- Header -->
        <div class="document-header">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="mb-0">
                            <i class="fa fa-file-image text-primary me-2"></i>
                            {{ $fileName }}
                        </h4>
                        <small class="text-muted">Document Viewer</small>
                    </div>
                    <div class="col-md-4 text-end">
                        <a href="{{ $filePath }}" download="{{ $fileName }}" class="btn btn-primary">
                            <i class="fa fa-download me-1"></i> Download
                        </a>
                        <button onclick="window.close()" class="btn btn-outline-secondary ms-2">
                            <i class="fa fa-times me-1"></i> Close
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="document-content loading" id="documentContent">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12 text-center">
                        <div class="loading-spinner">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2">Loading document...</p>
                        </div>

                        <img src="{{ $filePath }}" alt="{{ $fileName }}" class="document-image"
                            id="documentImage" onclick="toggleZoom()" onload="hideLoading()" onerror="showError()">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating Action Buttons -->
    <div class="document-actions">
        <button class="btn btn-primary btn-floating" onclick="toggleZoom()" title="Zoom In/Out">
            <i class="fa fa-search-plus" id="zoomIcon"></i>
        </button>
        <button class="btn btn-success btn-floating" onclick="downloadDocument()" title="Download">
            <i class="fa fa-download"></i>
        </button>
        <button class="btn btn-info btn-floating" onclick="printDocument()" title="Print">
            <i class="fa fa-print"></i>
        </button>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        let isZoomed = false;

        function toggleZoom() {
            const image = document.getElementById('documentImage');
            const icon = document.getElementById('zoomIcon');

            if (isZoomed) {
                image.classList.remove('zoomed');
                icon.className = 'fa fa-search-plus';
                isZoomed = false;
            } else {
                image.classList.add('zoomed');
                icon.className = 'fa fa-search-minus';
                isZoomed = true;
            }
        }

        function downloadDocument() {
            const link = document.createElement('a');
            link.href = '{{ $filePath }}';
            link.download = '{{ $fileName }}';
            link.click();
        }

        function printDocument() {
            const image = document.getElementById('documentImage');
            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <html>
                    <head>
                        <title>{{ $fileName }}</title>
                        <style>
                            body { margin: 0; text-align: center; }
                            img { max-width: 100%; height: auto; }
                        </style>
                    </head>
                    <body>
                        <img src="${image.src}" alt="{{ $fileName }}">
                    </body>
                </html>
            `);
            printWindow.document.close();
            printWindow.print();
        }

        function hideLoading() {
            console.log('Image loaded successfully');
            const content = document.getElementById('documentContent');
            if (content) {
                content.classList.remove('loading');
                content.classList.add('loaded');
                console.log('Loading spinner hidden, image shown');
            } else {
                console.log('Content element not found');
            }
        }

        function showError() {
            console.log('Error loading image');
            const content = document.getElementById('documentContent');
            if (content) {
                content.classList.remove('loading');
                content.querySelector('.container').innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fa fa-exclamation-triangle fa-2x mb-3"></i>
                        <h5>Error Loading Document</h5>
                        <p>The document could not be loaded. Please try again or contact support.</p>
                        <button onclick="window.location.reload()" class="btn btn-primary">Retry</button>
                    </div>
                `;
            }
        }

        // Debug: Log initial state
        console.log('Document viewer loaded');
        console.log('File path:', '{{ $filePath }}');
        console.log('File name:', '{{ $fileName }}');
        console.log('MIME type:', '{{ $mimeType }}');

        // Check if image element exists
        const image = document.getElementById('documentImage');
        console.log('Image element found:', !!image);
        if (image) {
            console.log('Image src:', image.src);
        }

        // Timeout fallback - hide loading after 10 seconds
        setTimeout(function() {
            const content = document.getElementById('documentContent');
            if (content && content.classList.contains('loading')) {
                console.log('Timeout reached, hiding loading');
                content.classList.remove('loading');
                content.classList.add('loaded');
            }
        }, 10000);

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                window.close();
            } else if (e.key === 'z' || e.key === 'Z') {
                toggleZoom();
            } else if (e.key === 'd' || e.key === 'D') {
                downloadDocument();
            } else if (e.key === 'p' || e.key === 'P') {
                printDocument();
            }
        });
    </script>
</body>

</html>
