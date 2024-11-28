<?php

namespace App\Livewire;

use App\Http\Controllers\FarmerController;
use App\Models\Farmer;
use App\Models\FarmerCows;
use DB;
use Livewire\Component;

class FarmersAdmin extends Component
{
    public function render()
    {
        $farmers = Farmer::join('users', 'users.id', '=', 'farmers.user_id')
            ->select('users.name AS farmer_name', 'farmers.*')
            ->addSelect(DB::raw('(SELECT COUNT(DISTINCT breed_id) FROM farmer_cows WHERE farmer_id = farmers.id) as breed_count'))
            ->get();

        // dd($farmers);

        return view('livewire.farmers-admin', [
            'farmers' => $farmers,
        ])->layout('layouts.admin')->title('Farmers');
    }
}
