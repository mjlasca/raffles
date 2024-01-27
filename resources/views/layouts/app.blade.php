<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Rifas') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

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
<body>
    
    <div class="flex">
        <!--Sidebar-->
        <div class="sm:flex h-screen bg-gray-800 hidden">
            
            <div class="w-64 bg-gray-100">
                <div class="bg-blue-500 py-4 h-40 flex flex-col items-center">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo" class="w-2/4">
                </div>
                <nav class="mt-8 tx-blue-700">
                    <ul class="flex flex-col items-center text-white">

                        @if (auth()->user()->role === 'Administrador')

                        <li class="mb-2">
                            <a href="/usuarios" class="inline-flex @if( str_contains( request()->path(),'usuarios') ) bg-green-500 @else bg-blue-500 @endif items-center px-3 py-3 border  text-sm font-lg rounded-md  hover:bg-green-500 hover:text-white focus:outline-none transition ease-in-out duration-150 w-40">
                                <span class="mr-2 p-1 rounded-md bg-blue-500">
                                    <img class="h-5 fill-white" src="{{ asset('img/icons/group-icon.svg') }}" alt="">
                                </span>
                                <p class="mt-1">
                                    Usuarios   
                                </p>
                            </a>
                        </li>

                        @endif
                        
                        <li class="mb-2">
                            <a href="/dashboard" class="inline-flex  @if(request()->is('dashboard')) bg-green-500 @else bg-blue-500 @endif items-center px-3 py-3 border  text-sm font-lg rounded-md  hover:bg-green-500 hover:text-white focus:outline-none transition ease-in-out duration-150 w-40">
                                <span class="mr-2 p-1 rounded-md bg-blue-500">
                                    <img class="h-5 fill-white" src="{{ asset('img/icons/tile-icon.svg') }}" alt="">
                                </span>
                                <p class="mt-1">
                                    Dashboard 
                                </p>
                            </a>
                        </li>

                        @if (auth()->user()->role === 'Administrador' || auth()->user()->role === 'Secretaria')
                        <li class="mb-2">
                            <a href="/rifas" class="inline-flex items-center @if( str_contains( request()->path(),'rifas') ) bg-green-500 @else bg-blue-500 @endif  px-3 py-3 border  text-sm font-lg rounded-md  hover:bg-green-500 hover:text-white focus:outline-none transition ease-in-out duration-150 w-40">
                                <span class="mr-2 p-1 rounded-md bg-blue-500">
                                    <img class="h-5 fill-white" src="{{ asset('img/icons/switch-icon.svg') }}" alt="">
                                </span>
                                <p class="mt-1">
                                    Rifas
                                </p>
                            </a>
                        </li>
                        

                        <li class="mb-2">
                            <a href="/asignaciones" class="inline-flex items-center  @if( str_contains( request()->path(),'asignaciones') ) bg-green-500 @else bg-blue-500 @endif px-3 py-3 border  text-sm font-lg rounded-md  hover:bg-green-500 hover:text-white focus:outline-none transition ease-in-out duration-150 w-40">
                                <span class="mr-2 p-1 rounded-md bg-blue-500">
                                    <img class="h-5 fill-white" src="{{ asset('img/icons/walk-icon.svg') }}" alt="">
                                </span>
                                <p class="mt-1">
                                    Asignaciones
                                </p>
                            </a>
                        </li>

                        @endif

                        <li class="mb-2">
                            <a href="/boletas" class="inline-flex items-center @if( str_contains( request()->path(),'boletas') || str_contains( request()->path(),'tickets') ) bg-green-500 @else bg-blue-500 @endif px-3 py-3 border  text-sm font-lg rounded-md  hover:bg-green-500 hover:text-white focus:outline-none transition ease-in-out duration-150 w-40">
                                <span class="mr-2 p-1 rounded-md bg-blue-500">
                                    <img class="h-5 fill-white" src="{{ asset('img/icons/ticket-icon.svg') }}" alt="">
                                </span>
                                <p class="mt-1">
                                    Boletas
                                </p>
                            </a>
                        </li>
                        @if (auth()->user()->role === 'Administrador' || auth()->user()->role === 'Secretaria')
                        <li class="mb-2">
                            <a href="/premios" class="inline-flex items-center @if( str_contains( request()->path(),'premios') ) bg-green-500 @else bg-blue-500 @endif px-3 py-3 border  text-sm font-lg rounded-md  hover:bg-green-500 hover:text-white focus:outline-none transition ease-in-out duration-150 w-40">
                                <span class="mr-2 p-1 rounded-md bg-blue-500">
                                    <img class="h-5 fill-white" src="{{ asset('img/icons/lightning-icon.svg') }}" alt="">
                                </span>
                                <p class="mt-1">
                                    Premios
                                </p>
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="/comisiones" class="inline-flex items-center @if( str_contains( request()->path(),'comisiones') ) bg-green-500 @else bg-blue-500 @endif px-3 py-3 border  text-sm font-lg rounded-md  hover:bg-green-500 hover:text-white focus:outline-none transition ease-in-out duration-150 w-40">
                                <span class="mr-2 p-1 rounded-md bg-blue-500">
                                    <img class="h-5 fill-white" src="{{ asset('img/icons/dollar-icon.svg') }}" alt="">
                                </span>
                                <p class="mt-1">
                                    Comisiones
                                </p>
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="/arqueos" class="inline-flex items-center @if( str_contains( request()->path(),'arqueos') ) bg-green-500 @else bg-blue-500 @endif px-3 py-3 border  text-sm font-lg rounded-md  hover:bg-green-500 hover:text-white focus:outline-none transition ease-in-out duration-150 w-40">
                                <span class="mr-2 p-1 rounded-md bg-blue-500">
                                    <img class="h-5 fill-white" src="{{ asset('img/icons/histogram-icon.svg') }}" alt="">
                                </span>
                                <p class="mt-1">
                                    Arqueos
                                </p>
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="/entregas" class="inline-flex items-center @if( str_contains( request()->path(),'entregas') ) bg-green-500 @else bg-blue-500 @endif px-3 py-3 border  text-sm font-lg rounded-md  hover:bg-green-500 hover:text-white focus:outline-none transition ease-in-out duration-150 w-40">
                                <span class="mr-2 p-1 rounded-md bg-blue-500">
                                    <img class="h-5 fill-white" src="{{ asset('img/icons/stack-icon.svg') }}" alt="">
                                </span>
                                <p class="mt-1">
                                    Entregas
                                </p>
                            </a>
                        </li>
                        @endif
                    </ul>
                </nav>
            </div>
        
        </div>
        <div class="w-full">
            <nav  class="bg-blue-500 text-white">
                <!-- Primary Navigation Menu -->
                <div class="max-w-8xl mx-auto px-2 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                    
                        <div class="">
                            <ul class="page-breadcrumb flex items-center space-x-2 text-sm mt-6">
                                <li class="flex items-center">
                                    <i class="fa fa-angle-right mx-2"></i>
                                    <a href="{{route('dashboard')}}">
                                        <img class="h-5 fill-white" src="{{ asset('img/icons/home-icon.svg') }}" alt="">
                                    </a>
                                  <i class="fa fa-angle-right">  </i>
                                </li>
            
                                @for($i = 1; $i < 2; $i++)
                                    <li class="flex items-center">
                                        @if( Request::segment($i) !== 'dashboard')
                                        
                                            <a href="/{{ Request::segment($i) }}"> 
                                                <img class="h-5" src="{{ asset('img/icons/left-icon.svg') }}" alt="">
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
                                    {{auth()->user()->name}}
                                </p>
                                <div>
                                    <a class="hidden md:block px-2 py-1 border  text-sm rounded-md text-blue-100 bg-green-500 hover:bg-white hover:text-blue-500" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        Cerrar sesión
                                    </a>
                                    
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                                
                                
                                
                                
                            </div>
                            <!-- Hamburger -->
                            <div class="-mr-2 flex items-center sm:hidden">
                                <button class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
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

