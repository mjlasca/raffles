<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Delivery;
use App\Models\PaymentTicket;
use App\Models\Raffle;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $sellers_users = User::select('id','name','lastname')->where('role','Vendedor')->get();
        if($current_user->role === 'Vendedor'){
            $sellers_users = User::select('id','name','lastname')->where('role','Vendedor')->where('id',$current_user->id)->get();
            $raffles = Assignment::select('raffles.id as raffle_id', 'raffles.name')
                                    ->join('raffles', 'assignments.raffle_id', '=', 'raffles.id')
                                    ->where('assignments.user_id', $current_user->id)
                                    ->get();
        }
            
        if(!empty($filter)){
            $tickets = Ticket::query();
            foreach ($filter as $key => $value) {
                if($key!= 'page' && !empty($value)){
                    $tickets->where($key,$value);
                }   
            }
            $tickets = $tickets->orderBy('raffle_id')->orderBy('ticket_number')->paginate(100);
            $tickets->appends($request->query());
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function pay(Request $req)
    {
        $current_user = Auth::user();
        $deliveries = Delivery::where('status',0)->whereColumn('total','>','used')->select('id','raffle_id','user_id','total')->get();
        
        if($current_user->role === 'Vendedor'){
            $sellers_users = User::select('id','name','lastname')->where('role','Vendedor')->where('id',$current_user->id)->get();
            $deliveries = Delivery::select('raffles.id as raffle_id', 'raffles.name')
                                    ->join('raffles', 'assignments.raffle_id', '=', 'raffles.id')
                                    ->where('assignments.user_id', $current_user->id)
                                    ->where('raffles.raffle_status', 1)
                                    ->where('raffles.status', 1)
                                    ->get();
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

        if(!empty($tickets)){
            $concat = [];
            $total = 0;
            for ($i=0; $i < count($tickets) ; $i++) { 
               Ticket::where('ticket_number', $tickets[$i])->where('raffle_id',$raffle_id)->increment('payment', $payments[$i]);
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

    public function checkTicket(Request $req){
        $ticket = Ticket::where('ticket_number',$req->input('number'))->where('raffle_id',$req->input('raffle_id'))->where('user_id',$req->input('user_id'))->get();

        return response()->json($ticket);
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
