<?php

namespace App\Http\Controllers;

use App\Models\Actividade;
use App\Models\logs;
use Exception;
use Illuminate\Http\Request;

class ActividadesController extends Controller
{
    public function getEmpresa($codempresa){
        $actividades = Actividade::get();
        return response()->json($actividades);
    }

    public function setActividades(Request $request){

        $rq = $request->all();
        try{
            foreach ($rq as $value) {

                $query = Actividade::where('cod', '=',$value['cod'])->get();
    
                $typeQuery = null;
    
                if( count( $query ) > 0 ){
                    
                    $rest = Actividade::where('cod', '=',$value['cod'])
                    ->where('version', '<=',$value['version'])
                    ->update([
                        "reg" => $value["id"],
                        "nombre" => $value["nombre"],
                        "ultmod" => $value["ultmod"],
                        "user_edit" => $value["user_edit"],
                        "codestado" => $value["codestado"],
                        "version" => $value["version"]
                    ]);
                    if($rest)
                        $typeQuery = 'UPDATE';
                }else{
                    
                    $act = new Actividade();
                    $act->reg = $value["id"];
                    $act->cod = $value["cod"];
                    $act->nombre = $value["nombre"];
                    $act->ultmod = $value["ultmod"];
                    $act->user_edit = $value["user_edit"];
                    $act->codestado = $value["codestado"];
                    $act->version = $value["version"];
                    if($act->save())
                        $typeQuery = 'INSERT';
                }
                
                if(!empty($typeQuery)){
                    $logs = new Logs();
                    $logs->message = "Registro ".$typeQuery." Actividades desde ".$value['user_edit']." ".implode(',',$value) ;
                    $logs->coderror = "Actividades:SET:200";
                    $logs->save();
                }
    
            }
            return response()->json(['message' => 'Actividades guardadas con éxito', 'success' => true]);
        }catch(Exception $ex){

            $logs = new Logs();
            $logs->message = "ERROR de registro Actividades desde ".$ex->getMessage() ;
            $logs->coderror = "Actividades:SET:400";
            $logs->save();
            return response()->json(['message' => 'Error al tratar de guardar o actualizar Actividades '.$ex->getMessage(), 'success' => true],400);

        }
    }
}
