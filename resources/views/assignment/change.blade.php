@extends('layouts.app')
@section('pageTitle', 'Cambio de boletas' )
@section('content')
    <div class="container mx-auto mt-8">
        <div class="bg-white shadow-md overflow-hidden rounded-md">
            <div class="py-4 px-6 bg-blue-500 flex text-white fill-white">
                <h2 class="text-2xl font-semibold">Cambio de boletas</h2><a href="{{route('asignaciones.change')}}" class="ml-2 bg-green-500 text-white py-2 px-4 rounded-md w-full md:w-64">Reiniciar proceso</a>
            </div>

            
            @if (!empty($selected['raffle']))
                @if (Request('user_0'))
                    <div>
                        <form method="POST"  class="p-6" onsubmit="return confirm('¿Está segur@ de mover los boletos?');">
                            <h3 class="h3">{{$selected['raffle']->name}}</h3>
                            <p>Vendedor al que se le van a mover boletas:</p>
                            <h3 class="h3">{{$selected['user_0']->name ." ".$selected['user_0']->lastname}}</h3>
                            <div class="md:flex mb-4">
                                <div>
                                    <input type="hidden" name="raffle_id" value="{{$selected['raffle']->id}}">
                                    <input type="hidden" name="user_0" value="{{Request('user_0')}}">
                                    @csrf
                                    <label for="raffle_id" class="block text-gray-700 text-sm font-bold mb-2">Seleccione el usuario que desea pasar las boletas</label>
                                    <select name="user_1" id="user_1" class="w-full border rounded-md py-2 px-3" required >
                                        <option value="">Seleccione el usuario</option>
                                        @foreach($selected['user_1'] as $user)
                                            <option value="{{$user->id}}">{{"$user->name $user->lastname"}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            
                            <h3 class="h3 mt-5 mb-2">Boletos disponibles para mover</h3>
                            <div class="grid grid-cols-3 md:grid-cols-8 gap-2">
                                @foreach ($selected['tickets'] as $ticket)
                                    <div class="flex w-20">
                                        <input class="w-full rounded-md" type="checkbox" name="tickets[]" value="{{$ticket->id}}">
                                        <label for="">{{$ticket->ticket_number}}</label>
                                    </div>    
                                @endforeach
                            </div>
                            <button type="submit" class="mt-5 bg-blue-500 text-white py-2 px-4 rounded-md w-full md:w-64">Confirmar paso</button>
                        </form>
                    </div>
                @else
                    <div>
                        <form method="GET"  class="p-6">
                            <h3 class="h3">{{$selected['raffle']->name}}</h3>
                            <div class="md:flex">
                                <div>
                                    <input type="hidden" name="raffle_id" value="{{$selected['raffle']->id}}">
                                    <label for="raffle_id" class="block text-gray-700 text-sm font-bold mb-2">Seleccione el usuario que desea mover boletas</label>
                                    <select name="user_0" id="user_0" class="w-full border rounded-md py-2 px-3" required >
                                        <option value="">Seleccione el usuario</option>
                                        @foreach($selected['user_0'] as $user)
                                                <option value="{{$user->id}}">{{"$user->name $user->lastname"}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <button type="submit" class="mt-2 bg-blue-500 text-white py-2 px-4 rounded-md w-full md:w-64">Aceptar</button>
                        </form>
                    </div>
                @endif
            @else    
                <div>
                    <form method="GET"  class="p-6">
                        <div class="md:flex">
                            <div>
                                <label for="raffle_id" class="block text-gray-700 text-sm font-bold mb-2">Seleccione la rifa de los boletos que desea cambiar</label>
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
                        </div>
                        <button type="submit" class="mt-2 bg-blue-500 text-white py-2 px-4 rounded-md w-full md:w-64">Aceptar</button>
                    </form>
                </div>
                @if (!empty($selected['tickets_change']))
                <div class="p-3">
                    <h3 class="h3 mt-5 mb-2">Boletos Modificados</h3>
                    <div class="grid grid-cols-3 md:grid-cols-8 gap-2">
                        @foreach ($selected['tickets_change'] as $ticket)
                            <div class="flex w-20">
                                <a href="/boletas/{{$ticket->id}}" target="_blank" class="bg-green-100">{{$ticket->ticket_number}}</a>
                            </div>    
                        @endforeach
                    </div>
                </div>
                    
                @endif
            @endif
        </div>

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
@endsection
