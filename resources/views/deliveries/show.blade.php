@extends('layouts.app')
@section('pageTitle', $delivery->description )
@section('content')
    <div class="container mx-auto mt-8">
        <div class="bg-white shadow-md overflow-hidden rounded-md">
            <div class="py-4 px-6 @if($delivery->status == 0) bg-red-500 @else bg-blue-500 @endif flex text-white fill-white">
                <h2 class="text-2xl font-semibold">{{ $delivery->description }}</h2>
            </div>

            <div class="p-6">
                <p class="mb-4"><strong>Descripción:</strong> {{ $delivery->description }}</p>
                <p class="mb-4"><strong>Rifa:</strong> {{ $delivery->raffle->name }}</p>
                <p class="mb-4"><strong>Vendedor:</strong> {{ $delivery->user->name }} {{ $delivery->user->lastname }}</p>
                @if($delivery->status == 0)
                    <h3 class="bg-red-500 text-white h3 p-2">Entrega anulada</h3>
                    <p>A los siguientes números se les retorno el valor pagado correspondientemente</p>
                @endif
                @foreach ($payments as $pay)
                    <p><b>No. Boleta : </b>{{$pay['ticket_number']}} / <b>Valor pagado : </b> ${{number_format($pay['payment'],0)}} </p>
                @endforeach
            </div>
            
        </div>
    </div>
@endsection
