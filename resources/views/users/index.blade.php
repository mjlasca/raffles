@extends('layouts.app')
@section('pageTitle', 'Usuarios')
@section('content')
    <div class="container mx-auto mt-8">
        <div class="bg-white shadow-md overflow-hidden rounded-md">
            <div class="py-4 px-6 bg-blue-500 flex text-white fill-white">
                <h2 class="text-2xl font-semibold">Usuarios</h2>
                <a class="ml-2 p-1 mt-1 bg-green-500 rounded-lg hover:bg-green-500" href="{{route('usuarios.create')}}">
                    <img class="h-5" src="{{ asset('img/icons/add-icon.svg') }}" alt="">
                </a>
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
                                <td class="py-2 px-4 flex">
                                    <a href="{{ route('usuarios.show', $user->id) }}" class="text-blue-500 hover:bg-green-500 p-1 bg-blue-500 rounded-md mr-1">
                                        <img class="h-5" src="{{ asset('img/icons/show-icon.svg') }}" alt="Ver registro" title="Ver registro">
                                    </a>
                                    <a href="{{ route('usuarios.edit', $user->id) }}" class="text-yellow-500 hover:bg-green-500 p-1 bg-blue-500 rounded-md mr-1">
                                        <img class="h-5" src="{{ asset('img/icons/edit-icon.svg') }}" alt="Editar" title="Editar">
                                    </a>
                                    <a href="{{ route('usuarios.destroy', $user->id) }}" class="text-yellow-500 hover:bg-green-500 p-1 bg-red-500 rounded-md mr-1">
                                        <img class="h-5" src="{{ asset('img/icons/delete-icon.svg') }}" alt="Eliminar" title="Eliminar">
                                    </a>
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
