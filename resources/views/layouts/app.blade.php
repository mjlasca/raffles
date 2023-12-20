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

</head>
<body>
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

        
    
        <!-- Responsive Navigation Menu -->
        <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
            <div class="pt-2 pb-3 space-y-1">
                
            </div>
    
            <!-- Responsive Settings Options -->
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800" x-data="{ name: '{{ auth()->user()->name }}' }" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
                    <div class="font-medium text-sm text-gray-500">{{ auth()->user()->email }}</div>
                </div>
    
            </div>
        </div>
    </nav>

    <div class="md:flex h-screen bg-gray-800">

        <div class="w-64 bg-gray-100">
            <div class="bg-blue-500 py-4 h-40 flex flex-col items-center">
                <img src="{{ asset('img/logo.png') }}" alt="Logo" class="w-20">
                <span class="text-white text-lg font-semibold mt-2">Company Name</span>
            </div>
            <nav class="mt-8 tx-blue-700">
                <ul class="flex flex-col items-center text-white">
                    <li class="mb-2">
                        <a href="/usuarios" class="inline-flex items-center px-3 py-3 border  text-sm font-lg rounded-md  hover:bg-green-500 hover:text-white focus:outline-none transition ease-in-out duration-150 w-40">
                            <span class="mr-2 p-1 rounded-md bg-blue-500">
                                <img class="h-5 fill-white" src="{{ asset('img/icons/group-icon.svg') }}" alt="">
                            </span>
                            <p class="mt-1">
                                Usuarios   
                            </p>
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="/dashboard" class="inline-flex items-center px-3 py-3 border  text-sm font-lg rounded-md  hover:bg-green-500 hover:text-white focus:outline-none transition ease-in-out duration-150 w-40">
                            <span class="mr-2 p-1 rounded-md bg-blue-500">
                                <img class="h-5 fill-white" src="{{ asset('img/icons/tile-icon.svg') }}" alt="">
                            </span>
                            <p class="mt-1">
                                Dashboard
                            </p>
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="/rifas" class="inline-flex items-center px-3 py-3 border  text-sm font-lg rounded-md  hover:bg-green-500 hover:text-white focus:outline-none transition ease-in-out duration-150 w-40">
                            <span class="mr-2 p-1 rounded-md bg-blue-500">
                                <img class="h-5 fill-white" src="{{ asset('img/icons/switch-icon.svg') }}" alt="">
                            </span>
                            <p class="mt-1">
                                Rifas
                            </p>
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="/asignaciones" class="inline-flex items-center px-3 py-3 border  text-sm font-lg rounded-md  hover:bg-green-500 hover:text-white focus:outline-none transition ease-in-out duration-150 w-40">
                            <span class="mr-2 p-1 rounded-md bg-blue-500">
                                <img class="h-5 fill-white" src="{{ asset('img/icons/walk-icon.svg') }}" alt="">
                            </span>
                            <p class="mt-1">
                                Asignaciones
                            </p>
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="/boletas" class="inline-flex items-center px-3 py-3 border  text-sm font-lg rounded-md  hover:bg-green-500 hover:text-white focus:outline-none transition ease-in-out duration-150 w-40">
                            <span class="mr-2 p-1 rounded-md bg-blue-500">
                                <img class="h-5 fill-white" src="{{ asset('img/icons/ticket-icon.svg') }}" alt="">
                            </span>
                            <p class="mt-1">
                                Boletas
                            </p>
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="/premios" class="inline-flex items-center px-3 py-3 border  text-sm font-lg rounded-md  hover:bg-green-500 hover:text-white focus:outline-none transition ease-in-out duration-150 w-40">
                            <span class="mr-2 p-1 rounded-md bg-blue-500">
                                <img class="h-5 fill-white" src="{{ asset('img/icons/lightning-icon.svg') }}" alt="">
                            </span>
                            <p class="mt-1">
                                Premios
                            </p>
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="/comisiones" class="inline-flex items-center px-3 py-3 border  text-sm font-lg rounded-md  hover:bg-green-500 hover:text-white focus:outline-none transition ease-in-out duration-150 w-40">
                            <span class="mr-2 p-1 rounded-md bg-blue-500">
                                <img class="h-5 fill-white" src="{{ asset('img/icons/dollar-icon.svg') }}" alt="">
                            </span>
                            <p class="mt-1">
                                Comisiones
                            </p>
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="/arqueos" class="inline-flex items-center px-3 py-3 border  text-sm font-lg rounded-md  hover:bg-green-500 hover:text-white focus:outline-none transition ease-in-out duration-150 w-40">
                            <span class="mr-2 p-1 rounded-md bg-blue-500">
                                <img class="h-5 fill-white" src="{{ asset('img/icons/histogram-icon.svg') }}" alt="">
                            </span>
                            <p class="mt-1">
                                Arqueos
                            </p>
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="/entregas" class="inline-flex items-center px-3 py-3 border  text-sm font-lg rounded-md  hover:bg-green-500 hover:text-white focus:outline-none transition ease-in-out duration-150 w-40">
                            <span class="mr-2 p-1 rounded-md bg-blue-500">
                                <img class="h-5 fill-white" src="{{ asset('img/icons/stack-icon.svg') }}" alt="">
                            </span>
                            <p class="mt-1">
                                Entregas
                            </p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    
    </div>
    <div id="app">
        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
