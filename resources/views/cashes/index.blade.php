@extends('layouts.app')
@section('pageTitle', 'Arqueos')
@section('content')
    <div class="container mx-auto mt-8">
        <div class="bg-white shadow-md overflow-hidden rounded-md">
            <div class="py-4 px-6 bg-blue-500 flex text-white fill-white">
                <h2 class="text-2xl font-semibold">Arqueos</h2>
                
            </div>
            

            <div>
                <form method="GET"  class="p-6">
                    
                    <div class="flex">
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
                    <button type="submit" class="mt-2 bg-blue-500 text-white py-2 px-4 rounded-md">Filtrar consulta</button>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="text-md font-semibold tracking-wide text-left text-white bg-green-500 uppercase border-b border-gray-600">
                            <th class="py-2 px-4 border-b">Fecha</th>
                            <th class="py-2 px-4 border-b">Total entregas</th>
                            <th class="py-2 px-4 border-b">Total salidas</th>
                            <th class="py-2 px-4 border-b">Total comisiones</th>
                            <th class="py-2 px-4 border-b">Detalle cierre</th>
                            <th class="py-2 px-4 border-b">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totals = ['deliveries' => 0,'outflows' => 0,'commission' => 0];
                        @endphp
                        @foreach($dayTotal as $key => $day)
                            <tr class="hover:bg-gray-100 border-b">
                                <td class="py-2 px-4">{{ $key }}</td>
                                <td class="py-2 px-4">{{ number_format($day['deliveries_total'],2) }}</td>
                                <td class="py-2 px-4">{{ number_format($day['outflows_total'],2) }}</td>
                                <td class="py-2 px-4">{{ number_format($day['commissions_total'],2) }}</td>
                                @if ( !empty( $day['cash']->id ))
                                    <td class="py-2 px-4">
                                        <b>Fecha cierre:</b> {{$day['cash']->updated_at}}<br>
                                        <b>Cerrado por:</b> {{$day['cash']->redited->name}} {{$day['cash']->redited->lastname}}<br>
                                        <b>Total:</b> {{$day['cash']->real_money_box}}<br>
                                        <b>Dinero manual:</b> {{$day['cash']->manual_money_box}}<br>
                                        <b>Diferencia:</b> {{$day['cash']->difference}}<br>
                                    </td>
                                @else
                                    <td class="py-2 px-4">No se ha cerrado el arqueo</td>
                                @endif
                                <td class="py-2 px-4 flex">
                                    @if ( !empty( $day['cash']->id ))
                                        <form action="{{ route('arqueos.destroy', $day['cash']) }}" method="POST" onsubmit="return confirmCommission('Está segur@ de eliminar la liquidación')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-yellow-500 hover:bg-green-500 p-1 bg-red-500 rounded-md mr-1"><img class="h-5" src="{{ asset('img/icons/delete-icon.svg') }}" alt="Eliminar registro" title="Ver registro"></button>
                                        </form>
                                    @else
                                        <a href="{{ route('arqueos.create', ['date' => $key]) }}" class="text-blue-500 hover:bg-green-500 p-1 bg-blue-500 rounded-md mr-1">
                                            <img class="h-5" src="{{ asset('img/icons/add-icon.svg') }}" alt="Cerrar día" title="Cerrar día">
                                        </a>
                                    @endif
                                    
                                    
                                </td>
                            </tr>
                            @php
                                $totals['deliveries'] = $totals['deliveries'] +  $day['deliveries_total'];
                                $totals['outflows'] = $totals['outflows'] +  $day['outflows_total'];
                                $totals['commission'] = $totals['commission'] +  $day['commissions_total'];
                            @endphp
                        @endforeach
                        @if ( !empty($totals) )
                            <tr class="bg-blue-100 border-b">
                                <td class="py-2 px-4"><b>TOTALES $</b></td>
                                <td class="py-2 px-4">{{ number_format($totals['deliveries'],2) }}</td>
                                <td class="py-2 px-4">{{ number_format($totals['outflows'],2) }}</td>
                                <td class="py-2 px-4">{{ number_format($totals['commission'],2) }}</td>
                                <td class="py-2 px-4" colspan="2"></td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                
            </div>
        </div>
        
    </div>
@endsection
