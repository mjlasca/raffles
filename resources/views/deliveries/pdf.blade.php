
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibo de Caja No. {{ $delivery->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        
        .recibo {
            border-bottom: 1px solid #ccc;
            padding: 10px;
        }
        .encabezado {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
        }
        .detalles {
            margin-top: 20px;
        }
        .detalle {
            display: flex;
            margin-bottom: 10px;
        }
        .detalle span {
            font-weight: bold;
            margin-right: 10px
        }
        .detalle p {
            margin-right: 10px
        }
        .firma {
            margin-top: 30px;
            text-align: center;
        }
        .d-table{
            display: table;
        }
        .d-cell{
            display: table-cell;
        }
        .space{
            color: #fff;
        }
    </style>
</head>
<body>
    
<div class="content">
    <div class="recibo">
        <div class="encabezado">
            Recibo de Caja No. {{ $delivery->id }}
        </div>
        <div class="detalles">
            <div class="detalle d-table">
                <div class="d-cell"><span>Fecha entrega:</span>{{ $delivery->created_at }}</div>
                <div class="d-cell"><span class="space">---------------------------</span><span>Monto:</span> ${{ number_format($delivery->total, 2, ',', '.') }}</div>
            </div>
            <div class="detalle">
                <span>Vendedor(a):</span>{{ $delivery->user->name }} {{ $delivery->user->lastname }}
            </div>
            <div class="detalle d-table">
                <div class="d-cell"><span>Teléfono:</span>{{ $delivery->user->phone }}</div>
                <div class="d-cell"><span class="space">-</span><span>Dirección:</span> {{ $delivery->user->address }}</div>
            </div>
            <div class="detalle">
                <span>Concepto:</span>{{ $delivery->description }}
            </div>
            <div class="detalle">
                
            </div>
            <!-- Agrega más detalles según sea necesario -->
        </div>
        
        <div class="firma">
            ___________________________<span class="space">-----------------</span>___________________________________<br>
            <span class="space">--</span>Firma vendedor<span class="space">-----------------------------</span>Firma recibe({{ $delivery->rcreate->name }} {{ $delivery->rcreate->lastname }})
            
        </div>
        
    </div>
    <div class="recibo">
        <div class="encabezado">
            Recibo de Caja No. {{ $delivery->id }}
        </div>
        <div class="detalles">
            <div class="detalle d-table">
                <div class="d-cell"><span>Fecha entrega:</span>{{ $delivery->created_at }}</div>
                <div class="d-cell"><span class="space">---------------------------</span><span>Monto:</span> ${{ number_format($delivery->total, 2, ',', '.') }}</div>
            </div>
            <div class="detalle">
                <span>Vendedor(a):</span>{{ $delivery->user->name }} {{ $delivery->user->lastname }}
            </div>
            <div class="detalle d-table">
                <div class="d-cell"><span>Teléfono:</span>{{ $delivery->user->phone }}</div>
                <div class="d-cell"><span class="space">-</span><span>Dirección:</span> {{ $delivery->user->address }}</div>
            </div>
            <div class="detalle">
                <span>Concepto:</span>{{ $delivery->description }}
            </div>
            <div class="detalle">
                
            </div>
            <!-- Agrega más detalles según sea necesario -->
        </div>
        
        <div class="firma">
            ___________________________<span class="space">-----------------</span>___________________________________<br>
            <span class="space">--</span>Firma vendedor<span class="space">-----------------------------</span>Firma recibe({{ $delivery->rcreate->name }} {{ $delivery->rcreate->lastname }})
            
        </div>
        
    </div>
    
</div>


</body>
</html>
