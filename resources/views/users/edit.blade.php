@extends('layouts.app')
@section('pageTitle', 'Editar '.$user->name )
@section('content')
    <div class="container mx-auto mt-8">
        <div class="bg-white shadow-md overflow-hidden rounded-md">
            <div class="py-4 px-6 bg-blue-500 flex text-white fill-white">
                <h2 class="text-2xl font-semibold">Editar Usuario / {{ $user->email }}</h2>
            </div>

            <form method="POST" action="{{ route('usuarios.update', $user->id) }}" class="p-6">
                @csrf
                @method('PUT')

                <div class="mb-4 md:w-1/2">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Nombres:</label>
                    <input type="text" name="name" id="name" title="Sólo letras, máximo 50 caracteres" pattern="[A-Za-z\s]{3,50}" class="w-full border rounded-md py-2 px-3" value="{{ $user->name }}" required>
                </div>

                <div class="mb-4 md:w-1/2">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Apellidos:</label>
                    <input type="text" name="lastname" title="Sólo letras, máximo 50 caracteres" pattern="[A-Za-z\s]{3,50}" id="lastname" class="w-full border rounded-md py-2 px-3" value="{{ $user->lastname }}" required>
                </div>

                <div class="mb-4 md:w-1/2">
                    <label for="phone" class="block text-gray-700 text-sm font-bold mb-2">Teléfono:</label>
                    <input type="text" name="phone" id="phone" pattern="[0-9]{10,10}" title="El teléfono debe tener sólo números y 10 caracteres" class="w-full border rounded-md py-2 px-3"  value="{{ $user->phone }}" required>
                </div>

                <div class="mb-4">
                    <label for="address" class="block text-gray-700 text-sm font-bold mb-2">Dirección:</label>
                    <input type="text" name="address" id="address" class="w-full border rounded-md py-2 px-3"  value="{{ $user->address }}" required>
                </div>

                <div class="mb-4 md:w-1/2">
                    <label for="role" class="block text-gray-700 text-sm font-bold mb-2">Rifa</label>
                    <select name="role" id="role" class="w-full border rounded-md py-2 px-3" required>
                        <option value="">Seleccione el rol</option>
                        <option value="Administrador" @if($user->role == 'Administrador') selected @endif >Administrador</option>
                        <option value="Secretaria" @if($user->role == 'Secretaria') selected @endif >Secretaria</option>
                        <option value="Vendedor" @if($user->role == 'Vendedor') selected @endif >Vendedor</option>
                    </select>
                </div>

                <div class="mb-4 md:w-1/2">
                    <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Contraseña:</label>
                    <input type="password" name="password" id="password" class="w-full border rounded-md py-2 px-3"  maxlength="8" >
                </div>

                <div class="mb-4 md:w-1/2">
                    <label for="password1" class="block text-gray-700 text-sm font-bold mb-2">Repita la contraseña:</label>
                    <input type="password" name="password1" id="password1" class="w-full border rounded-md py-2 px-3" maxlength="8"  >
                </div>

                <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-md">Actualizar</button>
            </form>
        </div>
    </div>
@endsection
