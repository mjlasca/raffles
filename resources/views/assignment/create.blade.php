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
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Nombre:</label>
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
                                <option value="{{ $seller->id }}" selected>{{ $seller->name }}</option>
                            @else    
                                <option value="{{ $seller->id }}">{{ $seller->name }}</option>
                            @endif
                            
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <div id="error-tickets" class="w-full bg-gray-100 text-red-500"></div>
                    <div class="flex">
                        <div id="tickets-help" class="mt-2 mr-1 text-gray-300">
                        </div>
                        <textarea class="w-full border rounded-md py-2 px-3" name="tickets" id="tickets" cols="30" rows="5">{{ old('tickets') }}</textarea>
                    </div>
                   
                </div>

                

                <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-md">Guardar</button>
            </form>
            
            

            @error('tickets')
                <div class="alert alert-danger">
                    <ul id="numbers">
                       {{$message}}
                    </ul>
                    <button type="submit" class="bg-green-500 text-white py-2 px-4 rounded-md" onclick="clearList()">Elimina éstos números</button>
                </div>
                
            @enderror
        </div>
    </div>
@endsection
