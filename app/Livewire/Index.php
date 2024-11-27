<?php

namespace App\Livewire;

use Livewire\Component;

class Index extends Component
{
    public int $number = 90;
    public function render()
    {
        return view('livewire.index');
    }
}
