<?php

namespace App\Http\Controllers;

use App\Models\Cobertura;
use App\Models\logs;
use Exception;
use Illuminate\Http\Request;


class CoberturasController extends Controller
{
    public function getEmpresa($codempresa){
        $coberturas = Cobertura::get();
        return response()->json($coberturas);
    }

    public function setCobertura(Request $request){

        $rq = $request->all();
        try{
            foreach ($rq as $value) {

                $query = Cobertura::where('nombre', '=',$value['nombre'])->get();
    
                $typeQuery = null;
    
                if( count( $query ) > 0 ){
                    
                    $rest = Cobertura::where('nombre', '=',$value['nombre'])
                    ->where('version', '<',$value['version'])
                    ->update([
                        "id" => $value["reg"],
                        "suma" => $value["suma"],
                        "gastos" => $value["gastos"],
                        "deducible" => $value["deducible"],
                        "vrMensual" => $value["vrMensual"],
                        "vrTrimestral" => $value["vrTrimestral"],
                        "vrSemestral" => $value["vrSemestral"],
                        "x21" => $value["x21"],
                        "x32" => $value["x32"],
                        "x64" => $value["x64"],
                        "ultmod" => $value["ultmod"],
                        "user_edit" => $value["user_edit"],
                        "version" => $value["version"],
                        "codestado" => $value["codestado"]
                    ]);
                    if($rest)
                        $typeQuery = 'UPDATE';
                }else{
                    
                    $cober = new Cobertura();
                    $cober->id = $value["reg"];
                    $cober->nombre = $value["nombre"];
                    $cober->suma = $value["suma"];
                    $cober->gastos = $value["gastos"];
                    $cober->deducible = $value["deducible"];
                    $cober->vrMensual = $value["vrMensual"];
                    $cober->vrTrimestral = $value["vrTrimestral"];
                    $cober->vrSemestral = $value["vrSemestral"];
                    $cober->x21 = $value["x21"];
                    $cober->x32 = $value["x32"];
                    $cober->x64 = $value["x64"];
                    $cober->ultmod = $value["ultmod"];
                    $cober->user_edit = $value["user_edit"];
                    $cober->version = $value["version"];
                    $cober->codestado = $value["codestado"];
                    if($cober->save())
                        $typeQuery = 'INSERT';
                }
                
                if(!empty($typeQuery)){
                    $logs = new Logs();
                    $logs->message = "Register ".$typeQuery." cobertura desde ".$value['user_edit']." ".implode(',',$value) ;
                    $logs->coderror = "Cobertura:SET:200";
                    $logs->save();
                }
    
            }
            return response()->json(['message' => 'Coberturas guardadas con éxito', 'success' => true]);
        }catch(Exception $ex){
            $logs = new Logs();
            $logs->message = "ERROR Register cobertura desde ".$ex->getMessage() ;
            $logs->coderror = "Cobertura:SET:400";
            $logs->save();
            return response()->json(['message' => 'Error al tratar de guardar o actualizar coberturas '.$ex->getMessage(), 'success' => true],400);
        }
        

        
    }
    
}
