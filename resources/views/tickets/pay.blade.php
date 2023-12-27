@extends('layouts.app')
@section('pageTitle', 'Realizar pago entrega' )
@section('content')
    <div class="container mx-auto mt-8">
        <div class="bg-white shadow-md overflow-hidden rounded-md">
            <div class="py-4 px-6 bg-blue-500 flex text-white fill-white">
                <h2 class="text-2xl font-semibold">Realizar pago boleta(s)</h2>
            </div>

            <form method="POST" action="{{ route('tickets.setpay') }}" class="p-6">
                @csrf

                <div class="md:flex">
                    <div class="mb-4 md:w-1/2 mr-2">
                        <label for="raffle_id" class="block text-gray-700 text-sm font-bold mb-2">Rifa</label>
                        <select name="raffle_id" id="raffle_id" class="w-full border rounded-md py-2 px-3" required>
                            <option value="">Seleccione una rifa</option>
                            @foreach($raffles as $raffle)
                                <option value="{{ $raffle->id }}">{{ $raffle->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if ($current_user->role === 'Administrador' || $current_user->role === 'Secretaria')
                        <div class="mb-4 md:w-1/2">
                            <label for="user_id" class="block text-gray-700 text-sm font-bold mb-2">Vendedor(es)</label>
                            <select name="user_id" id="user_id" class="w-full border rounded-md py-2 px-3"  required>
                                <option value="">Seleccione un(a) vendedor(a)</option>
                                @foreach($sellers_users as $seller)
                                    <option value="{{ $seller->id }}">{{ $seller->name }}</option>
                                @endforeach
                            </select>
                        </div>    
                    @else
                        <input type="hidden" name="user_id" id="user_id" value="{{$current_user->id}}">
                    @endif
                    
                </div>

                <div class="mb-4 tickets-pay">
                    <div class="row-ticket flex">
                        <input type="number" class="ticket-number w-full border rounded-md py-2 px-3" placeholder="#Boleta" autocomplete="off" required>
                        <input type="number" class="ticket-payment w-full border rounded-md py-2 px-3" placeholder="#Abono" autocomplete="off" required>    
                        <div class="flex">
                            <button type="button" class="bg-blue-500 text-white more px-3 rounded-md">+</button>
                            <button type="button" class="bg-red-500 text-white  less px-3 rounded-md">-</button>
                        </div>
                    </div>
                </div>

                <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-md">Realizar pago</button>
            </form>
        </div>
    </div>
@endsection
