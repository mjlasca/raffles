@extends('layouts.app')
@section('pageTitle', 'Arqueos')
@section('content')
    <div class="container mx-auto mt-8">
        <div class="bg-white shadow-md overflow-hidden rounded-md">
            <div class="py-4 px-6 bg-blue-500 flex text-white fill-white">
                <h2 class="text-2xl font-semibold">Arqueos</h2>
                <a class="ml-2 p-1 mt-1 bg-green-500 rounded-lg hover:bg-green-500" href="{{route('arqueos.export')}}?date1={{Request('date1')}}&date2={{Request('date2')}}">
                    <img class="h-5" src="{{ asset('img/icons/export-icon.svg') }}" alt="">
                </a>
            </div>
            

            <div>
                <form method="GET"  class="p-6">
                    
                    <div class="md:flex">
                        <div>
                            <label for="raffle_id" class="block text-gray-700 text-sm font-bold mb-2">Rifa</label>
                            <select name="raffle_id" id="raffle_id" class="w-full border rounded-md py-2 px-3" >
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
                        @foreach($cashes as $key => $cash)
                            @if ($key == 'totals')
                            <tr class="text-md font-semibold tracking-wide text-left bg-red-100 uppercase border-b border-gray-600">
                                <th colspan="2"></th>
                                <th class="py-2 px-4 border-b text-right">${{ number_format($cash['deliveries'],2)}}</th>
                                <th class="py-2 px-4 border-b text-right">${{ number_format($cash['outflows'],2)}}</th>
                                <th class="py-2 px-4 border-b text-right">${{ number_format($cash['commissions'],2)}}</th>
                                <th colspan="2"></th>
                            </tr>        
                            @endif
                        @endforeach
                        <tr class="text-md font-semibold tracking-wide text-left text-white bg-green-500 uppercase border-b border-gray-600">
                            <th class="py-2 px-4 border-b">Fecha</th>
                            <th class="py-2 px-4 border-b">Rifa</th>
                            <th class="py-2 px-4 border-b">Total entregas</th>
                            <th class="py-2 px-4 border-b">Total salidas</th>
                            <th class="py-2 px-4 border-b">Total comisiones</th>
                            <th class="py-2 px-4 border-b">Detalle cierre</th>
                            <th class="py-2 px-4 border-b">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cashes as $key => $cash)
                            @if ($key != 'totals')
                                <tr class="hover:bg-gray-100 border-b ">
                                    <td colspan="6" class="py-2 px-4 bg-green-300 dark:bg-green-500" colspan="7">{{ $key }}</td>
                                    <td class="py-2 px-4 bg-green-300 dark:bg-green-500" colspan="7">
                                        <a href="{{ route('arqueos.dayview', $key) }}" class="p-1 rounded-md mr-1">
                                            <img class="h-5" src="{{ asset('img/icons/show-icon.svg') }}" alt="Ver registro" title="Ver registro">
                                        </a>
                                    </td>
                                    @foreach ($cash as $item)
                                    <tr class="hover:bg-gray-100 border-b">
                                        <td class="py-2 px-4"></td>
                                        <td class="py-2 px-4">{{ $item['raffle']->name }}</td>
                                        <td class="py-2 px-4 text-right">${{ number_format($item['deliveries'] ?? 0,2) }}</td>
                                        <td class="py-2 px-4 text-right">${{ number_format($item['outflows'] ?? 0,2) }}</td>    
                                        <td class="py-2 px-4 text-right">${{ number_format($item['commissions'] ?? 0,2) }}</td>
                                        <td class="py-2 px-4"></td>
                                        <td class="py-2 px-4"></td>
                                    </tr>
                                    @endforeach    
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
                
            </div>
        </div>
        
    </div>
@endsection
