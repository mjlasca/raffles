@extends('layouts.app')
@section('pageTitle', 'Editar '.$raffle->name )
@section('content')
    <div class="container mx-auto mt-8">
        <div class="bg-white shadow-md overflow-hidden rounded-md">
            <div class="py-4 px-6 bg-blue-500 flex text-white fill-white">
                <h2 class="text-2xl font-semibold">Editar Rifa</h2>
            </div>

            <form method="POST" action="{{ route('rifas.update', $raffle->id) }}" class="p-6">
                @csrf
                @method('PUT')

                <div class="mb-4 md:w-1/2">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Nombre:</label>
                    <input type="text" name="name" id="name" class="w-full border rounded-md py-2 px-3" value="{{ $raffle->name }}" required>
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Descripción:</label>
                    <textarea name="description" id="description" class="w-full border rounded-md py-2 px-3" required>{{ $raffle->description }}</textarea>
                </div>

                <div class="mb-4 w-52">
                    <label for="price" class="block text-gray-700 text-sm font-bold mb-2">Precio:</label>
                    <input type="number" name="price" id="price" class="w-full border rounded-md py-2 px-3"  value="{{ $raffle->price }}" required>
                </div>

                <div class="mb-4 w-52">
                    <label for="raffle_date" class="block text-gray-700 text-sm font-bold mb-2">Fecha sorteo: </label>
                    <input type="date" name="raffle_date" id="raffle_date" class="w-full border rounded-md py-2 px-3" value="{{ \Carbon\Carbon::parse($raffle->raffle_date)->format('Y-m-d') }}" required>
                </div>

                <div class="mb-4 w-52">
                    <label for="tickets_number" class="block text-gray-700 text-sm font-bold mb-2">Número de boletas:</label>
                    <input type="number" name="tickets_number" id="tickets_number" class="w-full border rounded-md py-2 px-3" value="{{ $raffle->tickets_number }}"  required>
                </div>

                <div class="mb-4 w-52">
                    <label for="ticket_commission" class="block text-gray-700 text-sm font-bold mb-2">Comisión por boleta:</label>
                    <input type="number" name="ticket_commission" id="ticket_commission" class="w-full border rounded-md py-2 px-3" value="{{ $raffle->ticket_commission }}"  required>
                </div>

                <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-md">Actualizar Rifa</button>
            </form>
        </div>
    </div>
@endsection
