<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Receipt;
use Barryvdh\DomPDF\Facade\Pdf;

class ReceiptController extends Controller
{
    /**
     * Generate and download a PDF for a given receipt.
     */
    public function generatePDF($id)
    {
        $receipt = Receipt::with(['supplier', 'tireRequest.user', 'tireRequest.vehicle'])->findOrFail($id);

        // Load the PDF view and pass the receipt data
        // View path corrected to match resources/views/pdf/receipt.blade.php
        $pdf = Pdf::loadView('pdf.receipt', compact('receipt'));

        // Return the file for download
        return $pdf->download("receipt_{$id}.pdf");
    }
}
