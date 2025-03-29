<?php

namespace App\Http\Controllers;

use App\Exports\AssigmentsExport;
use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Raffle;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class AssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $req)
    {
        $assignments = Assignment::orderBy('updated_at','DESC');
        $raffles = Raffle::select('id','name')->where('disabled',0)->orderBy('name','ASC')->get();
        if($req->input('date1')){
            $date1 = $req->input('date1');
            $date2 = $date1;
            if($req->input('date2'))
                $date2 = $req->input('date2');
            
            $assignments = $assignments->whereBetween(DB::raw('DATE(updated_at)'),[$date1,$date2]);
        }

        if($req->input('keyword')){
            $assignments = $assignments->orWhereHas('user', function ($query) use ($req) {
                $query->where('name', 'like', '%'.$req->input('keyword').'%');
                $query->orWhere('lastname', 'like', '%'.$req->input('keyword').'%');
            });
        }

        if($req->input('raffle_id')){

            $assignments = $assignments->where('raffle_id',$req->input('raffle_id'));
        }

        

        $assignments = $assignments->paginate(50);
        return view('assignment.index', compact('assignments','raffles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $raffles = Raffle::where('raffle_status',0)->where('disabled',0)->select('id','name')->get();
        $sellers_users = User::select('id','name','lastname')->where('role','Vendedor')->orderBy('name','ASC')->get();
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
        
        $this->setAssignment($data['user_id'], $raffle[0], $tickets,$data);
        return redirect()->route('asignaciones.index');

        
    }

    private function setAssignment($user, Raffle $raffle, $tickets, $data_){
        $current_user = Auth::user();

        $dataCreate['tickets_numbers'] = implode(" ",$tickets);
        $dataCreate['user_id'] = (int)$user;
        $dataCreate['raffle_id'] = $raffle->id;
        $dataCreate['tickets_total'] = count($tickets);
        $dataCreate['create_user'] = $current_user->id;
        $dataCreate['edit_user'] = $current_user->id;
        $dataCreate['commission'] = $data_['commission'];
        $dataCreate['user_referred'] = $data_['user_referred'];
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
     * Change tickets seller to seller from raffle
     */
    public function change(Request $req)
    {
        $current_user = Auth::user();
        $raffles = Raffle::select('id','name')->where('disabled',0)->orderBy('name','ASC')->get();
        $sellers_users = User::select('id','name','lastname')->where('role','Vendedor')->orderBy('name','ASC')->get();
        $selected['raffle'] = null;
        if($req->input('user_1')){
            $req->validate(
                [
                    'tickets' => ['required'],
                ],
                [
                    'tickets' => 'No se ha seleccionado ningún ticket',
                ]
            );
            $user_0 = User::find($req->input('user_0'));
            $movements = "| " . $current_user->name . " " . $current_user->lastname . 
            " cambió el boleto de usuario, antes ($user_0->name $user_0->lastname) el " . date("d-m-Y h:i:s");
            Ticket::whereIn('id', $req->input('tickets'))->update([
                'user_id' => $req->input('user_1'),
                'movements' => DB::raw("IFNULL(CONCAT(" . DB::getPdo()->quote($movements) . ", movements), " . DB::getPdo()->quote($movements) . ")")
            ]);
            $selected['tickets_change'] = Ticket::whereIn('id',$req->input('tickets'))->get();
        }else{
            if($req->input('raffle_id')){
                $selected['raffle'] = Raffle::find($req->input('raffle_id'));
                $user_0 = User::select('id','name','lastname')->whereIn('id', Ticket::where('raffle_id',$selected['raffle']->id)->pluck('user_id'))
                ->orderBy('name', 'asc')
                ->get();
                
                $selected['user_0'] = $user_0;
                if($req->input('user_0')){
                    $selected['user_0'] = User::find($req->input('user_0'));
                    $user_1 = User::select('id','name','lastname')->whereIn('id', Ticket::where('raffle_id',$selected['raffle']->id)->where('user_id','!=',$req->input('user_0'))->pluck('user_id'))
                    ->orderBy('name', 'asc')
                    ->get();
                    $selected['user_1'] = $user_1;
                    $selected['tickets'] = Ticket::select('id','ticket_number')
                    ->where('raffle_id',$selected['raffle']->id)
                    ->where('payment',0)
                    ->where('user_id',$req->input('user_0'))->get();
                }
            }
        }
        
        return view('assignment.change', compact('raffles','sellers_users','selected'));
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

    public function export(Request $req){
        return Excel::download(new AssigmentsExport($req),'Asignaciones.xlsx');
    }
}
