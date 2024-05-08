<?php

namespace App\Http\Controllers;

use App\Exports\DeliveriesExport;
use App\Models\Delivery;
use App\Models\Raffle;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class DeliveryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        $deliveries = Delivery::orderBy('updated_at', 'DESC');
        $sellers_users = User::select('id','name','lastname')->where('role','Vendedor')->get();
        if(!empty($req->input('date1'))){
            $date1 = $req->input('date1');
            $date2 = $date1;
            if($req->input('date2'))
                $date2 = $req->input('date2');
            $deliveries = $deliveries->whereBetween('updated_at',[$date1.' 00:00:00',$date2.' 23:59:59']);
        }

        if($req->input('user_id')){
            $deliveries = $deliveries->where('user_id',$req->input('user_id'));
        }

        if($req->input('keyword')){
            $keyword = $req->input('keyword');

            $deliveries = $deliveries->where(function ($query) use ($keyword) {
                $query->whereHas('raffle', function ($q) use ($keyword) {
                    $q->where('name', 'like', '%' . $keyword . '%');
                })
                ->orWhereHas('redited', function ($q) use ($keyword) {
                    $q->where('name', 'like', '%' . $keyword . '%')
                      ->orWhere('lastname', 'like', '%' . $keyword . '%');
                });
            });
        }

        $deliveries = $deliveries->paginate('50');
        return view('deliveries.index', compact('deliveries','sellers_users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $raffles = Raffle::where('raffle_status',1)->where('status',1)->select('id','name')->get();
        $sellers_users = User::select('id','name','lastname')->where('role','Vendedor')->orderBy('name','ASC')->get();
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
            $raffle_id = $data['raffle_id'];
            $user_ = $data['user_id'];
            
            $sum = Delivery::where('raffle_id',$raffle_id)->where('user_id',$user_)->sum('total') + $data['total'];
            $sumTicket = Ticket::where('raffle_id',$raffle_id)->where('user_id',$user_)->sum('price');
            $sumPayment = Ticket::where('raffle_id',$raffle_id)->where('user_id',$user_)->sum('payment');
            $sumTotal = ($sumTicket - $sumPayment );
            $raffle = Raffle::find($data['raffle_id']);
            
        }

        $request->validate(
            [
                'total' => ['required', 'delivery_total:'.$data['raffle_id'].':'.$data['user_id']]
            ],
            [
                'total.delivery_total' => 'El valor de entrega supera el monto total de la rifa, Total sin engrega $('.number_format($sumTotal,0).') Suma total entregas $('.number_format($sum,0).')',
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
    public function show($id)
    {
        $delivery = Delivery::find($id);
        return view('deliveries.show', compact('delivery'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function payment($id, Request $req)
    {
        $delivery = Delivery::find($id);
        if(!empty($req->input('format')))
            return response()->json($delivery);
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

    public function export(Request $req){
        return Excel::download(new DeliveriesExport($req),'Entregas.xlsx');
    }
}
