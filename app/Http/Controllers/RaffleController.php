<?php

namespace App\Http\Controllers;

use App\Exports\RafflesExport;
use App\Exports\RafflesReportExport;
use App\Http\Controllers\Controller;
use App\Models\Raffle;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class RaffleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        $raffles = Raffle::orderBy('raffle_date','DESC');

        if($req->input('date1')){
            $date1 = $req->input('date1');
            $date2 = $date1;
            if($req->input('date2'))
                $date2 = $req->input('date2');
            
            $raffles = $raffles->whereBetween(DB::raw('DATE(raffle_date)'),[$date1,$date2]);
        }

        if($req->input('keyword')){
            $raffles = $raffles->where('name','like','%'.$req->input('keyword').'%');
        }

        
        
        $raffles = $raffles->paginate(50);
        return view('raffles.index', compact('raffles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('raffles.create');
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
        $request->merge([
            'disabled' => $request->has('disabled') ? 1 : 0
        ]);
        $data = $request->all();
        $data['create_user'] = $user->id;
        $data['edit_user'] = $user->id;
        $result = Raffle::create($data);
        return redirect()->route('rifas.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $req)
    {
        $raffle = Raffle::find($id);
        if(!empty($req->input('format')))
            return response()->json($raffle);
            $report = Ticket::where('tickets.raffle_id', $raffle->id)
            ->join('assignments', 'assignments.id', '=', 'tickets.assignment_id')
            ->selectRaw('tickets.user_id, 
                         COUNT(tickets.id) as total_tickets, 
                         SUM(tickets.payment) as total_payment,
                         SUM(CASE WHEN tickets.payment = tickets.price THEN assignments.commission ELSE 0 END) as total_to_commission,
                         SUM(assignments.commission) as total_assignment, 
                         COALESCE((SELECT SUM(commissions.total) FROM commissions WHERE commissions.raffle_id = tickets.raffle_id AND commissions.user_id = tickets.user_id), 0) as total_commissions,
                         COALESCE((SELECT SUM(deliveries.total) FROM deliveries WHERE deliveries.raffle_id = tickets.raffle_id AND deliveries.user_id = tickets.user_id), 0) as total_deliveries')
            ->groupBy('tickets.user_id')
            ->orderByDesc('total_tickets')
            ->get();
        
        
        
        return view('raffles.show', compact('raffle','report'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $raffle = Raffle::find($id);
        return view('raffles.edit', compact('raffle'));
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
        // Lógica para actualizar la rifa en la base de datos
        $raffle = Raffle::find($id);
        $request->merge([
            'disabled' => $request->has('disabled') ? 1 : 0
        ]);
        $raffle->update($request->all());

        return redirect()->route('rifas.index');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Raffle::destroy($id);
        return redirect()->route('raffles.index');
    }

    public function export(){
        return Excel::download(new RafflesExport,'Rifas.xlsx');
    }

    public function exportRaffle(Request $req){
        $raffle = Raffle::find($req->input('raffle_id'));
        return Excel::download(new RafflesReportExport($req),'Rifa_'.$raffle->name.'.xlsx');
    }

    
}
