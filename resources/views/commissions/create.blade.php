@extends('layouts.app')
@section('pageTitle', 'Crear comisión' )
@section('content')
    <div class="container mx-auto mt-8">
        <div class="bg-white rounded-md overflow-hidden">
            <div class="py-4 px-6 bg-blue-500">
                <h2 class="text-2xl text-white font-semibold text-gray-800">Crear comisión</h2>
            </div>

            <div>
                
                    @csrf
                    <div>
                        <table class="min-w-full">
                            <thead>
                                <tr class="text-md font-semibold tracking-wide text-left text-white bg-green-500 uppercase border-b border-gray-600">
                                    <th class="py-2 px-4 border-b">Rifa</th>
                                    <th class="py-2 px-4 border-b">Vendedor(a)</th>
                                    <th class="py-2 px-4 border-b">Suma</th>
                                    <th class="py-2 px-4 border-b">Acción</th>
        
                                </tr>
                            </thead>
                            <tbody>
                               @foreach ($sellers_users as $key => $data)
                                @foreach ($data as $ticket)
                                    <tr class="hover:bg-gray-100 border-b">
                                        <td class="py-2 px-4">{{ $ticket['raffle']->name }}</td>
                                        <td class="py-2 px-4">{{ $ticket['user'] }}</td>
                                        <td class="py-2 px-4">${{ number_format($ticket['sum']) }}</td>
                                        <td class="py-2 px-4 ">
                                            <div class="text-left">
                                                <div class="text-left mb-2">
                                                    <form method="POST" action="{{ route('comisiones.store') }}" onsubmit="return confirmCommission();">
                                                        @csrf
                                                        <input type="hidden" value="{{$ticket['user_id']}}" name="user_id">
                                                        <input type="hidden" value="{{$ticket['raffle']->id}}" name="raffle_id">
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
                                            <div class="detail--ticket bg-green-100 p-2 grid grid-cols-3 hidden detail-{{$ticket['raffle']->id}}{{$ticket['user_id']}}">
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
