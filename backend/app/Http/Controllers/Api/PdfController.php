<?php

namespace App\Http\Controllers\Api;

use Barryvdh\DomPDF\Facade\Pdf;

use App\Models\Venta;

use App\Http\Controllers\Controller;

class PdfController extends Controller
{

    public function ticket(
        $id
    )
    {

        $venta = Venta::with(

            'cliente',

            'detalles.producto',

            'user'

        )->findOrFail($id);

        $pdf = Pdf::loadView(

            'pdf.ticket',

            compact('venta')
        );

        return $pdf->download(
            'ticket.pdf'
        );
    }
}