<!DOCTYPE html>

<html>

<head>

    <meta charset="utf-8">

    <title>Reporte Ventas</title>

</head>

<body>

    <h1>Reporte de Ventas</h1>

    <table border="1" width="100%">

        <thead>

            <tr>

                <th>ID</th>
                <th>Número</th>
                <th>Total</th>
                <th>Fecha</th>

            </tr>

        </thead>

        <tbody>

            @foreach($ventas as $venta)

            <tr>

                <td>{{ $venta->id }}</td>

                <td>{{ $venta->numero_venta }}</td>

                <td>{{ $venta->total }}</td>

                <td>{{ $venta->created_at }}</td>

            </tr>

            @endforeach

        </tbody>

    </table>

</body>

</html>