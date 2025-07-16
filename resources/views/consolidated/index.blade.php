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
                            <th class="py-2 px-4 border-b border-r bg-yellow-400 text-center text-black" rowspan="2">Fecha</th>
                            @if (isset($headerConsolidate['deliveries']))
                            <th class="py-2 px-4 border-b border-r bg-yellow-400 text-center text-black" colspan="{{ count($headerConsolidate['deliveries']) }}">ENTREGAS</th>
                            @endif
                            @if (isset($headerConsolidate['outflows']))
                            <th class="py-2 px-4 border-b border-r bg-yellow-400 text-center text-black" colspan="{{ count($headerConsolidate['outflows']) }}">SALIDAS</th>
                            @endif
                            @if (isset($headerConsolidate['commissions']))
                            <th class="py-2 px-4 border-b border-r bg-yellow-400 text-center text-black" colspan="{{ count($headerConsolidate['commissions']) }}">COMISIONES</th>
                            @endif
                            @if (!empty($headerTotals))
                            <th class="py-2 px-4 border-b border-r bg-yellow-400 text-center text-black" colspan="{{ count($headerTotals) }}">TOTALES</th>
                            @endif
                        </tr>
                        <tr class="text-md font-semibold tracking-wide text-left text-white bg-green-500 uppercase border-b border-gray-600">
                            @if (isset($headerConsolidate['deliveries']))
                                @foreach ($headerConsolidate['deliveries'] as $header)
                                    <th class="py-2 px-4 border-b text-right">{{ $header }}</th>
                                @endforeach
                            @endif
                            @if (isset($headerConsolidate['outflows']))
                                @foreach ($headerConsolidate['outflows'] as $header)
                                    <th class="py-2 px-4 border-b text-right">{{ $header }}</th>
                                @endforeach
                            @endif
                            @if (isset($headerConsolidate['commissions']))
                                @foreach ($headerConsolidate['commissions'] as $header)
                                    <th class="py-2 px-4 border-b text-right">{{ $header }}</th>
                                @endforeach
                            @endif
                            @foreach ($headerTotals as $header)
                                <th class="py-2 px-4 border-b bg-blue-500 text-right">{{ $header }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                       @foreach ($consolidateArr as $key => $consolidate)
                            <tr class="border-b hover:bg-gray-100">
                            <td class="text-center">{{$key}}</td>
                                @if (isset($consolidate))
                                    @if (isset($headerConsolidate['deliveries']))
                                        @foreach ($headerConsolidate['deliveries'] as $k => $header)
                                            @if (isset($consolidate['deliveries'.$k]))
                                                <td class="text-right border-x p-2">${{ number_format($consolidate['deliveries'.$k]) }}</td>    
                                            @else
                                                <td class="border-x p-2"></td>
                                            @endif
                                        @endforeach
                                    @endif
                                    @if (isset($headerConsolidate['outflows']))
                                        @foreach ($headerConsolidate['outflows'] as $k => $header)
                                            @if (isset($consolidate['outflows'.$k]))
                                                <td class="text-right border-x p-2">${{ number_format($consolidate['outflows'.$k]) }}</td>    
                                            @else
                                                <td class="border-x p-2"></td>
                                            @endif
                                        @endforeach
                                    @endif
                                    @if (isset($headerConsolidate['commissions']))
                                        @foreach ($headerConsolidate['commissions'] as $k => $header)
                                            @if (isset($consolidate['commissions'.$k]))
                                                <td class="text-right border-x p-2">${{ number_format($consolidate['commissions'.$k]) }}</td>    
                                            @else
                                                <td class="border-x p-2"></td>
                                            @endif
                                        @endforeach
                                    @endif
                                    @foreach ($headerTotals as $k => $header)
                                        @if (isset($rowsTotals[$key.$k]))
                                            @if ( !isset($consolidate['outflows'.$k]) )
                                                <td class="text-right border-x p-2">${{ number_format($rowsTotals[$key.$k])  }}</td>
                                            @else     
                                                <td class="text-right border-x p-2">${{ number_format($rowsTotals[$key.$k] - $consolidate['outflows'.$k] )  }}</td>
                                            @endif
                                        @else
                                            <td class="border-x p-2"></td>
                                        @endif
                                    @endforeach
                                @endif
                            </tr>
                       @endforeach
                    </tbody>
                </table>

            </div>
        </div>

    </div>
@endsection
