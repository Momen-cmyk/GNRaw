<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Admin;


class TopUserInfo extends Component
{
    protected $listeners = ['updateTopUserInfo' =>  '$refresh'];

    public function render()
    {
        return view('livewire.admin.top-user-info', [
            'user' => Admin::findOrFail(auth()->id())
        ]);
    }
}
