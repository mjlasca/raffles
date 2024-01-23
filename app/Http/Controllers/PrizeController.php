<?php

namespace App\Http\Controllers;

use App\Models\Prize;
use App\Models\Raffle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrizeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $prizes = Prize::paginate(10);
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
}
