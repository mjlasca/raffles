@extends('layouts.app')
@section('pageTitle', 'Crear usuario' )
@section('content')
    <div class="container mx-auto mt-8">
        <div class="bg-white rounded-md overflow-hidden">
            <div class="py-4 px-6 bg-blue-500">
                <h2 class="text-2xl text-white font-semibold text-gray-800">Crear usuario</h2>
            </div>

            <form method="POST" action="{{ route('usuarios.store') }}" class="py-6 px-8">
                @csrf

                <div class="mb-4 md:w-1/2">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Nombres:</label>
                    <input type="text" name="name" id="name" maxlength="50" class="w-full border rounded-md py-2 px-3" required>
                </div>

                <div class="mb-4 md:w-1/2">
                    <label for="lastname" class="block text-gray-700 text-sm font-bold mb-2">Apellidos:</label>
                    <input type="text" name="lastname" id="lastname" maxlength="50" class="w-full border rounded-md py-2 px-3" required>
                </div>

                <div class="mb-4 md:w-1/2">
                    <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
                    <input type="email" name="email" id="email" class="w-full border rounded-md py-2 px-3" step="0.01" required>
                </div>

                <div class="mb-4 md:w-1/2">
                    <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Contraseña:</label>
                    <input type="password" name="password" maxlength="10" id="password" class="w-full border rounded-md py-2 px-3" step="0.01" required>
                </div>

                <div class="mb-4 md:w-1/2">
                    <label for="password1" class="block text-gray-700 text-sm font-bold mb-2">Contraseña:</label>
                    <input type="password" name="password1" maxlength="10" id="password1" class="w-full border rounded-md py-2 px-3" step="0.01" required>
                </div>

                <div class="mb-4 md:w-1/2">
                    <label for="phone" class="block text-gray-700 text-sm font-bold mb-2">Teléfono:</label>
                    <input type="text" name="phone" maxlength="10" id="phone" class="w-full border rounded-md py-2 px-3" required>
                </div>

                <div class="mb-4 md:w-1/2">
                    <label for="address" class="block text-gray-700 text-sm font-bold mb-2">Dirección:</label>
                    <input type="text" name="address" id="address" maxlength="100" class="w-full border rounded-md py-2 px-3" required>
                </div>

                <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-md">Guardar</button>
            </form>
        </div>
    </div>
@endsection
