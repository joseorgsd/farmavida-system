<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Exports\VentasExport;

use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

use App\Models\Venta;
class ReporteController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | EXPORTAR EXCEL
    |--------------------------------------------------------------------------
    */

    public function ventasExcel()
    {

        return Excel::download(

            new VentasExport,

            'ventas.xlsx'

        );
    }
    public function ventasPdf()
    {

        $ventas = Venta::all();

        $pdf = Pdf::loadView(

            'pdf.ventas',

            compact('ventas')

        );

        return $pdf->download(

            'ventas.pdf'

        );
    }
}