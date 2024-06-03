@extends('layouts.app')
@section('pageTitle', 'Usuarios')
@section('content')
    <div class="container mx-auto mt-8">
        <div class="bg-white shadow-md overflow-hidden rounded-md">
            <div class="py-4 px-6 bg-blue-500 flex  text-white fill-white">
                
                    <h2 class="text-2xl font-semibold">Usuarios</h2>
                    <a class="ml-2 p-1 mt-1 bg-green-500 rounded-lg hover:bg-green-500" href="{{route('usuarios.create')}}">
                        <img class="h-5" src="{{ asset('img/icons/add-icon.svg') }}" alt="">
                    </a>
                    <a class="ml-2 p-1 mt-1 bg-green-500 rounded-lg hover:bg-green-500" href="{{route('usuarios.export')}}">
                        <img class="h-5" src="{{ asset('img/icons/export-icon.svg') }}" alt="">
                    </a>
            </div>

            <div>
                <form method="GET"  class="p-6">
                    
                    <div class="md:flex">
                        <div>
                            <label for="keyword" class="block text-gray-700 text-sm font-bold mb-2">Buscar coincidencia</label>
                            <input type="text" class="w-full border rounded-md py-2 px-3" name="keyword" id="" value="{{ Request('keyword') }}" placeholder="Buscar en nombre,apellido,email">
                        </div>
                        <div>
                            <label for="role" class="block text-gray-700 text-sm font-bold mb-2">Rifa</label>
                            <select name="role" id="role" class="w-full border rounded-md py-2 px-3" >
                                <option value="">Seleccione el rol</option>
                                <option value="Administrador" @if(Request('role') == 'Administrador') selected @endif >Administrador</option>
                                <option value="Secretaria" @if(Request('role') == 'Secretaria') selected @endif >Secretaria</option>
                                <option value="Vendedor" @if(Request('role') == 'Vendedor') selected @endif >Vendedor</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="mt-2 bg-blue-500 text-white py-2 px-4 rounded-md w-full md:w-64">Filtrar consulta</button>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="text-md font-semibold tracking-wide text-left text-white bg-green-500 uppercase border-b border-gray-600">
                            <th class="py-2 px-4 border-b">Nombre</th>
                            <th class="py-2 px-4 border-b">Apellido</th>
                            <th class="py-2 px-4 border-b">Email</th>
                            <th class="py-2 px-4 border-b">Rol</th>
                            <th class="py-2 px-4 border-b">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr class="hover:bg-gray-100 border-b">
                                <td class="py-2 px-4">{{ $user->name }}</td>
                                <td class="py-2 px-4">{{ $user->lastname }}</td>
                                <td class="py-2 px-4">{{ $user->email }}</td>
                                <td class="py-2 px-4">{{ $user->role }}</td>
                                <td class="py-2 px-4 grid grid-cols-2 gap-2 md:flex">
                                    <a href="{{ route('usuarios.show', $user->id) }}" class="text-blue-500 hover:bg-green-500 p-1 bg-blue-500 rounded-md mr-1">
                                        <img class="h-5" src="{{ asset('img/icons/show-icon.svg') }}" alt="Ver registro" title="Ver registro">
                                    </a>
                                    <a href="{{ route('usuarios.edit', $user->id) }}" class="text-yellow-500 hover:bg-green-500 p-1 bg-blue-500 rounded-md mr-1">
                                        <img class="h-5" src="{{ asset('img/icons/edit-icon.svg') }}" alt="Editar" title="Editar">
                                    </a>
                                    <form action="{{ route('usuarios.delete', $user->id) }}" method="POST" onsubmit="return confirm('Está segur@ de eliminar el registro')">
                                        @csrf
                                        @method('PUT')
                                        <button class="text-yellow-500 hover:bg-green-500 p-1 bg-red-500 rounded-md mr-1"><img class="h-5" src="{{ asset('img/icons/delete-icon.svg') }}" alt="Eliminar registro" title="Ver registro"></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                
            </div>
        </div>
        <div class="mt-5">
            {{$users->links()}}
        </div>
    </div>
@endsection
