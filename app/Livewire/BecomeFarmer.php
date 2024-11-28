<?php

namespace App\Livewire;

use Livewire\Component;

class BecomeFarmer extends Component
{
    public function render()
    {
        return view('livewire.become-farmer')->layout('layouts.app')->title('Become a Farmer');
    }
}
