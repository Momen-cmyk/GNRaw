@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Page Title Here')
@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="title">
                    <h4>Profile</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Profile
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    @livewire('admin.profile')

@endsection
@push('scripts')
    <script>
        // Listen for Livewire profile update events
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('showToastr', (event) => {
                // Check if personal details were updated successfully
                if (event[0].type === 'success' && event[0].message.includes('personal details')) {
                    // Update the name in the header dropdown
                    updateHeaderUserName();
                }
            });
        });

        // Function to update header user name
        function updateHeaderUserName() {
            // Get the updated name from the form
            var updatedName = $('input[wire\\:model="name"]').val();

            if (updatedName) {
                // Update all instances of user name in header
                $('.user-info-dropdown .user-name').text(updatedName);
                $('.user-info-header .user-details h4').text(updatedName);

                console.log('Header user name updated to:', updatedName);
            }
        }

        // Handle profile picture upload
        $('#profilePictureForm').on('submit', function(e) {
            e.preventDefault();

            var form = this;
            var formData = new FormData(form);
            var submitBtn = $(form).find('button[type="submit"]');
            var originalText = submitBtn.html();

            // Disable submit button and show loading
            submitBtn.prop('disabled', true).html(
                '<span class="spinner-border spinner-border-sm me-2"></span>Uploading...');

            $.ajax({
                url: $(form).attr('action'),
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.status == 1) {
                        // Update the profile picture on the page
                        $('#profilePicturePreview').attr('src', response.image_path);

                        // Update header dropdown images
                        $('.user-info-dropdown .user-icon img').attr('src', response.image_path);
                        $('.user-info-header .user-avatar img').attr('src', response.image_path);

                        // Show success message
                        $().notifa({
                            vers: 1,
                            cssClss: 'success',
                            html: response.message,
                            delay: 2500
                        });
                        // Close modal and reset form
                        $('#profilePictureModal').modal('hide');
                        $(form)[0].reset();
                    } else {
                        $().notifa({
                            vers: 1,
                            cssClss: 'error',
                            html: response.message,
                            delay: 2500
                        });
                    }
                },
                error: function(xhr) {
                    var response = xhr.responseJSON;
                    if (response && response.message) {
                        alert(response.message);
                    } else {
                        alert('An error occurred while uploading the picture.');
                    }
                },
                complete: function() {
                    // Re-enable submit button
                    submitBtn.prop('disabled', false).html(originalText);
                }
            });
        });
    </script>

    {{-- <script>
        $('input[type = "file"][id="profilePictureFile"]').Kropify({
            preview: 'image#profilePicturePreview',
            viewMode: 1,
            aspectRatio: 1,
            cancelButtonText: 'Cancel',
            resetButtonText: 'Reset',
            cropButtonText: 'Crop & update',
            processURL: '{{ route('admin.admin.upload_profile_picture') }}',
            maxSize: 2097152,
            showLoader: true,
            success: function(data) {
                if (data.status == 1) {
                    $().notifa({
                        vers: 2,
                        cssClss: 'success',
                        html: data.message,
                        delay: 2500
                    });

                } else {
                    $().notifa({
                        vers: 2,
                        cssClss: 'error',
                        html: data.message,
                        delay: 2500
                    });
                }
            },
            errors: function(error, text) {
                console.log(text);
            },
        });
    </script> --}}
@endpush
