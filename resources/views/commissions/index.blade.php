@extends('layouts.app')
@section('pageTitle', 'Comisiones')
@section('content')
    <div class="container mx-auto mt-8">
        <div class="bg-white shadow-md overflow-hidden rounded-md">
            <div class="py-4 px-6 bg-blue-500 flex text-white fill-white">
                <h2 class="text-2xl font-semibold">Comisiones</h2>
                <a class="ml-2 p-1 mt-1 bg-green-500 rounded-lg hover:bg-green-500" href="{{route('comisiones.create')}}">
                    <img class="h-5" src="{{ asset('img/icons/add-icon.svg') }}" alt="">
                </a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="text-md font-semibold tracking-wide text-left text-white bg-green-500 uppercase border-b border-gray-600">
                            <th class="py-2 px-4 border-b">id</th>
                            <th class="py-2 px-4 border-b">Vendedor(a)</th>
                            <th class="py-2 px-4 border-b">Total liquidado</th>
                            <th class="py-2 px-4 border-b">Ver</th>

                        </tr>
                    </thead>
                    <tbody>
                       @foreach ($commissions as $commission)
                        <tr class="hover:bg-gray-100 border-b">
                            <td class="py-2 px-4">{{ $commission->id }}</td>
                            <td class="py-2 px-4">{{ $commission->user->name }} {{ $commission->user->lastname }}</td>
                            <td class="py-2 px-4">${{  number_format($commission->total,2) }}</td>
                            
                            <td class="py-2 px-4 flex">
                                <a href="{{ route('comisiones.show', $commission->id) }}" class="text-blue-500 hover:bg-green-500 p-1 bg-blue-500 rounded-md mr-1">
                                    <img class="h-5" src="{{ asset('img/icons/show-icon.svg') }}" alt="Ver registro" title="Ver registro">
                                </a>
                                <form action="{{ route('comisiones.destroy', $commission) }}" method="POST" onsubmit="return confirmCommission('Está segur@ de eliminar la liquidación')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-yellow-500 hover:bg-green-500 p-1 bg-red-500 rounded-md mr-1"><img class="h-5" src="{{ asset('img/icons/delete-icon.svg') }}" alt="Eliminar registro" title="Ver registro"></button>
                                </form>
                            </td>
                        </tr>
                       @endforeach
                    </tbody>
                </table>
                
            </div>
        </div>
        
    </div>
    <script src="{{ asset('js/commission.js') }}"></script>
@endsection
