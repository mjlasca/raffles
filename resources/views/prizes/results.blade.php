<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Resultados</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>



        @media (prefers-color-scheme: dark) {
            body,form,input,select,textarea,label    {
                background-color: #2c3e50 !important;
                color: #ecf0f1 !important;
            }
            .bg-gray-100, .bg-white{
                background-color: #2c3e50 !important;
            }

            table tr{
                background-color: #2c3e50 !important;
            }

        }
    </style>
</head>
<body class="">

    @php
        $a_month = [
            "01" => 'Enero',
            "02" => 'Febrero',
            "03" => 'Marzo',
            "04" => 'Abril',
            "05" => 'Mayo',
            "06" => 'Junio',
            "07" => 'Julio',
            "08" => 'Agosto',
            "09" => 'Septiembre',
            "10" => 'Octubre',
            "11" => 'Noviembre',
            "12" => 'Diciembre',
        ];
    @endphp 
    <section >
        <div class="bg-cover   bg-center bg-no-repeat" style="background-image: url('https://360radio.com.co/wp-content/uploads/2020/02/dinero-en-colombia-scaled.jpeg')">
            <div class="flex justify-center max-w-60 bg-white bg-opacity-50">
                <img class="w-60" src="{{ asset('img/logo_st.png') }}" alt="Logo">
            </div>
                        
            <div class="bg-blue-500 p-2">
                <h1 class="h2 mt-2 text-white text-center font-bold">
                    RESULTADOS
                </h1>
            </div>   
        </div>
        
    </section>

    <div class="bg-gradient-to-b from-blue-500 to-white h-screen mx-auto p-6 flex justify-center h-full">
        <div class="w-4/5">
            <!-- Mostrar rifas vigentes -->
            <div class="bg-white rounded-lg shadow-md p-4 mb-4">
                <h2 class="text-xl font-bold mb-2">Rifas vigentes</h2>
                <div class="max-w-full  overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="text-md font-semibold tracking-wide text-left text-white bg-gray-500 uppercase border-b border-gray-600">
                                <th class="py-2 px-4 border-b w-12"></th>
                                <th class="py-2 px-4 border-b">Fecha</th>
                                <th class="py-2 px-4 border-b w-1/2">Rifa</th>
                                <th class="py-2 px-4 border-b">Premio</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($current_prizes as $prize)
                                @if ($prize->type == 'Mayor')
                                    <tr class="hover:bg-gray-100 border-b border-blue-500 py-3 bg-blue-100">
                                @else
                                    <tr class="hover:bg-gray-100 border-b border-blue-500 py-3">
                                @endif
                                    <td class="py-3 px-4 ">
                                        <a href="https://wa.me/15551234567?text=Quiero comprar una boleta de la rifa {{$prize->raffle->name}}" class=" bg-green-500 text-white py-2 px-4 rounded-md" target="_blank">
                                            Comprar
                                        </a>
                                    </td>
                                    <td class="px-4">{{
                                        \Carbon\Carbon::parse($prize->award_date)->format('d')." de ".
                                        $a_month[\Carbon\Carbon::parse($prize->award_date)->format('m')]." del ".
                                        \Carbon\Carbon::parse($prize->award_date)->format('Y')
                                    }}</td>
                                    <td class="px-4">
                                        <b>{{$prize->raffle->name}}</b><br>
                                        {{$prize->detail}}
                                    </td>
                                    <td class="px-4">Premio {{$prize->type}}</td>

                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Mostrar resultados anteriores -->
            <div class="bg-white rounded-lg shadow-md p-4">
                <div class="sm:flex mb-2">
                    <h2 class="text-xl font-bold mb-2">Resultados Anteriores</h2>
                    <div class="ml-2">
                        <form method="GET"  class="">
                    
                            <div class="sm:flex">
                                <div>
                                    <input type="date" class="w-full border rounded-md py-2 px-3" name="date1" id="" value="{{ Request('date1') }}">
                                </div>
                                <div>
                                    <input type="date" class="w-full border rounded-md py-2 px-3" name="date2" id="" value="{{Request('date2')}}">
                                </div>
                                <button type="submit" class="sm:ml-2 bg-blue-500 text-white py-2 px-4 rounded-md w-full">Filtrar consulta</button>
                            </div>
                            
                        </form>
                    </div>
                </div>
                
                <div class="max-w-full  overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="text-md font-semibold tracking-wide text-left bg-gray-100 uppercase border-b border-gray-600">
                                <th class="py-2 px-4 border-b">Fecha</th>
                                <th class="py-2 px-4 border-b w-1/2">Rifa</th>
                                <th class="py-2 px-4 border-b">Premio</th>
                                <th class="py-2 px-4 border-b text-right">Boleto ganador</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($prizes as $prize)
                                @if ($prize->type == 'Mayor')
                                    <tr class="hover:bg-gray-100 border-b border-blue-500 py-2 bg-blue-100">
                                @else
                                    <tr class="hover:bg-gray-100 border-b border-blue-500 py-2">
                                @endif
                                    <td class="py-3 px-4">{{ 
                                        \Carbon\Carbon::parse($prize->award_date)->format('d')." de ".
                                        $a_month[\Carbon\Carbon::parse($prize->award_date)->format('m')]." del ".
                                        \Carbon\Carbon::parse($prize->award_date)->format('Y')
                                    }}</td>
                                    <td class="px-4">
                                        <b>{{$prize->raffle->name}}</b><br>
                                        {{$prize->detail}}
                                    </td>
                                    <td class="px-4">Premio {{$prize->type}}</td>
                                    <td class="px-4 text-right">{{$prize->winning_ticket}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="pagination mt-5">
                    {{$prizes->links()}}
                </div>
                
            </div>
        </div>
    </div>    
</body>


</html>