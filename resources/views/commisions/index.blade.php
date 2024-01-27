@extends('layouts.app')
@section('pageTitle', 'Arequeos')
@section('content')
    <div class="container mx-auto mt-8">
        <div class="bg-white shadow-md overflow-hidden rounded-md">
            <div class="py-4 px-6 bg-blue-500 flex text-white fill-white">
                <h2 class="text-2xl font-semibold">Arequeos</h2>
                <a class="ml-2 p-1 mt-1 bg-green-500 rounded-lg hover:bg-green-500" href="{{route('arequeos.create')}}">
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
                        @foreach($prizes as $prize)
                            <tr class="hover:bg-gray-100 border-b">
                                <td class="py-2 px-4">{{ $prize->detail }}</td>
                                <td class="py-2 px-4">{{ $prize->raffle->name }}</td>
                                <td class="py-2 px-4">{{ number_format($prize->percentage_condition,0) }}%</td>
                                <td class="py-2 px-4">{{ $prize->award_date }}</td>
                                <td class="py-2 px-4">{{ $prize->winning_ticket }}</td>
                                <td class="py-2 px-4 flex">
                                    <a href="{{ route('arequeos.show', $prize->id) }}" class="text-blue-500 hover:bg-green-500 p-1 bg-blue-500 rounded-md mr-1">
                                        <img class="h-5" src="{{ asset('img/icons/show-icon.svg') }}" alt="Ver registro" title="Ver registro">
                                    </a>
                                    <a href="{{ route('arequeos.edit', $prize->id) }}" class="text-yellow-500 hover:bg-green-500 p-1 bg-blue-500 rounded-md mr-1">
                                        <img class="h-5" src="{{ asset('img/icons/edit-icon.svg') }}" alt="Editar" title="Editar">
                                    </a>
                                    <a href="{{ route('arequeos.destroy', $prize->id) }}" class="text-yellow-500 hover:bg-green-500 p-1 bg-red-500 rounded-md mr-1">
                                        <img class="h-5" src="{{ asset('img/icons/delete-icon.svg') }}" alt="Eliminar" title="Eliminar">
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                
            </div>
        </div>
        <div class="mt-5">
            {{$prizes->links()}}
        </div>
    </div>
@endsection
