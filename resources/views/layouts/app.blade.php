<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <title>{{ config('app.name', 'Rifas') }}</title>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/base.js') }}" defer></script>


    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        @media (prefers-color-scheme: dark) {

            body,
            form,
            input,
            select,
            textarea,
            label,
            .select2-container,
            .select2-results,
            .select2-selection--single,
            .select2-selection__rendered {
                background-color: #2c3e50 !important;
                color: #ecf0f1 !important;
            }

            .bg-gray-100,
            .bg-white {
                background-color: #2c3e50 !important;
            }

            table tr {
                background-color: #2c3e50 !important;
            }
        }

        .select2-container,
        .select2-selection--single {
            box-sizing: border-box;
            margin: 0;
            position: relative;
            vertical-align: middle;
            display: block !important;
            width: 100% !important;
            border-radius: 0.375rem !important;
            max-height: 38px !important;
            min-width: 270px;
        }

        .select2-container .select2-selection--single {
            height: auto !important;
        }

        .select2-selection__rendered {
            padding: 0.5rem 1rem !important;
            line-height: none !important;
        }

        @media (max-width: 630px) {
            .sidebar-raffle {
                background-color: #ffffffde;
                position: fixed;
                width: 100%;
            }

            .sidebar-raffle img {
                max-width: 150px;
            }

            .nav-raffle {
                width: 90%;
                height: 100%;
                overflow-y: auto;
            }

            .nav-raffle nav {
                overflow-y: auto;
            }

            .close-nav {
                position: fixed;
                left: 10px;
                bottom: 10px;
            }
        }
    </style>
</head>

<body>
    <div class="flex">
        <!--Sidebar-->
        <div class="sidebar-raffle sm:flex h-screen bg-gray-800 hidden">

            <div class="nav-raffle w-64 bg-red-500">
                <div class="bg-blue-500 py-4 h-40 flex flex-col items-center">
                    <img src="{{ asset('img/logo.webp') }}" alt="Logo" class="w-2/4">
                </div>
                <nav class="mt-8 tx-blue-700">
                    <ul class="flex flex-col items-center text-white">

                        @if (auth()->user()->role === 'Administrador' || auth()->user()->role === 'Secretaria')
                            <li class="mb-2">
                                <a href="/usuarios"
                                    class="inline-flex @if (str_contains(request()->path(), 'usuarios')) bg-green-500 @else bg-blue-500 @endif items-center px-3 py-3 border  text-sm font-lg rounded-md  hover:bg-green-500 hover:text-white focus:outline-none transition ease-in-out duration-150 w-40">
                                    <span class="mr-2 p-1 rounded-md bg-blue-500">
                                        <img class="h-5 fill-white" src="{{ asset('img/icons/group-icon.svg') }}"
                                            alt="">
                                    </span>
                                    <p class="mt-1">
                                        Usuarios
                                    </p>
                                </a>
                            </li>
                        @endif

                        <li class="mb-2">
                            <a href="/"
                                class="inline-flex  @if (request()->path() == '/') bg-green-500 @else bg-blue-500 @endif items-center px-3 py-3 border  text-sm font-lg rounded-md  hover:bg-green-500 hover:text-white focus:outline-none transition ease-in-out duration-150 w-40">
                                <span class="mr-2 p-1 rounded-md bg-blue-500">
                                    <img class="h-5 fill-white" src="{{ asset('img/icons/tile-icon.svg') }}"
                                        alt="">
                                </span>
                                <p class="mt-1">
                                    Dashboard
                                </p>
                            </a>
                        </li>

                        @if (auth()->user()->role === 'Administrador' || auth()->user()->role === 'Secretaria')
                            <li class="mb-2">
                                <a href="/rifas"
                                    class="inline-flex items-center @if (str_contains(request()->path(), 'rifas')) bg-green-500 @else bg-blue-500 @endif  px-3 py-3 border  text-sm font-lg rounded-md  hover:bg-green-500 hover:text-white focus:outline-none transition ease-in-out duration-150 w-40">
                                    <span class="mr-2 p-1 rounded-md bg-blue-500">
                                        <img class="h-5 fill-white" src="{{ asset('img/icons/switch-icon.svg') }}"
                                            alt="">
                                    </span>
                                    <p class="mt-1">
                                        Rifas
                                    </p>
                                </a>
                            </li>
                        @endif

                        @if (auth()->user()->role === 'Administrador' || auth()->user()->role === 'Secretaria')
                            <li class="mb-2">
                                <a href="/asignaciones"
                                    class="inline-flex items-center  @if (str_contains(request()->path(), 'asignaciones')) bg-green-500 @else bg-blue-500 @endif px-3 py-3 border  text-sm font-lg rounded-md  hover:bg-green-500 hover:text-white focus:outline-none transition ease-in-out duration-150 w-40">
                                    <span class="mr-2 p-1 rounded-md bg-blue-500">
                                        <img class="h-5 fill-white" src="{{ asset('img/icons/walk-icon.svg') }}"
                                            alt="">
                                    </span>
                                    <p class="mt-1">
                                        Asignaciones
                                    </p>
                                </a>
                            </li>
                        @endif

                        <li class="mb-2">
                            <a href="/boletas"
                                class="inline-flex items-center @if (str_contains(request()->path(), 'boletas') || str_contains(request()->path(), 'tickets')) bg-green-500 @else bg-blue-500 @endif px-3 py-3 border  text-sm font-lg rounded-md  hover:bg-green-500 hover:text-white focus:outline-none transition ease-in-out duration-150 w-40">
                                <span class="mr-2 p-1 rounded-md bg-blue-500">
                                    <img class="h-5 fill-white" src="{{ asset('img/icons/ticket-icon.svg') }}"
                                        alt="">
                                </span>
                                <p class="mt-1">
                                    Boletas
                                </p>
                            </a>
                        </li>
                        @if (auth()->user()->role === 'Administrador' || auth()->user()->role === 'Secretaria')
                            <li class="mb-2">
                                <a href="/premios"
                                    class="inline-flex items-center @if (str_contains(request()->path(), 'premios')) bg-green-500 @else bg-blue-500 @endif px-3 py-3 border  text-sm font-lg rounded-md  hover:bg-green-500 hover:text-white focus:outline-none transition ease-in-out duration-150 w-40">
                                    <span class="mr-2 p-1 rounded-md bg-blue-500">
                                        <img class="h-5 fill-white" src="{{ asset('img/icons/lightning-icon.svg') }}"
                                            alt="">
                                    </span>
                                    <p class="mt-1">
                                        Premios
                                    </p>
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="/comisiones"
                                    class="inline-flex items-center @if (str_contains(request()->path(), 'comisiones')) bg-green-500 @else bg-blue-500 @endif px-3 py-3 border  text-sm font-lg rounded-md  hover:bg-green-500 hover:text-white focus:outline-none transition ease-in-out duration-150 w-40">
                                    <span class="mr-2 p-1 rounded-md bg-blue-500">
                                        <img class="h-5 fill-white" src="{{ asset('img/icons/dollar-icon.svg') }}"
                                            alt="">
                                    </span>
                                    <p class="mt-1">
                                        Comisiones
                                    </p>
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="/salidas"
                                    class="inline-flex items-center @if (str_contains(request()->path(), 'salidas')) bg-green-500 @else bg-blue-500 @endif px-3 py-3 border  text-sm font-lg rounded-md  hover:bg-green-500 hover:text-white focus:outline-none transition ease-in-out duration-150 w-40">
                                    <span class="mr-2 p-1 rounded-md bg-blue-500">
                                        <img class="h-5 fill-white" src="{{ asset('img/icons/exit-icon.svg') }}"
                                            alt="">
                                    </span>
                                    <p class="mt-1">
                                        Salidas
                                    </p>
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="/arqueos"
                                    class="inline-flex items-center @if (str_contains(request()->path(), 'arqueos')) bg-green-500 @else bg-blue-500 @endif px-3 py-3 border  text-sm font-lg rounded-md  hover:bg-green-500 hover:text-white focus:outline-none transition ease-in-out duration-150 w-40">
                                    <span class="mr-2 p-1 rounded-md bg-blue-500">
                                        <img class="h-5 fill-white" src="{{ asset('img/icons/histogram-icon.svg') }}"
                                            alt="">
                                    </span>
                                    <p class="mt-1">
                                        Arqueos
                                    </p>
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="/entregas"
                                    class="inline-flex items-center @if (str_contains(request()->path(), 'entregas')) bg-green-500 @else bg-blue-500 @endif px-3 py-3 border  text-sm font-lg rounded-md  hover:bg-green-500 hover:text-white focus:outline-none transition ease-in-out duration-150 w-40">
                                    <span class="mr-2 p-1 rounded-md bg-blue-500">
                                        <img class="h-5 fill-white" src="{{ asset('img/icons/stack-icon.svg') }}"
                                            alt="">
                                    </span>
                                    <p class="mt-1">
                                        Entregas
                                    </p>
                                </a>
                            </li>
                        @endif
                        @if (auth()->user()->role === 'Administrador' || auth()->user()->role === 'Secretaria')
                            <li class="mb-2">
                                <a href="/consolidado"
                                    class="inline-flex items-center  @if (str_contains(request()->path(), 'asignaciones')) bg-green-500 @else bg-blue-500 @endif px-3 py-3 border  text-sm font-lg rounded-md  hover:bg-green-500 hover:text-white focus:outline-none transition ease-in-out duration-150 w-40">
                                    <span class="mr-2 p-1 rounded-md bg-blue-500 fill-white">
                                        <img class="h-5 fill-white" src="{{ asset('img/icons/database-icon.svg') }}"
                                            alt="">
                                    </span>
                                    <p class="mt-1">
                                        Informe Consolidado
                                    </p>
                                </a>
                            </li>
                        @endif
                        @if (auth()->user()->role === 'Administrador')
                            <li class="mb-2">
                                <a href="/permisos-entregas"
                                    class="inline-flex items-center @if (str_contains(request()->path(), 'permisos-entregas')) bg-green-500 @else bg-blue-500 @endif px-3 py-3 border  text-sm font-lg rounded-md  hover:bg-green-500 hover:text-white focus:outline-none transition ease-in-out duration-150 w-40">
                                    <span class="mr-2 p-1 rounded-md bg-blue-500">
                                        <img class="h-5 fill-white" src="{{ asset('img/icons/stack-icon.svg') }}"
                                            alt="">
                                    </span>
                                    <p class="mt-1">
                                        Permisos entregas
                                    </p>
                                </a>
                            </li>
                        @endif
                        <li class="mb-5 sm:hidden">
                            <a class="inline-flex items-center bg-green-500 px-3 py-3 border  text-sm font-lg rounded-md  hover:bg-green-500 hover:text-white focus:outline-none transition ease-in-out duration-150 w-40 "
                                href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <span>
                                    <img class="h-5 fill-white" src="{{ asset('img/icons/close-icon.svg') }}"
                                        alt="">
                                </span>
                                <p class="mt-1 text-center">
                                    Cerrar sesión
                                </p>
                            </a>
                        </li>
                    </ul>
                </nav>
                <div>
                    <a href="#" class="close-nav flex justify-center items-center sm:hidden ">
                        <span class="p-2 rounded-md bg-red-500">
                            <img class="h-5 fill-white" src="{{ asset('img/icons/close-icon.svg') }}"
                                alt="">
                        </span>
                    </a>
                </div>
            </div>

        </div>
        <div class="w-full">
            <nav class="bg-blue-500 text-white">
                <!-- Primary Navigation Menu -->
                <div class="max-w-8xl mx-auto px-2 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">

                        <div class="">
                            <ul class="page-breadcrumb flex items-center space-x-2 text-sm mt-6">
                                <li class="flex items-center">
                                    <i class="fa fa-angle-right mx-2"></i>
                                    <a href="{{ route('dashboard') }}">
                                        <img class="h-5 fill-white" src="{{ asset('img/icons/home-icon.svg') }}"
                                            alt="">
                                    </a>
                                    <i class="fa fa-angle-right"> </i>
                                </li>

                                @for ($i = 1; $i < 2; $i++)
                                    <li class="flex items-center">
                                        @if (Request::segment($i) !== 'dashboard')
                                            <a href="/{{ Request::segment($i) }}">
                                                <img class="h-5" src="{{ asset('img/icons/left-icon.svg') }}"
                                                    alt="">
                                            </a>
                                        @endif
                                    </li>
                                @endfor
                                <li class="flex items-center">
                                    <i class="fa fa-angle-right "></i>
                                    | @yield('pageTitle')
                                </li>
                            </ul>
                        </div>

                        <!--Rigth-->
                        <div class="flex">
                            <div class="flex items-center">

                                <p class="mr-2">
                                    {{ auth()->user()->name }} {{ auth()->user()->lastname }}
                                </p>
                                <div>
                                    <a class="hidden md:block px-2 py-1 border  text-sm rounded-md text-blue-100 bg-green-500 hover:bg-white hover:text-blue-500"
                                        href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        Cerrar sesión
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                    </form>
                                </div>




                            </div>
                            <!-- Hamburger -->
                            <div class="-mr-2 flex items-center sm:hidden">
                                <button
                                    class="hamburguer inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 6h16M4 12h16M4 18h16" />
                                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden"
                                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                    </div>
                </div>

            </nav>
            <main class="py-4">
                @yield('content')
            </main>
        </div>
    </div>
</body>

</html>
