@extends('layouts.app')
@section('pageTitle', 'Realizar pago entrega' )
@section('content')
    <div class="container mx-auto mt-8">
        <div class="bg-white shadow-md overflow-hidden rounded-md">
            <div class="py-4 px-6 bg-blue-500 flex text-white fill-white">
                <h2 class="text-2xl font-semibold">Realizar pago boleta(s)</h2>
            </div>
            <div class="delivery-data sm:pl-3">
            </div>
            @if ($selected_user == FALSE)
                <form method="POST" id="formPay" action="{{ route('tickets.setpay') }}" class="p-6">
                    @csrf

                    <div class="md:flex">
                        <div class="mb-4 md:w-1/3 mr-2">
                            <label for="delivery_id" class="block text-gray-700 text-sm font-bold mb-2">Usuario</label>
                            <select name="users_deliveries" id="users_deliveries" class="w-full border rounded-md py-2 px-3" required>
                                <option value="">Seleccione un usuario</option>
                                @foreach($deliveries as $delivery)
                                    <option value="{{ $delivery->user_id }}">{{ $delivery->user->name }} {{ $delivery->user->lastname }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>  
            @else
                <form method="POST" id="formPay" action="{{ route('tickets.setpay') }}" class="p-6">
                    @csrf

                    <div class="md:flex">
                        <div class="mb-4 md:w-1/3 mr-2">
                            <label for="delivery_id" class="block text-gray-700 text-sm font-bold mb-2">Entrega</label>
                            <select name="delivery_id" id="delivery_id" class="w-full border rounded-md py-2 px-3" required>
                                <option value="">Seleccione una entrega</option>
                                @foreach($deliveries as $delivery)
                                    <option value="{{ $delivery->id }}">{{ $delivery->raffle->name }} - {{ $delivery->user->name }} {{ $delivery->user->lastname }} ${{ number_format($delivery->total,0) }} - ${{ number_format($delivery->used,0) }} </option>
                                @endforeach
                            </select>
                        </div>
                        @if ($current_user->role === 'Vendedor')
                            <input type="hidden" name="user_id" id="user_id" value="{{$current_user->id}}">
                        @else
                            <input type="hidden" name="user_id" id="user_id" value="">
                        @endif
                            <input type="hidden" name="raffle_id" id="raffle_id" value="">
                        
                        
                        
                    </div>
                    

                    <div class="content-tickets hidden">
                        <div class="mb-4 tickets-pay">
                            <div class="row-ticket flex mb-2">
                                <input type="number" name="ticket_number[]" class="ticket-number w-full border rounded-md py-2 px-3" placeholder="#Boleta" autocomplete="off" required>
                                <input type="number" min="1" name="ticket_payment[]" class="ticket-payment w-full border rounded-md py-2 px-3" placeholder="$Abono" autocomplete="off" required>    
                                <input type="text" name="customer_name[]" class="ticket w-full border rounded-md py-2 px-3" placeholder="Nombre" autocomplete="off" >    
                                <input type="number" name="customer_phone[]" class="ticket w-full border rounded-md py-2 px-3" placeholder="Teléfono" autocomplete="off" >    
                                <button type="button" class="bg-gray-100 text-white  px-3 rounded-md">-</button>
                            </div>
                            
                        </div>
                        <button type="button" class="bg-blue-500 text-white more py-2 px-4 rounded-md">+</button>
                        <button id="send" type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-md">Realizar pago</button>
                    </div>
                    
                    
                </form>
            @endif
            
            
            
        </div>
    </div>

    <script src="{{ asset('js/tickets-pay.js') }}"></script>
@endsection
