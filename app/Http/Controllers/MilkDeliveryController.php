<?php

namespace App\Http\Controllers;

use App\Models\Farmer;
use App\Models\MilkDelivery;
use Illuminate\Http\Request;

class MilkDeliveryController extends Controller
{
    public static function receiveMilk(Request $request)
    {
        $request->validate([
            'farmer-id' => 'required',
            'breed' => 'required',
            'milk-capacity' => 'required',
            'is-paid' => 'required',
        ]);

        $farmer_id = $request->farmer_id;
        $breed_id = $request->breed;
        $milk_capacity = $request->milk_capacity;
        $is_paid = $request->is_paid == 2;


        $farmer = Farmer::find($farmer_id);
        if (!$farmer) {
            return response()->json(['message' => 'Farmer not found'], 404);
        }

        $milk = new MilkDelivery();

        $milk->farmer_id = $farmer_id;
        $milk->breed_id = $breed_id;
        $milk->milk_capacity = $milk_capacity;
        $milk->is_paid = $is_paid;
        $milk->save();

        return redirect()->back()->with('success', 'Milk delivery received successfully');
    }
}
