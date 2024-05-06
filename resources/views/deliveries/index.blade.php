@extends('layouts.app')
@section('pageTitle', 'Entregas')
@section('content')
    <div class="container mx-auto mt-8">
        <div class="bg-white shadow-md overflow-hidden rounded-md">
            <div class="py-4 px-6 bg-blue-500 flex text-white fill-white">
                <h2 class="text-2xl font-semibold">Entregas</h2>
                <a class="ml-2 p-1 mt-1 bg-green-500 rounded-lg hover:bg-green-500" href="{{route('entregas.create')}}">
                    <img class="h-5" src="{{ asset('img/icons/add-icon.svg') }}" alt="">
                </a>
                <a class="ml-2 p-1 mt-1 bg-green-500 rounded-lg hover:bg-green-500" href="{{route('entregas.export')}}?date1={{Request('date1')}}&date2={{Request('date2')}}">
                    <img class="h-5" src="{{ asset('img/icons/export-icon.svg') }}" alt="">
                </a>
            </div>

            <div>
                <form method="GET"  class="p-6">
                    
                    <div class="md:flex">
                        <div>
                            <label for="keyword" class="block text-gray-700 text-sm font-bold mb-2">Buscar coincidencia</label>
                            <input type="text" class="w-full border rounded-md py-2 px-3" name="keyword" id="" value="{{ Request('keyword') }}" placeholder="Buscar...">
                        </div>
                        <div>
                            <label for="user_id" class="block text-gray-700 text-sm font-bold mb-2">Vendedor</label>
                            <select name="user_id" id="user_id" class="w-full border rounded-md py-2 px-3" >
                                <option value="">Seleccione un vendedor</option>
                                @foreach($sellers_users as $seller)
                                    @if ($seller->id == Request('user_id'))
                                        <option value="{{ $seller->id }}" selected>{{ $seller->name }} {{ $seller->lastname }}</option>
                                    @else    
                                        <option value="{{ $seller->id }}">{{ $seller->name }} {{ $seller->lastname }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="date1" class="block text-gray-700 text-sm font-bold mb-2">Fecha inicial</label>
                            <input type="date" class="w-full border rounded-md py-2 px-3" name="date1" id="" value="{{ Request('date1') }}">
                        </div>
                        <div>
                            <label for="date2" class="block text-gray-700 text-sm font-bold mb-2">Fecha final</label>
                            <input type="date" class="w-full border rounded-md py-2 px-3" name="date2" id="" value="{{Request('date2')}}">
                        </div>
                    </div>
                    <button type="submit" class="mt-2 bg-blue-500 text-white py-2 px-4 rounded-md w-full md:w-64">Filtrar consulta</button>
                </form>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="text-md font-semibold tracking-wide text-left text-white bg-green-500 uppercase border-b border-gray-600">
                            <th class="py-2 px-4 border-b">Fecha</th>
                            <th class="py-2 px-4 border-b">Reg</th>
                            <th class="py-2 px-4 border-b">Descripción</th>
                            <th class="py-2 px-4 border-b">Rifa</th>
                            <th class="py-2 px-4 border-b">Vendedor</th>
                            <th class="py-2 px-4 border-b">Valor entrega</th>
                            <th class="py-2 px-4 border-b">Canjeado</th>
                            <th class="py-2 px-4 border-b">Recibido por</th>
                            <th class="py-2 px-4 border-b">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($deliveries as $deliverie)
                            <tr class="hover:bg-gray-100 border-b">
                                <td class="py-2 px-4">{{ $deliverie->updated_at }}</td>
                                <td class="py-2 px-4">{{ $deliverie->id }}</td>
                                <td class="py-2 px-4">{{ $deliverie->description }}</td>
                                <td class="py-2 px-4">{{ $deliverie->raffle->name }}</td>
                                <td class="py-2 px-4">{{ $deliverie->user->name }} {{ $deliverie->user->lastname }}</td>
                                <td class="py-2 px-4">{{ $deliverie->total }}</td>
                                <td class="py-2 px-4">{{ $deliverie->used }}</td>
                                <td class="py-2 px-4">{{ $deliverie->redited->name }} {{ $deliverie->redited->lastname }}</td>
                                
                                <td class="py-2 px-4 md:flex grid grid-cols-2 gap-2">
                                    <a href="{{ route('entregas.show', $deliverie->id) }}" class="text-blue-500 hover:bg-green-500 p-1 bg-blue-500 rounded-md mr-1">
                                        <img class="h-5" src="{{ asset('img/icons/show-icon.svg') }}" alt="Ver registro" title="Ver registro">
                                    </a>
                                    <a href="{{ route('entregas.pdf', $deliverie->id) }}" target="_blank" class="text-blue-500 hover:bg-green-500 p-1 bg-blue-500 rounded-md mr-1">
                                        <img class="h-5" src="{{ asset('img/icons/pdf-icon.svg') }}" alt="Descargar Recibo" title="Descargar Recibo">
                                    </a>
                                    @if ($deliverie->used < 1)
                                        <a href="{{ route('entregas.edit', $deliverie->id) }}" class="text-yellow-500 hover:bg-green-500 p-1 bg-blue-500 rounded-md mr-1">
                                            <img class="h-5" src="{{ asset('img/icons/edit-icon.svg') }}" alt="Editar" title="Editar">
                                        </a>
                                        <a href="{{ route('entregas.destroy', $deliverie->id) }}" class="text-yellow-500 hover:bg-green-500 p-1 bg-red-500 rounded-md mr-1">
                                            <img class="h-5" src="{{ asset('img/icons/delete-icon.svg') }}" alt="Eliminar" title="Eliminar">
                                        </a>    
                                    @endif
                                    
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                
            </div>
        </div>
        <div class="mt-5">
            {{$deliveries->links()}}
        </div>
    </div>
@endsection
