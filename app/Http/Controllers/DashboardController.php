<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Commissions;
use App\Models\Delivery;
use App\Models\Prize;
use App\Models\Raffle;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $current_user = Auth::user();
        $data = [];
        if($current_user->role == 'Administrador' || $current_user->role == 'Secretaria'){
            $raffles = Raffle::whereHas('prizes', function($query) {
                                    $query->where('award_date', '>=', now());
                                    $query->where('type', 'Mayor');
                                })->with(['prizes', 'deliveries' => function ($query) {
                                    $query->select('raffle_id', DB::raw('SUM(total) as delivery_total'));
                                }])->get();
                                
            $data['current_raffles'] = $raffles;
        

            $raffles = Raffle::select('id')->where('raffle_date','>',now())->get();
            /*$sellers_deliveries = Ticket::select('raffle_id','user_id',
                                DB::raw('SUM(price) as total_tickets'),
                                DB::raw('(SELECT SUM(total) FROM deliveries WHERE deliveries.raffle_id = tickets.raffle_id AND deliveries.user_id = tickets.user_id) as total_delivery')
                                )
                                ->whereIn('raffle_id',$raffles)->groupBy('raffle_id','user_id')->get();
                        */
            $sellers_deliveries = Ticket::select(
                                    'tickets.raffle_id',
                                    'tickets.user_id',
                                    DB::raw('SUM(deliveries.used) as total_used'),
                                    DB::raw('SUM(deliveries.total) as total')
                                )
                                ->leftJoin('deliveries', 'tickets.raffle_id', '=', 'deliveries.raffle_id')
                                ->whereIn('tickets.raffle_id',$raffles)
                                ->groupBy('tickets.raffle_id')
                                ->get();

            $commisions = Ticket::select(
                                    'tickets.raffle_id',
                                    'tickets.payment_commission',
                                    'tickets.user_id',
                                    DB::raw('SUM(IF(tickets.payment_commission IS NOT NULL, assignments.commission,0)) as total'),
                                    'commissions.id'
                                )
                                ->leftJoin('commissions', 'tickets.payment_commission', '=', 'commissions.id')
                                ->leftJoin('assignments', 'tickets.assignment_id', '=', 'assignments.id')
                                ->whereIn('tickets.raffle_id',$raffles)
                                ->groupBy('tickets.raffle_id')
                                ->get();     

            $deliveries = Delivery::select(
                                    'raffle_id',
                                    'user_id',
                                    DB::raw('SUM(total) as total'),
                                    DB::raw('SUM(used) as used')
                                )
                                ->whereIn('raffle_id',$raffles)
                                ->groupBy('raffle_id','user_id')
                                ->get();
        
            $data['deliveries'] = $deliveries;
            $data['commissions'] = $commisions;
            $data['sellers_deliveries'] = $sellers_deliveries;

        }

        if($current_user->role == 'Vendedor'){

            /*$raffles = Raffle::whereHas('prizes', function($query) {
                                    $query->where('award_date', '>=', now());
                                })->with(['prizes', 'deliveries' => function ($query) {
                                    $query->select('raffle_id', DB::raw('SUM(total) as delivery_total'));
                                }])->get();*/
            $raffles = Raffle::select('id')->where('raffle_date','>',now())->get();
            $commisions = Ticket::select(
                                    'tickets.raffle_id',
                                    'tickets.payment_commission',
                                    'tickets.user_id',
                                    DB::raw('SUM(IF(tickets.payment_commission IS NOT NULL, assignments.commission,0)) as total'),
                                    'commissions.id'
                                )
                                ->leftJoin('commissions', 'tickets.payment_commission', '=', 'commissions.id')
                                ->leftJoin('assignments', 'tickets.assignment_id', '=', 'assignments.id')
                                ->where('tickets.user_id', $current_user->id)
                                ->whereIn('tickets.raffle_id',$raffles)
                                ->groupBy('tickets.raffle_id')
                                ->get();

            $data['commisions'] = $commisions;


            
        }

        $prizes = Prize::select('raffle_id','detail','award_date','percentage_condition','type','id')
                        ->where('award_date','>',now())
                        ->take(10)
                        ->get();

        $data['prizes'] = $prizes;
        
        return view('dashboard', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    public function destroy($id)
    {
        //
    }
}
