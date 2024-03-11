@extends('layouts.app')
@section('pageTitle', 'Dashboard')
@section('content')
    <div class="container mx-auto grid grid-cols-1 md:grid-cols-3 gap-4">

      @if ( auth()->user()->role === 'Vendedor' )
      <div class=" rounded-lg shadow">
        <div class="h-40">
            <img class="w-full h-40 rounded-t-lg" src="https://grupogeard.com/co/wp-content/uploads/sites/3/2020/08/Blog-7.jpg" alt="" srcset="">
        </div>
        <div class="mt-3">
            <b class="p-3 text-2xl">Premios vigentes</b>
            <div class="p-2 justify-between mt-2">
                <div>
                    <table class="w-full mb-5">
                        <thead>
                          <tr class="text-md font-semibold tracking-wide text-left bg-green-500 text-white uppercase border-b border-gray-600">
                            <th class="px-4">Rifa</th>
                            <th class="px-4">Premio</th>
                            <th class="px-4">Juega el</th>
                          </tr>
                        </thead>
                        <tbody >
                          @foreach ($data['prizes'] as $item)
                          <tr class="border-b">
                            <td class="p-2">
                              <div class="flex items-center text-sm">
                                <div>
                                  <p class="">{{$item->raffle->name}}</p>
                                </div>
                              </div>
                            </td>
                            <td class="p-2">
                              <p class=""><span class="font-semibold">{{ $item->type}}</span> @if ($item->type == 'Anticipado')
                                  con el {{ intval($item->percentage_condition) }}%
                              @endif</p>
                            </td>
                            <td class="p-2">
                              <p class="">{{ $item->award_date}}</p>
                            </td>
                            
                          </tr>    
                          @endforeach
                        </tbody>
                      </table>
                </div>
            </div>
        </div>
      </div>

      <div class=" rounded-lg shadow">
        <div class="h-40">
            <img class="w-full h-40 rounded-t-lg" src="https://www.ondasdeibague.com/images/Archivo/dinero_colombiano_0320.jpg" alt="" srcset="">
        </div>
        <div class="mt-3">
            <b class="p-3 text-2xl">Entregas pendientes</b>
            <div class="p-2 justify-between mt-2">
                <div>
                    <table class="w-full mb-5">
                        <thead>
                          <tr class="text-md font-semibold tracking-wide text-left bg-green-500 text-white uppercase border-b border-gray-600">
                            <th class="px-4">Rifa</th>
                            <th class="px-4">Asignado</th>
                            <th class="px-4">Entregado</th>
                            <th class="px-4">Saldo</th>
                          </tr>
                        </thead>
                        <tbody >
                          @foreach ($data['sellers_deliveries'] as $item)
                          <tr class="border-b">
                            <td class="p-2">
                                  <p class="">{{$item->raffle->name}}</p>
                            </td>
                            <td class="p-2">
                              <p class="">{{$item->user->name}} {{$item->user->lastname}}<br>${{ number_format($item->total_tickets) }}</p>
                            </td>
                            <td class="p-2">
                              <p class=" text-right">${{ number_format($item->total_delivery) }}</p>
                            </td>
                            <td class="p-2">
                              <p class=" text-right">${{ number_format($item->total_tickets - $item->total_delivery) }}</p>
                            </td>
                            
                          </tr>    
                          @endforeach
                        </tbody>
                      </table>
                </div>
            </div>
        </div>
      </div>
      @endif
      
    @if ( auth()->user()->role === 'Secretaria' )
    
      <div class="rounded-lg shadow">
        <div class="h-40 ">
            <img class="w-full h-40 rounded-t-lg" src="{{ asset('img/image-3.png') }}" alt="" srcset="">
        </div>
        <div class="mt-3">
            <b class="p-3 text-2xl">Rifas vigentes</b>
            <div class="p-2 justify-between mt-2">
                <div>
                    <table class="w-full mb-5">
                        <thead>
                          <tr class="text-md font-semibold tracking-wide text-left bg-green-500 text-white uppercase border-b border-gray-600">
                            <th class="px-4">Rifa</th>
                            <th class="px-4">Recaudado</th>
                          </tr>
                        </thead>
                        <tbody >
                          @foreach ($data['current_raffles'] as $item)
                          <tr class="border-b">
                            <td class="p-2">
                              <div class="flex items-center text-sm">
                                <div>
                                  <h5 class="h5 ">{{$item->raffle->name}}</h5>
                                  <p class=""><span class="font-semibold" >Total rifa</span> : ${{number_format($item->raffle->price * $item->raffle->tickets_number)}}
                                    <span class="font-semibold" >Entregas</span>   : ${{number_format($item->total)}}</p>
                                  <p><span class="font-semibold" >Saldo</span>      : ${{ number_format( ($item->raffle->price * $item->raffle->tickets_number) - $item->total )}}</p>
                                </div>
                              </div>
                            </td>
                            <td class="p-2">
                              <div class="border-2 w-full" style="height:40px;">
                                <div class="bg-green-500 text-center" style="height:37px;width:{{ ( $item->total /  ($item->raffle->price * $item->raffle->tickets_number) ) * 100 }}%;"> <h4 class="h4 text-center p-2"> {{  ( $item->total /  ($item->raffle->price * $item->raffle->tickets_number) ) * 100 }}% </h4></div>
                              </div>
                            </td>
                            
                          </tr>    
                          @endforeach
                        </tbody>
                      </table>
                </div>
            </div>
        </div>
      </div>
      
      <div class=" rounded-lg shadow">
        <div class="h-40">
            <img class="w-full h-40 rounded-t-lg" src="https://www.auditool.org/images/images/recaudo.jpg" alt="" srcset="">
        </div>
        <div class="mt-3">
            <b class="p-3 text-2xl">Comisiones</b>
            <div class="p-2 justify-between mt-2">
                <div>
                    <table class="w-full mb-5">
                        <thead>
                          <tr class="text-md font-semibold tracking-wide text-left bg-green-500 text-white uppercase border-b border-gray-600">
                            <th class="px-4">Rifa</th>
                            <th class="px-4">Entregado</th>
                          </tr>
                        </thead>
                        <tbody >
                          @foreach ($data['current_raffles'] as $item)
                          <tr class="border-b">
                            <td class="p-2">
                              <div class="flex items-center text-sm">
                                <div>
                                  <p class="">{{$item->raffle->name}}</p>
                                </div>
                              </div>
                            </td>
                            <td class="p-2">
                              <p class="text-right ">${{ number_format($item->total) }}</p>
                            </td>
                            
                          </tr>    
                          @endforeach
                        </tbody>
                      </table>
                </div>
            </div>
        </div>
      </div>

      <div class=" rounded-lg shadow">
        <div class="h-40">
            <img class="w-full h-40 rounded-t-lg" src="https://grupogeard.com/co/wp-content/uploads/sites/3/2020/08/Blog-7.jpg" alt="" srcset="">
        </div>
        <div class="mt-3">
            <b class="p-3 text-2xl">Premios vigentes</b>
            <div class="p-2 justify-between mt-2">
                <div>
                    <table class="w-full mb-5">
                        <thead>
                          <tr class="text-md font-semibold tracking-wide text-left bg-green-500 text-white uppercase border-b border-gray-600">
                            <th class="px-4">Rifa</th>
                            <th class="px-4">Premio</th>
                            <th class="px-4">Juega el</th>
                          </tr>
                        </thead>
                        <tbody >
                          @foreach ($data['prizes'] as $item)
                          <tr class="border-b">
                            <td class="p-2">
                              <div class="flex items-center text-sm">
                                <div>
                                  <p class="">{{$item->raffle->name}}</p>
                                </div>
                              </div>
                            </td>
                            <td class="p-2">
                              <p class=""><span class="font-semibold">{{ $item->type}}</span> @if ($item->type == 'Anticipado')
                                  con el {{ intval($item->percentage_condition) }}%
                              @endif</p>
                            </td>
                            <td class="p-2">
                              <p class="">{{ $item->award_date}}</p>
                            </td>
                            
                          </tr>    
                          @endforeach
                        </tbody>
                      </table>
                </div>
            </div>
        </div>
      </div>
      
    
      
      <div class=" rounded-lg shadow">
        <div class="h-40">
            <img class="w-full h-40 rounded-t-lg" src="https://www.ondasdeibague.com/images/Archivo/dinero_colombiano_0320.jpg" alt="" srcset="">
        </div>
        <div class="mt-3">
            <b class="p-3 text-2xl">Entregas pendientes</b>
            <div class="p-2 justify-between mt-2">
                <div>
                    <table class="w-full mb-5">
                        <thead>
                          <tr class="text-md font-semibold tracking-wide text-left bg-green-500 text-white uppercase border-b border-gray-600">
                            <th class="px-4">Rifa</th>
                            <th class="px-4">Asignado</th>
                            <th class="px-4">Entregado</th>
                            <th class="px-4">Saldo</th>
                          </tr>
                        </thead>
                        <tbody >
                          @foreach ($data['sellers_deliveries'] as $item)
                          <tr class="border-b">
                            <td class="p-2">
                                  <p class="">{{$item->raffle->name}}</p>
                            </td>
                            <td class="p-2">
                              <p class="">{{$item->user->name}} {{$item->user->lastname}}<br>${{ number_format($item->total_tickets) }}</p>
                            </td>
                            <td class="p-2">
                              <p class=" text-right">${{ number_format($item->total_delivery) }}</p>
                            </td>
                            <td class="p-2">
                              <p class=" text-right">${{ number_format($item->total_tickets - $item->total_delivery) }}</p>
                            </td>
                            
                          </tr>    
                          @endforeach
                        </tbody>
                      </table>
                </div>
            </div>
        </div>
      </div>

      <div class=" rounded-lg shadow">
        <div class="h-40">
            <img class="w-full h-40 rounded-t-lg" src="https://www.infobae.com/new-resizer/YbHhIxnL0QaqgSDT10Xy52wYUw8=/768x341/filters:format(webp):quality(85)/cloudfront-us-east-1.images.arcpublishing.com/infobae/CXVK75DVXFB4NKJXVH36ROY2VM.jpg" alt="" srcset="">
        </div>
        <div class="mt-3">
            <b class="p-3 text-2xl">Envío estados de cuenta</b>
            <div class="p-2 justify-between mt-2">
                <div>
                    <table class="w-full mb-5">
                        <thead>
                          <tr class="text-md font-semibold tracking-wide text-left bg-green-500 text-white uppercase border-b border-gray-600">
                            <th class="px-4">Rifa</th>
                            <th class="px-4">Vendedor(a)</th>
                            <th class="px-4">Enviar</th>
                          </tr>
                        </thead>
                        <tbody >
                          @foreach ($data['sellers_deliveries'] as $item)
                          <tr class="border-b">
                            <td class="p-2">
                                  <p class="">{{$item->raffle->name}}</p>
                            </td>
                            <td class="p-2">
                              <p class="">{{$item->user->name}} {{$item->user->lastname}}</p>
                            </td>
                            <td class="p-2">
                              <a href="https://wa.me/57{{$item->user->phone}}?text={{route('boletas.index')}}" target="_blank">
                                <p class=" text-right"><img class="w-8" src="{{ asset('img/icons/whatsapp-icon.svg') }}" alt="" srcset=""></p>
                              </a>
                            </td>
                          </tr>    
                          @endforeach
                        </tbody>
                      </table>
                </div>
            </div>
        </div>
      </div>
    @endif

    @if ( auth()->user()->role === 'Administrador' )
      <div class="rounded-lg shadow">
        <div class="h-40 ">
            <img class="h-40 rounded-t-lg" src="{{ asset('img/image-3.png') }}" alt="" srcset="">
        </div>
        <div class="mt-3">
            <b class="p-3 text-2xl">Rifas vigentes</b>
            <div class="p-2 justify-between mt-2">
                <div>
                    <table class="w-full mb-5">
                        <thead>
                          <tr class="text-md font-semibold tracking-wide text-left bg-green-500 text-white uppercase border-b border-gray-600">
                            <th class="px-4">Rifa</th>
                            <th class="px-4">Recaudado</th>
                          </tr>
                        </thead>
                        <tbody >
                          @foreach ($data['current_raffles'] as $item)
                          <tr class="border-b">
                            <td class="p-2">
                              <div class="flex items-center text-sm">
                                <div>
                                  <h5 class="h5 ">{{$item->raffle->name}}</h5>
                                  <p class=""><span class="font-semibold" >Total rifa</span> : ${{number_format($item->raffle->price * $item->raffle->tickets_number)}}
                                    <span class="font-semibold" >Entregas</span>   : ${{number_format($item->total)}}</p>
                                  <p><span class="font-semibold" >Saldo</span>      : ${{ number_format( ($item->raffle->price * $item->raffle->tickets_number) - $item->total )}}</p>
                                </div>
                              </div>
                            </td>
                            <td class="p-2">
                              <div class="border-2 w-full" style="height:40px;">
                                <div class="bg-green-500 text-center" style="height:37px;width:{{ ( $item->total /  ($item->raffle->price * $item->raffle->tickets_number) ) * 100 }}%;"> <h4 class="h4 text-center p-2"> {{  number_format(( $item->total /  ($item->raffle->price * $item->raffle->tickets_number) ) * 100),0 }}% </h4></div>
                              </div>
                            </td>
                            
                          </tr>    
                          @endforeach
                        </tbody>
                      </table>
                </div>
            </div>
        </div>
      </div>
      
      <div class="rounded-lg shadow">
        <div class="h-40">
            <img class="w-full h-40 rounded-t-lg" src="https://www.auditool.org/images/images/recaudo.jpg" alt="" srcset="">
        </div>
        <div class="mt-3">
            <b class="p-3 text-2xl">Comisiones</b>
            <div class="p-2 justify-between mt-2">
                <div>
                    <table class="w-full mb-5">
                        <thead>
                          <tr class="text-md font-semibold tracking-wide text-left bg-green-500 text-white uppercase border-b border-gray-600">
                            <th class="px-4">Rifa</th>
                            <th class="px-4">Entregado</th>
                          </tr>
                        </thead>
                        <tbody >
                          @foreach ($data['current_raffles'] as $item)
                          <tr class="border-b">
                            <td class="p-2">
                              <div class="flex items-center text-sm">
                                <div>
                                  <p class="">{{$item->raffle->name}}</p>
                                </div>
                              </div>
                            </td>
                            <td class="p-2">
                              <p class="text-right ">${{ number_format($item->total) }}</p>
                            </td>
                            
                          </tr>    
                          @endforeach
                        </tbody>
                      </table>
                </div>
            </div>
        </div>
      </div>

      <div class="rounded-lg shadow">
        <div class="h-40">
            <img class="w-full h-40 rounded-t-lg" src="https://grupogeard.com/co/wp-content/uploads/sites/3/2020/08/Blog-7.jpg" alt="" srcset="">
        </div>
        <div class="mt-3">
            <b class="p-3 text-2xl">Premios vigentes</b>
            <div class="p-2 justify-between mt-2">
                <div>
                    <table class="w-full mb-5">
                        <thead>
                          <tr class="text-md font-semibold tracking-wide text-left bg-green-500 text-white uppercase border-b border-gray-600">
                            <th class="px-4">Rifa</th>
                            <th class="px-4">Premio</th>
                            <th class="px-4">Juega el</th>
                          </tr>
                        </thead>
                        <tbody >
                          @foreach ($data['prizes'] as $item)
                          <tr class="border-b">
                            <td class="p-2">
                              <div class="flex items-center text-sm">
                                <div>
                                  <p class="">{{$item->raffle->name}}</p>
                                </div>
                              </div>
                            </td>
                            <td class="p-2">
                              <p class=""><span class="font-semibold">{{ $item->type}}</span> @if ($item->type == 'Anticipado')
                                  con el {{ intval($item->percentage_condition) }}%
                              @endif</p>
                            </td>
                            <td class="p-2">
                              <p class="">{{ $item->award_date}}</p>
                            </td>
                            
                          </tr>    
                          @endforeach
                        </tbody>
                      </table>
                </div>
            </div>
        </div>
      </div>
    @endif
  
@endsection
