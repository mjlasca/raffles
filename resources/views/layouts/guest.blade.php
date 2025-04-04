<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">        
    </head>
    <body class="font-sans text-gray-900 bg-gray-100 m-0 overflow-hidden">
        <div class="bg-red-500 text-white p-5 w-full text-center h4 fixed">
            ENTORNO DE PRUEBAS
        </div>
        <div class="flex items-center justify-center h-screen">
            <div class="md:w-3/5 flex items-center justify-center">
                <div class=" w-80">
                    <h2 class="text-blue-500 text-4xl"><b>Bienvenid@</b></h2>
                    <p>
                        Ingresa tu usuario y contraseña
                    </p>
                    @yield('content')
                </div>
            </div>
            <div class="hidden md:flex h-full w-2/5">
                <img class="w-full h-full" src="{{ asset('img/bg-login.png') }}" alt="Background login">
            </div>
        </div>
    </body>
</html>
