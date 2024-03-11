@extends('layouts.app')
@section('pageTitle', 'Editar ' )
@section('content')
    <div class="container mx-auto mt-8">
        <div class="bg-white shadow-md overflow-hidden rounded-md">
            <div class="py-4 px-6 bg-blue-500 flex text-white fill-white">
                <h2 class="text-2xl font-semibold">Editar premio</h2>
            </div>

            <form method="POST" action="{{ route('premios.update', $prize->id) }}" class="p-6">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="detail" class="block text-gray-700 text-sm font-bold mb-2">Detalle:</label>
                    <textarea name="detail" id="detail" class="w-full border rounded-md py-2 px-3" maxlength="500" required>{{ $prize->detail }}</textarea>
                </div>

                <div class="mb-4 md:w-1/2">
                    <label for="raffle_id" class="block text-gray-700 text-sm font-bold mb-2">Rifa</label>
                    <select name="raffle_id" id="raffle_id" class="w-full border rounded-md py-2 px-3" required>
                        <option value="">Seleccione una rifa</option>
                        @foreach($raffles as $raffle)
                            @if ($raffle->id == $prize->raffle_id)
                                <option value="{{ $raffle->id }}" selected>{{ $raffle->name }}</option>
                            @else
                                <option value="{{ $raffle->id }}">{{ $raffle->name }}</option>    
                            @endif
                        @endforeach
                    </select>
                </div>

                <div class="mb-4 md:w-1/5">
                    <label for="percentage_condition" class="block text-gray-700 text-sm font-bold mb-2">Condición de porcentaje:</label>
                    <input type="number" name="percentage_condition" min="0" max="100" id="percentage_condition" class="w-full border rounded-md py-2 px-3" value="{{$prize->percentage_condition}}" required>
                </div>

                <div class="mb-4 md:w-1/5">
                    <label for="award_date" class="block text-gray-700 text-sm font-bold mb-2">Fecha sorteo:</label>
                    <input type="date" name="award_date" id="award_date" class="w-full border rounded-md py-2 px-3" min="{{ now()->format('Y-m-d') }}" value="{{$prize->award_date}}" required>
                </div>

                <div class="mb-4 md:w-1/5">
                    <label for="winning_ticket" class="block text-gray-700 text-sm font-bold mb-2">Boleto ganador:</label>
                    <input type="text" name="winning_ticket" maxlength="10" pattern="[0-9]{0,10}" title="El número ganador debe tener sólo números" id="winning_ticket" class="w-full border rounded-md py-2 px-3" value="{{$prize->winning_ticket}}">
                </div>

                <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-md">Actualizar Rifa</button>
            </form>
        </div>
    </div>
@endsection
