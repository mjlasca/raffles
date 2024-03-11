@extends('layouts.app')
@section('pageTitle', 'Crear comisión' )
@section('content')
    <div class="container mx-auto mt-8">
        <div class="bg-white rounded-md overflow-hidden">
            <div class="py-4 px-6 bg-blue-500">
                <h2 class="text-2xl text-white font-semibold text-gray-800">Crear comisión</h2>
            </div>

            <form method="POST" action="{{ route('comisiones.store') }}" class="py-6 px-8" onsubmit="return confirmCommission();">
                @csrf

                <div class="mb-4 md:w-1/2">
                    <label for="user_id" class="block text-gray-700 text-sm font-bold mb-2">Vendedor</label>
                    <select name="user_id" id="user_id" class="w-full border rounded-md py-2 px-3" required>
                        <option value="">Seleccione un vendedor</option>
                        @foreach($sellers_users as $seller)
                                <option value="{{ $seller['user_id'] }}">{{ $seller['user'] }} Total a pagar : {{ $seller['sum'] }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="button--submit hidden bg-blue-500 text-white py-2 px-4 rounded-md">Liquidar comisión</button>
            </form>
            <div class="detail--ticket p-2">
                @foreach ($sellers_users as $seller)
                    <div data-id="{{$seller['user_id']}}" class="hidden overflow-hidden grid grid-cols-2 gap-1">
                        @foreach ($seller['detail'] as $detail)
                        <a href="/boletas?raffle_id={{$detail['ticket']->raffle_id}}&ticket_number={{$detail['ticket']->ticket_number}}" target="_blank" class="text-md hover:text-indigo-600 transition duration-500 ease-in-out p-1">
                            <div class="p-2 rounded mt-1 shadow-md flex justify-between">
                                <div>
                                    <p class="font-semibold">#{{$detail['ticket']->ticket_number}}</p>
                                    <p>{{$detail['ticket']->raffle->name}}</p>
                                </div>
                                <div class="text-right">
                                    
                                    <p >Valor boleta : ${{ number_format($detail['ticket']->raffle->price, 0)}}</p>
                                    <p >Comisión : ${{ number_format($detail['ticket']->assignment->commission, 0)}}</p>
                                    
                                </div>
                                    
                            </div>
                        </a>
                        @endforeach
                    </div>
                @endforeach
            </div>
            
        </div>
    </div>

    <script src="{{ asset('js/commission.js') }}"></script>
@endsection
