<?php

namespace App\Livewire\Admin;

use Livewire\Component;
//use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Helpers\CMail;
use App\Models\UserSocialLink;
use App\Livewire\Admin\TopUserInfo;

class Profile extends Component
{
    public $tab = null;

    public $tabname = 'personal_details';

    protected $queryString = ['tab' => ['Keep' => true]];

    public $name, $email, $username, $bio;

    public $current_password, $new_password, $new_password_confirmation;

    public $facebook_url, $instagram_url, $youtube_url, $linkedin_url, $x_url, $github_url;

    protected $listeners = ['updateProfile' =>  '$refresh'];

    public function selectTab($tab)
    {
        $this->tab = $tab;
    }

    public function mount()
    {
        $this->tab = Request('tab') ? Request('tab') : $this->tabname;

        //Poppulate
        $user = Admin::findOrFail(Auth::id());
        $this->name = $user->name;
        $this->email = $user->email;
        $this->username = $user->username;
        $this->bio = $user->bio;

        //Populate Social Links Form
        if (!is_null($user->social_links)) {
            $this->facebook_url = $user->social_links->facebook_url;
            $this->instagram_url = $user->social_links->instagram_url;
            $this->youtube_url = $user->social_links->youtube_url;
            $this->linkedin_url = $user->social_links->linkedin_url;
            $this->x_url = $user->social_links->x_url;
            $this->github_url = $user->social_links->github_url;
        }
    }

    public function updatePersonalDetails()
    {
        $user = Admin::findOrFail(Auth::id());

        $this->validate([
            'name' => 'required',
            'username' => 'required|unique:admins,username,' . Auth::id(),
        ]);


        $user->name = $this->name;
        $user->username = $this->username;
        $user->bio = $this->bio;
        $updated = $user->save();

        sleep(0.5);
        if ($updated) {
            $this->dispatch('showToastr', [
                'type' => 'success',
                'message' => 'Your personal details has been updated successfully!.'
            ]);
            $this->dispatch('updateTopUserInfo')->to(TopUserInfo::class);
        } else {
            $this->dispatch('showToastr', [
                'type' => 'error',
                'message' => 'Something went wrong!'
            ]);
        }
    }
    public function updatePassword()
    {
        $user = Admin::findOrFail(Auth::id());

        $this->validate([
            'current_password' => [
                'required',
                'min:5',
                function ($attribute, $value, $fail) use ($user) {
                    if (!Hash::check($value, $user->password)) {
                        return $fail(__('Your current password does not match our records.'));
                    }
                }
            ],
            'new_password' => 'required|min:5|confirmed',
            //'new_password' => 'required|min:5|required_with:new_password_confirmation|same:new_password_confirmation',

            'new_password_confirmation' => 'required'
        ]);
        $updated = $user->update([
            'password' => Hash::make($this->new_password)
        ]);
        if ($updated) {

            $date = array(
                'user' => $user,
                'new_password' => $this->new_password
            );
            //password-change-template
            $mail_body = view('email-templates.password-change-template', $date)->render();

            $mail_config = array(
                'recipient_address' => $user->email,
                'recipient_name' => $user->name,
                'subject' => 'Password Changes',
                'body' => $mail_body,
            );
            CMail::send($mail_config);

            // logout and Redirect User to login page
            Auth::logout();
            Session::flash('info', 'Your password has been successfully changed , Plase login with your new password.');
            $this->redirectRoute('admin.login');
        } else {
            $this->dispatch('showToastr', ['type' => 'error', 'message' => 'Something went wrong!']);
        }
    }
    public function updateSocialLinks()
    {
        $this->validate([
            'facebook_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'youtube_url' => 'nullable|url',
            'linkedin_url' => 'nullable|url',
            'x_url' => 'nullable|url',
            'github_url' => 'nullable|url',
        ]);

        //Get User Details
        $user = Admin::findOrFail(Auth::id());
        $data = array(
            'facebook_url' => $this->facebook_url,
            'instagram_url' => $this->instagram_url,
            'youtube_url' => $this->youtube_url,
            'linkedin_url' => $this->linkedin_url,
            'x_url' => $this->x_url,
            'github_url' => $this->github_url,
        );

        if (!is_null($user->social_links)) {

            $query = $user->social_links()->update($data);
        } else {

            $data['user_id'] = $user->id;
            $query = UserSocialLink::insert($data);
        }
        if ($query) {
            $this->dispatch('showToastr', [
                'type' => 'success',
                'message' => 'Your social links have been updates successfully!.'
            ]);
        } else {
            $this->dispatch('showToastr', [
                'type' => 'error',
                'message' => 'Something went wrong!'
            ]);
        }
    }
    public function render()
    {
        return view('livewire.admin.profile', [
            'user' => Admin::findOrFail(Auth::id())
        ]);
    }
}
