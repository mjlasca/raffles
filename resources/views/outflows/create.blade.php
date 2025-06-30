@extends('layouts.app')
@section('pageTitle', 'Crear salida' )
@section('content')
    <div class="container mx-auto mt-8">
        <div class="bg-white rounded-md overflow-hidden">
            <div class="py-4 px-6 bg-blue-500">
                <h2 class="text-2xl text-white font-semibold text-gray-800">Crear salida</h2>
            </div>

            <form method="POST" action="{{ route('salidas.store') }}" class="py-6 px-8">
                @csrf
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
                    <label for="payment_method_id" class="block text-gray-700 text-sm font-bold mb-2">Método de pago</label>
                    <select name="payment_method_id" id="payment_method_id" class="w-full border rounded-md py-2 px-3" required>
                        <option value="">Seleccione método pago</option>
                        @foreach($paymentMethods as $pay)
                            @if ($pay->id == old("payment_method_id"))
                                <option value="{{ $pay->id }}" selected>{{ $pay->description }}</option>
                            @else
                            <option value="{{ $pay->id }}">{{ $pay->description }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="mb-4 md:w-1/2">
                    <label for="office_id" class="block text-gray-700 text-sm font-bold mb-2">Oficina</label>
                    <select name="office_id" id="office_id" class="w-full border rounded-md py-2 px-3" required>
                        <option value="">Seleccione la oficina</option>
                        @foreach($offices as $office)
                            @if ($office->id == old("office_id"))
                                <option value="{{ $office->id }}" selected>{{ $office->description }}</option>
                            @else
                            <option value="{{ $office->id }}">{{ $office->description }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="detail" class="block text-gray-700 text-sm font-bold mb-2">Detalle:</label>
                    <textarea name="detail" id="detail" class="w-full border rounded-md py-2 px-3" required maxlength="500"></textarea>
                </div>

                <div class="mb-4 w-52">
                    <label for="total" class="block text-gray-700 text-sm font-bold mb-2">Valor:</label>
                    <input type="number" name="total" id="total" class="w-full border rounded-md py-2 px-3"  required>
                </div>

                <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-md">Guardar</button>
            </form>
        </div>
    </div>

@endsection
