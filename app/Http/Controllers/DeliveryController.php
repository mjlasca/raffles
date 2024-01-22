<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Models\Raffle;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class DeliveryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $deliveries = Delivery::paginate(10);
        return view('deliveries.index', compact('deliveries'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $raffles = Raffle::where('raffle_status',1)->where('status',1)->select('id','name')->get();
        $sellers_users = User::select('id','name')->where('role','Vendedor')->get();
        return view('deliveries.create', compact('raffles','sellers_users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /**
         * falta hacer validación de entregas por usuario, ya que no importa cuantas entregas haga un usuario (así supera sus asignaciones)
         * se valida sólo por el monto de la rifa
         */
        $user = Auth::user();
        $data = $request->all();
        if(isset($data['raffle_id'])){
            $raffle = Raffle::find($data['raffle_id']);
            $sum = Delivery::where('raffle_id',$data['raffle_id'])->sum('total') ;
        }
        $request->validate(
            [
                'total' => ['required', 'delivery_total:'.$data['raffle_id']]
            ],
            [
                'total.delivery_total' => 'El valor de entrega supera el monto total de la rifa, #Boletas('.$raffle->tickets_number.') Total $('.number_format($raffle->price * $raffle->tickets_number,0).') Suma total entregas $('.number_format($sum,0).')',
            ]
        );
        
        $data['create_user'] = $user->id;
        $data['edit_user'] = $user->id;

        if(Delivery::create($data)){
            if( ($raffle->price * $raffle->tickets_number) == ($sum + (+ (!empty($data['total']) ? $data['total'] : 0))))
                $raffle->update(['status'=>0]);
        }
        return redirect()->route('entregas.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $req)
    {
        $delivery = Delivery::find($id);
        if(!empty($req->input('format')))
            return response()->json($delivery);
        return view('deliveries.show', compact('delivery'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $delivery = Delivery::find($id);
        $raffles = Raffle::where('raffle_status',0)->select('id','name')->get();
        $sellers_users = User::select('id','name','lastname')->where('role','Vendedor')->get();
        return view('deliveries.edit', compact('delivery','raffles','sellers_users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $current_user =  Auth::user();
        $delivery = Delivery::find($id);
        $data = $request->all();
        $data['edit_user'] = $current_user->id;
        $delivery->update($data);

        return redirect()->route('entregas.index');
    }

    public function pdf($id){
        $data = [];
        $delivery = Delivery::find($id);
        $data["delivery"] = $delivery;
        $pdf = Pdf::loadView('deliveries.pdf', $data);
        return $pdf->stream('Recibo_entrega_'.$delivery->raffle_id.'_'.$delivery->user->name.'_'.$delivery->user->lastname.'_No.'.$delivery->id.'.pdf');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
