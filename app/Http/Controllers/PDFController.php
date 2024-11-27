<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PDFController extends Controller
{
    public function generatePDF()
    {
        // Load data from database
        $data = [
            'title' => 'Welcome to Laravel PDF',
            'date' => date('m/d/Y')
        ];

        $pdf = PDF::loadView('report', $data);
        // generate filename using the current timestamp, Download_2024-09-12_12:00:00.pdf
        $filename = 'Download_' . date('Y-m-d_H:i:s') . '.pdf';
        return $pdf->download($filename);
    }
}
