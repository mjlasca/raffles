@extends('layouts.app')
@section('pageTitle', $assignment->raffle->name  ."/".  $assignment->user->name  )
@section('content')
    <div class="container mx-auto mt-8">
        <div class="bg-white shadow-md overflow-hidden rounded-md">
            <div class="py-4 px-6 bg-blue-500 flex text-white fill-white">
                <h2 class="text-2xl font-semibold">{{ $assignment->raffle->name }} / {{ $assignment->user->name }} {{ $assignment->user->lastname }}</h2>
            </div>

            <div class="p-6">
                @if (!empty($assignment->user_referred))
                    <p class="mb-4"><strong>Referido:</strong> {{ $assignment->referred->name }} {{ $assignment->referred->lastname }} </p>    
                @endif
                
                <p class="mb-4"><strong>Total boletas:</strong> {{ $assignment->tickets_total }} </p>
                <div class="grid grid-cols-10 gap-2">
                @foreach ($table as $item)
                
                    <a target="_blank" href="{{route('boletas.index',['raffle_id' => $assignment->raffle->id, 'user_id' => $assignment->user->id, 'ticket_number' => $item])}}">
                        <p class="p-2 bg-gray-100" >{{ $item }}</p>
                    </a>
                @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
