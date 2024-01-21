@extends('layouts.app')
@section('pageTitle', 'Boleta #'.$ticket->ticket_number )
@section('content')
    <div class="container mx-auto mt-8">
        <div class="bg-white shadow-md overflow-hidden rounded-md">
            <div class="py-4 px-6 bg-blue-500 flex text-white fill-white">
                <h2 class="text-2xl font-semibold flex">
                
                 Rifa : {{ $ticket->raffle->name }} Boleta #{{ $ticket->ticket_number }}  </h2>
                 <a href="{{ route('boletas.edit', $ticket->id) }}" class="bg-green-500 p-1">
                    <img class="h-5" src="{{ asset('img/icons/edit-icon.svg') }}" alt="Editar" title="Editar">
                </a>
            </div>

            <div class="p-6">
                <p class="mb-4"><strong>Vendedor(a):</strong> {{ $ticket->user->name }} {{ $ticket->user->lastname }}</p>
                <p class="mb-4"><strong>Nombre cliente:</strong> {{ $ticket->customer_name }}</p>
                <p class="mb-4"><strong>Teléfono cliente:</strong> {{ $ticket->customer_phone }}</p>
                <p class="mb-4"><strong>Precio:</strong> ${{ $ticket->price }}</p>
                <p class="mb-4"><strong>Abonado:</strong> ${{ $ticket->payment }}</p>
                
            </div>
            <div class="flex">
                
            </div>

        </div>
    </div>
@endsection
