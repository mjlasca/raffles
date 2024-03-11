<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Raffle;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $assignments = Assignment::paginate(50);
        return view('assignment.index', compact('assignments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $raffles = Raffle::where('raffle_status',0)->select('id','name')->get();
        $sellers_users = User::select('id','name','lastname')->where('role','Vendedor')->get();
        return view('assignment.create', compact('raffles','sellers_users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $data = $request->all();
        $tickets =  explode("\r\n",$data['tickets'] );
        $raffle = Raffle::where('id',$data['raffle_id'])->select('id','tickets_number','price','ticket_commission')->get();
        $existingIds = Ticket::where('raffle_id',$data['raffle_id'])->whereIn('ticket_number', $tickets)->pluck('ticket_number')->toArray();
        $invalidValues = [];
        foreach ($tickets as $val) {
            if ($val >= $raffle[0]->tickets_number) {
                $invalidValues[] = $val;
            }
        }

        $request->validate(
            [
                'tickets' => ['required', 'tickets_exists:'.$data['raffle_id'],'length_ticket:'.$raffle[0]->tickets_number],
                'name' => ['required'],
                'raffle_id' => ['required'],
                'user_id' => ['required'],
                'commission' => ['required','max_value:'.$raffle[0]->price],
            ],
            [
                'tickets_exists' => 'Los siguientes números de boletas ya fueron asignados: (' . implode(',', $existingIds).')',
                'length_ticket' => 'Los siguientes valores son mayores al total de boletas: (' . implode(',', $invalidValues).')',
                'commission.max_value' => 'El valor de comisión no puede ser mayor al de la rifa ($'.number_format($raffle[0]->price,0).')'
            ]
        );

        
        $data['create_user'] = $user->id;
        $data['edit_user'] = $user->id;
        
        $this->setAssignment($data['user_id'], $raffle[0], $tickets,$data['commission']);
        return redirect()->route('asignaciones.index');

        
    }

    private function setAssignment($user, Raffle $raffle, $tickets, $commission){
        $current_user = Auth::user();

        $dataCreate['tickets_numbers'] = implode(" ",$tickets);
        $dataCreate['user_id'] = (int)$user;
        $dataCreate['raffle_id'] = $raffle->id;
        $dataCreate['tickets_total'] = count($tickets);
        $dataCreate['create_user'] = $current_user->id;
        $dataCreate['edit_user'] = $current_user->id;
        $dataCreate['commission'] = $commission;
        $assign = Assignment::create($dataCreate);
        
        if($assign){
            
            foreach ($tickets as $ticketNumber) {

                $data[] = [
                    'user_id' => $user,
                    'raffle_id' => $raffle->id,
                    'ticket_number' => $ticketNumber,
                    'price' => $raffle->price,
                    'create_user' => $current_user->id,
                    'edit_user' => $current_user->id,
                    'assignment_id' => $assign->id
                ];
            }
            if(Ticket::insert($data)){
                $ticketsTotal = Ticket::where('raffle_id',$raffle->id)->count();

                if ($ticketsTotal == $raffle->tickets_number) {
                    Raffle::where('id', $raffle->id)->update(['raffle_status' => 1]);
                }
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $assignment = Assignment::with('user')->with('raffle')->find($id);
        $table = explode(" ", $assignment->tickets_numbers);
        return view('assignment.show', compact('assignment','table'));
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
