<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;

class PDFController extends Controller
{
    public function generatePDF()
    {
        $farmer = (new FarmerController)->getFarmerData(auth()->user()->id, true);
        $deliveries = (new FarmerController)->getFarmersDeliveries(auth()->user()->id, 0);

        $total_amount = 0;
        $total_milk_amount = 0;

        foreach ($deliveries as $delivery) {
            $total_amount += $delivery->milk_capacity * $delivery->rate;
            $total_milk_amount += $delivery->milk_capacity;
        }

        $data = [
            'title' => "Farmer's Report",
            'farmer' => $farmer,
            'deliveries' => $deliveries,
            'total_amount' => $total_amount,
            'total_milk_amount' => $total_milk_amount,
        ];

        // dd($data);

        $pdf = PDF::loadView('report-preview', $data);
        $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        // generate filename using the current timestamp, Farmer's Name 2024-09-12_12:00:00.pdf
        $filename = auth()->user()->name . " " . date('Y-m-d_H:i:s') . '.pdf';

        return $pdf->download($filename);
    }
}
