<?php

namespace App\Exports;

use App\Models\Venta;

use Maatwebsite\Excel\Concerns\FromCollection;

class VentasExport implements FromCollection
{

    public function collection()
    {

        return Venta::select(

            'id',

            'numero_venta',

            'cliente_id',

            'subtotal',

            'igv',

            'total',

            'created_at'

        )->get();
    }
}