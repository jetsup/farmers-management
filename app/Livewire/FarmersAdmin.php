<?php

namespace App\Livewire;

use App\Http\Controllers\FarmerController;
use Livewire\Component;

class FarmersAdmin extends Component
{
    public function render()
    {
        $farmers = (new FarmerController())->getFarmers();

        return view('livewire.farmers-admin', [
            'farmers' => $farmers,
        ])->layout('layouts.admin')->title('Farmers');
    }
}
