<?php

namespace App\Http\Controllers;

use App\Exports\PrizesExport;
use App\Models\Prize;
use App\Models\Raffle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class PrizeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        
        $prizes = Prize::orderBy('updated_at','DESC');
        if(!empty($req->input('date1'))){
            $date1 = $req->input('date1');
            $date2 = $date1;
            if($req->input('date2'))
                $date2 = $req->input('date2');
            $deliveries = $prizes->whereBetween('updated_at',[$date1.' 00:00:00',$date2.' 23:59:59']);
        }

        if($req->input('keyword')){
            $keyword = $req->input('keyword');

            $prizes = $prizes->where(function ($query) use ($keyword) {
                $query->whereHas('raffle', function ($q) use ($keyword) {
                    $q->where('name', 'like', '%' . $keyword . '%');
                })
                ->orWhereHas('redited', function ($q) use ($keyword) {
                    $q->where('name', 'like', '%' . $keyword . '%')
                      ->orWhere('lastname', 'like', '%' . $keyword . '%');
                });
            });
        }

        $prizes = $prizes->paginate('50');
        return view('prizes.index', compact('prizes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //raffle status 2 is prize winning 
        $raffles = Raffle::where('status','<',2)->select('id','name')->get();
        return view('prizes.create', compact('raffles'));
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
        $request->validate(
            [
                'type' => ['required', 'jackpot:'.$data['raffle_id']]
            ],
            [
                'type.jackpot' => 'El premio mayor ya fue asignado a ésta rifa, sólo puede haber un premio mayor',
            ]
        );

        $data['status'] = 1;
        $data['create_user'] = $user->id;
        $data['edit_user'] = $user->id;
        $result = Prize::create($data);
        return redirect()->route('premios.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $prize = Prize::find($id);
        return view('prizes.show', compact('prize'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $prize = Prize::find($id);
        $raffles = Raffle::where('status','<',2)->select('id','name')->get();

        return view('prizes.edit', compact('prize','raffles'));
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
        $prize = Prize::find($id);

        $request->validate(
            [
                'winning_ticket' => ['win_ticket:'.$request->input('raffle_id').':'.$id]
            ],
            [
                'winning_ticket.win_ticket' => 'El boleto ganador no pertece a la rifa seleccionada o no tiene el pago mínimo',
            ]
        );

        
        $prize->update($request->all());
        return redirect()->route('premios.index');
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

    public function export(){
        return Excel::download(new PrizesExport,'Premios.xlsx');
    }

    
    public function results(Request $req){

        $today = date('Y-m-d');
        $prizes = Prize::where('award_date','<',$today)->orderBy('award_date','DESC');
        $current_prizes = Prize::where('award_date','>=',$today)->orderBy('award_date','DESC')->get();

        if(!empty($req->input('date1'))){
            $date1 = $req->input('date1');
            $date2 = $date1;
            if($req->input('date2'))
                $date2 = $req->input('date2');
            $prizes = $prizes->whereBetween('award_date',[$date1.' 00:00:00',$date2.' 23:59:59']);
        }

        $prizes = $prizes->paginate('50');
        return view('prizes.results', compact('prizes','current_prizes'));
    }
}
