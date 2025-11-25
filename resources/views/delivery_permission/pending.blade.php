@extends('layouts.app')
@section('pageTitle', 'Permiso pendiente' )
@section('content')
    <div class="container mx-auto mt-8">
        <div class="bg-white shadow-md overflow-hidden rounded-md">
            <div class="py-4 px-6 bg-blue-500 flex text-white fill-white">
                <h2 class="text-2xl font-semibold">Permiso para entrega</h2>
            </div>
            <div class="p-3"> 
                <h3 class="h4">El permiso se ha enviado a los administradores, debe esperar la notificación por correo para poder modificar la entrega</h3>
            </div>
        </div>
    </div>
@endsection
