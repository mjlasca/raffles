@extends('layouts.app')
@section('pageTitle', 'Rifas')
@section('content')
    <div class="container mx-auto mt-8">
        <div class="bg-white shadow-md overflow-hidden rounded-md">
            <div class="py-4 px-6 bg-blue-500 flex text-white fill-white">
                <h2 class="text-2xl font-semibold">Rifas</h2>
                <a class="ml-2 p-1 mt-1 bg-green-500 rounded-lg hover:bg-green-500" href="{{route('rifas.create')}}">
                    <img class="h-5" src="{{ asset('img/icons/add-icon.svg') }}" alt="">
                </a>
                <a class="ml-2 p-1 mt-1 bg-green-500 rounded-lg hover:bg-green-500" href="{{route('rifas.export')}}">
                    <img class="h-5" src="{{ asset('img/icons/export-icon.svg') }}" alt="">
                </a>
            </div>
            <div>
                <form method="GET"  class="p-6">
                    
                    <div class="md:flex">
                        <div>
                            <label for="keyword" class="block text-gray-700 text-sm font-bold mb-2">Buscar coincidencia</label>
                            <input type="text" class="w-full border rounded-md py-2 px-3" name="keyword" id="" value="{{ Request('keyword') }}" placeholder="Buscar...">
                        </div>
                        <div>
                            <label for="date1" class="block text-gray-700 text-sm font-bold mb-2">Fecha inicial</label>
                            <input type="date" class="w-full border rounded-md py-2 px-3" name="date1" id="" value="{{ Request('date1') }}">
                        </div>
                        <div>
                            <label for="date2" class="block text-gray-700 text-sm font-bold mb-2">Fecha final</label>
                            <input type="date" class="w-full border rounded-md py-2 px-3" name="date2" id="" value="{{Request('date2')}}">
                        </div>
                    </div>
                    <button type="submit" class="mt-2 bg-blue-500 text-white py-2 px-4 rounded-md w-full md:w-64">Filtrar consulta</button>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="text-md font-semibold tracking-wide text-left text-white bg-green-500 uppercase border-b border-gray-600">
                            <th class="py-2 px-4 border-b">Nombre</th>
                            <th class="py-2 px-4 border-b">Descripción</th>
                            <th class="py-2 px-4 border-b">Precio</th>
                            <th class="py-2 px-4 border-b">Fecha sorteo</th>
                            <th class="py-2 px-4 border-b">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($raffles as $raffle)
                            <tr class="hover:bg-gray-100 border-b">
                                <td class="py-2 px-4">{{ $raffle->name }}</td>
                                <td class="py-2 px-4">{{ $raffle->description }}</td>
                                <td class="py-2 px-4">${{ $raffle->price }}</td>
                                <td class="py-2 px-4">{{ \Carbon\Carbon::parse($raffle->raffle_date)->format('Y-m-d') }}</td>
                                <td class="py-2 px-4 grid grid-cols-2 gap-2 md:flex">
                                    <a href="{{ route('rifas.show', $raffle->id) }}" class="text-blue-500 hover:bg-green-500 p-1 bg-blue-500 rounded-md mr-1">
                                        <img class="h-5" src="{{ asset('img/icons/show-icon.svg') }}" alt="Ver registro" title="Ver registro">
                                    </a>
                                    <a href="{{ route('rifas.edit', $raffle->id) }}" class="text-yellow-500 hover:bg-green-500 p-1 bg-blue-500 rounded-md mr-1">
                                        <img class="h-5" src="{{ asset('img/icons/edit-icon.svg') }}" alt="Editar" title="Editar">
                                    </a>
                                    
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                
            </div>
        </div>
        <div class="mt-5">
            {{$raffles->links()}}
        </div>
    </div>
@endsection
