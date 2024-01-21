@extends('layouts.app')
@section('pageTitle', $user->name )
@section('content')
    <div class="container mx-auto mt-8">
        <div class="bg-white shadow-md overflow-hidden rounded-md">
            <div class="py-4 px-6 bg-blue-500 flex text-white fill-white">
                <h2 class="text-2xl font-semibold">{{ $user->name }}</h2>
            </div>

            <div class="p-6">
                <p class="mb-4"><strong>Nombre:</strong> {{ $user->name }} {{ $user->lastname }}</p>
                <p class="mb-4"><strong>Email:</strong> {{ $user->email }}</p>
                <p class="mb-4"><strong>Teléfono:</strong> {{ $user->phone }}</p>
                <p class="mb-4"><strong>Dirección:</strong> {{ $user->address }}</p>
                <p class="mb-4"><strong>Rol:</strong> {{ $user->role }}</p>
                
            </div>
        </div>
    </div>
@endsection
