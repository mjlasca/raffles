@extends('layouts.app')
@section('pageTitle', 'Boletas')
@section('content')
    <div class="container mx-auto mt-8">
        <div class="bg-white shadow-md overflow-hidden rounded-md">
            <div class="py-4 px-6 bg-blue-500 flex text-white fill-white">
                <h2 class="text-2xl font-semibold">Boletas</h2>
                
                <a class="ml-2 p-1 mt-1 bg-green-500 rounded-lg hover:bg-green-500" href="{{route('boletas.pay')}}">
                    <img class="h-5" src="{{ asset('img/icons/dollar-icon.svg') }}" alt="Pagar boletas" title="Pagar boletas">
                </a>
                <a class="ml-2 p-1 mt-1 bg-green-500 rounded-lg hover:bg-green-500" href="{{route('boletas.export')}}?raffle_id={{Request('raffle_id')}}&user_id={{Request('user_id')}}">
                    <img class="h-5" src="{{ asset('img/icons/export-icon.svg') }}" alt="">
                </a>
                
            </div>
            <div>
                <form method="GET"  class="p-6">
                    <div class="md:flex">
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

                        <div class="mb-4 mr-2 ml-2 mt-5 md:w-1/3">
                            <input type="checkbox" name="removable" id="removable" value="1"  @if (Request('removable') == 1)
                            checked
                            @endif >
                            <label for="">Desprendible</label>
                        </div>
 
                    </div>
                    <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-md w-full md:w-64">Filtrar consulta</button>
                </form>
            </div>
            @isset($tickets)
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            @if ($totals)
                                <tr class="hover:bg-blue-500 hover:text-white bg-red-100">
                                    <td colspan="2"></td>
                                    <td colspan="2">TOTALES $</td>
                                    <td class="py-2 px-4 text-right">{{ number_format($totals['total']) }}</td>
                                    <td class="py-2 px-4 text-right">{{ number_format($totals['payment']) }}</td>
                                    <td></td>
                                    <td class="py-2 px-4 text-right">{{ number_format($totals['commission']) }}</td>
                                    <td></td>
                                </tr>                                
                            @endif
                            <tr class="text-md font-semibold tracking-wide text-left text-white bg-green-500 uppercase border-b border-gray-600">
                                @if (auth()->user()->role === 'Administrador' || auth()->user()->role === 'Secretaria')
                                <th class="py-2 px-4 border-b">
                                    <input type="checkbox" class="check-removable_all">
                                    Desp.
                                </th>    
                                @endif
                                <th class="py-2 px-4 border-b">Rifa</th>
                                <th class="py-2 px-4 border-b">No. Boleta</th>
                                <th class="py-2 px-4 border-b">Vendedor(a)</th>
                                <th class="py-2 px-4 border-b">Valor</th>
                                <th class="py-2 px-4 border-b">Abonado</th>
                                <th class="py-2 px-4 border-b">Nombre cliente</th>
                                <th class="py-2 px-4 border-b">Comisión</th>
                                <th class="py-2 px-4 border-b">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                            @foreach($tickets as $ticket)
                                <tr class="hover:bg-gray-100 border-b @if ($ticket->removable == 1)
                                    bg-green-100
                                @endif " >
                                    @if (auth()->user()->role === 'Administrador' || auth()->user()->role === 'Secretaria')
                                    @if ($ticket->price == $ticket->payment)
                                    <td class="py-2 px-4">
                                        @if ($ticket->removable == 1)
                                            <input type="checkbox" checked disabled>
                                        @else
                                            <input type="checkbox" class="check-removable" value="{{$ticket->id}}">
                                        @endif
                                    </td>
                                    @else
                                    <td class="py-2 px-4">
                                        <input type="checkbox"  disabled>
                                    </td>
                                    @endif
                                    @endif
                                    <td class="py-2 px-4">{{ $ticket->raffle->name }}</td>
                                    <td class="py-2 px-4">{{ $ticket->ticket_number }}</td>
                                    <td class="py-2 px-4">{{ $ticket->user->name }} {{ $ticket->user->lastname }}</td>
                                    <td class="py-2 px-4 text-right">${{ $ticket->price }}</td>
                                    @if (strpos($ticket->movements, 'distribuido') !== false)
                                        <td class="py-2 px-4 text-right bg-red-100">${{ $ticket->payment }}</td>
                                    @else
                                        <td class="py-2 px-4 text-right">${{ $ticket->payment }}</td>
                                    @endif
                                    <td class="py-2 px-4 text-right">{{ $ticket->customer_name }}</td>
                                    <td class="py-2 px-4 text-right"> @if ($ticket->price == $ticket->payment) ${{ $ticket->assignment->commission }} @else $0 @endif </td>
                                    <td class="py-2 px-4 md:flex grid grid-cols-2 gap-2">
                                        <a href="{{ route('boletas.show', $ticket->id) }}" class="text-blue-500 hover:bg-green-500 p-1 bg-blue-500 rounded-md mr-1">
                                            <img class="h-5" src="{{ asset('img/icons/show-icon.svg') }}" alt="Ver registro" title="Ver registro">
                                        </a>
                                        @if (auth()->user()->role !== 'Vendedor')
                                            <a href="{{ route('boletas.edit', $ticket->id) }}" class="text-yellow-500 hover:bg-green-500 p-1 bg-blue-500 rounded-md mr-1">
                                                <img class="h-5" src="{{ asset('img/icons/edit-icon.svg') }}" alt="Editar" title="Editar">
                                            </a>    
                                        @endif
                                        
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    
                </div>
                
            @endisset
            
        </div>
        @if (auth()->user()->role === 'Administrador' || auth()->user()->role === 'Secretaria')
            @isset($tickets)
                @csrf
                <button class="bg-blue-500 text-white py-2 px-4 rounded-md action-checks">Guardar selección desprendibles</button> 
            @endisset
        @endif
        <div class="pag mt-5">
            @isset($tickets)
            {{$tickets->links()}}
            @endisset
        </div>
    </div>
    <script src="{{ asset('js/tickets-index.js') }}"></script>    
@endsection


