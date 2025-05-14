<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;


class ReceiptController extends Controller
{
    public function download($id)
    {
        $sale = Sale::with(['customer', 'items', 'payments'])->findOrFail($id);
        $pdf = new PDF();
        
        $pdf = $pdf->loadView('receipts.download', compact('sale'));
        
        return $pdf->download('receipt-'.$sale->invoice_number.'.pdf');
    }
}
