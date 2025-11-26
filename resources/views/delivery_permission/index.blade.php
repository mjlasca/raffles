@extends('layouts.app')
@section('pageTitle', 'Permisos entregas')
@section('content')
    <div class="container mx-auto mt-8">
        <div class="bg-white shadow-md overflow-hidden rounded-md">
            <div class="py-4 px-6 bg-blue-500 flex text-white fill-white">
                <h2 class="text-2xl font-semibold">Permisos entregas</h2>
            </div>
            <div>
                <form method="GET" class="p-6">

                    <div class="md:flex">
                        <div>
                            <label for="keyword" class="block text-gray-700 text-sm font-bold mb-2">Buscar
                                coincidencia</label>
                            <input type="text" class="w-full border rounded-md py-2 px-3" name="keyword" id=""
                                value="{{ Request('keyword') }}" placeholder="Buscar...">
                        </div>
                        <div>
                            <label for="date1" class="block text-gray-700 text-sm font-bold mb-2">Fecha inicial</label>
                            <input type="date" class="w-full border rounded-md py-2 px-3" name="date1" id=""
                                value="{{ Request('date1') }}">
                        </div>
                        <div>
                            <label for="date2" class="block text-gray-700 text-sm font-bold mb-2">Fecha final</label>
                            <input type="date" class="w-full border rounded-md py-2 px-3" name="date2" id=""
                                value="{{ Request('date2') }}">
                        </div>
                    </div>
                    <button type="submit" class="mt-2 bg-blue-500 text-white py-2 px-4 rounded-md w-full md:w-64">Filtrar
                        consulta</button>
                </form>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr
                            class="text-md font-semibold tracking-wide text-left text-white bg-green-500 uppercase border-b border-gray-600">
                            <th class="py-2 px-4 border-b">No.</th>
                            <th class="py-2 px-4 border-b">Entrega</th>
                            <th class="py-2 px-4 border-b">Solicitado por</th>
                            <th class="py-2 px-4 border-b">Dado por</th>
                            <th class="py-2 px-4 border-b">Fecha para el permiso</th>
                            <th class="py-2 px-4 border-b">Fecha solicitud</th>
                            <th class="py-2 px-4 border-b">Fecha aprobación</th>
                            <th class="py-2 px-4 border-b">Estado</th>
                            <th class="py-2 px-4 border-b">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($deliveryPermission as $per)
                            <tr class="hover:bg-gray-100 border-b">
                                <td class="py-2 px-4">{{ $per->id }}</td>
                                <td class="py-2 px-4">{{ $per->deliveries->raffle->name }} <br>
                                    Consecutivo : {{ $per->deliveries->consecutive }}
                                </td>
                                <td class="py-2 px-4">{{ $per->urequests->name }} {{ $per->urequests->lastname }}</td>
                                <td class="py-2 px-4">{{ $per->uallow->name ?? '' }} {{ $per->uallow->lastname ?? '' }}
                                </td>
                                <td class="py-2 px-4">{{ carbon\Carbon::parse($per->date_permission)->format('d/m/Y') }}
                                </td>
                                <td class="py-2 px-4">{{ carbon\Carbon::parse($per->created_at)->format('d/m/Y') }}
                                </td>

                                @if ($per->status == 0)
                                    <td></td>
                                    <td class="py-2 px-4">Pendiente</td>
                                    <td class="py-2 px-4 grid grid-cols-2 gap-2 md:flex">
                                        <a href="{{ route('delivery_permission.edit', $per->id) }}"
                                            class="text-yellow-500 hover:bg-green-500 p-1 bg-blue-500 rounded-md mr-1">
                                            <img class="h-5" src="{{ asset('img/icons/edit-icon.svg') }}"
                                                alt="">
                                        </a>
                                    </td>
                                @elseif ($per->status == 1)
                                    <td class="py-2 px-4">{{ carbon\Carbon::parse($per->updated_at)->format('d/m/Y') }}
                                    </td>
                                    <td class="py-2 px-4">Aprobado</td>
                                @elseif ($per->status == 2)
                                    <td class="py-2 px-4">{{ carbon\Carbon::parse($per->updated_at)->format('d/m/Y') }}
                                    </td>
                                    <td class="py-2 px-4">Utilizado</td>
                                @endif

                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
        <div class="pagination mt-5">
            {{ $deliveryPermission->links() }}
        </div>
    </div>
@endsection
