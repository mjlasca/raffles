@extends('layouts.app')
@section('pageTitle', 'Dashboard')
@section('content')
    <div class="container mx-auto grid grid-cols-1 md:grid-cols-3 gap-4">

      @if ( auth()->user()->role === 'Vendedor' )
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
                            <th class="px-4 text-right">Comisión recibida</th>
                          </tr>
                        </thead>
                        <tbody >
                          @foreach ($data['commisions'] as $item)
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
                                con mínimo ${{ number_format($item->percentage_condition) }}
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
      
    @if ( auth()->user()->role === 'Secretaria' )
    
    <div class="rounded-lg shadow">
      <div class="h-40 ">
          <img class="h-40 rounded-t-lg" src="{{ asset('img/image-3.png') }}" alt="" srcset="">
      </div>
      <div class="mt-3">
          @if (Request('before'))
            <b class="p-3 text-2xl">Rifas Anteriores </b>    
            <p>
              <a class="bg-green-400 ml-3" href="/">Ver Rifas Vigentes</a>
            </p>
          @else
            <b class="p-3 text-2xl">Rifas vigentes </b>
            <p>
              <a class="bg-green-400 ml-3" href="?before=true">Ver Rifas anteriores</a>
            </p>
          @endif
          <div class="p-2 justify-between mt-2">
              <div>
                @foreach ($data['current_raffles'] as $item)
                  <div class="mb-2 flex chart-raffle justify-center items-center border-1 border-gray-200 rounded-lg">
                    <div class="w-1/2 p-2">
                      <p>
                        <b>{{$item->name}}</b><br>
                        <b>Juega el </b>{{$item->raffle_date}}<br>
                        <b data-total-total-raffle="{{$item->price * $item->tickets_number}}">Total rifa </b>${{ number_format($item->price * $item->tickets_number,0,null,".")}}<br>
                        @if( !empty($item->deliveries[0]) )
                          <b data-total-delivery="{{$item->delivery_total}}">Total entregas </b>${{ number_format($item->delivery_total,0,null,".")}}<br>
                        @else
                          <b data-total-delivery="0">Total entregas </b>${{ number_format(0,0,null,".")}}<br>
                        @endif
                      </p>
                    </div>
                    <div class="w-1/2">
                      <canvas  id="chart-{{$item->id}}"></canvas>
                    </div>
                  </div>
                @endforeach
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
                          <th class="px-4 text-right">Liquidado</th>
                        </tr>
                      </thead>
                      <tbody >
                        @foreach ($data['commissions'] as $item)
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
                            <p class="">{{ $item->type}}</p>
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
            <img class="w-full h-40 rounded-t-lg" src="https://i.pinimg.com/originals/2d/30/a8/2d30a852489756c66c282226abaf6f3d.jpg" alt="" srcset="">
        </div>
        <div class="mt-3">
            <b class="p-3 text-2xl">Entregas pendientes</b>
            <div class="p-2 justify-between mt-2">
                <div class="w-full mb-5 overflow-auto">
                    <table>
                        <thead>
                          <tr class="text-md font-semibold tracking-wide text-left bg-green-500 text-white uppercase border-b border-gray-600">
                            <th class="px-4">Rifa</th>
                            <th class="px-4">Asignado</th>
                            <th class="px-4">Entrega</th>
                            <th class="px-4">Canjeado</th>
                          </tr>
                        </thead>
                        <tbody >
                          @foreach ($data['deliveries'] as $item)
                            @if ($item->total > $item->used)
                            <tr class="border-b">
                              <td class="p-2">  
                                    <p class="">{{$item->raffle->name}}</p>
                              </td>
                              <td class="p-2">
                                <p class="">{{$item->user->name}} {{$item->user->lastname}}</p>
                              </td>
                              <td class="p-2">
                                <p class=" text-right">${{ number_format($item->total) }}</p>
                              </td>
                              <td class="p-2">
                                <p class=" text-right">${{ number_format($item->used) }}</p>
                              </td>
                              
                            </tr>          
                            @endif
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
                              <a href="https://wa.me/57{{$item->user->phone}}?text={{route('boletas.index')}}?user_id={{$item->user_id}}%26raffle_id={{$item->raffle_id}}" target="_blank">
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
          @if (Request('before'))
            <b class="p-3 text-2xl">Rifas Anteriores </b>    
            <p>
              <a class="bg-green-400 ml-3" href="/">Ver Rifas Vigentes</a>
            </p>
          @else
            <b class="p-3 text-2xl">Rifas vigentes </b>
            <p>
              <a class="bg-green-400 ml-3" href="?before=true">Ver Rifas anteriores</a>
            </p>
          @endif
            <div class="p-2 justify-between mt-2">
                <div>
                  @if(isset($data['current_raffles']))
                    @foreach ($data['current_raffles'] as $item)
                    <div class="mb-2 flex chart-raffle justify-center items-center border-1 border-gray-200 rounded-lg">
                        <div class="w-1/2 p-2">
                          <p>
                            <b>{{$item->name}}</b><br>
                            <b>Juega el </b>{{$item->raffle_date}}<br>
                            <b data-total-total-raffle="{{$item->price * $item->tickets_number}}">Total rifa </b>${{ number_format($item->price * $item->tickets_number,0,null,".")}}<br>
                            @if( !empty($item->delivery_total) )
                              <b data-total-delivery="{{$item->delivery_total}}">Total entregas </b>${{ number_format($item->delivery_total,0,null,".")}}<br>
                            @else
                            <b data-total-delivery="0">Total entregas </b>${{ number_format(0,0,null,".")}}<br>
                            @endif
                          </p>
                        </div>
                        <div class="w-1/2">
                          <canvas  id="chart-{{$item->id}}"></canvas>
                        </div>
                      </div>
                    @endforeach
                  @endif
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
                            <th class="px-4 text-right">Liquidado</th>
                          </tr>
                        </thead>
                        <tbody >
                          @if(isset($data['commissions']))
                            @foreach ($data['commissions'] as $item)
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
                          @endif
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
                          @if(isset($data['prizes']))
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
                                    con mínimo ${{ number_format($item->percentage_condition) }}
                                @endif</p>
                              </td>
                              <td class="p-2">
                                <p class="">{{ $item->award_date}}</p>
                              </td>
                              
                            </tr>    
                            @endforeach
                          @endif
                        </tbody>
                      </table>
                </div>
            </div>
        </div>
      </div>
    @endif
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/dashboard.js') }}"></script>    
@endsection
