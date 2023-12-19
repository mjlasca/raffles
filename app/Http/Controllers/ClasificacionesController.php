<?php

namespace App\Http\Controllers;

use App\Models\Clasificacione;
use App\Models\logs;
use Exception;
use Illuminate\Http\Request;

class ClasificacionesController extends Controller
{
    public function getEmpresa($codempresa){
        $clasificaciones = Clasificacione::get();
        return response()->json($clasificaciones);
    }

    public function setClasificaciones(Request $request){

        $rq = $request->all();
        try{
            foreach ($rq as $value) {

                $query = Clasificacione::where('reg', '=',$value['id'])->get();
    
                $typeQuery = null;
    
                if( count( $query ) > 0 ){
                    
                    $rest = Clasificacione::where('reg', '=',$value['id'])
                    ->where('version', '<=',$value['version'])
                    ->update([
                        "cod" => $value["cod"],
                        "nombre" => $value["nombre"],
                        "id_actividad" => $value["id_actividad"],
                        "ultmod" => $value["ultmod"],
                        "user_edit" => $value["user_edit"],
                        "version" => $value["version"],
                        "codestado" => $value["codestado"]
                    ]);
                    if($rest)
                        $typeQuery = 'UPDATE';
                }else{
                    
                    $clas = new Clasificacione();
                    $clas->reg = $value["id"];
                    $clas->cod = $value["cod"];
                    $clas->nombre = $value["nombre"];
                    $clas->id_actividad = $value["id_actividad"];
                    $clas->ultmod = $value["ultmod"];
                    $clas->user_edit = $value["user_edit"];
                    $clas->version = $value["version"];
                    $clas->codestado = $value["codestado"];
                    if($clas->save())
                        $typeQuery = 'INSERT';
                }
                
                if(!empty($typeQuery)){
                    $logs = new Logs();
                    $logs->message = "Register ".$typeQuery." Clasificaciones desde ".$value['user_edit']." ".implode(',',$value) ;
                    $logs->coderror = "Clasificaciones:SET:200";
                    $logs->save();
                }
    
            }
            return response()->json(['message' => 'Clasificaciones guardadas con éxito', 'success' => true]);
        }catch(Exception $ex){
            $logs = new Logs();
            $logs->message = "ERROR Register Clasificaciones desde ".$ex->getMessage() ;
            $logs->coderror = "Clasificaciones:SET:400";
            $logs->save();
            return response()->json(['message' => 'Error al tratar de guardar o actualizar Clasificaciones '.$ex->getMessage(), 'success' => true],400);
        }
        

        
    }
}
