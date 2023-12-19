@extends('layouts.app')
@section('pageTitle', $raffle->name )
@section('content')
    <div class="container mx-auto mt-8">
        <div class="bg-white shadow-md overflow-hidden rounded-md">
            <div class="py-4 px-6 bg-blue-500 flex text-white fill-white">
                <h2 class="text-2xl font-semibold">{{ $raffle->name }}</h2>
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
                
            </div>
        </div>
    </div>
@endsection
