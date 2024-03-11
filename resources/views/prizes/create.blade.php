@extends('layouts.app')
@section('pageTitle', 'Crear premio' )
@section('content')
    <div class="container mx-auto mt-8">
        <div class="bg-white rounded-md overflow-hidden">
            <div class="py-4 px-6 bg-blue-500">
                <h2 class="text-2xl text-white font-semibold text-gray-800">Crear premio</h2>
            </div>

            <form method="POST" action="{{ route('premios.store') }}" class="py-6 px-8">
                @csrf

                <div class="mb-4">
                    <label for="detail" class="block text-gray-700 text-sm font-bold mb-2">Detalle el premio:</label>
                    <textarea name="detail" id="detail" class="w-full border rounded-md py-2 px-3" maxlength="300" rows="3" value="{{old('detail')}}" required>{{old('detail')}}</textarea>
                    <small>Éste detalle será visto por diferentes usuarios o clientes, por favor detalle el premio con esa intención</small>
                </div>

                <div class="mb-4 md:w-1/2">
                    <label for="raffle_id" class="block text-gray-700 text-sm font-bold mb-2">Rifa</label>
                    <select name="raffle_id" id="raffle_id" class="w-full border rounded-md py-2 px-3" required>
                        <option value="">Seleccione una rifa</option>
                        @foreach($raffles as $raffle)
                            @if ($raffle->id == old('raffle_id'))
                                <option value="{{ $raffle->id }}" selected>{{ $raffle->name }}</option>    
                            @else
                                <option value="{{ $raffle->id }}">{{ $raffle->name }}</option>
                            @endif
                            
                        @endforeach
                    </select>
                </div>

                <div class="mb-4 md:w-1/2">
                    <label for="type" class="block text-gray-700 text-sm font-bold mb-2">Tipo de premio</label>
                    <select name="type" id="type" class="w-full border rounded-md py-2 px-3" required>
                        <option value="">Seleccione tipo de premio</option>
                        <option value="Mayor">Mayor</option>
                        <option value="Anticipado">Anticipado</option>
                    </select>
                </div>

                <div class="mb-4 md:w-1/5">
                    <label for="percentage_condition" class="block text-gray-700 text-sm font-bold mb-2">Cantidad mínima que debe tener paga:</label>
                    <input type="number" name="percentage_condition" id="percentage_condition" class="w-full border rounded-md py-2 px-3" value="{{old('percentage_condition')}}" required>
                </div>

                <div class="mb-4 md:w-1/5">
                    <label for="award_date" class="block text-gray-700 text-sm font-bold mb-2">Fecha sorteo:</label>
                    <input type="date" name="award_date" id="award_date" class="w-full border rounded-md py-2 px-3" min="{{ now()->format('Y-m-d') }}" value="{{old('award_date')}}" required>
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
