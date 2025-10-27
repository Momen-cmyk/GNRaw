<div>
    <div class="pd-20 card-box mb-4">
        <div class="tab">
            <ul class="nav nav-tabs customtab" role="tablist">
                <li class="nav-item">
                    <a wire:click="selectTab('general_settings')"
                        class="nav-link {{ $tab == 'general_settings' ? 'active' : '' }}" data-toggle="tab"
                        href="#general_settings" role="tab" aria-selected="false">General Settings </a>
                </li>
                <li class="nav-item">
                    <a wire:click="selectTab('social_media')"
                        class="nav-link {{ $tab == 'social_media' ? 'active' : '' }}" data-toggle="tab"
                        href="#social_media" role="tab" aria-selected="false">Social Media</a>
                </li>
                <li class="nav-item">
                    <a wire:click="selectTab('logo_favicon')"
                        class="nav-link {{ $tab == 'logo_favicon' ? 'active' : '' }}" data-toggle="tab"
                        href="#logo_favicon" role="tab" aria-selected="false">Logo
                        & favicon</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade {{ $tab == 'general_settings' ? 'active show' : '' }}" id="general_settings"
                    role="tabpanel">
                    <div class="pd-20">
                        <form wire:submit='updateSiteInfo()'>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for =""><b>Site Name</b></label>
                                        <input type="text" class="form-control" wire:model="site_title"
                                            placeholder="Enter Site title">
                                        @error('site_title')
                                            <span class="text-danger ml-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for =""><b>Site Email</b></label>
                                        <input type="text" class="form-control" wire:model="site_email"
                                            placeholder="Enter Site Email">
                                        @error('site_email')
                                            <span class="text-danger ml-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for =""><b>Site Phone Number</b><small>(Optional)</small></label>
                                        <input type="text" class="form-control" wire:model="site_phone"
                                            placeholder="Enter Site Contact Phone">
                                        @error('site_phone')
                                            <span class="text-danger ml-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for =""><b>Site Address</b><small>(Optional)</small></label>
                                        <input type="text" class="form-control" wire:model="site_address"
                                            placeholder="Enter Site Address">
                                        @error('site_address')
                                            <span class="text-danger ml-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for =""><b>City</b><small>(Optional)</small></label>
                                        <input type="text" class="form-control" wire:model="site_city"
                                            placeholder="Enter City">
                                        @error('site_city')
                                            <span class="text-danger ml-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for =""><b>State/Province</b><small>(Optional)</small></label>
                                        <input type="text" class="form-control" wire:model="site_state"
                                            placeholder="Enter State/Province">
                                        @error('site_state')
                                            <span class="text-danger ml-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for =""><b>Country</b><small>(Optional)</small></label>
                                        <input type="text" class="form-control" wire:model="site_country"
                                            placeholder="Enter Country">
                                        @error('site_country')
                                            <span class="text-danger ml-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for =""><b>ZIP/Postal Code</b><small>(Optional)</small></label>
                                        <input type="text" class="form-control" wire:model="site_zip_code"
                                            placeholder="Enter ZIP/Postal Code">
                                        @error('site_zip_code')
                                            <span class="text-danger ml-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for =""><b>Website URL</b><small>(Optional)</small></label>
                                        <input type="url" class="form-control" wire:model="site_website"
                                            placeholder="Enter Website URL">
                                        @error('site_website')
                                            <span class="text-danger ml-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for =""><b>Site Meta
                                                Keywords</b><small>(Optional)</small></label>
                                        <input type="text" class="form-control" wire:model="site_meta_keywords"
                                            placeholder="Eg: ecommerce, free api, laravel">
                                        @error('site_meta_keywords')
                                            <span class="text-danger ml-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for =""><b>Site Description</b><small>(Optional)</small></label>
                                <textarea class="form-control" wire:model="site_description" cols="4" rows="3"
                                    placeholder="Enter a brief description about your company..."></textarea>
                                @error('site_description')
                                    <span class="text-danger ml-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for =""><b>Site Meta Description</b><small>(Optional)</small></label>
                                <textarea class="form-control" wire:model="site_meta_description" cols="4" rows="4"
                                    placeholder="Type site meta description ..."></textarea>
                                @error('site_meta_description')
                                    <span class="text-danger ml-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </form>
                    </div>
                </div>
                <div class="tab-pane fade {{ $tab == 'social_media' ? 'active show' : '' }}" id="social_media"
                    role="tabpanel">
                    <div class="pd-20">
                        <form wire:submit='updateSiteInfo()'>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for=""><b>Facebook URL</b><small>(Optional)</small></label>
                                        <input type="url" class="form-control" wire:model="facebook_url"
                                            placeholder="https://facebook.com/yourpage">
                                        @error('facebook_url')
                                            <span class="text-danger ml-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for=""><b>X (Twitter) URL</b><small>(Optional)</small></label>
                                        <input type="url" class="form-control" wire:model="twitter_url"
                                            placeholder="https://x.com/yourhandle">
                                        @error('twitter_url')
                                            <span class="text-danger ml-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for=""><b>Instagram URL</b><small>(Optional)</small></label>
                                        <input type="url" class="form-control" wire:model="instagram_url"
                                            placeholder="https://instagram.com/yourhandle">
                                        @error('instagram_url')
                                            <span class="text-danger ml-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for=""><b>LinkedIn URL</b><small>(Optional)</small></label>
                                        <input type="url" class="form-control" wire:model="linkedin_url"
                                            placeholder="https://linkedin.com/company/yourcompany">
                                        @error('linkedin_url')
                                            <span class="text-danger ml-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for=""><b>YouTube URL</b><small>(Optional)</small></label>
                                        <input type="url" class="form-control" wire:model="youtube_url"
                                            placeholder="https://youtube.com/channel/yourchannel">
                                        @error('youtube_url')
                                            <span class="text-danger ml-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for=""><b>WhatsApp Number</b><small>(Optional)</small></label>
                                        <input type="text" class="form-control" wire:model="whatsapp_number"
                                            placeholder="+1234567890">
                                        @error('whatsapp_number')
                                            <span class="text-danger ml-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for=""><b>Telegram Username</b><small>(Optional)</small></label>
                                        <input type="text" class="form-control" wire:model="telegram_username"
                                            placeholder="@yourusername">
                                        @error('telegram_username')
                                            <span class="text-danger ml-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </form>
                    </div>
                </div>
                <div class="tab-pane fade {{ $tab == 'logo_favicon' ? 'active show' : '' }}" id="logo_favicon"
                    role="tabpanel">
                    <div class="pd-20">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Site Logo</h6>
                                <div class="mb-2 mt-1" style="max-width: 200px">
                                    <img wire:ignore src="" alt="" class="img-thumbnail"
                                        data-ijabo-default-img="/images/site/{{ isset(settings()->site_logo) ? settings()->site_logo : '' }}"
                                        id="preview_site_logo">
                                </div>
                                <form action="{{ route('admin.update_logo') }}" method="post"
                                    enctype="multipart/form-data" id="updateLogoForm">
                                    @csrf
                                    <div class="mb-2">
                                        <input type="file" name="site_logo" id="" class="form-control">
                                        <span class="text-danger ml-1"></span>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Change Logo</button>
                                </form>
                            </div>
                            <div class="col-md-5">
                                <h6>Site Favicon</h6>
                                <div class="mb-2 mt-1" style="max-width: 200px">
                                    <img wire:ignore src="" alt="" class="img-thumbnail"
                                        data-ijabo-default-img="/images/site/{{ isset(settings()->site_favicon) ? settings()->site_favicon : '' }}"
                                        id="preview_site_favicon">
                                </div>
                                <form action="{{ route('admin.update_favicon') }}" method="post"
                                    enctype="multipart/form-data" id="updateFaviconForm">
                                    @csrf
                                    <div class="mb-2">
                                        <input type="file" name="site_favicon" id=""
                                            class="form-control">
                                        <span class="text-danger ml-1"></span>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Change Favicon</button>
                                </form>
                                <script>
                                    document.getElementById('updateFaviconForm').addEventListener('submit', function(e) {
                                        e.preventDefault();
                                        var formData = new FormData(this);

                                        fetch('{{ route('admin.update_favicon') }}', {
                                                method: 'POST',
                                                body: formData,
                                                headers: {
                                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                                        'content')
                                                }
                                            })
                                            .then(response => response.json())
                                            .then(data => {
                                                if (data.status == 1) {
                                                    // Update all favicon link elements
                                                    var linkElements = document.querySelectorAll('link[rel="icon"]');
                                                    linkElements.forEach(function(element) {
                                                        element.href = '/' + data.image_path;
                                                    });

                                                    // Update preview image
                                                    document.getElementById('preview_site_favicon').src = '/' + data.image_path;

                                                    alert(data.message);
                                                } else {
                                                    alert(data.message);
                                                }
                                            })
                                            .catch(error => {
                                                console.error('Error:', error);
                                                alert('An error occurred while updating favicon.');
                                            });
                                    });
                                </script>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
