<div>
    <div class="row">
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-30">
            <div class="pd-20 card-box height-100-p">
                <div class="profile-photo">
                    <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#profilePictureModal"
                        class="edit-avatar"><i class="fa fa-pencil"></i></a>
                    <img src="{{ $user->picture }}" alt="" class="avatar-photo" id ="profilePicturePreview">
                </div>
                <h5 class="text-center h5 mb-0">{{ $user->name }}</h5>
                <p class="text-center text-muted font-14">
                    {{ $user->email }}
                </p>

                <div class="profile-social">
                    <h5 class="mb-20 h5 text-blue">Social Links</h5>
                    <ul class="clearfix">
                        @if ($user->social_links)
                            @if ($user->social_links->facebook_url)
                                <li>
                                    <a href="{{ $user->social_links->facebook_url }}" target="_blank" class="btn"
                                        data-bgcolor="#3b5998" data-color="#ffffff"
                                        style="color: rgb(255, 255, 255); background-color: rgb(59, 89, 152);"><i
                                            class="fa fa-facebook"></i></a>
                                </li>
                            @endif
                            @if ($user->social_links->x_url)
                                <li>
                                    <a href="{{ $user->social_links->x_url }}" target="_blank" class="btn"
                                        data-bgcolor="#1da1f2" data-color="#ffffff"
                                        style="color: rgb(255, 255, 255); background-color: rgb(29, 161, 242);"><i
                                            class="fa fa-twitter"></i></a>
                                </li>
                            @endif
                            @if ($user->social_links->linkedin_url)
                                <li>
                                    <a href="{{ $user->social_links->linkedin_url }}" target="_blank" class="btn"
                                        data-bgcolor="#007bb5" data-color="#ffffff"
                                        style="color: rgb(255, 255, 255); background-color: rgb(0, 123, 181);"><i
                                            class="fa fa-linkedin"></i></a>
                                </li>
                            @endif
                            @if ($user->social_links->instagram_url)
                                <li>
                                    <a href="{{ $user->social_links->instagram_url }}" target="_blank" class="btn"
                                        data-bgcolor="#f46f30" data-color="#ffffff"
                                        style="color: rgb(255, 255, 255); background-color: rgb(244, 111, 48);"><i
                                            class="fa fa-instagram"></i></a>
                                </li>
                            @endif
                            @if ($user->social_links->youtube_url)
                                <li>
                                    <a href="{{ $user->social_links->youtube_url }}" target="_blank" class="btn"
                                        data-bgcolor="#ff0000" data-color="#ffffff"
                                        style="color: rgb(255, 255, 255); background-color: rgb(255, 0, 0);"><i
                                            class="fa fa-youtube"></i></a>
                                </li>
                            @endif
                            @if ($user->social_links->github_url)
                                <li>
                                    <a href="{{ $user->social_links->github_url }}" target="_blank" class="btn"
                                        data-bgcolor="#333" data-color="#ffffff"
                                        style="color: rgb(255, 255, 255); background-color: rgb(51, 51, 51);"><i
                                            class="fa fa-github"></i></a>
                                </li>
                            @endif
                        @else
                            <li class="text-muted">No social links added yet</li>
                        @endif
                    </ul>
                </div>


            </div>
        </div>
        <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 mb-30">
            <div class="card-box height-100-p overflow-hidden">
                <div class="profile-tab height-100-p">
                    <div class="tab height-100-p">
                        <ul class="nav nav-tabs customtab" role="tablist">
                            <li class="nav-item">
                                <a wire:click="selectTab('personal_details')"
                                    class="nav-link {{ $tab == 'personal_details' ? 'active' : '' }}" data-toggle="tab"
                                    href="#personal_details" role="tab">Personal Details</a>
                            </li>
                            <li class="nav-item">
                                <a wire:click="selectTab('update_password')"
                                    class="nav-link {{ $tab == 'update_password' ? 'active' : '' }}" data-toggle="tab"
                                    href="#update_password" role="tab">Update
                                    Password</a>
                            </li>
                            <li class="nav-item">
                                <a wire:click="selectTab('social_links')"
                                    class="nav-link {{ $tab == 'social_links' ? 'active' : '' }}" data-toggle="tab"
                                    href="#social_links" role="tab">Social
                                    Links</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade {{ $tab == 'personal_details' ? 'show active' : '' }}"
                                id="personal_details" role="tabpanel">
                                <div class="pd-20">
                                    <form wire:submit="updatePersonalDetails()">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="">Full Name</label>
                                                    <input type="text" class="form-control" wire:model="name"
                                                        placeholder="Enter full name">
                                                    @error('name')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="">Email</label>
                                                    <input type="text" class="form-control" wire:model="email"
                                                        placeholder="Enter email address" disabled>
                                                    @error('email')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="">Username</label>
                                                    <input type="text" class="form-control" wire:model="username"
                                                        placeholder="Enter UserName">
                                                    @error('username')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="">Bio</label>
                                                    <textarea wire:model="bio" cols="4" rows="4" class="form-control" placeholder="Enter your bio...."></textarea>
                                                    @error('bio')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group mt-3">
                                            <button type="submit" class="btn btn-primary"> Save Changes </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="tab-pane fade {{ $tab == 'update_password' ? 'show active' : '' }}"
                                id="update_password" role="tabpanel">
                                <div class="pd-20 profile-task-wrap">

                                    <form wire:submit="updatePassword">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="">Current Password</label>
                                                    <input type="password" class="form-control"
                                                        wire:model="current_password"
                                                        placeholder="Enter current password">
                                                    @error('current_password')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="">New Password</label>
                                                    <input type="password" class="form-control"
                                                        wire:model="new_password" placeholder="Enter new password">
                                                    @error('new_password')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="">Confirm New Password</label>
                                                    <input type="password" class="form-control"
                                                        wire:model="new_password_confirmation"
                                                        placeholder="Enter confirm new password">
                                                    @error('new_password_confirmation')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-primary"> Save Changes
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="tab-pane fade {{ $tab == 'social_links' ? 'show active' : '' }}"
                                id="social_links" role="tabpanel">
                                <div class="pd-20 profile-task-wrap">
                                    <form method="post" wire:submit="updateSocialLinks()">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="">Facebook</label>
                                                    <input type="text" class="form-control"
                                                        wire:model="facebook_url" placeholder="facebook URL">
                                                    @error('facebook_url')
                                                        <span class="text-danger ml-1">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="">Instagram</label>
                                                    <input type="text" class="form-control"
                                                        wire:model="instagram_url" placeholder="Instagram URL">
                                                    @error('instagram_url')
                                                        <span class="text-danger ml-1">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="">Youtube</label>
                                                    <input type="text" class="form-control"
                                                        wire:model="youtube_url" placeholder="Youtube URL">
                                                    @error('youtube_url')
                                                        <span class="text-danger ml-1">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="">LinkedIn</label>
                                                    <input type="text" class="form-control"
                                                        wire:model="linkedin_url" placeholder="LinkedIn URL">
                                                    @error('linkedin_url')
                                                        <span class="text-danger ml-1">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="">X</label>
                                                    <input type="text" class="form-control" wire:model="x_url"
                                                        placeholder="X URL">
                                                    @error('x_url')
                                                        <span class="text-danger ml-1">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="">Github</label>
                                                    <input type="text" class="form-control"
                                                        wire:model="github_url" placeholder="Github URL">
                                                    @error('github_url')
                                                        <span class="text-danger ml-1">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </form>
                                </div>
                            </div>

                            <!-- Setting Tab End -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Picture Modal -->
    <div class="modal fade" id="profilePictureModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Change Profile Picture</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="profilePictureForm" action="{{ route('admin.admin.upload_profile_picture') }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Select New Picture</label>
                            <input type="file" class="form-control" name="profilePictureFile" accept="image/*"
                                required>
                            <div class="form-text">Supported formats: JPEG, PNG, JPG, GIF. Max size: 2MB</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Upload Picture</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
