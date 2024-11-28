<?php

namespace App\Livewire;

use App\Http\Controllers\FarmerController;
use App\Models\MilkDelivery;
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

    public function payFarmer($record_id)
    {
        MilkDelivery::where('id', $record_id)->update(['is_paid' => 1]);
        session()->flash('message', 'Farmer paid successfully.');
    }
}
