<?php

namespace App\Repositories;

use App\Models\Venta;

use App\Interfaces\VentaRepositoryInterface;

class VentaRepository implements VentaRepositoryInterface
{
    public function crearVenta(array $data)
    {
        return Venta::create($data);
    }
}