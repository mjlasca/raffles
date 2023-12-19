<?php

namespace App\Http\Controllers;

use App\Models\usuario;
use Illuminate\Http\Request;

class UsuariosController extends Controller
{
    public function getEmpresa($codempresa){
        $usuarios = usuario::where('codempresa', '=',$codempresa)->get();
        return response()->json($usuarios);
    }

    public function setUsuario(Request $request){

        $rq = $request->all();
        
        foreach ($rq as $key => $value) {
            $user = usuario::where('loggin', '=',$rq[$key]['loggin'])
                ->where('codempresa', '=',$rq[$key]['codempresa'])
                ->get();

            if( count( $user ) > 0 ){
                
                usuario::where('loggin', '=',$rq[$key]['loggin'])
                ->where('codempresa', '=',$rq[$key]['codempresa'])
                ->where('version', '<',$rq[$key]['version'])
                ->update([
                    "pass" => $rq[$key]['pass'] != null ? $rq[$key]['pass'] : $user[0]['pass'],
                    "nombre" => $rq[$key]['nombre'],
                    "mail" => $rq[$key]['mail'],
                    "perfil" => $rq[$key]['perfil'],
                    "allow" => $rq[$key]['allow'],
                    "comisionprima" => $rq[$key]['comisionprima'],
                    "comisionpremio" => $rq[$key]['comisionpremio'],
                    "codigoproductor" => $rq[$key]['codigoproductor'],
                    "codestado" => $rq[$key]['codestado'],
                    "adminempresa" => $rq[$key]['adminempresa'],
                    "codorganizador" => $rq[$key]['codorganizador'],
                    "version" => $rq[$key]['version'],
                ]);
            }else{
                
                $user_ = new usuario();
                $user_->id  = $rq[$key]['id'];
                $user_->loggin  = $rq[$key]['loggin'];
                $user_->pass  = $rq[$key]['pass'];
                $user_->nombre  = $rq[$key]['nombre'];
                $user_->mail  = $rq[$key]['mail'];
                $user_->perfil  = $rq[$key]['perfil'];
                $user_->allow  = $rq[$key]['allow'];
                $user_->comisionprima  = $rq[$key]['comisionprima'];
                $user_->comisionpremio  = $rq[$key]['comisionpremio'];
                $user_->codigoproductor  = $rq[$key]['codigoproductor'];
                $user_->codestado  = $rq[$key]['codestado'];
                $user_->adminempresa  = $rq[$key]['adminempresa'];
                $user_->codorganizador  = $rq[$key]['codorganizador'];
                $user_->codempresa  = $rq[$key]['codempresa'];
                $user_->version  = $rq[$key]['version'];
                $user_->save();
            }

        }
        
        return response()->json(['message' => 'Usuario modificado con éxito', 'success' => true]);
    }
}
