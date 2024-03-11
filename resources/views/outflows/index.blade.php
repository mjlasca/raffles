@extends('layouts.app')
@section('pageTitle', 'Salidas')
@section('content')
    <div class="container mx-auto mt-8">
        <div class="bg-white shadow-md overflow-hidden rounded-md">
            <div class="py-4 px-6 bg-blue-500 flex text-white fill-white">
                <h2 class="text-2xl font-semibold">Salidas</h2>
                <a class="ml-2 p-1 mt-1 bg-green-500 rounded-lg hover:bg-green-500" href="{{route('salidas.create')}}">
                    <img class="h-5" src="{{ asset('img/icons/add-icon.svg') }}" alt="">
                </a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="text-md font-semibold tracking-wide text-left text-white bg-green-500 uppercase border-b border-gray-600">
                            <th class="py-2 px-4 border-b">id</th>
                            <th class="py-2 px-4 border-b">Fecha</th>
                            <th class="py-2 px-4 border-b">Generado por</th>
                            <th class="py-2 px-4 border-b">Detalle</th>
                            <th class="py-2 px-4 border-b text-right">Total</th>
                            <th class="py-2 px-4 border-b">Acción</th>


                        </tr>
                    </thead>
                    <tbody>
                       @foreach ($outflows as $outflow)
                       @if (auth()->user()->role === $outflow->redited->role || auth()->user()->role === 'Administrador')
                        <tr class="hover:bg-gray-100 border-b">
                            <td class="py-2 px-4">{{ $outflow->id }}</td>
                            <td class="py-2 px-4">{{ $outflow->updated_at }}</td>
                            <td class="py-2 px-4">{{ $outflow->redited->name }} {{ $outflow->redited->lastname }}</td>
                            <td class="py-2 px-4">{{ $outflow->detail }}</td>
                            <td class="py-2 px-4 text-right">${{ number_format($outflow->total,2) }}</td>
                            <td class="py-2 px-4 flex">
                                <form action="{{ route('salidas.destroy', $outflow) }}" method="POST" onsubmit="return confirmCommission('Está segur@ de eliminar el registro')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-yellow-500 hover:bg-green-500 p-1 bg-red-500 rounded-md mr-1"><img class="h-5" src="{{ asset('img/icons/delete-icon.svg') }}" alt="Eliminar registro" title="Ver registro"></button>
                                </form>
                            </td>
                        </tr>
                       @endif
                                
                       @endforeach
                    </tbody>
                </table>
                
            </div>
        </div>
        
    </div>
    <script src="{{ asset('js/commission.js') }}"></script>
@endsection
