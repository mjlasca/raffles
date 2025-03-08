<?php

namespace App\Http\Controllers;

use App\Exports\CommissionsExport;
use App\Models\Commissions;
use App\Models\Raffle;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class CommissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        $commissions = Commissions::orderBy('updated_at', 'DESC');
        $raffles = Raffle::select('id','name')->where('disabled',0)->orderBy('name','ASC')->get();
        if(!empty($req->input('date1'))){
            $date1 = $req->input('date1');
            $date2 = $req->input('date2');
            $commissions->whereBetween(DB::raw('DATE(updated_at)'),[$date1,$date2]);
        }

        if($req->input('keyword')){

            $commissions = $commissions->whereHas('user', function ($query) use ($req) {
                $query->where('name', 'like', '%'.$req->input('keyword').'%');
                $query->orWhere('lastname', 'like', '%'.$req->input('keyword').'%');
            });
        }
        if($req->input('raffle_id')){

            $commissions = $commissions->where('raffle_id',$req->input('raffle_id'));
        }
        
        $commissions = $commissions->paginate(50);
        return view('commissions.index', compact('commissions','raffles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $req)
    {
        $sellers = User::select('id','name','lastname')->where('role','Vendedor')->orderBy('name','ASC')->get();
        $raffles = Raffle::select('id','name')->where('disabled',0)->orderBy('name','ASC')->get();
        $tickets = Ticket::where('payment_commission', null)->whereHas('raffle', function ($query) {
                            $query->where('raffle_status', '>', -1);
                        })
                        ->whereColumn('price', 'payment')
                        ->join('users', 'tickets.user_id', '=', 'users.id')
                        ->orderBy('users.name', 'ASC')
                        ->select('tickets.*')
                        ->get();
                        //dd($req->input('user_id'));
        if($req->input('user_id'))
            $tickets = $tickets->where('user_id',$req->input('user_id'));
        if($req->input('raffle_id'))
            $tickets = $tickets->where('raffle_id',$req->input('raffle_id'));                        
        //$tickets = $tickets->get();
        $sellers_users = [];
        if($req->input('raffle_id') || $req->input('user_id'))
        {
            $sum = [];
            $aux = [];
            foreach ($tickets as $key => $ticket) {
                $sum[$ticket->user_id][$ticket->raffle_id] = $sum[$ticket->user_id][$ticket->raffle_id] ?? 0;
                $sum[$ticket->user_id][$ticket->raffle_id] += $ticket->assignment->commission;
                $aux[$ticket->user_id][$ticket->raffle_id][] = [
                    'ticket' => $ticket,
                ];
                $sellers_users[$ticket->user_id][$ticket->raffle_id] = [
                    'user' => $ticket->user->name . " " .$ticket->user->lastname,
                    'user_id' => $ticket->user_id,
                    'raffle' => $ticket->raffle,
                    'sum' => $sum[$ticket->user_id][$ticket->raffle_id],
                    'detail' => $aux[$ticket->user_id][$ticket->raffle_id]
                ];
                
            }
        }                
        
        return view('commissions.create', compact('sellers_users','sellers','raffles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!empty($request->input('user_id')) && !empty($request->input('raffle_id'))){
            $user = Auth::user();
            
            $tickets = Ticket::where('user_id',$request->input('user_id'))->where('raffle_id',$request->input('raffle_id'))
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

                $sum = 0;
                $concat = "";
                $arrRaffle = [];
                $commission = false;
                foreach ($tickets as $key => $ticket) {
                    
                    if(!in_array($ticket->raffle_id,$arrRaffle)){
                        
                        $data['raffle_id'] = $ticket->raffle_id;
                        $commission = Commissions::create($data);
                        $arrRaffle[] = $ticket->raffle_id;
                        $sum = 0;
                        $concat = "";
                    }


                    $sum += $ticket->assignment->commission;
                    $concat .= $ticket->raffle->name . " Boleta #" . $ticket->ticket_number. " Comisión: ".$ticket->assignment->commission.";";
                    Ticket::where('ticket_number', $ticket->ticket_number)->where('raffle_id',$ticket->raffle_id)->update([
                        'payment_commission' =>$commission->id
                    ]);

                    if($commission){
                        $commission->update([
                            'total' => $sum,
                            'detail'=> $concat
                        ]);
                    }
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

    public function export(Request $req){
        return Excel::download(new CommissionsExport($req),'Comisiones.xlsx');
    }
}
