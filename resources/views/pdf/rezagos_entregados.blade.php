<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Rezagos Entregados</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h2 { margin: 0 0 6px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border: 1px solid #000; padding: 4px; font-size: 11px; }
        th { background: #eee; }
        .meta { margin-top: 6px; }
    </style>
</head>
<body>
    <h2>Rezagos Entregados</h2>
    <div class="meta">
        <strong>Fecha de entrega:</strong> {{ $fecha }}<br>
        <strong>Entregado por:</strong> {{ $usuario }}
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Código</th>
                <th>Destinatario</th>
                <th>Teléfono</th>
                <th>Peso</th>
                <th>Aduana</th>
                <th>Zona</th>
                <th>Tipo</th>
                <th>Ciudad</th>
                <th>Estado anterior</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $i => $r)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $r->codigo }}</td>
                    <td>{{ $r->destinatario }}</td>
                    <td>{{ $r->telefono }}</td>
                    <td>{{ $r->peso }}</td>
                    <td>{{ $r->aduana }}</td>
                    <td>{{ $r->zona }}</td>
                    <td>{{ $r->tipo }}</td>
                    <td>{{ $r->ciudad }}</td>
                    <td>REZAGO</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
