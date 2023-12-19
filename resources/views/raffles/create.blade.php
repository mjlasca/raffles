@extends('layouts.app')
@section('pageTitle', 'Crear Rifa' )
@section('content')
    <div class="container mx-auto mt-8">
        <div class="bg-white rounded-md overflow-hidden">
            <div class="py-4 px-6 bg-blue-500">
                <h2 class="text-2xl text-white font-semibold text-gray-800">Crear rifa</h2>
            </div>

            <form method="POST" action="{{ route('rifas.store') }}" class="py-6 px-8">
                @csrf

                <div class="mb-4 md:w-1/2">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Nombre:</label>
                    <input type="text" name="name" id="name" class="w-full border rounded-md py-2 px-3" required>
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Descripción:</label>
                    <textarea name="description" id="description" class="w-full border rounded-md py-2 px-3" required></textarea>
                </div>

                <div class="mb-4 w-52">
                    <label for="price" class="block text-gray-700 text-sm font-bold mb-2">Precio:</label>
                    <input type="number" name="price" id="price" class="w-full border rounded-md py-2 px-3" step="0.01" required>
                </div>

                <div class="mb-4 w-52">
                    <label for="raffle_date" class="block text-gray-700 text-sm font-bold mb-2">Fecha sorteo:</label>
                    <input type="date" name="raffle_date" id="raffle_date" class="w-full border rounded-md py-2 px-3" step="0.01" required>
                </div>

                <div class="mb-4 w-52">
                    <label for="tickets_number" class="block text-gray-700 text-sm font-bold mb-2">Número de boletas:</label>
                    <input type="number" name="tickets_number" id="tickets_number" class="w-full border rounded-md py-2 px-3" step="0.01" required>
                </div>

                <div class="mb-4 w-52">
                    <label for="ticket_commission" class="block text-gray-700 text-sm font-bold mb-2">Comisión por boleta:</label>
                    <input type="number" name="ticket_commission" id="ticket_commission" class="w-full border rounded-md py-2 px-3" step="0.01" required>
                </div>

                <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-md">Guardar</button>
            </form>
        </div>
    </div>
@endsection
