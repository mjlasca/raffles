<?php

namespace App\Http\Controllers;

use App\Models\Outflow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OutFlowController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $outflows  = Outflow::orderBy('updated_at', 'DESC')->paginate('50');
        return view('outflows.index', compact('outflows'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('outflows.create');
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
}
