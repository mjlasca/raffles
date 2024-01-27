@extends('layouts.app')
@section('pageTitle', 'Asignaciones')
@section('content')
    <div class="container mx-auto mt-8">
        <div class="bg-white shadow-md overflow-hidden rounded-md">
            <div class="py-4 px-6 bg-blue-500 flex text-white fill-white">
                <h2 class="text-2xl font-semibold">Asignaciones</h2>
                <a class="ml-2 p-1 mt-1 bg-green-500 rounded-lg hover:bg-green-500" href="{{route('asignaciones.create')}}">
                    <img class="h-5" src="{{ asset('img/icons/add-icon.svg') }}" alt="">
                </a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="text-md font-semibold tracking-wide text-left text-white bg-green-500 uppercase border-b border-gray-600">
                            <th class="py-2 px-4 border-b">Nombre rifa</th>
                            <th class="py-2 px-4 border-b">Vendedor</th>
                            <th class="py-2 px-4 border-b"># Boletas asignadas</th>
                            <th class="py-2 px-4 border-b">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        @foreach($assignments as $assignment)

                            <tr class="hover:bg-gray-100 border-b">
                                <td class="py-2 px-4">
                                    <a href="rifas/{{ $assignment->raffle->id }}"> {{ $assignment->raffle->name }} </a>
                                   <br> # boletas : {{ $assignment->raffle->tickets_number }}
                                </td>
                                <td class="py-2 px-4">
                                    {{ $assignment->user->name }} {{ $assignment->user->lastname }}
                                </td>
                                <td class="py-2 px-4 text-center">
                                    {{ $assignment->tickets_total }}
                                </td>
                                <td class="py-2 px-4 flex">
                                    <a href="{{ route('asignaciones.show', $assignment->id) }}" class="text-blue-500 hover:bg-green-500 p-1 bg-blue-500 rounded-md mr-1">
                                        <img class="h-5" src="{{ asset('img/icons/show-icon.svg') }}" alt="Ver registro" title="Ver registro">
                                    </a>
                                    <a href="{{ route('asignaciones.destroy', $assignment->id) }}" class="text-yellow-500 hover:bg-green-500 p-1 bg-red-500 rounded-md mr-1">
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
            {{$assignments->links()}}
        </div>
    </div>
@endsection
