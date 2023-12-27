@extends('layouts.app')
@section('pageTitle', 'Boletas')
@section('content')
    <div class="container mx-auto mt-8">
        <div class="bg-white shadow-md overflow-hidden rounded-md">
            <div class="py-4 px-6 bg-blue-500 flex text-white fill-white">
                <h2 class="text-2xl font-semibold">Boletas</h2>
                
                <a class="ml-2 p-1 mt-1 bg-white rounded-lg hover:bg-green-500" href="{{route('boletas.pay')}}">
                    <img class="h-5" src="{{ asset('img/icons/dollar-icon.svg') }}" alt="Pagar boletas" title="Pagar boletas">
                </a>
            </div>
            <div>
                <form method="GET"  class="p-6">
                    <div class="flex">
                        <div class="mb-4 mr-2 md:w-1/3">
                            <label for="raffle_id" class="block text-gray-700 text-sm font-bold mb-2">Rifa </label>
                            <select name="raffle_id" id="raffle_id" class="w-full border rounded-md py-2 px-3" >
                                <option value="">Seleccione una rifa</option>
                                @foreach($raffles as $raffle)
                                    @if (Request('raffle_id') == $raffle->id )
                                    <option value="{{ $raffle->id }}" selected >{{ $raffle->name }}</option>    
                                    @else
                                    <option value="{{ $raffle->id }}">{{ $raffle->name }}</option>    
                                    @endif
                                @endforeach
                            </select>
                        </div>
        
                        <div class="mb-4 mr-2 md:w-1/3">
                            <label for="user_id" class="block text-gray-700 text-sm font-bold mb-2">Vendedor(es)</label>
                            <select name="user_id" id="user_id" class="w-full border rounded-md py-2 px-3" >
                                <option value="">Seleccione un(a)</option>
                                @foreach($sellers_users as $seller)
                                    @if (Request('user_id') == $seller->id )
                                        <option value="{{ $seller->id }}" selected>{{ $seller->name }} {{ $seller->lastname }}</option>
                                    @else
                                        <option value="{{ $seller->id }}">{{ $seller->name }} {{ $seller->lastname }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4 md:w-1/3">
                            <label for="ticket_number" class="block text-gray-700 text-sm font-bold mb-2">Número boleta</label>
                            <input type="text" class="w-full border rounded-md py-2 px-3" value="{{Request('ticket_number')}}" name="ticket_number" id="ticket_number">
                        </div>

                    </div>
                    <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-md">Filtrar consulta</button>
                </form>
            </div>
            @isset($tickets)
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="text-md font-semibold tracking-wide text-left text-white bg-green-500 uppercase border-b border-gray-600">
                                <th class="py-2 px-4 border-b">Rifa</th>
                                <th class="py-2 px-4 border-b">No. Boleta</th>
                                <th class="py-2 px-4 border-b">Vendedor(a)</th>
                                <th class="py-2 px-4 border-b">Valor</th>
                                <th class="py-2 px-4 border-b">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tickets as $ticket)
                                <tr class="hover:bg-gray-100 border-b">
                                    <td class="py-2 px-4">{{ $ticket->raffle->name }}</td>
                                    <td class="py-2 px-4">{{ $ticket->ticket_number }}</td>
                                    <td class="py-2 px-4">{{ $ticket->user->name }} {{ $ticket->user->lastname }}</td>
                                    <td class="py-2 px-4">${{ $ticket->raffle->price }}</td>
                                    <td class="py-2 px-4 flex">
                                        <a href="{{ route('boletas.show', $ticket->id) }}" class="text-blue-500 hover:bg-green-500 p-1 bg-blue-500 rounded-md mr-1">
                                            <img class="h-5" src="{{ asset('img/icons/show-icon.svg') }}" alt="Ver registro" title="Ver registro">
                                        </a>
                                        <a href="{{ route('boletas.edit', $ticket->id) }}" class="text-yellow-500 hover:bg-green-500 p-1 bg-blue-500 rounded-md mr-1">
                                            <img class="h-5" src="{{ asset('img/icons/edit-icon.svg') }}" alt="Editar" title="Editar">
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                </div>
            @endisset
            
        </div>
        <div class="pag mt-5">
            @isset($tickets)
            {{$tickets->links()}}
            @endisset
        </div>
    </div>
@endsection
