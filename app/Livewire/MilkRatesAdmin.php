<?php

namespace App\Livewire;

use App\Models\CowBreeds;
use App\Models\Rate;
use Livewire\Component;

class MilkRatesAdmin extends Component
{
    public $breed;
    public $rate;

    public function render()
    {
        $rates = Rate::select('rates.*', 'cow_breeds.breed as breed')
            ->join('cow_breeds', 'rates.breed_id', '=', 'cow_breeds.id')
            ->whereIn('rates.id', function ($query) {
                $query->selectRaw('MAX(id)')
                    ->from('rates')
                    ->groupBy('breed_id');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // dd($rates, count($rates));

        $breeds = CowBreeds::all();

        return view('livewire.milk-rates-admin', [
            'rates' => $rates,
            'breeds' => $breeds,
        ])->layout('layouts.admin')->title('Milk Rates');
    }

    /**
     * Create a new rate
     */
    public function createRate()
    {
        $this->validate([
            'breed' => 'required',
            'rate' => 'required',
        ]);

        $rate = new Rate();
        $rate->breed_id = $this->breed;
        $rate->rate = $this->rate;
        $rate->save();

        $this->rate = '';

        session()->flash('message', 'Rate added successfully.');
    }
}
