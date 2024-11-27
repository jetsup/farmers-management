<?php

namespace App\Livewire;

use App\Http\Controllers\FarmerController;
use Livewire\Component;

class RecordsAdmin extends Component
{
    public function render()
    {
        $farmers_records = (new FarmerController())->getFarmersRecords();
        return view('livewire.records-admin', [
            'farmers_records' => $farmers_records,
        ])->layout('layouts.admin')->title('Records');
    }
}
