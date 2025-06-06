@extends('layouts.app')
@section('pageTitle', 'Métodos de pago')
@section('content')
    <div class="container mx-auto mt-8">
        <div class="bg-white shadow-md overflow-hidden rounded-md">
            <div class="py-4 px-6 bg-blue-500 flex text-white fill-white">
                <h2 class="text-2xl font-semibold">Métodos de pago</h2>
            </div>
            <div>
                <form method="POST"  class="p-6">
                    <h3>Crear un nuevo método de pago</h3>
                    <div class="md:flex">
                        <div>
                            @csrf
                            <input type="hidden" name="idpayment">
                            <input type="text" required class="w-full border rounded-md py-2 px-3" name="description" id="" value="{{ Request('description') }}" placeholder="Descripción">
                            <small>La descripción puede ser por ejemplo Nequi 3013333333 ó Bancolombia CTA 785544222... </small>
                        </div>
                    </div>
                    <button type="submit" class="mt-2 bg-blue-500 text-white py-2 px-4 rounded-md w-full md:w-64">Guardar</button>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="text-md font-semibold tracking-wide text-left text-white bg-green-500 uppercase border-b border-gray-600">
                            <th class="py-2 px-4 border-b">Descripción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payment_methods as $pay)
                            <tr class="hover:bg-gray-100 border-b">
                                <td class="py-2 px-4">{{$pay->description}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
        <div class="pagination mt-5">

        </div>
    </div>
@endsection
