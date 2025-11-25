@extends('layouts.app')
@section('pageTitle', 'Editar ' )
@section('content')
    <div class="container mx-auto mt-8">
        <div class="bg-white shadow-md overflow-hidden rounded-md">
            <div class="py-4 px-6 bg-blue-500 flex text-white fill-white">
                <h2 class="text-2xl font-semibold">Editar Entrega</h2>
            </div>
            
                <form method="POST" action="" class="p-6">
                    @csrf
                    @method('PUT')



                    <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-md">Actualizar</button>
                </form>
             
        </div>
    </div>
@endsection
