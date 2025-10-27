<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Document Error</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .error-container {
            background: white;
            border-radius: 20px;
            padding: 3rem;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            text-align: center;
            max-width: 600px;
        }

        .error-icon {
            font-size: 5rem;
            color: #dc3545;
            margin-bottom: 1rem;
        }

        .error-title {
            font-size: 2rem;
            color: #2c3e50;
            margin-bottom: 1rem;
        }

        .error-message {
            color: #6c757d;
            margin-bottom: 2rem;
        }

        .btn {
            padding: 0.75rem 2rem;
            border-radius: 50px;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon">
            <i class="fa fa-exclamation-circle"></i>
        </div>

        <h1 class="error-title">Error Loading Document</h1>

        <p class="error-message">
            An error occurred while trying to load the document.
        </p>

        @if(isset($message))
        <div class="alert alert-danger" role="alert">
            <i class="fa fa-exclamation-triangle"></i>
            {{ $message }}
        </div>
        @endif

        <div class="d-flex gap-2 justify-content-center">
            <button onclick="window.close()" class="btn btn-outline-secondary">
                <i class="fa fa-times"></i> Close Window
            </button>
            <button onclick="window.location.reload()" class="btn btn-primary">
                <i class="fa fa-refresh"></i> Try Again
            </button>
        </div>

        <div class="mt-4">
            <p class="text-muted small">
                <i class="fa fa-info-circle"></i>
                Please check if the document exists and try again.
            </p>
        </div>
    </div>
</body>
</html>

