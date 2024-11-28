<?php

namespace App\Http\Controllers;

use App\Models\CowBreeds;
use Illuminate\Http\Request;

class CowBreedsController extends Controller
{
    public function createBreed(Request $request)
    {
        $request->validate([
            'breed' => 'required',
        ]);

        $req_breed = strtolower($request->breed);

        $breed = CowBreeds::where('breed', $req_breed)->first();
        if ($breed) {
            return redirect()->back()->with('error', 'Breed already exists');
        }

        $breed = new CowBreeds();

        $breed->breed = $req_breed;
        $breed->save();

        return redirect()->back()->with('success', 'Breed created successfully');
    }
}
