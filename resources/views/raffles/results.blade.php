<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Resultados</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>



        @media (prefers-color-scheme: dark) {
            body,form,input,select,textarea,label    {
                background-color: #2c3e50 !important;
                color: #ecf0f1 !important;
            }
            .bg-gray-100, .bg-white{
                background-color: #2c3e50 !important;
            }

            table tr{
                background-color: #2c3e50 !important;
            }

            

        }
    </style>
</head>
<body class="">
    <div class="container mx-auto p-6">
        <section class="bg-pearl">
            <div class="container py-16">
              <div class="flex">
                <div class="relative inline-flex items-center m-auto align-middle">
                  <div class="max-w-3xl space-y-8 lg:text-center">
                    <h1 data-animate="title" class="text-green-500 mb-4 text-4xl font-bold leading-none lg:text-6xl" style="opacity: 1; transform: translateY(0px);">
                      <span class="bg-clip-text bg-gradient-to-r from-heart to-picton upper">Resultados </span>
                      de los sorteos
                    </h1>
                  </div>
                </div>
              </div>
            </div>
          </section>
    
    <!-- Mostrar el último resultado -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-4">
        <h2 class="text-xl font-bold mb-2">Último Resultado</h2>
        <div class="flex items-center justify-between">
        <p class="text-lg">Ganador: Juan Pérez</p>
        <p class="text-lg">Premio: $1000</p>
        </div>
    </div>
    <!-- Mostrar rifas vigentes -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-4">
        <h2 class="text-xl font-bold mb-2">Rifas vigentes</h2>
        <ul>
            <li class="mb-2">Rifa tuluá juega el 20 de abril 2024</li>
            <!-- Agrega más resultados anteriores según sea necesario -->
        </ul>
    </div>
    <!-- Mostrar resultados anteriores -->
    <div class="bg-white rounded-lg shadow-md p-4">
        <h2 class="text-xl font-bold mb-2">Resultados Anteriores</h2>
        <ul>
        <li class="mb-2">Sorteo 1: Ganador - María López, Premio - $800</li>
        <li class="mb-2">Sorteo 2: Ganador - Carlos García, Premio - $1200</li>
        <li class="mb-2">Sorteo 3: Ganador - Ana Martínez, Premio - $950</li>
        <!-- Agrega más resultados anteriores según sea necesario -->
        </ul>
    </div>
    </div>    
</body>


</html>