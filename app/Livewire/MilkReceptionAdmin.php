<?php

namespace App\Livewire;

use App\Http\Controllers\MilkDeliveryController;
use App\Models\CowBreeds;
use App\Models\Farmer;
use App\Models\FarmerCows;
use App\Models\MilkDelivery;
use App\Models\Rate;
use Livewire\Component;
use Request;

class MilkReceptionAdmin extends Component
{
    public $farmer_id;
    public $rate;
    public $milk_capacity;
    public $is_paid;

    public function render()
    {
        $milk_delivery = MilkDelivery::join('farmers', 'milk_delivery.farmer_id', '=', 'farmers.id')
            ->join('rates', 'milk_delivery.rate_id', '=', 'rates.id')
            ->join('cow_breeds', 'rates.breed_id', '=', 'cow_breeds.id')
            ->join('users', 'farmers.user_id', '=', 'users.id')
            ->select('milk_delivery.*', 'users.name as farmer_name', 'cow_breeds.breed as breed_name')
            ->get();

        $cow_breeds = CowBreeds::all();

        $rates = Rate::select('rates.*', 'cow_breeds.breed as breed')
            ->join('cow_breeds', 'rates.breed_id', '=', 'cow_breeds.id')
            ->whereIn('rates.id', function ($query) {
                $query->selectRaw('MAX(id)')
                    ->from('rates')
                    ->groupBy('breed_id');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // dd($milk_delivery);

        return view('livewire.milk-reception-admin', [
            'milk_deliveries' => $milk_delivery,
            'rates' => $rates,
        ])->layout('layouts.admin')->title('Milk Reception');
    }

    public function receiveMilk()
    {
        // FIXME: This function needs optimization. Kinda lags
        $this->validate([
            'farmer_id' => 'required',
            'rate' => 'required',
            'milk_capacity' => 'required',
            'is_paid' => 'required',
        ]);


        $farmer_id = $this->farmer_id;
        $rate = $this->rate;
        $milk_capacity = $this->milk_capacity;
        $is_paid = $this->is_paid == 2;

        // check if the farmer id is genuine
        if (Farmer::find($farmer_id) == null) {
            session()->flash('error', 'Farmer not found');
            return;
        }

        // check if the farmer owns this breed
        $breed_id = Rate::find($rate)->breed_id;
        $farmer_cows = FarmerCows::where('farmer_id', $farmer_id)->get();

        $breed_found = false;
        foreach ($farmer_cows as $cow) {
            if ($cow->breed_id == $breed_id) {
                $breed_found = true;
                break;
            }
        }

        if (!$breed_found) {
            session()->flash('error', 'Farmer does not own this breed of cow');
            return;
        }

        $milk_delivery = new MilkDelivery();
        $milk_delivery->farmer_id = $farmer_id;
        $milk_delivery->rate_id = $rate;
        $milk_delivery->milk_capacity = $milk_capacity;
        $milk_delivery->is_paid = $is_paid;
        $milk_delivery->save();

        session()->flash('message', 'Milk received successfully');
    }
}
