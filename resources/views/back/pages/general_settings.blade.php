@extends('back.layout.pages-layout')
@section('pageTitlw', isset($pageTitle) ? $pageTitle : 'Page Title Here')
@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="title">
                    <h4>Tabs</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Settings
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    @livewire('admin.settings')

@endsection
@push('scripts')
    <script>
        $('input[type="file"][name="site_logo"]').ijaboViewer({
            preview: '#preview_site_logo',
            allowedExtensions: ['jpg', 'jpeg', 'png', 'svg'],
            imageShape: 'circle',
            onError: function(msg) {
                alert(msg);
            },
            onErrorShape: function(msg) {
                alert(msg);
            },
            onInvalidType: function(msg) {
                alert(msg);
            },
            onDone: function(response) {}

        });

        $('#updateLogoForm').submit(function(e) {
            e.preventDefault();
            var form = this;
            var inputVal = $(form).find('input[type="file"]').val();
            var errorElement = $(form).find('span.text-danger');
            errorElement.text('');


            if (inputVal.length > 0) {
                $.ajax({
                    url: $(form).attr('action'),
                    method: $(form).attr('method'),
                    data: new FormData(form),
                    processData: false,
                    datatype: 'json',
                    contentType: false,
                    beforeSend: function() {},
                    success: function(data) {
                        if (data.status == 1) {
                            $(form)[0].reset();
                            $().notifa({
                                vers: 2,
                                cssClass: 'success',
                                html: data.message,
                                delay: 3000
                            });
                            $('img.site_logo').each(function() {
                                $(this).attr('src', '/' + data.image_path);
                            });
                        } else {
                            $().notifa({
                                vers: 2,
                                cssClass: 'error',
                                html: data.message,
                                delay: 3000
                            });
                        }
                    }
                });
            } else {
                errorElement.text('Please, Select an image file.');
            }
        });
        /////////////////////////////////////////////////////////////////

        $('input[type="file"][name="site_favicon"]').ijaboViewer({
            preview: 'img#preview_site_favicon',
            allowedExtensions: ['jpg', 'jpeg', 'png', 'ico'],
            imageShape: 'square',
            onError: function(msg) {
                alert(msg);
            },
            onErrorShape: function(msg) {
                alert(msg);
            },
            onInvalidType: function(msg) {
                alert(msg);
            },
            onDone: function(response) {}

        });

        $('#updateFaviconForm').submit(function(e) {
            e.preventDefault();
            var form = this;
            var inputVal = $(form).find('input[type="file"]').val();
            var errorElement = $(form).find('span.text-danger');
            errorElement.text('');


            if (inputVal.length > 0) {
                $.ajax({
                    url: $(form).attr('action'),
                    method: $(form).attr('method'),
                    data: new FormData(form),
                    processData: false,
                    datatype: 'json',
                    contentType: false,
                    beforeSend: function() {},
                    success: function(data) {
                        if (data.status == 1) {

                            $(form)[0].reset();
                            $().notifa({
                                vers: 2,
                                cssClass: 'success',
                                html: data.message,
                                delay: 2700
                            });
                            // Update all favicon link elements
                            var linkElements = document.querySelectorAll('link[rel="icon"]');
                            linkElements.forEach(function(element) {
                                element.href = '/' + data.image_path;
                            });
                            $('img.site_favicon').each(function() {
                                $(this).attr('src', '/' + data.image_path);
                            });
                        } else {
                            $().notifa({
                                vers: 2,
                                cssClass: 'error',
                                html: data.message,
                                delay: 2700
                            });
                        }
                    }
                });
            } else {
                errorElement.text('Please, Select an image file.');
            }
        });
    </script>
@endpush
