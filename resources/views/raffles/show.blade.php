@extends('layouts.app')
@section('pageTitle', $raffle->name )
@section('content')
    <div class="container mx-auto mt-8">
        <div class="bg-white shadow-md overflow-hidden rounded-md">
            @if ($raffle->disabled == 0)
                <div class="py-4 px-6 bg-blue-500 flex text-white fill-white">
            @else
                <div class="py-4 px-6 bg-orange-500 flex text-white fill-white">
            @endif
                <h2 class="text-2xl font-semibold">{{ $raffle->name }}</h2>
                <a class="ml-2 p-1 mt-1 bg-green-500 rounded-lg hover:bg-green-500" href="{{route('rifas.export_cons')}}?raffle_id={{$raffle->id}}">
                    <img class="h-5" src="{{ asset('img/icons/export-icon.svg') }}" alt="">
                </a>
            </div>

            <div class="p-6">
                <p class="mb-4"><strong>Descripción:</strong> {{ $raffle->description }}</p>
                <p class="mb-4"><strong>Precio:</strong> ${{ $raffle->price }}</p>
                <p class="mb-4"><strong>Boleta ganadora:</strong> {{ $raffle->winning_ticket_id }}</p>
                <p class="mb-4"><strong>Fecha sorteo:</strong> {{ $raffle->raffle_date }}</p>
                <p class="mb-4"><strong>Número de boletas:</strong> {{ $raffle->tickets_number }}</p>
                <p class="mb-4"><strong>Comisión por boleta:</strong> {{ $raffle->ticket_commission }}</p>
                @if ($raffle->raffle_status == 0)
                <p class="mb-4"><strong>Rifa asignada:</strong> NO</p>
                @else
                <p class="mb-4"><strong>Rifa asignada:</strong> SI</p>
                @endif
                <p class="mb-4 "><strong>Rifa abierta/cerrada:</strong> @if($raffle->disabled == 0 ) ABIERTA @else <span class="bg-orange-500">CERRADA</span> @endif</p>
                
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="text-md font-semibold tracking-wide text-left text-white bg-green-500 uppercase border-b border-gray-600">
                        <th class="py-2 px-4 border-b">Vendedor</th>
                        <th class="py-2 px-4 border-b text-right">#Boletos</th>
                        <th class="py-2 px-4 border-b text-right">Entregado</th>
                        <th class="py-2 px-4 border-b text-right">Canjeado</th>
                        <th class="py-2 px-4 border-b text-right">Comisión posible</th>
                        <th class="py-2 px-4 border-b text-right">Comisión ganada</th>
                        <th class="py-2 px-4 border-b text-right">Comisión liquidada</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $val['total_tickets'] = 0;
                        $val['total_deliveries'] = 0;
                        $val['total_payment'] = 0;
                        $val['total_assignment'] = 0;
                        $val['total_to_commission'] = 0;
                        $val['total_commissions'] = 0;
                    @endphp
                    @foreach($report as $item)
                        <tr class="hover:bg-gray-100 border-b">
                            <td class="py-2 px-4">{{ $item->user->name. " " . $item->user->lastname }}</td>
                            <td class="py-2 px-4 text-right">{{ $item->total_tickets }}</td>
                            <td class="py-2 px-4 text-right">${{ number_format($item->total_deliveries) }}</td>
                            <td class="py-2 px-4 text-right">${{ number_format($item->total_payment) }}</td>
                            <td class="py-2 px-4 text-right">${{ number_format($item->total_assignment) }}</td>
                            <td class="py-2 px-4 text-right">${{ number_format($item->total_to_commission) }}</td>
                            <td class="py-2 px-4 text-right">${{ number_format($item->total_commissions) }}</td>
                        </tr>
                        @php
                            $val['total_tickets'] += $item->total_tickets;
                            $val['total_deliveries'] += $item->total_deliveries;
                            $val['total_payment'] += $item->total_payment;
                            $val['total_assignment'] += $item->total_assignment;
                            $val['total_to_commission'] += $item->total_to_commission;
                            $val['total_commissions'] += $item->total_commissions;
                        @endphp
                    @endforeach
                    <tr class="bg-green-500 border-b">
                        <td class="py-2 px-4">TOTALES</td>
                        <td class="py-2 px-4 text-right">{{$val['total_tickets']}}</td>
                        <td class="py-2 px-4 text-right">${{number_format($val['total_deliveries'])}}</td>
                        <td class="py-2 px-4 text-right">${{number_format($val['total_payment'])}}</td>
                        <td class="py-2 px-4 text-right">${{number_format($val['total_assignment'])}}</td>
                        <td class="py-2 px-4 text-right">${{number_format($val['total_to_commission'])}}</td>
                        <td class="py-2 px-4 text-right">${{number_format($val['total_commissions'])}}</td>
                    </tr>
                </tbody>
            </table>

        </div>
    </div>
@endsection
