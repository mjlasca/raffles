@extends('layouts.app')
@section('pageTitle', 'Dashboard')
@section('content')
    <div class="container mx-auto md:flex ">
      <div class="w-full md:w-1/2 items-center justify-center px-2 mt-10">
        <div class="flex justify-between  rounded-lg shadow">
            <div class="">
                <b class="p-3 text-2xl">Rifas vigentes</b>
                <div class="p-3 flex justify-between mt-2">
                    <div>
                        <h3>Total entregas</h3>
                        <p>$ 1.520.000</p>
                    </div>
                    <div>
                        <h3>Total faltante</h3>
                        <p>$ 3.820.000</p>
                    </div>
                </div>
            </div>
            <div>
                <img src="{{ asset('img/image-4.png') }}" alt="" srcset="">
            </div>
        </div>
        <div class="flex justify-between  rounded-lg shadow mt-10">
            <div class="">
                <b class="p-3 text-2xl">Comisiones último mes</b>
                <div class="p-3 flex justify-between mt-2">
                    <div>
                        <h3>Total comisiones</h3>
                        <p>$ 630.000</p>
                    </div>
                </div>
            </div>
            <div>
                <img src="{{ asset('img/image-5.png') }}" alt="" srcset="">
            </div>
        </div>
        <div class="rounded-lg shadow mt-10">
            <div>
                <img class="w-full" src="{{ asset('img/image-6.png') }}" alt="" srcset="">
            </div>
            <div class="mt-5">
                <b class="p-3 text-2xl">Enviar estado de cuenta último mes</b>
                <div class="p-3 justify-between mt-2">
                    <div>
                        <table class="w-full">
                            <thead>
                              <tr class="text-md font-semibold tracking-wide text-left text-gray-900 bg-gray-100 uppercase border-b border-gray-600">
                                <th class="px-4 py-3">Vendedor</th>
                                <th class="px-4 py-3">Estado</th>
                                <th class="px-4 py-3">Enviar</th>
                              </tr>
                            </thead>
                            <tbody class="bg-white">
                              <tr class="text-gray-700">
                                <td class="px-4 py-3 border">
                                  <div class="flex items-center text-sm">
                                    <div>
                                      <p class="font-semibold text-black">Rafael</p>
                                      <p class="text-xs text-gray-600">López Sepúlveda</p>
                                    </div>
                                  </div>
                                </td>
                                <td class="px-4 py-3 text-ms font-semibold border">Octubre/23</td>
                                <td class="px-4 py-3 text-md font-semibold border bg-green-500">
                                    <a href="https://web.whatsapp.com/send/?text=Entrega realizada https://example.com" target="_blank">
                                        <img class="h-5" src="{{ asset('img/icons/whatsapp-icon.svg') }}" alt="" srcset="">
                                    </a>
                                </td>
                              </tr>
                              <tr class="text-gray-700">
                                <td class="px-4 py-3 border">
                                  <div class="flex items-center text-sm">
                                    <div>
                                      <p class="font-semibold text-black">Melissa</p>
                                      <p class="text-xs text-gray-600">Martínez Polanco</p>
                                    </div>
                                  </div>
                                </td>
                                <td class="px-4 py-3 text-md font-semibold border">Octubre/23</td>
                                <td class="px-4 py-3 text-md font-semibold border bg-green-500">
                                    <a href="https://web.whatsapp.com/send/?text=Entrega realizada https://example.com" target="_blank">
                                        <img class="h-5" src="{{ asset('img/icons/whatsapp-icon.svg') }}" alt="" srcset="">
                                    </a>
                                </td>
                              </tr>
                              
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
      </div>
      <div class="w-full md:w-1/2 px-2 mt-10">
        <div class="rounded-lg shadow">
            <div>
                <img class="w-full" src="{{ asset('img/image-3.png') }}" alt="" srcset="">
            </div>
            <div class="mt-5">
                <b class="p-3 text-2xl">Vendedores sin entregas</b>
                <div class="p-3 justify-between mt-2">
                    <div>
                        <table class="w-full">
                            <thead>
                              <tr class="text-md font-semibold tracking-wide text-left text-gray-900 bg-gray-100 uppercase border-b border-gray-600">
                                <th class="px-4 py-3">Vendedor</th>
                                <th class="px-4 py-3">Rifa</th>
                              </tr>
                            </thead>
                            <tbody class="bg-white">
                              <tr class="text-gray-700">
                                <td class="px-4 py-3 border">
                                  <div class="flex items-center text-sm">
                                    <div>
                                      <p class="font-semibold text-black">Martín</p>
                                      <p class="text-xs text-gray-600">Bernal Buitrago</p>
                                    </div>
                                  </div>
                                </td>
                                <td class="px-4 py-3 text-ms font-semibold border">Rifa Ginebra 2023-2</td>
                                
                              </tr>
                              <tr class="text-gray-700">
                                <td class="px-4 py-3 border">
                                  <div class="flex items-center text-sm">
                                    <div>
                                      <p class="font-semibold text-black">Melissa</p>
                                      <p class="text-xs text-gray-600">Martínez Polanco</p>
                                    </div>
                                  </div>
                                </td>
                                <td class="px-4 py-3 text-md font-semibold border">Rifa Tuluá 2023-2</td>
                                
                              </tr>
                              
                            </tbody>
                          </table>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>
@endsection
