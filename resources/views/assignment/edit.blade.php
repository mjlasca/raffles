@extends('layouts.app')
@section('pageTitle', 'Editar '.$user->name )
@section('content')
    <div class="container mx-auto mt-8">
        <div class="bg-white shadow-md overflow-hidden rounded-md">
            <div class="py-4 px-6 bg-blue-500 flex text-white fill-white">
                <h2 class="text-2xl font-semibold">Editar Usuario</h2>
            </div>

            <form method="POST" action="{{ route('usuarios.update', $user->id) }}" class="p-6">
                @csrf
                @method('PUT')

                <div class="mb-4 md:w-1/2">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Nombre:</label>
                    <input type="text" name="name" id="name" class="w-full border rounded-md py-2 px-3" value="{{ $user->name }}" required>
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Descripción:</label>
                    <textarea name="description" id="description" class="w-full border rounded-md py-2 px-3" required>{{ $user->description }}</textarea>
                </div>

                <div class="mb-4 w-52">
                    <label for="price" class="block text-gray-700 text-sm font-bold mb-2">Precio:</label>
                    <input type="number" name="price" id="price" class="w-full border rounded-md py-2 px-3" step="0.01" value="{{ $user->price }}" required>
                </div>

                <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-md">Actualizar</button>
            </form>
        </div>
    </div>
@endsection
