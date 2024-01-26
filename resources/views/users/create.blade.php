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
                    <input type="text" name="name" id="name" title="Sólo letras, máximo 50 caracteres" pattern="[A-Za-z\s]{3,50}" maxlength="50" value="{{old('name')}}" class="w-full border rounded-md py-2 px-3" required>
                </div>

                <div class="mb-4 md:w-1/2">
                    <label for="lastname" class="block text-gray-700 text-sm font-bold mb-2">Apellidos:</label>
                    <input type="text" name="lastname" id="lastname" title="Sólo letras, máximo 50 caracteres" pattern="[A-Za-z\s]{3,50}" maxlength="50" value="{{old('lastname')}}" class="w-full border rounded-md py-2 px-3" required>
                </div>

                <div class="mb-4 md:w-1/2">
                    <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
                    <input type="email" name="email" id="email" value="{{old('email')}}" class="w-full border rounded-md py-2 px-3" step="0.01" required>
                </div>

                <div class="mb-4 md:w-1/2">
                    <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Contraseña:</label>
                    <input type="password" name="password" maxlength="10" id="password" class="w-full border rounded-md py-2 px-3" step="0.01" required>
                </div>

                <div class="mb-4 md:w-1/2">
                    <label for="password_confirmation" class="block text-gray-700 text-sm font-bold mb-2">Contraseña:</label>
                    <input type="password" name="password_confirmation" maxlength="10" id="password_confirmation" class="w-full border rounded-md py-2 px-3" step="0.01" required>
                </div>

                <div class="mb-4 md:w-1/2">
                    <label for="role" class="block text-gray-700 text-sm font-bold mb-2">Rifa</label>
                    <select name="role" id="role" class="w-full border rounded-md py-2 px-3" required>
                        <option value="">Seleccione el rol</option>
                        <option value="Administrador">Administrador</option>
                        <option value="Secretaria">Secretaria</option>
                        <option value="Vendedor">Vendedor</option>
                    </select>
                </div>

                <div class="mb-4 md:w-1/2">
                    <label for="phone" class="block text-gray-700 text-sm font-bold mb-2">Teléfono:</label>
                    <input type="text" name="phone" maxlength="10" pattern="[0-9]{10,10}" title="El teléfono debe tener sólo números y 10 caracteres" id="phone" class="w-full border rounded-md py-2 px-3"  value="{{old('phone')}}" required>
                </div>

                <div class="mb-4 md:w-1/2">
                    <label for="address" class="block text-gray-700 text-sm font-bold mb-2">Dirección:</label>
                    <input type="text" name="address" id="address" maxlength="100" class="w-full border rounded-md py-2 px-3"  value="{{old('address')}}" required>
                </div>

                <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-md">Guardar</button>
            </form>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
@endsection
