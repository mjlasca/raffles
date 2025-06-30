@extends('layouts.app')
@section('pageTitle', 'Informe Consolidado')
@section('content')
    <div class="container mx-auto mt-8">
        <div class="bg-white shadow-md overflow-hidden rounded-md">
            <div class="py-4 px-6 bg-blue-500 flex text-white fill-white">
                <h2 class="text-2xl font-semibold">Informe Consolidado</h2>
                <a class="ml-2 p-1 mt-1 bg-green-500 rounded-lg hover:bg-green-500" href="{{route('salidas.export')}}?date1={{Request('date1')}}&date2={{Request('date2')}}&raffle_id={{Request('raffle_id')}}&payment_method_id={{Request('payment_method_id')}}">
                    <img class="h-5" src="{{ asset('img/icons/export-icon.svg') }}" alt="">
                </a>
            </div>
            <div>
                <form method="GET"  class="p-6">

                    <div class="md:flex">
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
                            <label for="payment_method_id" class="block text-gray-700 text-sm font-bold mb-2">Método de pago</label>
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
                            <label for="office_id" class="block text-gray-700 text-sm font-bold mb-2">Oficina</label>
                            <select name="office_id" id="office_id" class="w-full border rounded-md py-2 px-3" >
                                <option value="">Seleccione una oficina</option>
                                @foreach($offices as $office)
                                    @if ($office->id == Request('office_id'))
                                        <option value="{{ $office->id }}" selected>{{ $office->description }}</option>
                                    @else
                                        <option value="{{ $office->id }}">{{ $office->description }}</option>
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
                            <th class="py-2 px-4 border-b">Oficina</th>
                            <th class="py-2 px-4 border-b">Generado por</th>
                            <th class="py-2 px-4 border-b">Detalle</th>
                            <th class="py-2 px-4 border-b text-right">Total</th>
                            <th class="py-2 px-4 border-b">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                       
                    </tbody>
                </table>

            </div>
        </div>

    </div>
@endsection
