@extends('layouts.app')
@section('pageTitle', 'Métodos de pago')
@section('content')
    <div class="container mx-auto mt-8">
        <div class="bg-white shadow-md overflow-hidden rounded-md">
            <div class="py-4 px-6 bg-blue-500 flex text-white fill-white">
                <h2 class="text-2xl font-semibold">Permisos para entregas</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="text-md font-semibold tracking-wide text-left text-white bg-green-500 uppercase border-b border-gray-600">
                            <th class="py-2 px-4 border-b">No.</th>
                            <th class="py-2 px-4 border-b">Entrega</th>
                            <th class="py-2 px-4 border-b">Solicitado por</th>
                            <th class="py-2 px-4 border-b">Dado por</th>
                            <th class="py-2 px-4 border-b">Fecha para el permiso</th>
                            <th class="py-2 px-4 border-b">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($deliveryPermission as $per)
                            <tr class="hover:bg-gray-100 border-b">
                                <td class="py-2 px-4">{{$per->id}}</td>
                                <td class="py-2 px-4">{{$per->delivery_id}}</td>
                                <td class="py-2 px-4">{{$per->user_requests}}</td>
                                <td class="py-2 px-4">{{$per->allow_user}}</td>
                                <td class="py-2 px-4">{{$per->date_permission}}</td>
                                <td class="py-2 px-4">{{$per->status}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
        <div class="pagination mt-5">

        </div>
    </div>
@endsection
