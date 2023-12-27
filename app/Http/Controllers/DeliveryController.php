<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Models\Raffle;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeliveryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $deliveries = Delivery::paginate(10);
        return view('deliveries.index', compact('deliveries'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $raffles = Raffle::where('raffle_status',0)->select('id','name')->get();
        $sellers_users = User::select('id','name')->where('role','Vendedor')->get();
        return view('deliveries.create', compact('raffles','sellers_users'));
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
        $data['create_user'] = $user->id;
        $data['edit_user'] = $user->id;
        $result = Delivery::create($data);
        return redirect()->route('entregas.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $delivery = Delivery::find($id);
        return view('deliveries.show', compact('delivery'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $delivery = Delivery::find($id);
        $raffles = Raffle::where('raffle_status',0)->select('id','name')->get();
        $sellers_users = User::select('id','name','lastname')->where('role','Vendedor')->get();
        return view('deliveries.edit', compact('delivery','raffles','sellers_users'));
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
        $current_user =  Auth::user();
        $delivery = Delivery::find($id);
        $data = $request->all();
        $data['edit_user'] = $current_user->id;
        $delivery->update($data);

        return redirect()->route('entregas.index');
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
