<?php

namespace App\Livewire;

use App\Models\CowBreeds;
use Livewire\Component;

class CowBreedsAdmin extends Component
{
    public function render()
    {
        $breeds = CowBreeds::all();
        
        return view('livewire.cow-breeds-admin', [
            'breeds' => $breeds,
        ])->layout('layouts.admin')->title('Cow Breeds');
    }
}
