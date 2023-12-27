<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Raffle;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $assignments = Assignment::with('user')->with('raffle')->paginate(10);
        return view('assignment.index', compact('assignments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $raffles = Raffle::where('raffle_status',0)->select('id','name')->get();
        $sellers_users = User::select('id','name')->where('role','Vendedor')->get();
        return view('assignment.create', compact('raffles','sellers_users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $data = $request->all();
        $data['create_user'] = $user->id;
        $data['edit_user'] = $user->id;
        $raffle = Raffle::where('id',$data['raffle_id'])->select('id','tickets_number','price','ticket_commission')->get();
        $this->setAssignment($data['user_id'], $raffle[0], $data);
        return redirect()->route('asignaciones.index');
    }

    function parts($total, $n) {
        $parts = [];
        $divint = floor($total / $n);
        $rest = $total % $n;
    
        for ($i = 0; $i < $n; $i++) {
            $part = $divint;
    
            if ($i < $rest) {
                $part++;
            }
    
            $parts[] = $part;
        }
    
        return $parts;
    }

    private function setAssignment(Array $users, Raffle $raffle, $dataCreate){
        $current_user = Auth::user();
        $tickets_numbers = ($raffle->tickets_number - 1);
        $available = range(0,$tickets_numbers);
        $parts = $this->parts($tickets_numbers, count($users));
        
        $resultAvailable = $available;

        foreach ($users  as $key =>  $user) {
            $data = [];
            $randArray = array_values(array_intersect_key($resultAvailable, array_flip(array_rand($resultAvailable, $parts[$key]))));
            
            foreach ($randArray as $ticketNumber) {

                $data[] = [
                    'user_id' => $user,
                    'raffle_id' => $raffle->id,
                    'ticket_number' => $ticketNumber,
                    'create_user' => $current_user->id,
                    'edit_user' => $current_user->id,
                ];
            }
            
            $resultAvailable = array_diff($resultAvailable, $randArray);

            
            if(Ticket::insert($data)){
                $dataCreate['tickets_numbers'] = implode(",",$randArray);
                $dataCreate['user_id'] = $user;
                $dataCreate['tickets_total'] = $raffle->tickets_number;
                Assignment::create($dataCreate);
                $raffle->update([
                    'raffle_status' => 1
                ]);
            }
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $assignment = Assignment::with('user')->with('raffle')->find($id);
        $table = explode(",", $assignment->tickets_numbers);
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
