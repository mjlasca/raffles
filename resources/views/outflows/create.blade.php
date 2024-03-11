@extends('layouts.app')
@section('pageTitle', 'Crear salida' )
@section('content')
    <div class="container mx-auto mt-8">
        <div class="bg-white rounded-md overflow-hidden">
            <div class="py-4 px-6 bg-blue-500">
                <h2 class="text-2xl text-white font-semibold text-gray-800">Crear salida</h2>
            </div>

            <form method="POST" action="{{ route('salidas.store') }}" class="py-6 px-8">
                @csrf
                <div class="mb-4">
                    <label for="detail" class="block text-gray-700 text-sm font-bold mb-2">Detalle:</label>
                    <textarea name="detail" id="detail" class="w-full border rounded-md py-2 px-3" required maxlength="500"></textarea>
                </div>

                <div class="mb-4 w-52">
                    <label for="total" class="block text-gray-700 text-sm font-bold mb-2">Valor:</label>
                    <input type="number" name="total" id="total" class="w-full border rounded-md py-2 px-3"  required>
                </div>

                <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-md">Guardar</button>
            </form>
        </div>
    </div>

@endsection
