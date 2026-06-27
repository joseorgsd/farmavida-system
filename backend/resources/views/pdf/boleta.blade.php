<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <title>Boleta</title>

    <style>

        body{
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .container{
            width: 100%;
        }

        .title{
            text-align: center;
            font-size: 22px;
            font-weight: bold;
        }

        table{
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td{
            border: 1px solid black;
        }

        th, td{
            padding: 8px;
            text-align: center;
        }

        .totales{
            margin-top: 20px;
            text-align: right;
        }

    </style>

</head>

<body>

<div class="container">

    <div class="title">

        FARMACIA SYSTEM

    </div>

    <hr>

    <p>

        <strong>N° Venta:</strong>
        {{ $venta->numero_venta }}

    </p>

    <p>

        <strong>Cliente:</strong>
        {{ $venta->cliente->nombre }}

    </p>

    <p>

        <strong>Fecha:</strong>
        {{ $venta->created_at }}

    </p>

    <table>

        <thead>

        <tr>

            <th>Producto</th>

            <th>Cantidad</th>

            <th>Precio</th>

            <th>Subtotal</th>

        </tr>

        </thead>

        <tbody>

        @foreach($venta->detalles as $detalle)

            <tr>

                <td>
                    {{ $detalle->producto->nombre }}
                </td>

                <td>
                    {{ $detalle->cantidad }}
                </td>

                <td>
                    S/ {{ number_format($detalle->precio, 2) }}
                </td>

                <td>
                    S/ {{ number_format($detalle->subtotal, 2) }}
                </td>

            </tr>

        @endforeach

        </tbody>

    </table>

    <div class="totales">

        <p>

            <strong>Subtotal:</strong>

            S/ {{ number_format($venta->subtotal, 2) }}

        </p>

        <p>

            <strong>IGV 18%:</strong>

            S/ {{ number_format($venta->igv, 2) }}

        </p>

        <p>

            <strong>Total:</strong>

            S/ {{ number_format($venta->total, 2) }}

        </p>

    </div>

</div>

</body>
</html>