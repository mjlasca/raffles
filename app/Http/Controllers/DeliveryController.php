<?php

namespace App\Http\Controllers;

use App\Exports\DeliveriesExport;
use App\Services\RequestPermissionService;
use App\Models\Delivery;
use App\Models\DeliveryPermission;
use App\Models\PaymentMethod;
use App\Models\PaymentTicket;
use App\Models\Raffle;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;


class DeliveryController extends Controller
{
    protected $deliveryPermission;

    public function __construct(RequestPermissionService $deliveryPermission) {
        $this->deliveryPermission = $deliveryPermission;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        $deliveries = Delivery::orderBy('id', 'DESC')->orderBy('consecutive','DESC');
        $sellers_users = User::select('id','name','lastname')->where('role','Vendedor')->orderBy('name','ASC')->get();
        $raffles = Raffle::where('status',1)->select('id','name')->where('disabled',0)->orderBy('name','ASC')->get();
        $paymentMethods = PaymentMethod::where('status',1)->get();
        if(!empty($req->input('date1'))){
            $date1 = $req->input('date1');
            $date2 = $date1;
            if($req->input('date2'))
                $date2 = $req->input('date2');
            $deliveries = $deliveries->whereBetween('created_at',[$date1.' 00:00:00',$date2.' 23:59:59']);
        }

        if($req->input('user_id')){
            $deliveries = $deliveries->where('user_id',$req->input('user_id'));
        }
        if($req->input('raffle_id')){
            $deliveries = $deliveries->where('raffle_id',$req->input('raffle_id'));
        }
        if($req->input('payment_method_id')){
            $deliveries = $deliveries->where('payment_method_id',$req->input('payment_method_id'));
        }

        if($req->input('keyword')){
            $keyword = $req->input('keyword');

            $deliveries = $deliveries->where('id','like','%' . $keyword . '%')->orWhere('consecutive','like','%' . $keyword . '%')->orWhere(function ($query) use ($keyword) {
                $query->whereHas('redited', function ($q) use ($keyword) {
                    $q->where('name', 'like', '%' . $keyword . '%')
                      ->orWhere('lastname', 'like', '%' . $keyword . '%');
                });
            });
        }

        $deliveryTotal = Delivery::select(
            DB::raw('COUNT(1) as count'),
            DB::raw('SUM(deliveries.total) as total'),
            DB::raw('SUM(deliveries.used) as total_used'),
        );
        $deliveryTotal->where('status',1);
        if($req->input('raffle_id'))
            $deliveryTotal->where('deliveries.raffle_id', $req->input('raffle_id'));
        if($req->input('user_id'))
            $deliveryTotal->where('deliveries.user_id', $req->input('user_id'));
        if($req->input('payment_method_id'))
            $deliveryTotal->where('deliveries.payment_method_id', $req->input('payment_method_id'));
        if(!empty($req->input('date1'))){
            $date1 = $req->input('date1');
            $date2 = $date1;
            if($req->input('date2'))
                $date2 = $req->input('date2');
            $deliveryTotal = $deliveryTotal->whereBetween('created_at',[$date1.' 00:00:00',$date2.' 23:59:59']);
        }

        $deliveryTotal = $deliveryTotal->get();

        if(!empty($deliveryTotal)){
            $totals = [
                'count' => $deliveryTotal[0]['count'],
                'total' => $deliveryTotal[0]['total'],
                'used' => $deliveryTotal[0]['total_used'],
            ];
        }

        $deliveries = $deliveries->paginate('50');
        return view('deliveries.index', compact('deliveries','sellers_users','raffles','totals','paymentMethods'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $raffles = Raffle::where('status',1)->where('disabled',0)->select('raffles.id','raffles.name')->leftJoin(
            'assignments', 'assignments.raffle_id', '=', 'raffles.id'
        )->groupBy('raffles.id')->get();
        $sellers_users = User::select('id','name','lastname')->where('role','Vendedor')->orderBy('name','ASC')->get();
        $date = date('Y-m-d');
        $paymentMethods = PaymentMethod::where('status',1)->get();
        return view('deliveries.create', compact('raffles','sellers_users','date','paymentMethods'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $data = $request->all();

        if(isset($data['raffle_id'])){
            $raffle_id = $data['raffle_id'];
            $user_ = $data['user_id'];

            $sum = Delivery::where('raffle_id',$raffle_id)->where('user_id',$user_)->where('status',1)->sum('total') + $data['total'];
            $sumTicket = Ticket::where('raffle_id',$raffle_id)->where('user_id',$user_)->sum('price');
            $sumPayment = Ticket::where('raffle_id',$raffle_id)->where('user_id',$user_)->sum('payment');
            $sumTotal = ($sumTicket - $sum ) < 0 ? 0 : ($sumTicket - $sum );
            $raffle = Raffle::find($data['raffle_id']);

        }

        $request->validate(
            [
                'total' => ['required', 'delivery_total:'.$data['raffle_id'].':'.$data['user_id']],
                'date' => ['required']
            ],
            [
                'total.delivery_total' => 'El total a entregar debe ser $('.number_format($sumTicket,0).'). El valor de entrega supera el monto total de la rifa, total sin engrega $('.number_format($sumTotal,0).') Suma total entregas mas la actual $('.number_format($sum,0).')',
            ]
        );


        $data['create_user'] = $user->id;
        $data['edit_user'] = $user->id;
        $data['consecutive'] = $this->consecutive($data['raffle_id']);
        $existingDelivery = Delivery::where('raffle_id',$data['raffle_id'])
                            ->where('user_id',$data['user_id'])
                            ->where('description',$data['description'])
                            ->where('created_at', $data['date']." ".date('h:i:s'))
                            ->first();
        if( empty($existingDelivery) && $deliveryQuery = Delivery::create($data)){
            if( ($raffle->price * $raffle->tickets_number) == ($sum + (+ (!empty($data['total']) ? $data['total'] : 0))))
                $raffle->update(['status'=>0]);
            if(isset($data['date'])){
                //$deli = Delivery::find($deliveryQuery->id);
                $timestamp = Carbon::parse($data['date'])->setTimeFromTimeString(date('H:i:s'));
                $deliveryQuery->created_at = $timestamp;
                $deliveryQuery->updated_at = $timestamp;
                $deliveryQuery->save();
            }
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
        $paymentTicket = PaymentTicket::where('delivery_id',$id)->get();
        $payments = [];
        foreach ($paymentTicket as $key => $pay) {
            $arrayPays = explode(';',$pay->detail);
            foreach ($arrayPays as $k => $val) {
                $arrVal = explode(',',$val);
                if(!empty($arrVal[1])){
                    $payments[] = [
                        'ticket_number'  => $arrVal[0],
                        'payment' => $arrVal[1]
                    ];    
                }
            }
        }
        return view('deliveries.show', compact('delivery','payments'));
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
        if(!empty($req->input('format'))){
            $data['delivery'] = $delivery;
            $data['raffle'] = $delivery->raffle;
           return response()->json($data);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $current_user =  Auth::user();
        $delivery = Delivery::find($id);
        $permission = NULL;
        $flag = TRUE;
        $resPermission = $this->deliveryPermission->allowPermission($delivery, $current_user);
        if(empty($resPermission) && $current_user->role != 'Administrador'){
            $permission = 0;
            $flag = FALSE;
        }
        if(!empty($resPermission) && $current_user->role != 'Administrador'){
            $permission = $resPermission->status;
            if($permission == 0){
                $flag = FALSE;
                return view('delivery_permission.pending');
            }
            if($permission == 2){
                $flag = FALSE;
            }
        }
        if(!$flag){
            return redirect()->route('delivery_permission.create',['delivery_id' => $id]);
        }
        $raffles = Raffle::where('status',1)->select('id','name')->where('disabled',0)->get();
        $sellers_users = User::select('id','name','lastname')->where('role','Vendedor')->get();
        $paymentMethods = PaymentMethod::where('status',1)->get();
        return view('deliveries.edit', compact('delivery','raffles','sellers_users','paymentMethods','permission', 'flag','current_user'));
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
        if(isset($data['raffle_id'])){
            $raffle_id = $data['raffle_id'];
            $user_ = $data['user_id'];

            $sum = Delivery::where('raffle_id',$raffle_id)->where('id','!=',$id)->where('status',1)->where('user_id',$user_)->sum('total') + $data['total'];
            $sumTicket = Ticket::where('raffle_id',$raffle_id)->where('user_id',$user_)->sum('price');
            $sumTotal = ($sumTicket - $sum ) < 0 ? 0 : ($sumTicket - $sum );
        }
        $request->validate(
            [
                'raffle_id' => ['delivery_payment:'.$id],
                'total' => ['required', 'delivery_total:'.$data['raffle_id'].':'.$data['user_id'].':'.$id],
                'date' => ['required'],
            ],
            [
                'raffle_id.delivery_payment' => 'La entrega ya tiene pagos canjeados, no es posible modificar esta entrega',
                'total.delivery_total' => 'El total a entregar debe ser $('.number_format($sumTicket,0).'). El valor de entrega supera el monto total de la rifa, total sin engrega $('.number_format($sumTotal,0).') Suma total entregas mas la actual $('.number_format($sum,0).')',
            ]
        );
        $data['edit_user'] = $current_user->id;
        $delivery->update($data);
        $delivery->created_at = $data['date']." ".date('h:i:s');
        $delivery->save();

        return redirect()->route('entregas.index');
    }

    private function consecutive($raffle_id){
        $lastDelivery = Delivery::where('raffle_id', $raffle_id)->count();
        return $lastDelivery + 1 ?? 1;
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

    public function cancel($id)
    {
        $delivery = Delivery::find($id);
        $current_user = Auth::user();
        $flag = TRUE;
        $resPermission = $this->deliveryPermission->allowPermission($delivery, $current_user);
        if(empty($resPermission) && $current_user->role != 'Administrador'){
            $permission = 0;
            $flag = FALSE;
        }
        if(!empty($resPermission) && $current_user->role != 'Administrador'){
            $permission = $resPermission->status;
            if($permission == 0){
                $flag = FALSE;
                return view('delivery_permission.pending');
            }
            if($permission == 2){
                $flag = FALSE;
            }
        }
        if(!$flag){
            return redirect()->route('delivery_permission.create',['delivery_id' => $id]);
        }
        if($delivery->used > 0){
            $paymentReturn = PaymentTicket::where('delivery_id',$id)->get();
            $data = [];
            foreach ($paymentReturn as $key => $pay) {

                if(!empty($pay->detail)){
                    $arrPay = explode(';',$pay->detail);
                    if( is_array($arrPay) ){
                        foreach ($arrPay as $key => $value) {
                            $payment = explode(',', $value);
                            $history = "| ".$current_user->name." ".$current_user->lastname." ha ANULADO el abono en la entrega No. $delivery->consecutive por $".number_format($payment[1],0)." el ".date("d-m-Y h:i:s")." ";
                            $rest = Ticket::where('ticket_number', intval($payment[0]))
                                    ->where('raffle_id', intval($delivery->raffle_id))
                                    ->update([
                                        'payment' => DB::raw("payment - " . intval($payment[1])),
                                        'movements' => DB::raw("CONCAT(" . DB::getPdo()->quote($history) . ", movements)")
                                    ]);
                        }
                    }
                }
                if($rest > 0){
                    $pay->update(
                        ['status' => 2]
                    );
                }
            }
        }
        if(!empty($delivery)){
            $delivery->update([
                'status' => 0
            ]);
        }
        return redirect()->route('entregas.index',['keyword' => $id]);
    }

    public function export(Request $req){
        return Excel::download(new DeliveriesExport($req),'Entregas.xlsx');
    }

    public function proccess() : void {
        $raffleIds = Delivery::select('raffle_id')->distinct()->pluck('raffle_id');

        foreach ($raffleIds as $raffleId) {
            $deliveries = Delivery::where('raffle_id', $raffleId)->orderBy('id')->get();
            $consecutive = 1;
            foreach ($deliveries as $delivery) {
                $delivery->consecutive = $consecutive++;
                $delivery->save();
            }
        }
    }
}
