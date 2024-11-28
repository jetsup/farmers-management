<?php

namespace App\Livewire;

use App\Models\CowBreeds;
use Livewire\Component;

class CowBreedsAdmin extends Component
{
    public $breed_name;
    public function render()
    {
        $breeds = CowBreeds::all();

        return view('livewire.cow-breeds-admin', [
            'breeds' => $breeds,
        ])->layout('layouts.admin')->title('Cow Breeds');
    }

    public function createBreed()
    {
        $this->validate([
            'breed_name' => 'required|string|min:3',
        ]);

        $req_breed = strtolower($this->breed_name);

        $breed = CowBreeds::where('breed', $req_breed)->first();
        if ($breed) {
            session()->flash('error', 'Breed already exists');
        }

        $breed = new CowBreeds();

        $breed->breed = $req_breed;
        $breed->save();

        $this->breed_name = '';
        session()->flash('success', 'Breed created successfully');
    }
}
