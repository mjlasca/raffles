<?php

namespace App\Http\Controllers;

use App\Exports\CashesExport;
use App\Models\Cash;
use App\Models\Commissions;
use App\Models\Delivery;
use App\Models\Outflow;
use App\Models\Raffle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class CashController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $raffles = Raffle::select('id','name')->get();
        $date1 = date('Y-m-d', strtotime('-10 days'));
        $date2 = date('Y-m-d');

        $raffle_id = null;
        if(!empty($request->input('date1'))){
            $date1 = $request->input('date1');
        }
        if(!empty($request->input('date2'))){
            $date2 = $request->input('date2');
        }
        if(!empty($request->input('raffle_id'))){
            $raffle_id = $request->input('raffle_id');
        }

        $cashes = $this->queryMovements($date1,$date2,$raffle_id);
        return view('cashes.index', compact('cashes','raffles'));
    }

    private function queryMovements($date1, $date2, $raffle_id = null){

        /*$totals = Raffle::where('id', $raffle_id)
                ->withSum(['deliveries' => function ($query) use ($date1, $date2) {
                    $query->whereBetween('created_at', [$date1, $date2]);
                }], 'total')
                ->withSum(['commissions' => function ($query) use ($date1, $date2) {
                    $query->whereBetween('created_at', [$date1, $date2]);
                }], 'total')
                ->withSum(['outflows' => function ($query) use ($date1, $date2) {
                    $query->whereBetween('created_at', [$date1, $date2]);
                }], 'total')
                ->paginate(50);*/
                
        $deliveries = Delivery::select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as total'), 'raffle_id')
                        ->whereBetween('created_at', [$date1, $date2])
                        ->groupBy('date', 'raffle_id')
                        ->get();
                    
                    if(!empty($raffle_id)){
                        $deliveries = Delivery::select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as total'), 'raffle_id')
                        ->whereBetween('created_at', [$date1, $date2])
                        ->where('raffle_id', $raffle_id)
                        ->groupBy('date', 'raffle_id')
                        ->get();
                    }
                        
                            
                    
        $commissions = Commissions::select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as total'), 'raffle_id')
                        ->whereBetween('created_at', [$date1, $date2])
                        ->groupBy('date', 'raffle_id')
                        ->get();

                        if(!empty($raffle_id)){
                            $commissions = Commissions::select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as total'), 'raffle_id')
                                ->whereBetween('created_at', [$date1, $date2])
                                ->where('raffle_id', $raffle_id)
                                ->groupBy('date', 'raffle_id')
                                ->get();
                        }
                            
        $outflows = Outflow::select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as total'), 'raffle_id')
                        ->whereBetween('created_at', [$date1, $date2])
                        ->groupBy('date', 'raffle_id')
                        ->get();                

                        if(!empty($raffle_id)){
                            $outflows = Outflow::select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as total'), 'raffle_id')
                                ->whereBetween('created_at', [$date1, $date2])
                                ->where('raffle_id', $raffle_id)
                                ->groupBy('date', 'raffle_id')
                                ->get();                
                        }
        
        $results = [];
        $results['totals']['deliveries'] = 0;
        $results['totals']['commissions'] = 0;
        $results['totals']['outflows'] = 0;
        foreach ($deliveries as $key => $value) {
            $results[$value->date][$value->raffle->id]['deliveries'] = $value->total;
            $results[$value->date][$value->raffle->id]['raffle'] = $value->raffle;
            $results['totals']['deliveries'] += $value->total;
        }
        foreach ($commissions as $key => $value) {
            $results[$value->date][$value->raffle->id]['commissions'] = $value->total;
            $results[$value->date][$value->raffle->id]['raffle'] = $value->raffle;
            $results['totals']['commissions'] += $value->total;
        }
        foreach ($outflows as $key => $value) {
            $results[$value->date][$value->raffle->id]['outflows'] = $value->total;
            $results[$value->date][$value->raffle->id]['raffle'] = $value->raffle;
            $results['totals']['outflows'] += $value->total;
        }
        return $results;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        
        if(!empty($request->input('date'))){
            $date = $request->input('date');
            $dayTotal = $this->queryMovements($date,$date);
            $dateFormat = date('d',strtotime($date)).' de '.trans('date.'.date('F', strtotime($date))).' del '.date('Y', strtotime($date));
            $cash = Cash::where('day_date',$date)->count();
            
            if($cash < 1){
                $total = 0;
                foreach ($dayTotal as $value) {
                    $total =  $value['deliveries_total'] - ( $value['outflows_total'] + $value['commissions_total'] );
                }
                
                return view('cashes.create', compact('dayTotal','date','dateFormat','total'));    
            }
                
            
        }

        return redirect()->route('arqueos.index');
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
        Cash::create($data);
        return redirect()->route('arqueos.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $date
     * @return \Illuminate\Http\Response
     */
    public function dayView(Request $req, $date)
    {
        $raffles = Raffle::select('id','name')->get();
        $deliveries = Delivery::where('created_at','>=', $date." 00:01:00")->where('created_at','<=', $date." 23:59:59");
        $outflows = Outflow::where('created_at','>=', $date." 00:01:00")->where('created_at','<=', $date." 23:59:59");
        $commissions = Commissions::where('created_at','>=', $date." 00:01:00")->where('created_at','<=', $date." 23:59:59");
        $raffle_ = "Todas las rifas";
        
        if($req->input('raffle_id')){
            $raffle = Raffle::find($req->input('raffle_id'));
            $raffle_ = $raffle->name;
            $deliveries = $deliveries->where('raffle_id',$req->input('raffle_id'));
            $outflows = $outflows->where('raffle_id',$req->input('raffle_id'));
            $commissions = $commissions->where('raffle_id',$req->input('raffle_id'));
        }
        $deliveries = $deliveries->get();
        $outflows = $outflows->get();
        $commissions = $commissions->get();

        return view('cashes.dayview', compact('deliveries','outflows','date','raffles','raffle_','commissions'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
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
    public function destroy(Cash $cash)
    {
        $cash->delete();
        return redirect()->route('arqueos.index');
    }

    public function export(Request $req){
        return Excel::download(new CashesExport($req),'Arqueos.xlsx');
    }
}
