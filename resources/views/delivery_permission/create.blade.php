@extends('layouts.app')
@section('pageTitle', 'Solicitar permiso')
@section('content')
    <div class="container mx-auto mt-8">
        <div class="bg-white shadow-md overflow-hidden rounded-md">
            <div class="py-4 px-6 bg-blue-500 flex text-white fill-white">
                <h2 class="text-2xl font-semibold">Solicitar permiso</h2>
            </div>
            <div class="p-3">
                <h3 class="h4">No tiene permisos para editar/anular la entrega</h3>
                <form method="POST" action="{{ route('permisos-entregas.store') }}" class="p-6">
                    @csrf
                    <input type="hidden" name="delivery_id" value="{{ $delivery->id }}">
                    <input type="hidden" name="current_user" value="{{ $current_user->id }}">
                    <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-md">Solicitar
                        permiso</button>
                </form>
            </div>
        </div>
    </div>
@endsection
