<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Raffle;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = $request->all();
        $raffles = Raffle::select('id','name')->get();
        $sellers_users = User::select('id','name','lastname')->get();
        if(!empty($filter)){
            $tickets = Ticket::query();
            foreach ($filter as $key => $value) {
                if($key!= 'page' && !empty($value)){
                    $tickets->where($key,$value);
                }   
            }
            $tickets = $tickets->orderBy('raffle_id')->orderBy('ticket_number')->paginate(100);
            $tickets->appends($request->query());
            return view('tickets.index', compact('tickets','raffles','sellers_users'));
        }
        
        return view('tickets.index', compact('raffles','sellers_users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
