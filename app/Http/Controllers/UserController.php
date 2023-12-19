<?php

namespace App\Http\Controllers;

use App\Models\logs;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    //

    public function confirmarpuntodeventa(){
        $req = request()->all();

        if(isset($req['userpunto'])){
            if($req['userpunto'] != ""){
                $cons = DB::table('users')->where('email',$req['userpunto'])->orWhere('prefijo',$req['prefijo'])->get();
            
                if(count($cons) > 0){
                    /*$user = new User();
                    $user->where('api_token',$req['api_token'])->update([
                        "active" => true
                    ]);*/
        
                    $cons['res'] = "El usuario o el prefijo ya existen";
                    return response()->json($cons, 400);
                }
                else{
                    return response()->json(['res' => 'El punto no existe'], 200);
                }
                    
            }
        }
        


        if($req['op'] == "abierto"){
            $cons = DB::table('users')->where('api_token',$req['api_token'])->get();
        
            if(count($cons) > 0){
                /*$user = new User();
                $user->where('api_token',$req['api_token'])->update([
                    "active" => true
                ]);*/
    
                $cons['res'] = "Token confirmado";
                return response()->json($cons, 200);
            }
            else
                return response()->json(['res' => 'El token no existe o está siendo utilizado'], 400);
        }

        

        if($req['op'] == "cerrado"){
            
                /*$user = new User();
                $user->where('api_token',$req['api_token'])->update([
                    "active" => false
                ]);*/
                $cons['res'] = "Usuario cerrado";
                return response()->json($cons, 200);
    
        }

        
            
    }

    public function editarpuntodeventa(){
        $req = request()->all();
        $result = $this->savepuntos($req);
        if ($result != "")
            return response()->json(['res' => 'Hay errores al tratar de procesar los puntos de venta ' . $result], 400);
        else
            return response()->json(['res' => 'Se ha actulizado los puntos de venta con éxito'], 200);
    }


    public function getPuntos(){

        $req = request()->all(); 

        $datos = [];

        if($req["rolpuntodeventa"] == "COLABORADOR"){
            $sql = "SELECT email,name,prefijo,rol,api_token,codempresa FROM users WHERE (codempresa = '".$req["codempresa"]."' AND rol = 'COLABORADOR' ) ";
            $datos["puntos"] = DB::select($sql);
        }
        else if
        ($req["rolpuntodeventa"] == "PRINCIPAL"){
            $sql = "SELECT email,name,prefijo,rol,api_token,codempresa FROM users  ";
            $datos["puntos"] = DB::select($sql);
        }else {
            return response()->json(['res' => 'Acceso inválido'], 400);
        }

        return response()->json($datos, 200);
        
    }

    private function savepuntos($req)
    {
        try{

            $errores = "";
            if ($req["listpuntosdeventa"] != null) {
                
                foreach ($req["listpuntosdeventa"] as $value) {
                    
                        $user = new User();
                        $cons = $data = DB::table('users')->where('email',$value['usuario'])->get();
                        if(count($cons) > 0){
                            /*$user->where('email',$value['usuario'])->update([
                                "name" => $value["nombre"],
                                "prefijo" => $value["prefijo"],
                                "rol" => $value["rol"],
                                "api_token" => $value["apitoken"],
                                "perfil" => $value["perfil"],
                                "codempresa" => $value["codempresa"],
                                "master" => $value["master"],
                                "organizador" => $value["organizador"],
                                "codmaster" => $value["codmaster"],
                                "codorganizador" => $value["codorganizador"],
                                "aseguradora" => $value["aseguradora"]
                            ]);*/
                        }else{
                            
                            $user->name = $value["nombre"];
                            $user->prefijo = $value["prefijo"];
                            $user->rol = $value["rol"];
                            $user->api_token     = $value["apitoken"];
                            $user->email = $value["usuario"];
                            $user->password = bcrypt($user->email);
                            $user->perfil = $value["perfil"];
                            $user->codempresa = $value["codempresa"];
                            $user->master = $value["master"];
                            $user->organizador = $value["organizador"];
                            $user->aseguradora = $value["aseguradora"];
                            $user->codmaster = $value["codmaster"];
                            $user->codorganizador = $value["codorganizador"];
                            try{
                                if (!$user->save()) {
                                    $errores .= "No se pudo guardar el registro con usuario " . $user->email;
                                }
                            }catch (Exception $e){
                                $logs = new logs();
                                $logs->saveerror($e->getMessage(), "", "", "161");                    
                            }
                            
                        }
                    
                }
            }

            return $errores;
            
        }catch(Exception $ex){
            $logs = new logs();
            $logs->saveerror($ex->getMessage(), "", "", "160");
            
            return $errores = "HAY errores";
        }
    }
}
