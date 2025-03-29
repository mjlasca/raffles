@extends('layouts.app')
@section('pageTitle', 'Comisiones')
@section('content')
    <div class="container mx-auto mt-8">
        <div class="bg-white shadow-md overflow-hidden rounded-md">
            <div class="py-4 px-6 bg-blue-500 flex text-white fill-white">
                <h2 class="text-2xl font-semibold">Comisiones</h2>
                <a class="ml-2 p-1 mt-1 bg-green-500 rounded-lg hover:bg-green-500" href="{{route('comisiones.create')}}">
                    <img class="h-5" src="{{ asset('img/icons/add-icon.svg') }}" alt="">
                </a>
                <a class="ml-2 p-1 mt-1 bg-green-500 rounded-lg hover:bg-green-500" href="{{route('comisiones.export')}}?date1={{Request('date1')}}&date2={{Request('date2')}}&raffle_id={{Request('raffle_id')}}&payment_method_id={{Request('payment_method_id')}}">
                    <img class="h-5" src="{{ asset('img/icons/export-icon.svg') }}" alt="">
                </a>
            </div>
            <div>
                <form method="GET"  class="p-6">

                    <div class="md:flex">
                        <div>
                            <label for="keyword" class="block text-gray-700 text-sm font-bold mb-2">Buscar coincidencia</label>
                            <input type="text" class="w-full border rounded-md py-2 px-3" name="keyword" id="" value="{{ Request('keyword') }}" placeholder="Buscar usuario">
                        </div>
                        <div>
                            <label for="raffle_id" class="block text-gray-700 text-sm font-bold mb-2">Rifas</label>
                            <select name="raffle_id" id="raffle_id" class="w-full border rounded-md py-2 px-3"  >
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
                        <div>
                            <label for="raffle_id" class="block text-gray-700 text-sm font-bold mb-2">Método de pago</label>
                            <select name="payment_method_id" id="payment_method_id" class="w-full border rounded-md py-2 px-3" >
                                <option value="">Seleccione un método</option>
                                @foreach($paymentMethods as $pay)
                                    @if ($pay->id == Request('payment_method_id'))
                                        <option value="{{ $pay->id }}" selected>{{ $pay->description }}</option>
                                    @else
                                        <option value="{{ $pay->id }}">{{ $pay->description }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="date1" class="block text-gray-700 text-sm font-bold mb-2">Fecha inicial</label>
                            <input type="date" class="w-full border rounded-md py-2 px-3" name="date1" id="" value="{{ Request('date1') }}">
                        </div>
                        <div>
                            <label for="date2" class="block text-gray-700 text-sm font-bold mb-2">Fecha final</label>
                            <input type="date" class="w-full border rounded-md py-2 px-3" name="date2" id="" value="{{Request('date2')}}">
                        </div>
                    </div>
                    <button type="submit" class="mt-2 bg-blue-500 text-white py-2 px-4 rounded-md w-full md:w-64">Filtrar consulta</button>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="text-md font-semibold tracking-wide text-left text-white bg-green-500 uppercase border-b border-gray-600">
                            <th class="py-2 px-4 border-b">id</th>
                            <th class="py-2 px-4 border-b">Fecha</th>
                            <th class="py-2 px-4 border-b">Rifa</th>
                            <th class="py-2 px-4 border-b">Método de pago</th>
                            <th class="py-2 px-4 border-b">Vendedor(a)</th>
                            <th class="py-2 px-4 border-b">Total liquidado</th>
                            <th class="py-2 px-4 border-b">Ver</th>

                        </tr>
                    </thead>
                    <tbody>
                       @foreach ($commissions as $commission)
                        <tr class="hover:bg-gray-100 border-b">
                            <td class="py-2 px-4">{{ $commission->id }}</td>
                            <td class="py-2 px-4">{{ $commission->updated_at }}</td>
                            <td class="py-2 px-4">{{ $commission->raffle->name }}</td>
                            @if (empty($commission->payment_method_id))
                                <td class="py-2 px-4">-- N/A --</td>
                            @else
                                <td class="py-2 px-4">{{ $commission->paymentMethod->description }}</td>
                            @endif
                            <td class="py-2 px-4">{{ $commission->user->name }} {{ $commission->user->lastname }}</td>
                            <td class="py-2 px-4">${{  number_format($commission->total,2) }}</td>

                            <td class="py-2 px-4 ">
                                <div class="grid gap-2 md:flex">
                                    <a href="{{ route('comisiones.show', $commission->id) }}" class="text-blue-500 hover:bg-green-500 p-1 bg-blue-500 rounded-md mr-1">
                                        <img class="h-5 max-w-5" src="{{ asset('img/icons/show-icon.svg') }}" alt="Ver registro" title="Ver registro">
                                    </a>
                                    <form action="{{ route('comisiones.destroy', $commission) }}" method="POST" onsubmit="return confirmCommission('Está segur@ de eliminar la liquidación')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-yellow-500 hover:bg-green-500 p-1 bg-red-500 rounded-md mr-1 "><img class="h-5  max-w-5" src="{{ asset('img/icons/delete-icon.svg') }}" alt="Eliminar registro" title="Ver registro"></button>
                                    </form>
                                </div>

                            </td>
                        </tr>
                       @endforeach
                    </tbody>
                </table>

            </div>
        </div>
        <div class="pagination mt-5">
            {{$commissions->links()}}
        </div>

    </div>
    <script src="{{ asset('js/commission.js') }}"></script>
@endsection
