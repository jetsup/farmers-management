<?php

namespace App\Livewire;

use Livewire\Component;

class NotificationsAdmin extends Component
{
    public function render()
    {
        return view('livewire.notifications-admin')->layout('layouts.admin')->title('Notifications');
    }
}
