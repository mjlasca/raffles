<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Permiso solicitado</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial;
            background: #f6f6f6;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }
        h1 { font-size: 20px; margin-bottom: 12px; color: #111827; }
        p { margin: 8px 0; color: #374151; }
        .label { font-weight: 600; color: #111827; display: inline-block; width: 150px; }
        .card { background: #f9fafb; padding: 12px; border-radius: 6px; margin: 12px 0; }
        .button {
            display: inline-block;
            padding: 12px 18px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
        }
        .btn-primary { background: #2563eb; color: #ffffff !important; }
        .footer { font-size: 12px; color: #6b7280; margin-top: 18px; }
        @media (max-width:480px){
            .container { padding: 12px; }
            .label { display:block; width:auto; margin-bottom:4px; }
        }
    </style>
</head>
<body>
<div class="container">

    <h1>Permiso solicitado</h1>

    <div class="card">
        <p>
            <span class="label">Usuario que solicita:</span>
            {{ $user }}
        </p>

        <p>
            <span class="label">Rifa:</span>
            {{ $raffle }}
            
        </p>

        <p>
            <span class="label">Entrega:</span>
            {{ $delivery }}
            
        </p>

        <p>
            <span class="label">Fecha solicitud:</span>
            @if (!empty($date_permission))
                {{ Carbon\Carbon::parse($date_permission)->format('d/m/Y') }}
            @endif
        </p>
    </div>

    <p>Para otorgar el permiso haz clic en el siguiente enlace:</p>

    <p>
        <a href="{{ $url }}" class="button btn-primary">Dar permiso</a>
    </p>

    <p class="footer">
        Este es un mensaje automático, por favor no responda a este correo.
    </p>

</div>
</body>
</html>
