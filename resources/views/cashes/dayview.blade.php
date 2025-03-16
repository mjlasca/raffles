@extends('layouts.app')
@section('pageTitle', "Vista Diaria" )
@section('content')
    <div class="container mx-auto mt-8">
        <div class="bg-white shadow-md overflow-hidden rounded-md">
            <div class="py-4 px-6 bg-blue-500 flex text-white fill-white">
                <h2 class="text-2xl font-semibold">Movimientos del {{$date}} | {{ $raffle_ }}</h2>
            </div>
            <form method="GET"  class="p-6">
                    
                <div class="md:flex w-80">
                    <div>
                        <select name="raffle_id" id="raffle_id" class="w-full border rounded-md py-2 px-3" >
                            <option value="">Todas las rifas</option>
                            @foreach($raffles as $raffle)
                                @if ($raffle->id == Request('raffle_id'))
                                    <option value="{{ $raffle->id }}" selected>{{ $raffle->name }}</option>    
                                @else
                                    <option value="{{ $raffle->id }}">{{ $raffle->name }}</option>
                                @endif
                                
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="bg-blue-500 text-white py-2 px-2 rounded-md w-full">Filtrar</button>
                </div>
                
            </form>
            @php
                $arraDay = ['Monday' => 'Lunes', 'Tuesday' => 'Martes', 'Wednesday' => 'Miércoles', 'Thursday' => 'Jueves','Friday' => "Viernes",'Saturday' => 'Sábado', 'Sunday' => 'Domingo']
            @endphp
            <div class="p-6">
                <p class="mb-4"><strong>Fecha:</strong> {{ $date }}</p>
                <p class="mb-4"><strong>Día:</strong> {{ $arraDay[\Carbon\Carbon::parse($date)->format('l')] }}</p>
                <p class="mb-4"><strong>Total Ingresos:</strong> ${{ number_format($deliveries->sum('total')) }}</p>
                <p class="mb-4"><strong>Total Egresos:</strong> ${{ number_format($outflows->sum('total')) }}</p>
                <p class="mb-4"><strong>Diferencia:</strong> ${{ number_format($deliveries->sum('total') - $outflows->sum('total')) }}</p>
            </div>
        </div>

        <div class="md:flex justify-between mt-3">
            <div class="overflow-x-auto max-w-50">
                <table class="">
                    <thead>
                        <tr class="text-md font-semibold tracking-wide text-left text-black uppercase border-b border-gray-600">
                            <th colspan="4" class="py-2 px-4 border-b  bg-yellow-500 dark:bg-yellow-500 text-center">Ingresos</th>
                        </tr>
                        <tr class="text-md font-semibold tracking-wide text-left text-white bg-green-500 uppercase border-b border-gray-600">
                            <th class="py-2 px-4 border-b">Fecha</th>
                            <th class="py-2 px-4 border-b">Consecutivo</th>
                            <th class="py-2 px-4 border-b">Vendedor</th>
                            <th class="py-2 px-4 border-b">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                       @foreach ($deliveries as $delivery)
                       <tr class="hover:bg-gray-100 border-b">
                        <td class="py-2 px-4 ">{{ \Carbon\Carbon::parse($delivery->created_at)->format('d/m/Y h:i:s') }}</td>
                        <td class="py-2 px-4 ">{{ $delivery->consecutive }}</td>
                        <td class="py-2 px-4 "> {{$delivery->raffle->name}} <br>{{ $delivery->user->name ." ". $delivery->user->lastname }}</td>
                        <td class="py-2 px-4 text-right">${{ number_format($delivery->total) }}</td>
                       </tr>
                       @endforeach
                    </tbody>
                </table>
                
            </div>
            <div class="overflow-x-auto w-2/5">
                <table class="">
                    <thead>
                        <tr class="text-md font-semibold tracking-wide text-left text-black uppercase border-b border-gray-600">
                            <th colspan="3" class="py-2 px-4 border-b  bg-yellow-500 dark:bg-yellow-500 text-center">Egresos</th>
                        </tr>
                        <tr class="text-md font-semibold tracking-wide text-left text-white bg-green-500 uppercase border-b border-gray-600">
                            <th class="py-2 px-4 border-b">Fecha</th>
                            <th class="py-2 px-4 border-b">Detalle</th>
                            <th class="py-2 px-4 border-b">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($outflows as $outflow)
                        <tr class="hover:bg-gray-100 border-b">
                         <td class="py-2 px-4 ">{{ \Carbon\Carbon::parse($outflow->created_at)->format('d/m/Y h:i:s') }}</td>
                         <td class="py-2 px-4 max-w-20">{{$delivery->raffle->name}} <br>{{ $outflow->detail }}</td>
                         <td class="py-2 px-4 text-right">${{ number_format($outflow->total) }}</td>
                        </tr>
                        @endforeach
                        @foreach ($commissions as $commission)
                        <tr class="hover:bg-gray-100 border-b">
                         <td class="py-2 px-4 ">{{ \Carbon\Carbon::parse($commission->created_at)->format('d/m/Y h:i:s') }}</td>
                         <td class="py-2 px-4 max-w-20">{{$delivery->raffle->name}} <br>Pago Comisión a {{ $commission->user->name." ".$commission->user->lastname }}</td>
                         <td class="py-2 px-4 text-right">${{ number_format($commission->total) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                
            </div>
        </div>
    </div>
@endsection
