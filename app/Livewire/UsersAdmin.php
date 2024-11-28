<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class UsersAdmin extends Component
{
    public function render()
    {
        $users = User::all();
        
        return view('livewire.users-admin', [
            'users' => $users,
        ])->layout('layouts.admin')->title('Users');
    }
}
