<?php

namespace App\Models;
use App\Models\DetalleVenta;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Venta extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [

        'numero_venta',
        'cliente_id',
        'user_id',
        'subtotal',
        'igv',
        'total',
        'tipo_comprobante',
        'forma_pago',
        'monto_pagado',
        'vuelto',
        'estado'

    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function detalles()
    {
        return $this->hasMany(DetalleVenta::class);
    }
}