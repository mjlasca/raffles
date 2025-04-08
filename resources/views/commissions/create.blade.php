@extends('layouts.app')
@section('pageTitle', 'Crear comisión' )
@section('content')
    <div class="container mx-auto mt-8">
        <div class="bg-white rounded-md overflow-hidden">
            <div class="py-4 px-6 bg-blue-500">
                <h2 class="text-2xl text-white font-semibold text-gray-800">Crear comisión</h2>
            </div>

            <div>

                    <div>
                        <form method="GET"  class="p-6">
                            <div class="md:flex">
                                <div>
                                    <label for="user_id" class="block text-gray-700 text-sm font-bold mb-2">Vendedor</label>
                                    <select name="user_id" id="user_id" class="w-full border rounded-md py-2 px-3" >
                                        <option value="">Seleccione un vendedor</option>
                                        @foreach($sellers as $seller)
                                            @if ($seller->id == Request('user_id'))
                                                <option value="{{ $seller->id }}" selected>{{ $seller->name }} {{ $seller->lastname }}</option>
                                            @else
                                                <option value="{{ $seller->id }}">{{ $seller->name }} {{ $seller->lastname }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="raffle_id" class="block text-gray-700 text-sm font-bold mb-2">Rifas</label>
                                    <select name="raffle_id" id="raffle_id" class="w-full border rounded-md py-2 px-3" required >
                                        <option value="">Seleccione una rifa</option>
                                        @foreach($raffles as $raffle)
                                            @if ($raffle->id == Request('raffle_id'))
                                                <option value="{{ $raffle->id }}" selected>{{ $raffle->name }}</option>
                                            @else
                                                <option value="{{ $raffle->id }}">{{ $raffle->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <button type="submit" class="mt-2 bg-blue-500 text-white py-2 px-4 rounded-md w-full md:w-64">Filtrar consulta</button>
                        </form>
                    </div>
                    <div>
                        <table class="min-w-full">
                            <thead>
                                <tr class="text-md font-semibold tracking-wide text-left text-white bg-green-500 uppercase border-b border-gray-600">
                                    <th class="py-2 px-4 border-b" colspan="2"></th>
                                    <th class="py-2 px-4 border-b text-right">${{ number_format($totalCommission)}}</th>

                                </tr>
                                <tr class="text-md font-semibold tracking-wide text-left text-white bg-green-500 uppercase border-b border-gray-600">
                                    <th class="py-2 px-4 border-b">Rifa</th>
                                    <th class="py-2 px-4 border-b">Vendedor(a)</th>
                                    <th class="py-2 px-4 border-b text-right">Suma</th>
                                    <th class="py-2 px-4 border-b">Acción</th>

                                </tr>
                            </thead>
                            <tbody>
                               @foreach ($sellers_users as $key => $data)
                                @foreach ($data as $ticket)
                                    <tr class="hover:bg-gray-100 border-b">
                                        <td class="py-2 px-4">{{ $ticket['raffle']->name }}</td>
                                        <td class="py-2 px-4">{{ $ticket['user'] }}</td>
                                        <td class="py-2 px-4 text-right">${{ number_format($ticket['sum']) }}</td>
                                        <td class="py-2 px-4 ">
                                            <div class="text-left">
                                                <div class="text-left mb-2">
                                                    <form method="POST" action="{{ route('comisiones.store') }}" onsubmit="return confirmCommission();">
                                                        @csrf
                                                        <input type="hidden" value="{{$ticket['user_id']}}" name="user_id">
                                                        <input type="hidden" value="{{$ticket['raffle']->id}}" name="raffle_id">
                                                        <div class="mb-4 md:w-1/2">
                                                            <select name="payment_method_id" id="payment_method_id" class="w-full border rounded-md py-2 px-3" required>
                                                                <option value="">Seleccione método pago</option>
                                                                @foreach($paymentMethods as $pay)
                                                                    @if ($pay->id == old("payment_method_id"))
                                                                        <option value="{{ $pay->id }}" selected>{{ $pay->description }}</option>
                                                                    @else
                                                                    <option value="{{ $pay->id }}">{{ $pay->description }}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <button class="text-white hover:bg-green-500 p-2 px-2 bg-blue-500 rounded-md mr-1 ">Registrar Comisión</button>
                                                    </form>
                                                </div>
                                                <a href="javascript:void(0);" onclick="showAndHide('detail-{{$ticket['raffle']->id}}{{$ticket['user_id']}}')" class="text-white hover:bg-blue-500 p-1 bg-green-500 rounded-md mr-1">
                                                    Ver detalle
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4">
                                            <div class="detail--ticket bg-green-100 dark:text-gray-400 p-2 grid grid-cols-3 hidden detail-{{$ticket['raffle']->id}}{{$ticket['user_id']}}">
                                                @foreach ($ticket['detail'] as $detail)
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
                                        </td>
                                    </tr>
                                @endforeach
                               @endforeach
                            </tbody>
                        </table>
                    </div>

                    <button type="submit" class="button--submit hidden bg-blue-500 text-white py-2 px-4 rounded-md">Liquidar comisión</button>

            </div>

        </div>
    </div>

    <script src="{{ asset('js/commission.js') }}"></script>
@endsection
