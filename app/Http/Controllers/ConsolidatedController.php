<?php

namespace App\Http\Controllers;

use App\Models\Commissions;
use App\Models\Delivery;
use App\Models\Office;
use App\Models\Outflow;
use App\Models\PaymentMethod;
use App\Models\Raffle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConsolidatedController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        $raffles = Raffle::where('status',1)->select('id','name')->where('disabled',0)->orderBy('name','ASC')->get();
        $paymentMethods = PaymentMethod::where('status',1)->get();
        $offices = Office::where('status',1)->get();
        $consolidateArr = [];
        $headerConsolidate = [];



        $deliveries = Delivery::selectRaw('DATE(created_at) as date, payment_method_id, SUM(total) as total, office_id')
                    ->whereNotNull('payment_method_id')
                    ->whereNotNull('office_id')
                    ->groupByRaw('DATE(created_at), payment_method_id, office_id');
                    
        $outflow = Outflow::selectRaw('DATE(created_at) as date, payment_method_id, SUM(total) as total, office_id')
                    ->whereNotNull('payment_method_id')
                    ->whereNotNull('office_id')
                    ->groupByRaw('DATE(created_at), office_id');
                    

        $commissions = Commissions::selectRaw('DATE(created_at) as date, payment_method_id, SUM(total) as total, office_id')
                    ->whereNotNull('payment_method_id')
                    ->whereNotNull('office_id')
                    ->groupByRaw('DATE(created_at), office_id');

        
        if($req->input('raffle_id')){
            $deliveries = $deliveries->where('raffle_id',$req->input('raffle_id'));
            $outflow = $outflow->where('raffle_id',$req->input('raffle_id'));
            $commissions = $commissions->where('raffle_id',$req->input('raffle_id'));
        }
        if($req->input('office_id')){
            $deliveries = $deliveries->where('office_id',$req->input('office_id'));
            $outflow = $outflow->where('office_id',$req->input('office_id'));
            $commissions = $commissions->where('office_id',$req->input('office_id'));
        }
        if($req->input('date1')){
            $deliveries = $deliveries->where('created_at', '>=',$req->input('date1').' 01:00:00');
            $outflow = $outflow->where('created_at', '>=',$req->input('date1').' 01:00:00');
            $commissions = $commissions->where('created_at', '>=',$req->input('date1').' 01:00:00');
        }
        if($req->input('date2')){
            $deliveries = $deliveries->where('created_at', '<=',$req->input('date2').' 23:59:00');
            $outflow = $outflow->where('created_at', '<=',$req->input('date2').' 23:59:00');
            $commissions = $commissions->where('created_at', '<=',$req->input('date2').' 23:59:00');
        }
                    
        $deliveries = $deliveries->get();
        $outflow = $outflow->get();
        $commissions = $commissions->get();
                    
        $headerTotals = [];
        $rowsTotals = [];
        foreach ($deliveries as $key => $value) {   
            $headerConsolidate['deliveries'][$value->office_id.$value->payment_method_id] = $value->paymentMethod->description ." ". $value->office->description;
            $consolidateArr[$value->date]['deliveries'.$value->office_id.$value->payment_method_id]  = isset($consolidateArr[$value->date][$value->office_id][$value->payment_method_id]) ? ($value->total + $consolidateArr[$value->date][$value->office_id][$value->payment_method_id]) : $value->total;
            if($value->payment_method_id == 1){
                $headerTotals[0] = 'Total Entregas Efectivo';
                $rowsTotals[$value->date."0"] = isset($rowsTotals[$value->date]) ? ( $rowsTotals[$value->date] + $consolidateArr[$value->date]['deliveries'.$value->office_id.$value->payment_method_id] ) : $consolidateArr[$value->date]['deliveries'.$value->office_id.$value->payment_method_id];
                $headerTotals[$value->office_id] = 'Total Entregas '.$value->office->description;
                $rowsTotals[$value->date.$value->office_id] = isset($rowsTotals[$value->date]) ? ( $rowsTotals[$value->date] + $consolidateArr[$value->date]['deliveries'.$value->office_id.$value->payment_method_id] ) : $consolidateArr[$value->date]['deliveries'.$value->office_id.$value->payment_method_id];
            }
        }
        ksort($headerTotals);
        foreach ($outflow as $key => $value) {
            $headerConsolidate['outflows'][$value->office_id] = "Salidas ". $value->office->description;
            $consolidateArr[$value->date]['outflows'.$value->office_id]  = isset($consolidateArr[$value->date][$value->office_id]) ? ($value->total + $consolidateArr[$value->date][$value->office_id]) : $value->total;
        }
        foreach ($commissions as $key => $value) {
            $headerConsolidate['commissions'][$value->office_id] = "Comisiones ". $value->office->description;
            $consolidateArr[$value->date]['commissions'.$value->office_id]  = isset($consolidateArr[$value->date][$value->office_id]) ? ($value->total + $consolidateArr[$value->date][$value->office_id]) : $value->total;
        }
        ksort($consolidateArr);
        return view('consolidated.index', compact('raffles','paymentMethods','offices','consolidateArr','headerConsolidate','headerTotals','rowsTotals'));
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
