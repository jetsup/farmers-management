<?php

namespace App\Livewire;

use App\Http\Controllers\FarmerController;
use App\Http\Controllers\PDFController;
use Livewire\Component;

class ReportsFarmer extends Component
{
    public function render()
    {
        $deliveries = (new FarmerController)->getFarmersDeliveries(auth()->user()->id, 0);
        return view('livewire.reports-farmer', [
            'deliveries' => $deliveries
        ])->layout('layouts.farmer')->title('Reports');
    }

    public function download()
    {
        // create a downloadable PDF of the report of the current farmer
        return (new PDFController)->generatePDF();
    }
}
