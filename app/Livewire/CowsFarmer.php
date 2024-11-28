<?php

namespace App\Livewire;

use App\Models\CowBreeds;
use App\Models\FarmerCows;
use Livewire\Component;

class CowsFarmer extends Component
{
    public $name;
    public $breed;

    public function render()
    {
        $cows = FarmerCows::join('cow_breeds', 'cow_breeds.id', '=', 'farmer_cows.breed_id')
            ->select('cow_breeds.*', 'farmer_cows.*')
            ->get();


        $breeds = CowBreeds::all();
        // dd($cows, $breeds);

        return view('livewire.cows-farmer', [
            'cows' => $cows,
            'breeds' => $breeds,
        ])->layout('layouts.farmer')->title('Cows');
    }

    public function deleteCow($id)
    {
        FarmerCows::where('id', $id)->delete();
        session()->flash('message', 'Cow deleted successfully.');
    }

    public function addCow()
    {
        $this->validate([
            'breed' => 'required',
            'name' => 'required',
        ]);

        // check if cow already exists
        $cow = FarmerCows::where('name', $this->name)
            ->where('farmer_id', '=', auth()->user()->id)
            ->where('breed_id', '=', $this->breed)
            ->first();

        if ($cow) {
            session()->flash('error', 'Cow already exists.');
            return;
        }

        $farmer_cow = new FarmerCows();
        $farmer_cow->name = strtolower($this->name);
        $farmer_cow->farmer_id = auth()->user()->id;
        $farmer_cow->breed_id = $this->breed;
        $farmer_cow->save();

        $this->name = '';
        $this->breed = '';

        session()->flash('message', 'Cow added successfully.');
    }
}
