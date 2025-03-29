@extends('layouts.app')
@section('pageTitle', 'Crear entrega' )
@section('content')
    <div class="container mx-auto mt-8">
        <div class="bg-white rounded-md overflow-hidden">
            <div class="py-4 px-6 bg-blue-500">
                <h2 class="text-2xl text-white font-semibold text-gray-800">Crear entrega</h2>
            </div>

            <form method="POST" action="{{ route('entregas.store') }}" class="py-6 px-8">
                @csrf

                <div class="mb-4">
                    <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Descripción:</label>
                    <textarea name="description" id="description" class="w-full border rounded-md py-2 px-3" required>{{old('description')}}</textarea>
                </div>

                <div class="mb-4 w-52">
                    <label for="total" class="block text-gray-700 text-sm font-bold mb-2">Fecha de entrega</label>
                    <input type="date" min="0" name="date" id="date" class="w-full border rounded-md py-2 px-3" step="0.01" @if (old('date'))
                        value="{{old('date')}}"
                    @else value="{{$date}}" @endif  required>
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
                    <label for="user_id" class="block text-gray-700 text-sm font-bold mb-2">Vendedor</label>
                    <select name="user_id" id="user_id" class="w-full border rounded-md py-2 px-3" required>
                        <option value="">Seleccione un vendedor</option>
                        @foreach($sellers_users as $seller)
                            @if ($seller->id == old('user_id'))
                                <option value="{{ $seller->id }}" selected>{{ $seller->name }} {{ $seller->lastname }}</option>
                            @else
                                <option value="{{ $seller->id }}">{{ $seller->name }} {{ $seller->lastname }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div class="mb-4 w-52">
                    <label for="total" class="block text-gray-700 text-sm font-bold mb-2">Valor entrega:</label>
                    <input type="number" min="1" name="total" id="total" class="w-full border rounded-md py-2 px-3" step="0.01" value="{{old('total')}}" required>
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
