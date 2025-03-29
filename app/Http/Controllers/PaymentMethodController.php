<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Faker\Provider\ar_EG\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        $payment_methods = PaymentMethod::get();
        return view('paymentmethod.index',compact('payment_methods'));
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
    public function store(Request $req)
    {
        $current_user = Auth::user();
        if($req->input('description')){
            $data['create_user'] = $current_user->id;
            $data['edit_user'] = $current_user->id;
            $data['description'] = strtoupper( $req->input('description') );
            $payment = PaymentMethod::where('description',$data['description'])->first();

            if($req->input('idpayment')){
                $payment = PaymentMethod::find($req->input('idpayment'));
            }else{
                if(empty($payment)){
                    PaymentMethod::create($data);
                }else{
                    $payment->update([
                        'description' => $data['description'],
                        'edit_user' => $current_user->id
                    ]);
                }
            }
        }
        return redirect()->route('paymentmethod.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PaymentMethod  $paymentMethod
     * @return \Illuminate\Http\Response
     */
    public function show(PaymentMethod $paymentMethod)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PaymentMethod  $paymentMethod
     * @return \Illuminate\Http\Response
     */
    public function edit(PaymentMethod $paymentMethod)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PaymentMethod  $paymentMethod
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PaymentMethod  $paymentMethod
     * @return \Illuminate\Http\Response
     */
    public function destroy(PaymentMethod $paymentMethod)
    {
        //
    }
}
