@extends('layouts.app')
@section('pageTitle', 'Crear premio' )
@section('content')
    <div class="container mx-auto mt-8">
        <div class="bg-white rounded-md overflow-hidden">
            <div class="py-4 px-6 bg-blue-500">
                <h2 class="text-2xl text-white font-semibold text-gray-800">Crear premio</h2>
            </div>

            <form method="POST" action="{{ route('premios.store') }}" class="py-6 px-8">
                @csrf

                <div class="mb-4">
                    <label for="detail" class="block text-gray-700 text-sm font-bold mb-2">Detalle:</label>
                    <textarea name="detail" id="detail" class="w-full border rounded-md py-2 px-3" maxlength="500" required></textarea>
                </div>

                <div class="mb-4 md:w-1/2">
                    <label for="raffle_id" class="block text-gray-700 text-sm font-bold mb-2">Rifa</label>
                    <select name="raffle_id" id="raffle_id" class="w-full border rounded-md py-2 px-3" required>
                        <option value="">Seleccione una rifa</option>
                        @foreach($raffles as $raffle)
                            <option value="{{ $raffle->id }}">{{ $raffle->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4 md:w-1/5">
                    <label for="percentage_condition" class="block text-gray-700 text-sm font-bold mb-2">Condición de porcentaje:</label>
                    <input type="number" name="percentage_condition" min="0" max="100" id="percentage_condition" class="w-full border rounded-md py-2 px-3" required>
                </div>

                <div class="mb-4 md:w-1/5">
                    <label for="award_date" class="block text-gray-700 text-sm font-bold mb-2">Fecha sorteo:</label>
                    <input type="date" name="award_date" id="award_date" class="w-full border rounded-md py-2 px-3" min="{{ now()->format('Y-m-d') }}" required>
                </div>

                <div class="mb-4 md:w-1/5">
                    <label for="winning_ticket" class="block text-gray-700 text-sm font-bold mb-2">Boleto ganador:</label>
                    <input type="text" name="winning_ticket" maxlength="10" pattern="[0-9]{0,10}" title="El número ganador debe tener sólo números" id="winning_ticket" class="w-full border rounded-md py-2 px-3">
                </div>

                <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-md">Guardar</button>
            </form>
        </div>
    </div>
@endsection
