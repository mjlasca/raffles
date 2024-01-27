@extends('layouts.app')
@section('pageTitle', 'Entregas')
@section('content')
    <div class="container mx-auto mt-8">
        <div class="bg-white shadow-md overflow-hidden rounded-md">
            <div class="py-4 px-6 bg-blue-500 flex text-white fill-white">
                <h2 class="text-2xl font-semibold">Entregas</h2>
                <a class="ml-2 p-1 mt-1 bg-green-500 rounded-lg hover:bg-green-500" href="{{route('entregas.create')}}">
                    <img class="h-5" src="{{ asset('img/icons/add-icon.svg') }}" alt="">
                </a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="text-md font-semibold tracking-wide text-left text-white bg-green-500 uppercase border-b border-gray-600">
                            <th class="py-2 px-4 border-b">Reg</th>
                            <th class="py-2 px-4 border-b">Descripción</th>
                            <th class="py-2 px-4 border-b">Rifa</th>
                            <th class="py-2 px-4 border-b">Vendedor</th>
                            <th class="py-2 px-4 border-b">Valor entrega</th>
                            <th class="py-2 px-4 border-b">Canjeado</th>
                            <th class="py-2 px-4 border-b">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($deliveries as $deliverie)
                            <tr class="hover:bg-gray-100 border-b">
                                <td class="py-2 px-4">{{ $deliverie->id }}</td>
                                <td class="py-2 px-4">{{ $deliverie->description }}</td>
                                <td class="py-2 px-4">{{ $deliverie->raffle->name }}</td>
                                <td class="py-2 px-4">{{ $deliverie->user->name }} {{ $deliverie->user->lastname }}</td>
                                <td class="py-2 px-4">{{ $deliverie->total }}</td>
                                <td class="py-2 px-4">{{ $deliverie->used }}</td>
                                
                                <td class="py-2 px-4 flex">
                                    <a href="{{ route('entregas.show', $deliverie->id) }}" class="text-blue-500 hover:bg-green-500 p-1 bg-blue-500 rounded-md mr-1">
                                        <img class="h-5" src="{{ asset('img/icons/show-icon.svg') }}" alt="Ver registro" title="Ver registro">
                                    </a>
                                    <a href="{{ route('entregas.pdf', $deliverie->id) }}" target="_blank" class="text-blue-500 hover:bg-green-500 p-1 bg-blue-500 rounded-md mr-1">
                                        <img class="h-5" src="{{ asset('img/icons/pdf-icon.svg') }}" alt="Descargar Recibo" title="Descargar Recibo">
                                    </a>
                                    @if ($deliverie->used < 1)
                                        <a href="{{ route('entregas.edit', $deliverie->id) }}" class="text-yellow-500 hover:bg-green-500 p-1 bg-blue-500 rounded-md mr-1">
                                            <img class="h-5" src="{{ asset('img/icons/edit-icon.svg') }}" alt="Editar" title="Editar">
                                        </a>
                                        <a href="{{ route('entregas.destroy', $deliverie->id) }}" class="text-yellow-500 hover:bg-green-500 p-1 bg-red-500 rounded-md mr-1">
                                            <img class="h-5" src="{{ asset('img/icons/delete-icon.svg') }}" alt="Eliminar" title="Eliminar">
                                        </a>    
                                    @endif
                                    
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                
            </div>
        </div>
        <div class="mt-5">
            {{$deliveries->links()}}
        </div>
    </div>
@endsection
