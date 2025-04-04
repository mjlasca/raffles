@extends('layouts.app')
@section('pageTitle', 'Crear asignacion' )
@section('content')
    <script src="{{ asset('js/assignments.js') }}" defer></script>
    <div class="container mx-auto mt-8">
        <div class="bg-white rounded-md overflow-hidden">
            <div class="py-4 px-6 bg-blue-500">
                <h2 class="text-2xl text-white font-semibold text-gray-800">Crear asignación</h2>
            </div>

            <form method="POST" onsubmit="return prevForm(event)" action="{{ route('asignaciones.store') }}" class="py-6 px-8">
                @csrf

                <div class="mb-4 md:w-1/2">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Detalle:</label>
                    <input type="text" name="name" id="name" maxlength="50" value="{{old('name')}}" class="w-full border rounded-md py-2 px-3" required>
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
                    <label for="user_id" class="block text-gray-700 text-sm font-bold mb-2">Vendedor(es)</label>
                    <select name="user_id" id="user_id" class="w-full border rounded-md py-2 px-3" required>
                            <option value="">Seleccione un(a) vendedor(a)</option>
                        @foreach($sellers_users as $seller)
                            @if ($seller->id == old('user_id'))
                                <option value="{{ $seller->id }}" selected>{{ $seller->name }} {{ $seller->lastname }}</option>
                            @else    
                                <option value="{{ $seller->id }}">{{ $seller->name }} {{ $seller->lastname }}</option>
                            @endif
                            
                        @endforeach
                    </select>
                </div>

                <div class="mb-4 md:w-1/2">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Comisión por boleta:</label>
                    <input type="number" name="commission" id="commission" value="{{old('commission')}}" class="w-full border rounded-md py-2 px-3" required>
                </div>

                <div class="mb-4 md:w-1/2">
                    <label for="user_referred" class="block text-gray-700 text-sm font-bold mb-2">Referido por</label>
                    <select name="user_referred" id="user_referred" class="w-full border rounded-md py-2 px-3" required>
                            <option value="">Seleccione un(a) vendedor(a)</option>
                        @foreach($sellers_users as $seller)
                            @if ($seller->id == old('user_referred'))
                                <option value="{{ $seller->id }}" selected>{{ $seller->name }} {{ $seller->lastname }}</option>
                            @else    
                                <option value="{{ $seller->id }}">{{ $seller->name }} {{ $seller->lastname }}</option>
                            @endif
                            
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    
                    <div class="flex">
                        <div id="tickets-help" class="mt-2 mr-1 text-gray-300">
                        </div>
                        <textarea class="w-full border rounded-md py-2 px-3" name="tickets" id="tickets" cols="30" rows="5" required>{{ old('tickets') }}</textarea>
                    </div>
                   
                </div>

                

                <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-md">Guardar</button>
            </form>
            

            <div id="error-tickets" class="fixed bottom-0 text-center text-lg  left-0 right-0 sm:w-full bg-red-500 text-white"></div>
            
            

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            
                            @if (strpos($error, 'Los siguientes') !== false)
                                <li id="numbers">
                                    {{ $error }}
                                    <button type="submit" class="bg-green-500 text-white text-center  py-2 px-4 rounded-md" onclick="clearList()">Elimina éstos números</button>
                                </li>
                            @else
                                <li>{{ $error }}</li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
@endsection
