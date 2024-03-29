<?php

namespace App\Http\Controllers;

use App\Models\Cash;
use App\Models\Raffle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        $date1 = date('Y-m-d', strtotime('-30 days'));
        $date2 = date('Y-m-d');

        if(!empty($request->input('date1')))
            $date1 = $request->input('date1');
        if(!empty($request->input('date2')))
            $date2 = $request->input('date2');

        if(!empty($request->input('raffle_id')))
            $dayTotal = $this->queryMovements($date1,$date2, $request->input('raffle_id'));
        else
            $dayTotal = $this->queryMovements($date1,$date2);

        return view('cashes.index', compact('dayTotal','raffles'));
    }

    private function queryMovements($date1, $date2, $raffle_id = null){
        $dayTotal = DB::table('deliveries')
            ->select(DB::raw('DATE(updated_at) as day_date'), DB::raw('SUM(total) as deliveries_total'))
            ->whereBetween('updated_at',[$date1.' 00:00:00',$date2.' 23:59:59'])
            ->groupBy(DB::raw('DATE(updated_at)'))
            ->orderBy('updated_at','DESC')
            ->get();

        $dayTotal = $dayTotal->merge(
            DB::table('outflows')
                ->select(DB::raw('DATE(updated_at) as day_date'), DB::raw('SUM(total) as outflows_total'))
                ->whereBetween('updated_at',[$date1.' 00:00:00',$date2.' 23:59:59'])
                ->groupBy(DB::raw('DATE(updated_at)'))
                ->orderBy('updated_at','DESC')
                ->get()
        );
        

        $dayTotal = $dayTotal->merge(
            DB::table('commissions')
                ->select(DB::raw('DATE(updated_at) as day_date'), DB::raw('SUM(total) as commissions_total'))
                ->whereBetween('updated_at',[$date1.' 00:00:00',$date2.' 23:59:59'])
                ->groupBy(DB::raw('DATE(updated_at)'))
                ->orderBy('updated_at','DESC')
                ->get()
        );

        if(!empty($raffle_id)){
            $dayTotal = DB::table('deliveries')
            ->select(DB::raw('DATE(updated_at) as day_date'), DB::raw('SUM(total) as deliveries_total'))
            ->where('raffle_id',$raffle_id)
            ->whereBetween('updated_at',[$date1.' 00:00:00',$date2.' 23:59:59'])
            ->groupBy(DB::raw('DATE(updated_at)'))
            ->orderBy('updated_at','DESC')
            ->get();

            $dayTotal = $dayTotal->merge(
                DB::table('outflows')
                    ->select(DB::raw('DATE(updated_at) as day_date'), DB::raw('SUM(total) as outflows_total'))
                    ->where('raffle_id',$raffle_id)
                    ->whereBetween('updated_at',[$date1.' 00:00:00',$date2.' 23:59:59'])
                    ->groupBy(DB::raw('DATE(updated_at)'))
                    ->orderBy('updated_at','DESC')
                    ->get()
            );
            

            $dayTotal = $dayTotal->merge(
                DB::table('commissions')
                    ->select(DB::raw('DATE(updated_at) as day_date'), DB::raw('SUM(total) as commissions_total'))
                    ->where('raffle_id',$raffle_id)
                    ->whereBetween('updated_at',[$date1.' 00:00:00',$date2.' 23:59:59'])
                    ->groupBy(DB::raw('DATE(updated_at)'))
                    ->orderBy('updated_at','DESC')
                    ->get()
            );
        }

        $dayTotal = $dayTotal->merge( Cash::select('updated_at','manual_money_box','day_date','id','real_money_box','difference','deliveries','day_outings','create_user','edit_user',)
                    ->whereBetween('day_date',[$date1.' 00:00:00',$date2.' 23:59:59'])
                    ->orderBy('day_date','DESC')
                    ->get() 
        );

        $dayTotal = $dayTotal->groupBy('day_date')->map(function ($item) {
            $cash = null;
            foreach ($item as $key => $value) {
                if(is_a($value, Cash::class)){
                    $cash = $value;
                    break;
                }
            }
            return [
                'deliveries_total' => $item->sum('deliveries_total'),
                'outflows_total' => $item->sum('outflows_total'),
                'commissions_total' => $item->sum('commissions_total'),
                'cash' => $cash
            ];
        });

        return $dayTotal;
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
    public function destroy(Cash $cash)
    {
        $cash->delete();
        return redirect()->route('arqueos.index');
    }
}
