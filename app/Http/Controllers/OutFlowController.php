<?php

namespace App\Http\Controllers;

use App\Exports\OutflowsExport;
use App\Models\Outflow;
use App\Models\Raffle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class OutFlowController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        $outflows  = Outflow::orderBy('updated_at', 'DESC');
        if(!empty($req->input('date1'))){
            $date1 = $req->input('date1');
            $date2 = $date1;
            if($req->input('date2'))
                $date2 = $req->input('date2');
            $outflows = $outflows->whereBetween(DB::raw('DATE(updated_at)'),[$date1,$date2]);
        }

        if($req->input('keyword')){
            $keyword = $req->input('keyword');

            $outflows = $outflows->where(function ($query) use ($keyword) {
                $query->whereHas('raffle', function ($q) use ($keyword) {
                    $q->where('name', 'like', '%' . $keyword . '%');
                })
                ->orWhereHas('redited', function ($q) use ($keyword) {
                    $q->where('name', 'like', '%' . $keyword . '%')
                      ->orWhere('lastname', 'like', '%' . $keyword . '%');
                });
            });
        }

        $outflows = $outflows->paginate('50');
        return view('outflows.index', compact('outflows'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $raffles = Raffle::where('raffle_date', '>=','NOW()')->get();
        return view('outflows.create',compact('raffles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $current_user = Auth::user();
        $data['edit_user'] = $current_user->id;
        $data['create_user'] = $current_user->id;

        Outflow::create($data);

        return redirect()->route('salidas.index');
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
    public function destroy(Request $req, Outflow $outflow)
    {
        $current_user = Auth::user();
        
        if($outflow->redited->role === $current_user->role || $current_user->role === 'Administrador')
            $outflow->delete();
        return redirect()->route('salidas.index');
    }

    public function export(Request $req){
        return Excel::download(new OutflowsExport($req),'Salidas.xlsx');
    }
}
