<?php

namespace App\Http\Controllers;

use App\Exports\TicketsExport;
use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Delivery;
use App\Models\PaymentTicket;
use App\Models\Raffle;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $current_user = Auth::user();
        $filter = $request->all();
        $raffles = Raffle::select('id','name')->get();
        $sellers_users = User::select('id','name','lastname')->where('role','Vendedor')->orderBy('name','ASC')->get();
        if($current_user->role === 'Vendedor'){
            $sellers_users = User::select('id','name','lastname')->where('role','Vendedor')->where('id',$current_user->id)->get();
            $raffles = Assignment::select('raffles.id as raffle_id', 'raffles.name')
                                    ->join('raffles', 'assignments.raffle_id', '=', 'raffles.id')
                                    ->where('assignments.user_id', $current_user->id)
                                    ->get();
        }
        
        if( isset($filter["raffle_id"]) || isset($filter["user_id"]) || isset($filter["ticket_number"]) ){
            $tickets = Ticket::query();
            foreach ($filter as $key => $value) {
                
                if($key!= 'page' && $value != null){
                    $tickets->where($key,$value);
                }   
                
                    
            }

            $tickets = $tickets->orderBy('raffle_id')->orderBy('ticket_number')->paginate(100);
            $tickets->appends($filter);
            
            

            return view('tickets.index', compact('tickets','raffles','sellers_users'));
        }
        
        return view('tickets.index', compact('raffles','sellers_users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $ticket = Ticket::find($id);
        return view('tickets.show', compact('ticket'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $ticket = Ticket::find($id);
        $sellers_users = User::select('id','name','lastname')->where('role','Vendedor')->get();
        return view('tickets.edit', compact('ticket','sellers_users'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function pay(Request $req)
    {
        $current_user = Auth::user();
        $deliveries = Delivery::whereColumn('total','>','used')->select('id','raffle_id','user_id','total','used')->get();
        
        if($current_user->role === 'Vendedor'){
            $sellers_users = User::select('id','name','lastname')->where('role','Vendedor')->where('id',$current_user->id)->get();
            $deliveries = Delivery::where('user_id', $current_user->id)->whereColumn('used','<','total')->get();
        }
        $sellers_users = User::select('id','name')->where('role','Vendedor')->get();
        return view('tickets.pay', compact('deliveries','sellers_users','current_user'));
    }

    public function setpay(Request $req){
        $current_user = Auth::user();

        $delivery_id = $req->input('delivery_id');
        $user_id = $req->input('user_id');
        $raffle_id = $req->input('raffle_id');
        $tickets = $req->input('ticket_number');
        $payments = $req->input('ticket_payment');
        $names = $req->input('customer_name');
        $phones = $req->input('customer_phone');

        if(!empty($tickets)){
            $concat = [];
            $total = 0;
            for ($i=0; $i < count($tickets) ; $i++) { 
                
                Ticket::where('ticket_number', $tickets[$i])->where('raffle_id',$raffle_id)->increment('payment', $payments[$i]);
                $history = "'| ".$current_user->name." ".$current_user->lastname." ha hecho un abono de $".number_format($payments[$i],0)." el ".date("d-m-Y h:i:s")." '";
                Ticket::where('ticket_number', $tickets[$i])->where('raffle_id',$raffle_id)->update([
                    'customer_name' => $names[$i],
                    'customer_phone' => $phones[$i],
                    'movements' =>  DB::raw("IFNULL(CONCAT($history,movements), $history)")
                ]);

                $concat[] = $tickets[$i].",".$payments[$i];
                $total += $payments[$i];
            }

            Delivery::where('id',$delivery_id)->increment('used', $total);

            $data['delivery_id'] = $delivery_id;
            $data['payment_value'] = $total;
            $data['detail'] = implode(";", $concat);
            $data['create_user'] = $current_user->id;
            $data['edit_user'] = $current_user->id;
            PaymentTicket::create($data);
        }
        
        return redirect()->route('boletas.index', ['raffle_id' => $raffle_id, 'user_id' => $user_id]);
    }

    public function payall(Request $req){

        $current_user = Auth::user();
        
        if($req->input('delivery_id')){
            $delivery = Delivery::find($req->input('delivery_id'));

            $to_use = $delivery->total - $delivery->used;
            $to_use_0 = $delivery->total - $delivery->used;
            $tickets_0 = Ticket::where('raffle_id',$delivery->raffle_id)->where('user_id',$delivery->user_id)->whereColumn('price','>','payment')->get();

            //tickets id base
            $ticketIds = [];
            foreach ($tickets_0 as $key => $ticket) {
                $ticketIds[] = $ticket->id;
            }
            //price ticket
            $price = $tickets_0[0]->price;
            
            //base total tickets initial
            $total_b = $tickets_0->sum('payment');

            //concat history payment
            $history = "'". $current_user->name." ".$current_user->lastname." ha hecho un pago distribuido " .date("d-m-Y h:i:s") ."'";
            
            //results update
            $restUpdate = 0;
            
            foreach ($ticketIds as $key => $value) {

                //update ticket
                $rUpdate = Ticket::where('id', $value)
                        ->whereRaw('price > payment ') // Asegura que price sea mayor o igual a payment + $additionalPayment
                        ->update([
                            'payment' => DB::raw(' price - payment '),
                            'movements' =>  DB::raw("IFNULL(CONCAT($history,movements), $history)")
                        ]);
                        
                if(is_numeric($rUpdate)){
                    $restUpdate += (int)$rUpdate;
                }
                
                //after sum
                $total_x = Ticket::whereIn('id', $ticketIds)->sum('payment');
                $to_use_dynamic = abs($total_b - $total_x);
                
                if($to_use_dynamic >= ($to_use - $price) ){
                    
                    $result_used = $to_use - $to_use_dynamic; 
                    //update ticket
                    $rUpdate = Ticket::where('raffle_id', $delivery->raffle_id)
                        ->where('user_id', $delivery->user_id)
                        ->whereRaw('(price - payment) >=  '.$result_used)
                        ->first();

                    if ($rUpdate) {
                        $rUpdate->update([
                            'payment' => $result_used,
                            'movements' => DB::raw("IFNULL(CONCAT($history, movements), $history)")
                        ]);
                    }

                    $restUpdate = 1;
                    break;
                }

            }

            if($restUpdate > 0){
                //condition for money used update in delivery table
                $delivery->update([
                    'used' => DB::raw('used + ' . $to_use_dynamic ),
                ]);
            }
            
            return redirect()->route('boletas.index', ['raffle_id' => $delivery->raffle_id, 'user_id' => $delivery->user_id]);
        }

    }

    public function checkTicket(Request $req){
        $ticket = Ticket::where('ticket_number',$req->input('number'))->where('raffle_id',$req->input('raffle_id'))->where('user_id',$req->input('user_id'))->get();
        
        return response()->json($ticket);
        
    }


    public function removable(Request $req){
        
        if(!empty($req->input('checks'))){
            $arrChecks = explode(',',$req->input('checks'));
            $ticket = Ticket::whereIn('id',$arrChecks)->update([
                'removable' => 1
            ]);
        }

        return response()->json($ticket);
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $current_user = Auth::user();
        $ticket = Ticket::find($id);
        $req = $request->all();
        if($ticket->removable != 1){
            $concat = "";
            if( $req['user_id'] != $ticket->user_id ){
                $userChange = User::find($req['user_id']);
                $concat .=  "| ".$current_user->name." " .$current_user->lastname. " modificó el usuario de ésta boleta de ".$ticket->user->name." ".$ticket->user->lastname. " a ".$userChange->name." ".$userChange->lastname." el ".date("d-m-Y h:i:s");
            }
    
            if( $req['payment'] != $ticket->payment ){
                $concat .=  "| ".$current_user->name." " .$current_user->lastname. " modificó el abono de ésta boleta de ".$ticket->payment. " a ".$req['payment']." el ".date("d-m-Y h:i:s");
            }
    
            $req['movements'] = $concat . $ticket->movements;
                
            $req['edit_user'] = $current_user->id;
            
            $ticket->update($req);
        }
        return redirect()->route('boletas.show', $id);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function export(Request $req){
        return Excel::download(new TicketsExport($req),'Boletas.xlsx');
    }
}
