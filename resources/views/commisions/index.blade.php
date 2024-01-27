@extends('layouts.app')
@section('pageTitle', 'Comisiones')
@section('content')
    <div class="container mx-auto mt-8">
        <div class="bg-white shadow-md overflow-hidden rounded-md">
            <div class="py-4 px-6 bg-blue-500 flex text-white fill-white">
                <h2 class="text-2xl font-semibold">Comisiones</h2>
                <a class="ml-2 p-1 mt-1 bg-green-500 rounded-lg hover:bg-green-500" href="{{route('comisiones.create')}}">
                    <img class="h-5" src="{{ asset('img/icons/add-icon.svg') }}" alt="">
                </a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="text-md font-semibold tracking-wide text-left text-white bg-green-500 uppercase border-b border-gray-600">
                            <th class="py-2 px-4 border-b">Detalle</th>
                            <th class="py-2 px-4 border-b">Rifa</th>
                            <th class="py-2 px-4 border-b">Participa con</th>
                            <th class="py-2 px-4 border-b">Fecha sorteo</th>
                            <th class="py-2 px-4 border-b">Boleto ganador</th>
                            <th class="py-2 px-4 border-b">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                       
                    </tbody>
                </table>
                
            </div>
        </div>
        
    </div>
@endsection
