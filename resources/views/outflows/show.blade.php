@extends('layouts.app')
@section('pageTitle', $commission->id )
@section('content')
    <div class="container mx-auto mt-8">
        <div class="bg-white shadow-md overflow-hidden rounded-md">
            <div class="py-4 px-6 bg-blue-500 flex text-white fill-white">
                <h2 class="text-2xl font-semibold">Liquidación No. {{ $commission->id }}</h2>
            </div>

            <div class="p-6">
                <p class="mb-4"><strong>Vendedor:</strong> {{ $commission->user->name }} {{ $commission->user->lastname }}</p>
                <p class="mb-4"><strong>Total liquidado:</strong> ${{  number_format($commission->total,2) }}</p>
                <p class="mb-4"><strong>Detalle:</strong> 
                    @php
                    $array_vals = explode(';', $commission->detail)
                    @endphp
                        @foreach ($array_vals as $item)
                            <p>{{$item}}</p>
                        @endforeach
                </p>
                
            </div>
        </div>
    </div>
@endsection
