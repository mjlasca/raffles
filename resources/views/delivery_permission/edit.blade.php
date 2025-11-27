@extends('layouts.app')
@section('pageTitle', 'Edición permiso')
@section('content')
    <div class="container mx-auto mt-8">
        <div class="bg-white shadow-md overflow-hidden rounded-md">
            <div class="py-4 px-6 bg-blue-500 flex text-white fill-white">
                <h2 class="text-2xl font-semibold">Permiso para entrega</h2>
            </div>
            <form method="POST" action="{{ route('delivery_permission.update', $deliveryPermission->id) }}" class="p-6">
                @csrf
                @method('PUT')
                <div class="w-1/4">
                    <label for="date1" class="block text-gray-700 text-sm font-bold mb-2">Fecha límite para permiso</label>
                    <input type="date" class="w-full border rounded-md py-2 px-3" name="date_permission" id=""
                        value="" required min="{{ $dateMin }}" max="{{ $dateMax }}">
                </div>

                <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-md">Aprobar permiso</button>
            </form>
        </div>
    </div>
@endsection
