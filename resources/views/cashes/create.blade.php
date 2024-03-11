@extends('layouts.app')
@section('pageTitle', 'Cerrar arqueo' )
@section('content')
    <div class="container mx-auto mt-8">
        <div class="bg-white rounded-md overflow-hidden">
            <div class="py-4 px-6 bg-blue-500">
                <h2 class="text-2xl text-white font-semibold text-gray-800">Cerrar arqueo {{ $dateFormat }}</h2>
            </div>

            <form method="POST" action="{{ route('arqueos.store') }}" class="py-6 px-8" onsubmit="return confirmM('Está segur@ de hacer el registro');">
                @csrf
                
                @foreach ($dayTotal as $day)
                    <input type="hidden" name="real_money_box" id="real_money_box" value="{{$total}}" >
                    <input type="hidden" name="day_date" value="{{$date}}">
                    <div class="mb-4 md:w-1/5">
                        <label for="day_date" class="block text-gray-700 text-sm font-bold mb-2">Suma total entregas:</label>
                        <input type="text" value="${{ $day['deliveries_total']}}" class="w-full border rounded-md py-2 px-3 disabled" disabled readonly required>
                    </div>
                    <div class="mb-4 md:w-1/5">
                        <label for="day_date" class="block text-gray-700 text-sm font-bold mb-2">Suma total salidas:</label>
                        <input type="text" value="$-{{ $day['outflows_total']}}" class="w-full border rounded-md py-2 px-3 disabled" disabled readonly required>
                    </div>
                    <div class="mb-4 md:w-1/5">
                        <label for="day_date" class="block text-gray-700 text-sm font-bold mb-2">Suma total comisiones:</label>
                        <input type="text" value="$-{{ $day['commissions_total']}}" class="w-full border rounded-md py-2 px-3 disabled" disabled readonly required>
                    </div>
                    <div class="mb-4 md:w-1/5">
                        <label for="day_date" class="block text-gray-700 text-sm font-bold mb-2">Suma total virtual:</label>
                        <input type="text" value="${{ $total}}" class="w-full border rounded-md py-2 px-3 disabled" disabled readonly required>
                    </div>
                    <div class="mb-4 md:w-1/5">
                        <label for="manual_money_box" class="block text-gray-700 text-sm font-bold mb-2">Suma manual:</label>
                        <input type="number" name="manual_money_box" maxlength="10" id="manual_money_box" class="w-full border rounded-md py-2 px-3" required>
                    </div>
                    <div class="mb-4 md:w-1/5">
                        <label for="difference" class="block text-gray-700 text-sm font-bold mb-2">Diferencia:</label>
                        <input type="number" name="difference" maxlength="10" id="difference" class="w-full border rounded-md py-2 px-3" readonly required>
                    </div>
                @endforeach
                

                <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-md">Guardar</button>
            </form>
        </div>
    </div>
    <script src="{{ asset('js/cash.js') }}"></script>
@endsection
