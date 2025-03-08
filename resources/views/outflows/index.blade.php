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
                <a class="ml-2 p-1 mt-1 bg-green-500 rounded-lg hover:bg-green-500" href="{{route('salidas.export')}}?date1={{Request('date1')}}&date2={{Request('date2')}}&raffle_id={{Request('raffle_id')}}">
                    <img class="h-5" src="{{ asset('img/icons/export-icon.svg') }}" alt="">
                </a>
            </div>
            <div>
                <form method="GET"  class="p-6">
                    
                    <div class="md:flex">
                        <div>
                            <label for="keyword" class="block text-gray-700 text-sm font-bold mb-2">Buscar coincidencia</label>
                            <input type="text" class="w-full border rounded-md py-2 px-3" name="keyword" id="" value="{{ Request('keyword') }}" placeholder="Buscar usuario o rifa">
                        </div>
                        <div>
                            <label for="raffle_id" class="block text-gray-700 text-sm font-bold mb-2">Rifas</label>
                            <select name="raffle_id" id="raffle_id" class="w-full border rounded-md py-2 px-3" required >
                                <option value="">Seleccione una rifa</option>
                                @foreach($raffles as $raffle)
                                    @if ($raffle->id == Request('raffle_id'))
                                        <option value="{{ $raffle->id }}" selected>{{ $raffle->name }}</option>
                                    @else    
                                        <option value="{{ $raffle->id }}">{{ $raffle->name }}</option>
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
                            <th class="py-2 px-4 border-b">id</th>
                            <th class="py-2 px-4 border-b">Fecha</th>
                            <th class="py-2 px-4 border-b">Rifa</th>
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
                            <td class="py-2 px-4"> {{ $outflow->raffle->name }}</td>
                            <td class="py-2 px-4">{{ $outflow->redited->name }} {{ $outflow->redited->lastname }}</td>
                            <td class="py-2 px-4">{{ $outflow->detail }}</td>
                            <td class="py-2 px-4 text-right">${{ number_format($outflow->total,2) }}</td>
                            <td class="py-2 px-4 md:flex grid gap-2">
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
        <div class="pagination mt-5">
            {{$outflows->links()}}
        </div>
        
    </div>
    <script src="{{ asset('js/commission.js') }}"></script>
@endsection
