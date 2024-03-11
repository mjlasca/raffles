<?php

namespace App\Http\Controllers;

use App\Models\Commissions;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $commissions = Commissions::orderBy('updated_at', 'DESC')->paginate(50);
        return view('commissions.index', compact('commissions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tickets = Ticket::where('payment_commission', null)->whereHas('raffle', function ($query) {
                            $query->where('raffle_status', '>', 0);
                        })
                        ->whereColumn('price', 'payment')
                        ->get();
                        
        $sellers_users = [];
        $sum = 0;
        $aux = [];
        foreach ($tickets as $key => $ticket) {
            $sum += $ticket->assignment->commission;
            
            $aux[$ticket->user_id][] = [
                'ticket' => $ticket,
            ];
            $sellers_users[$ticket->user_id] = [
                'user' => $ticket->user->name,
                'user_id' => $ticket->user_id,
                'sum' => $sum,
                'detail' => $aux[$ticket->user_id]
            ];
            
        }
        
        return view('commissions.create', compact('sellers_users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if(!empty($request->input('user_id'))){
            $user = Auth::user();

            $tickets = Ticket::where('user_id',$request->input('user_id'))->whereHas('raffle', function ($query) {
                $query->where('raffle_status', '>', 0);
            })
            ->whereColumn('price', 'payment')
            ->get();

            $data = [
                'user_id' => $request->input('user_id') ,
                'percentage'=> 0,
                'val_commission'=> 0,
                'total' => 0,
                'detail'=> '',
                'create_user' => $user->id,
                'edit_user' => $user->id,
            ];

            if($commission = Commissions::create($data)){
                $sum = 0;
                $concat = "";
                foreach ($tickets as $key => $ticket) {
                    $sum += $ticket->assignment->commission;
                    $concat .= $ticket->raffle->name . " Boleta #" . $ticket->ticket_number. " Comisión: ".$ticket->assignment->commission.";";
                    Ticket::where('ticket_number', $ticket->ticket_number)->where('raffle_id',$ticket->raffle_id)->update([
                        'payment_commission' =>$commission->id
                    ]);
                }

                $commission->update([
                    'total' => $sum,
                    'detail'=> $concat
                ]);
            }
            

        }
        return redirect()->route('comisiones.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $commission = Commissions::find($id);
        return view('commissions.show', compact('commission'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Commissions $commission)
    {
        $commission->delete();
        return redirect()->route('comisiones.index');
    }
}
