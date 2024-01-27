@extends('layouts.app')
@section('pageTitle', 'Editar boleta #'.$ticket->ticket_number )
@section('content')
    <div class="container mx-auto mt-8">
        <div class="bg-white shadow-md overflow-hidden rounded-md">
            <div class="py-4 px-6 bg-blue-500 flex text-white fill-white">
                <h2 class="text-2xl font-semibold">Editar boleta</h2>
            </div>

            <form method="POST" action="{{ route('boletas.update', $ticket->id) }}" class="p-6">
                @csrf
                @method('PUT')

                <div class="flex">
                    <div class="mb-4 mr-1 w-1/2">
                        <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Rifa:</label>
                        <input type="text"  class="w-full bg-gray-100 rounded-md py-2 px-3" value="{{ $ticket->raffle->name }}" readonly>
                    </div>
                    <div class="mb-4 mr-1">
                        <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Número de boleta:</label>
                        <input type="text"  class="w-full bg-gray-100 rounded-md py-2 px-3" value="{{ $ticket->ticket_number }}" readonly>
                    </div>

                    <div class="mb-4">
                        <label for="price" class="block text-gray-700 text-sm font-bold mb-2">Precio:</label>
                        <input type="number" class="w-full bg-gray-100 rounded-md py-2 px-3"  value="{{ $ticket->price }}" readonly>
                    </div>
                </div>
                
                <div class="mb-4 md:w-1/2">
                    <label for="user_id" class="block text-gray-700 text-sm font-bold mb-2">Vendedor(a)</label>
                    <select name="user_id" id="user_id" class="w-full border rounded-md py-2 px-3" required>
                            <option value="">Seleccione un(a) vendedor(a)</option>
                        @foreach($sellers_users as $seller)
                            @if ($seller->id == $ticket->user->id)
                                <option value="{{ $seller->id }}" selected>{{ $seller->name }} {{ $seller->lastname }}</option>
                            @else    
                                <option value="{{ $seller->id }}">{{ $seller->name }} {{ $seller->lastname }}</option>
                            @endif
                            
                        @endforeach
                    </select>
                </div>

                <div class="mb-4 md:w-1/2">
                    <label for="payment" class="block text-gray-700 text-sm font-bold mb-2">Abonado:</label>
                    <input type="number" name="payment" id="payment" pattern="[0-9]{1,15}" min="0" max="{{($ticket->price)}}" value="{{($ticket->payment)}}" title="El teléfono debe tener sólo números y 10 caracteres" class="w-full border rounded-md py-2 px-3" required>
                    <small>Puede poner éste campo en cero (0)</small>
                </div>

                <div class="mb-4 mr-1 w-1/2">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Nombre cliente:</label>
                    <input type="text" name="customer_name" id="customer_name" class="w-full border rounded-md py-2 px-3" value="{{ $ticket->customer_name }}" >
                </div>

                <div class="mb-4 md:w-1/2">
                    <label for="customer_phone" class="block text-gray-700 text-sm font-bold mb-2">Teléfono:</label>
                    <input type="text" name="customer_phone" id="customer_phone" pattern="[0-9]{10,10}" title="El teléfono debe tener sólo números y 10 caracteres" class="w-full border rounded-md py-2 px-3"  value="{{ $ticket->customer_phone }}">
                </div>


                <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-md">Actualizar boleta</button>
            </form>

            
            
        </div>
    </div>
@endsection
