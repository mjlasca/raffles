@extends('layouts.app')
@section('pageTitle', $assignment->raffle->name  ."/".  $assignment->user->name  )
@section('content')
    <div class="container mx-auto mt-8">
        <div class="bg-white shadow-md overflow-hidden rounded-md">
            <div class="py-4 px-6 bg-blue-500 flex text-white fill-white">
                <h2 class="text-2xl font-semibold">{{ $assignment->raffle->name }} / {{ $assignment->user->name }}</h2>
            </div>

            <div class="p-6">
                <p class="mb-4"><strong>Total boletas:</strong> {{ $assignment->tickets_total }} </p>
                <div class="grid grid-cols-10 gap-4">
                @foreach ($table as $item)
                    <p class="bg-gray-100" >{{ $item }}</p>
                @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
