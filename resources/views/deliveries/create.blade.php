@extends('layouts.app')
@section('pageTitle', 'Crear entrega' )
@section('content')
    <div class="container mx-auto mt-8">
        <div class="bg-white rounded-md overflow-hidden">
            <div class="py-4 px-6 bg-blue-500">
                <h2 class="text-2xl text-white font-semibold text-gray-800">Crear entrega</h2>
            </div>

            <form method="POST" action="{{ route('entregas.store') }}" class="py-6 px-8">
                @csrf

                <div class="mb-4">
                    <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Descripción:</label>
                    <textarea name="description" id="description" class="w-full border rounded-md py-2 px-3" required></textarea>
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

                <div class="mb-4 md:w-1/2">
                    <label for="user_id" class="block text-gray-700 text-sm font-bold mb-2">Vendedor</label>
                    <select name="user_id" id="user_id" class="w-full border rounded-md py-2 px-3" required>
                        <option value="">Seleccione un vendedor</option>
                        @foreach($sellers_users as $seller)
                            <option value="{{ $seller->id }}">{{ $seller->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4 w-52">
                    <label for="total" class="block text-gray-700 text-sm font-bold mb-2">Valor entrega:</label>
                    <input type="number" name="total" id="total" class="w-full border rounded-md py-2 px-3" step="0.01" required>
                </div>


                <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-md">Guardar</button>
            </form>
        </div>
    </div>
@endsection
