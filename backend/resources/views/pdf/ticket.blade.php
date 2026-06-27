<!DOCTYPE html>

<html>

<head>

    <meta charset="UTF-8">

    <style>

        body {

            font-family: Arial;

            font-size: 12px;
        }

        table {

            width: 100%;

            border-collapse: collapse;
        }

        th, td {

            border-bottom: 1px solid #ddd;

            padding: 6px;
        }

    </style>

</head>

<body>

    <center>

        <h2>FARMAVIDA</h2>

        <p>
            Ticket de Venta
        </p>

    </center>

    <hr>

    <p>

        <strong>Cliente:</strong>

        {{ $venta->cliente->nombre }}

    </p>

    <p>

        <strong>Comprobante:</strong>

        {{ $venta->tipo_comprobante }}

    </p>

    <p>

        <strong>Fecha:</strong>

        {{ $venta->created_at }}

    </p>

    <table>

        <thead>

            <tr>

                <th>
                    Producto
                </th>

                <th>
                    Cant
                </th>

                <th>
                    Precio
                </th>

                <th>
                    Sub
                </th>

            </tr>

        </thead>

        <tbody>

            @foreach(
                $venta->detalles
                as $item
            )

            <tr>

                <td>

                    {{ $item->producto->nombre }}

                </td>

                <td>

                    {{ $item->cantidad }}

                </td>

                <td>

                    {{ $item->precio_venta }}

                </td>

                <td>

                    {{ $item->subtotal }}

                </td>

            </tr>

            @endforeach

        </tbody>

    </table>

    <hr>

    <h3>

        TOTAL:
        S/ {{ $venta->total }}

    </h3>

</body>

</html>