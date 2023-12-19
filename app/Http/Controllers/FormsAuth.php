<?php

namespace App\Http\Controllers;

use App\Models\PendingDuplicate;
use Illuminate\Http\Request;

class FormsAuth extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function formPay($pref, $id){

        $regpending = PendingDuplicate::where('prefijo', $pref)->where('idpropuesta', $id)->where('status',0)->first();
        if($regpending){
            return view('propuesta-duplicate.index', compact('pref','id'));
        }

        return response()->json(["success" => FALSE, 'msg' => 'La propuesta '.$pref.'-'.$id.' no tiene pendientes por duplicar']);

        
    }

}
