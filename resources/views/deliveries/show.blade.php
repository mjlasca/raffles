@extends('layouts.app')
@section('pageTitle', $delivery->description )
@section('content')
    <div class="container mx-auto mt-8">
        <div class="bg-white shadow-md overflow-hidden rounded-md">
            <div class="py-4 px-6 bg-blue-500 flex text-white fill-white">
                <h2 class="text-2xl font-semibold">{{ $delivery->description }}</h2>
            </div>

            <div class="p-6">
                <p class="mb-4"><strong>Descripción:</strong> {{ $delivery->description }}</p>
                <p class="mb-4"><strong>Rifa:</strong> {{ $delivery->raffle->name }}</p>
                <p class="mb-4"><strong>Vendedor:</strong> {{ $delivery->user->name }} {{ $delivery->user->lastname }}</p>
                
            </div>
        </div>
    </div>
@endsection
