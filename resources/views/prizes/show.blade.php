@extends('layouts.app')
@section('pageTitle', \Illuminate\Support\Str::limit($prize->detail, $longitud = 50, $terminador = '...') )
@section('content')
    <div class="container mx-auto mt-8">
        <div class="bg-white shadow-md overflow-hidden rounded-md">
            <div class="py-4 px-6 bg-blue-500 flex text-white fill-white">
                <h2 class="text-2xl font-semibold">{{ \Illuminate\Support\Str::limit($prize->detail, $longitud = 50, $terminador = '...') }}</h2>
            </div>

            <div class="p-6">
                <p class="mb-4"><strong>Tipo de premio:</strong> {{ $prize->type }}</p>
                <p class="mb-4"><strong>Descripción:</strong> {{ $prize->detail }}</p>
                <p class="mb-4"><strong>Rifa:</strong> {{ $prize->raffle->name }}</p>
                <p class="mb-4"><strong>Participa con:</strong> {{ number_format($prize->percentage_condition,0) }}%</p>
                <p class="mb-4"><strong>Fecha sorteo:</strong> {{ $prize->award_date }}</p>
                <p class="mb-4"><strong>Boleto ganador:</strong> {{ $prize->winning_ticket }}</p>
                
            </div>
        </div>
    </div>
@endsection
