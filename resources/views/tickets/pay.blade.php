@extends('layouts.app')
@section('pageTitle', 'Realizar pago entrega' )
@section('content')
    <div class="container mx-auto mt-8">
        <div class="bg-white shadow-md overflow-hidden rounded-md">
            <div class="py-4 px-6 bg-blue-500 flex text-white fill-white">
                
                <h2 class="ml-2 text-2xl font-semibold">Realizar pago boleta(s) @if ($selected_user !== FALSE) de {{$selected_user->name ." ".$selected_user->lastname}} <a href="" class="bg-green-500 text-white py-2 px-4 rounded-md text-sm">Seleccionar otro usuario</a> @endif</h2>
                
            </div>
            <div id="error-container">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
            <div class="delivery-data sm:pl-3 w-64">
            </div>
            @if ($selected_user == FALSE)
                <form method="POST" id="formPrePay" action="" class="p-6">
                    @csrf

                    <div class="">
                        <div class="mb-4 md:w-1/3 mr-2">
                            <label for="delivery_id" class="block text-gray-700 text-sm font-bold mb-2">Usuario</label>
                            <select name="users_deliveries" id="users_deliveries" class="w-full border rounded-md py-2 px-3" required>
                                <option value="">Seleccione un usuario</option>
                                @foreach($deliveries as $delivery)
                                    <option value="{{ $delivery->user_id }}">{{ $delivery->user->name }} {{ $delivery->user->lastname }}</option>
                                @endforeach
                            </select>

                        </div>
                        <div class="mt-2">
                            <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-md w-full md:w-64">Enviar</button>
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
                                    <option value="{{ $delivery->id }}">{{ $delivery->raffle->name }} - ${{ number_format($delivery->total,0) }} - ${{ number_format($delivery->used,0) }} </option>
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

    <div class="modal-pay hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 max-h-full bg-blue-500/90">
        <div class="modal-content w-full h-full flex justify-center items-center rounded-md">
            <div class="flex w-1/4 p-3 bg-gray-100">
                <form method="POST" action="/tickets/payall" onsubmit="return confirm('¿Está seguro de distribuir lo disponible? si lo hace, el sistema asignará equitativamente a cada boleta que tenga para ésta rifa');">
                <input type="number" id="distributive_value" name="distributive_value" class="w-full border rounded-md py-2 px-3" required placeholder="Valor de distribución" autocomplete="off">
                <p class="text-white">El sistema intentará abonar las boletas con lo que está disponible, es posible que no se pueda abonar todo el saldo, el resto tendrá que hacerse de forma manual o volver a hacer una distribución con un valor menor</p>
                <input type="hidden" name="delivery_modal_id" id="delivery_modal_id">
                @csrf
                <div class="flex mt-2">
                    <input class="bg-red-500 dark:bg-red-500 text-white py-2 px-4 rounded-md" type="submit" value="Aceptar">
                    <a class="border text-white py-2 px-4 rounded-md" href="javascript:void(0)" onclick="showModalPay()">Cancelar</a>
                </div>
                </form>
            </div>

        </div>
    </div>

    <script src="{{ asset('js/tickets-pay.js') }}"></script>
@endsection
