<?php

namespace App\Http\Controllers;

use App\Models\Actividade;
use App\Models\arqueo;
use App\Models\barrio;
use App\Models\BarriosPropuesta;
use App\Models\Clasificacione;
use App\Models\cliente;
use App\Models\Cobertura;
use App\Models\exportacione;
use App\Models\gruposbarrio;
use App\Models\lineas_rendicione;
use App\Models\logs;
use App\Models\LineasPropuesta;
use App\Models\migracionespunto;
use App\Models\perfile;
use Illuminate\Http\Request;
use App\Models\Propuesta;
use App\Models\rendicione;
use App\Models\usuario;
use Exception;
use Illuminate\Support\Facades\DB;

class MigracionBarriosController extends Controller
{
    //
    public function callpropuesta()
    {
        $req = request()->all();

        $result = $this->savepropuesta($req);
        if ($result != "")
            return response()->json(['res' => 'Hubo errores al subir la información ' . $result], 400);
        else{
            if($req["rolpuntodeventa"] == "PRINCIPAL"){
                $exporta = new exportacione();
                $exporta->fecha = $req["fechamigracion"];
                $exporta->save();
            }

            
            return response()->json(['res' => 'Se han subido todos los datos de migracion con exito', 'fechamigracion' => date("Y-m-d h:i:s")], 200);
        }
            
    }

    private function savepropuesta($req)
    {

        
        $errores = "";
        try{
            if ($req["listpropuestas"] != null) {
                foreach ($req["listpropuestas"] as $value) {
                    $propuesta = new Propuesta();
                    $cons = $propuesta->where('prefijo',$value["prefijo"])->where('idpropuesta',$value["idpropuesta"])->where('codempresa',$value["codempresa"])->get();
                    if(count($cons) > 0){
                        
                        if(isset($value["version"])){
                            $res = false;
                            if($value["tipopago"] != "" && $value["usuariopaga"] != "" && $value["paga"] = 1 &&  $value["formadepago"] = 'CREDITO'){
                                $res =  $propuesta->where('prefijo',$value["prefijo"])->where('idpropuesta',$value["idpropuesta"])->where('codempresa',$value["codempresa"])->where('version','<',$value["version"])->update([
                                    "documento" => $value["documento"],
                                    "nombre" => $value["nombre"],
                                    "num_polizas" => $value["num_polizas"],
                                    "meses" => $value["meses"],
                                    "id_cobertura" => $value["id_cobertura"],
                                    "id_barrio" => $value["id_barrio"],
                                    "nueva_poliza" => $value["nueva_poliza"],
                                    "premio" => $value["premio"],
                                    "premio_total" => $value["premio_total"],
                                    "fechaDesde" => $value["fechaDesde"],
                                    "fechaHasta" => $value["fechaHasta"],
                                    "clausula" => $value["clausula"],
                                    "barrio_beneficiario" => $value["barrio_beneficiario"],
                                    "ultmod" => $value["ultmod"],
                                    "useredit" => $value["user_edit"],
                                    "codestado" => $value["codestado"],
                                    "cobertura_suma" => $value["cobertura_suma"],
                                    "cobertura_deducible" => $value["cobertura_deducible"],
                                    "cobertura_gastos" => $value["cobertura_gastos"],
                                    "promocion" => $value["promocion"],
                                    "paga" => $value["paga"],
                                    "fecha_paga" => $value["fecha_paga"],
                                    "referencia" => $value["referencia"],
                                    "prima" => $value["prima"],
                                    "master" => $value["master"],
                                    "organizador" => $value["organizador"],
                                    "productor" => $value["productor"],
                                    "reg" => $value["idpropuesta"],
                                    "formadepago" => $value["formadepago"],
                                    "usuariopaga" => $value["usuariopaga"],
                                    "tipopago" => $value["tipopago"],
                                    "compformadepago" => $value["compformapago"],
                                    "codempresa" => $value["codempresa"],
                                    "idpropuesta" => $value["idpropuesta"],
                                    "data_barrios" => isset($value["data_barrios"]) ? $value["data_barrios"] : "",
                                    "nota" => $value["nota"],
                                    "version" => $value["version"],
                                    "prefijo" => $value["prefijo"]
                                ]);
                            }else{
                                $res = $propuesta->where('prefijo',$value["prefijo"])->where('idpropuesta',$value["idpropuesta"])->where('codempresa',$value["codempresa"])->where('version','<',$value["version"])->update([
                                    "documento" => $value["documento"],
                                    "nombre" => $value["nombre"],
                                    "num_polizas" => $value["num_polizas"],
                                    "meses" => $value["meses"],
                                    "id_cobertura" => $value["id_cobertura"],
                                    "id_barrio" => $value["id_barrio"],
                                    "nueva_poliza" => $value["nueva_poliza"],
                                    "premio" => $value["premio"],
                                    "premio_total" => $value["premio_total"],
                                    "fechaDesde" => $value["fechaDesde"],
                                    "fechaHasta" => $value["fechaHasta"],
                                    "clausula" => $value["clausula"],
                                    "barrio_beneficiario" => $value["barrio_beneficiario"],
                                    "ultmod" => $value["ultmod"],
                                    "useredit" => $value["user_edit"],
                                    "codestado" => $value["codestado"],
                                    "cobertura_suma" => $value["cobertura_suma"],
                                    "cobertura_deducible" => $value["cobertura_deducible"],
                                    "cobertura_gastos" => $value["cobertura_gastos"],
                                    "promocion" => $value["promocion"],
                                    "paga" => $value["paga"],
                                    "referencia" => $value["referencia"],
                                    "prima" => $value["prima"],
                                    "master" => $value["master"],
                                    "organizador" => $value["organizador"],
                                    "productor" => $value["productor"],
                                    "reg" => $value["idpropuesta"],
                                    "formadepago" => $value["formadepago"],
                                    "codempresa" => $value["codempresa"],
                                    "idpropuesta" => $value["idpropuesta"],
                                    "nota" => $value["nota"],
                                    "version" => $value["version"],
                                    "data_barrios" => isset($value["data_barrios"]) ? $value["data_barrios"] : "",
                                    "prefijo" => $value["prefijo"]
                                ]);
                            }
    
                            
    
                            if(isset($value["codempresa"]) && $res){
                                DB::select("DELETE FROM lineas_propuestas WHERE prefijo = '".$value["prefijo"]."' AND id_propuesta = '".$value["idpropuesta"]."' AND codempresa = '".$value["codempresa"]."'  ");
                                /*DB::select("DELETE FROM barrios_propuestas WHERE prefijo = '".$value["prefijo"]."' AND id_propuesta = '".$value["idpropuesta"]."' AND codempresa = '".$value["codempresa"]."'  ");*/
                            }
                        }else{
                            if($value["tipopago"] != "" && $value["usuariopaga"] != "" && $value["paga"] = 1 &&  $value["formadepago"] = 'CREDITO'){
                                $propuesta->where('prefijo',$value["prefijo"])->where('idpropuesta',$value["idpropuesta"])->where('codempresa',$value["codempresa"])->update([
                                    "documento" => $value["documento"],
                                    "nombre" => $value["nombre"],
                                    "num_polizas" => $value["num_polizas"],
                                    "meses" => $value["meses"],
                                    "id_cobertura" => $value["id_cobertura"],
                                    "id_barrio" => $value["id_barrio"],
                                    "nueva_poliza" => $value["nueva_poliza"],
                                    "premio" => $value["premio"],
                                    "premio_total" => $value["premio_total"],
                                    "fechaDesde" => $value["fechaDesde"],
                                    "fechaHasta" => $value["fechaHasta"],
                                    "clausula" => $value["clausula"],
                                    "barrio_beneficiario" => $value["barrio_beneficiario"],
                                    "ultmod" => $value["ultmod"],
                                    "useredit" => $value["user_edit"],
                                    "codestado" => $value["codestado"],
                                    "cobertura_suma" => $value["cobertura_suma"],
                                    "cobertura_deducible" => $value["cobertura_deducible"],
                                    "cobertura_gastos" => $value["cobertura_gastos"],
                                    "promocion" => $value["promocion"],
                                    "paga" => $value["paga"],
                                    "fecha_paga" => $value["fecha_paga"],
                                    "referencia" => $value["referencia"],
                                    "prima" => $value["prima"],
                                    "master" => $value["master"],
                                    "organizador" => $value["organizador"],
                                    "productor" => $value["productor"],
                                    "reg" => $value["idpropuesta"],
                                    "formadepago" => $value["formadepago"],
                                    "usuariopaga" => $value["usuariopaga"],
                                    "tipopago" => $value["tipopago"],
                                    "compformadepago" => $value["compformapago"],
                                    "codempresa" => $value["codempresa"],
                                    "idpropuesta" => $value["idpropuesta"],
                                    "data_barrios" => isset($value["data_barrios"]) ? $value["data_barrios"] : "",
                                    "nota" => $value["nota"],
                                    "prefijo" => $value["prefijo"]
                                ]);
                            }else{
                                $propuesta->where('prefijo',$value["prefijo"])->where('idpropuesta',$value["idpropuesta"])->where('codempresa',$value["codempresa"])->update([
                                    "documento" => $value["documento"],
                                    "nombre" => $value["nombre"],
                                    "num_polizas" => $value["num_polizas"],
                                    "meses" => $value["meses"],
                                    "id_cobertura" => $value["id_cobertura"],
                                    "id_barrio" => $value["id_barrio"],
                                    "nueva_poliza" => $value["nueva_poliza"],
                                    "premio" => $value["premio"],
                                    "premio_total" => $value["premio_total"],
                                    "fechaDesde" => $value["fechaDesde"],
                                    "fechaHasta" => $value["fechaHasta"],
                                    "clausula" => $value["clausula"],
                                    "barrio_beneficiario" => $value["barrio_beneficiario"],
                                    "ultmod" => $value["ultmod"],
                                    "useredit" => $value["user_edit"],
                                    "codestado" => $value["codestado"],
                                    "cobertura_suma" => $value["cobertura_suma"],
                                    "cobertura_deducible" => $value["cobertura_deducible"],
                                    "cobertura_gastos" => $value["cobertura_gastos"],
                                    "promocion" => $value["promocion"],
                                    "paga" => $value["paga"],
                                    "referencia" => $value["referencia"],
                                    "prima" => $value["prima"],
                                    "master" => $value["master"],
                                    "organizador" => $value["organizador"],
                                    "productor" => $value["productor"],
                                    "reg" => $value["idpropuesta"],
                                    "formadepago" => $value["formadepago"],
                                    "codempresa" => $value["codempresa"],
                                    "idpropuesta" => $value["idpropuesta"],
                                    "nota" => $value["nota"],
                                    "data_barrios" => isset($value["data_barrios"]) ? $value["data_barrios"] : "",
                                    "prefijo" => $value["prefijo"]
                                ]);
                            }
    
                            
    
                            if(isset($value["codempresa"])){
                                DB::select("DELETE FROM lineas_propuestas WHERE prefijo = '".$value["prefijo"]."' AND id_propuesta = '".$value["idpropuesta"]."' AND codempresa = '".$value["codempresa"]."'  ");
                                DB::select("DELETE FROM barrios_propuestas WHERE prefijo = '".$value["prefijo"]."' AND id_propuesta = '".$value["idpropuesta"]."' AND codempresa = '".$value["codempresa"]."'  ");
                            }
                        }
                        
                            

                    }else{
                        $propuesta->documento = $value["documento"];
                        if (isset($value["nombre"]))
                            $propuesta->nombre = $value["nombre"];
                        $propuesta->num_polizas = $value["num_polizas"];
                        $propuesta->meses = $value["meses"];
                        $propuesta->id_cobertura = $value["id_cobertura"];
                        $propuesta->id_barrio = $value["id_barrio"];
                        $propuesta->nueva_poliza = $value["nueva_poliza"];
                        $propuesta->premio = $value["premio"];
                        $propuesta->premio_total = $value["premio_total"];
                        $propuesta->fechaDesde = $value["fechaDesde"];
                        $propuesta->fechaHasta = $value["fechaHasta"];
                        $propuesta->clausula = $value["clausula"];
                        $propuesta->barrio_beneficiario = $value["barrio_beneficiario"];
                        $propuesta->ultmod = $value["ultmod"];
                        $propuesta->useredit = $value["user_edit"];
                        $propuesta->codestado = $value["codestado"];
                        $propuesta->cobertura_suma = $value["cobertura_suma"];
                        $propuesta->cobertura_deducible = $value["cobertura_deducible"];
                        $propuesta->cobertura_gastos = $value["cobertura_gastos"];
                        $propuesta->promocion = $value["promocion"];
                        $propuesta->paga = $value["paga"];
                        $propuesta->fecha_paga = $value["fecha_paga"];
                        $propuesta->referencia = $value["referencia"];
                        $propuesta->prima = $value["prima"];
                        $propuesta->master = $value["master"];
                        $propuesta->organizador = $value["organizador"];
                        $propuesta->productor = $value["productor"];
                        $propuesta->reg = $value["idpropuesta"];
                        $propuesta->fecha_nacimiento = $value["fecha_nacimiento"];
                        $propuesta->formadepago = $value["formadepago"];
                        $propuesta->usuariopaga = $value["usuariopaga"];
                        $propuesta->tipopago = $value["tipopago"];
    
                        $propuesta->compformadepago = $value["compformapago"];
                        if (isset($value["codempresa"]))
                            $propuesta->codempresa = $value["codempresa"];
                        if (isset($value["idpropuesta"]))
                            $propuesta->idpropuesta = $value["idpropuesta"];
                        if (isset($value["puntodeventa"]))
                            $propuesta->puntodeventa = $value["puntodeventa"];
                        if (isset($value["prefijo"]))
                            $propuesta->prefijo = $value["prefijo"];
                        if (isset($value["nota"]))
                            $propuesta->nota = $value["nota"];
                        if (isset($value["data_barrios"]))
                            $propuesta->data_barrios = $value["data_barrios"];
                        $propuesta->save();
                    }

                }
            }
        }catch(Exception $ex){
            $logs = new Logs();
            $logs->saveerror($ex->getMessage(), "", "", "111");
        }


        try{
            if (isset($req["listgrupobarrios"])){
                if ($req["listgrupobarrios"] != null) {
                    $aux = "";
                    foreach ($req["listgrupobarrios"] as $value) {

                            $gbarrio  = new gruposbarrio();
                            $gbarrio->id = $value["id"];
                            $gbarrio->nombre = $value["nombre"];
                            $gbarrio->idbarrio = $value["idbarrio"];
                            $gbarrio->nombrebarrio = $value["nombrebarrio"];
                            $gbarrio->ultmod = $value["ultmod"];
                            
                            DB::select("DELETE FROM gruposbarrios WHERE id = '".$gbarrio->id."' AND idbarrio = '".$gbarrio->idbarrio."' ");
                                if($value['codestado'] != "0"){                                
                                    $gbarrio->save();
                            }
                    }
                }
            }
        }catch(Exception $ex){
            $logs = new Logs();
            $logs->saveerror($ex->getMessage(), "", "", "121");
        }

        try{
            if (isset($req["listarqueos"])){
                if ($req["listarqueos"] != null) {
                    $aux = "";
                    foreach ($req["listarqueos"] as $value) {

                            $arq = new arqueo();
                            $arq->id = $value["id"];
                            $arq->usuario = $value["usuario"];
                            $arq->nombre = $value["nombre"];
                            $arq->fechadia = $value["fechadia"];
                            $arq->valorinicial = $value["valorinicial"];
                            $arq->dinerorealcaja = $value["dinerorealcaja"];
                            $arq->valormanual = $value["valormanual"];
                            $arq->cuadredescuadre = $value["cuadredescuadre"];
                            $arq->supervisor = $value["supervisor"];
                            $arq->nombresupervisor = $value["nombresupervisor"];
                            $arq->observaciones = $value["observaciones"];
                            $arq->ultmod = $value["ultmod"];
                            $arq->user_edit = $value["user_edit"];
                            $arq->codestado = $value["codestado"];
                            $arq->rendicion = $value["rendicion"];
                            $arq->cantpolizas = $value["cantpolizas"];
                            $arq->puntodeventa = $req["prefpuntodeventa"];
                            $arq->codempresa = $value["codempresa"];

                            if($aux != $value["id"]){
                                DB::select("DELETE FROM arqueos WHERE id = '".$arq->id."' ");
                                $aux = $value["id"];
                            }

                            $arq->save();

                            

                    }
                }
            }
        }catch(Exception $ex){
            $logs = new Logs();
            $logs->saveerror("ARQUEOS ".$ex->getMessage(), "", "", "171");
        }

        try{
            if (isset($req["listrendiciones"])){
                if ($req["listrendiciones"] != null) {
                    $aux = "";
                    foreach ($req["listrendiciones"] as $value) {

                            $rend = new rendicione();
                            $rend->reg = $value["reg"];
                            $rend->idarqueos = $value["idarqueos"];
                            $rend->detalle = $value["detalle"];
                            $rend->valor = $value["valor"];
                            $rend->nocomprobante = $value["nocomprobante"];
                            $rend->entregadopor = $value["entregadopor"];
                            $rend->entregadoa = $value["entregadoa"];
                            $rend->ultmod = $value["ultmod"];
                            $rend->user_edit = $value["user_edit"];
                            $rend->codestado = $value["codestado"];
                            $rend->codempresa = $value["codempresa"];
                            $rend->puntodeventa = $req["prefpuntodeventa"];

                            if($aux != $value["reg"]){
                                DB::select("DELETE FROM rendiciones WHERE reg = '".$rend->reg."' ");
                                $aux = $value["reg"];
                            }

                            $rend->save();

                    }
                }
            }
        }catch(Exception $ex){
            $logs = new Logs();
            $logs->saveerror("RENDICIONES ".$ex->getMessage(), "", "", "172");
        }

        try{
            if (isset($req["listlineasrendiciones"])){
                if ($req["listlineasrendiciones"] != null) {

                    $aux = "";

                    foreach ($req["listlineasrendiciones"] as $value) {

                            $linrend = new lineas_rendicione();
                            $linrend->id = $value["id"];
                            $linrend->idrendicion = $value["idrendicion"];
                            $linrend->fechaarqueo = $value["fechaarqueo"];
                            $linrend->ultmod = $value["ultmod"];
                            $linrend->valordia = $value["valordia"];
                            $linrend->codempresa = $value["codempresa"];
                            $linrend->puntodeventa = $req["prefpuntodeventa"];

                            if($aux != $value["id"]){
                                DB::select("DELETE FROM lineas_rendiciones WHERE id = '".$linrend->id."' ");
                                $aux = $value["id"];
                            }

                            $linrend->save();

                    }
                }
            }
        }catch(Exception $ex){
            $logs = new Logs();
            $logs->saveerror("LIENAS RENDICIONES ".$ex->getMessage(), "", "", "173");
        }
        


        try{
            if (isset($req["listusuarios"])){
                if ($req["listusuarios"] != null) {
                    foreach ($req["listusuarios"] as $value) {
                        $usuario  = new usuario();
                        $cons = $usuario->where('loggin',$value["loggin"])->where('codempresa',$value["codempresa"])->get();
                        if(count($cons) > 0){
                            $usuario->where('loggin',$value["loggin"])->where('codempresa',$value["codempresa"])->update([
                                "pass" => $value["pass"],
                                "nombre" => $value["nombre"],
                                "mail" => $value["mail"],
                                "perfil" => $value["perfil"],
                                "allow" => $value["allow"],
                                "comisionprima" => $value["comisionprima"],
                                "comisionpremio" => $value["comisionpremio"],
                                "codigoproductor" => $value["codigoproductor"],
                                "codorganizador" => isset($value["codorganizador"]) ? $value["codorganizador"] : null ,
                                "codestado" => $value["codestado"],
                                "codempresa" => $value["codempresa"],
                                "adminempresa" => $value["adminempresa"]
                            ]);
                        }else{
                            $usuario->id = $value["id"];
                            $usuario->loggin = $value["loggin"];
                            $usuario->pass = $value["pass"];
                            $usuario->nombre = $value["nombre"];
                            $usuario->mail = $value["mail"];
                            $usuario->perfil = $value["perfil"];
                            $usuario->allow = $value["allow"];
                            $usuario->comisionprima = $value["comisionprima"];
                            $usuario->codigoproductor = $value["codigoproductor"];
                            $usuario->codorganizador = isset($value["codorganizador"]) ? $value["codorganizador"] : null;
                            $usuario->comisionpremio = $value["comisionpremio"];
                            $usuario->codestado = $value["codestado"];
                            $usuario->codempresa = $value["codempresa"];
                            $usuario->adminempresa = $value["adminempresa"];
                            $usuario->save();
                        }
                    }
                }
            }
        }catch(Exception $ex){
            $logs = new Logs();
            $logs->saveerror($ex->getMessage(), "", "", "115");
        }

        try{
            if (isset($req["listperfiles"])) {
                if ($req["listperfiles"] != null) {
                    foreach ($req["listperfiles"] as $value) {
                        $perf  = new perfile();
                        $cons = $perf->where('modulo',$value["modulo"])->where('nombre',$value["nombre"])->where('codempresa',$value["codempresa"])->get();
                        if(count($cons) > 0){
                            $perf->where('modulo',$value["modulo"])->where('nombre',$value["nombre"])->where('codempresa',$value["codempresa"])->update([
                                "nombre" => $value["nombre"],
                                "modulo" => $value["modulo"],
                                "access" => $value["access"],
                                "vista" => $value["vista"],
                                "edicion" => $value["edicion"],
                                "eliminar" => $value["eliminar"],
                                "exportar" => $value["exportar"],
                                "codempresa" => $value["codempresa"]
                            ]);
                        }else{
                            $perf->nombre = $value["nombre"];
                            $perf->modulo = $value["modulo"];
                            $perf->access = $value["access"];
                            $perf->vista = $value["vista"];
                            $perf->edicion = $value["edicion"];
                            $perf->eliminar = $value["eliminar"];
                            $perf->exportar = $value["exportar"];
                            $perf->codempresa = $value["codempresa"];
                            $perf->save();
                        }
                    }
                }
            }
        }catch(Exception $ex){
            $logs = new Logs();
            $logs->saveerror($ex->getMessage(), "", "", "116");
        }

        

        try{

            if ($req["listlineaspropuestas"] != null) {
                
                $aux ="";

                foreach ($req["listlineaspropuestas"] as $value) {

                    

                    $lineaspropuestas = new LineasPropuesta();

                    

                    $lineaspropuestas->reg = $value["id"];
                    $lineaspropuestas->id_propuesta = $value["id_propuesta"];
                    $lineaspropuestas->documento = $value["documento"];
                    $lineaspropuestas->tipo_documento = $value["tipo_documento"];
                    $lineaspropuestas->apellidos = $value["apellidos"];
                    $lineaspropuestas->nombres = $value["nombres"];
                    $lineaspropuestas->fecha_nacimiento = $value["fecha_nacimiento"];
                    $lineaspropuestas->id_actividad = $value["id_actividad"];
                    $lineaspropuestas->id_clasificacion = $value["id_clasificacion"];
                    $lineaspropuestas->premio = $value["premio"];
                    $lineaspropuestas->ultmod = $value["ultmod"];
                    $lineaspropuestas->user_edit = $value["user_edit"];
                    $lineaspropuestas->codestado = $value["codestado"];
                    $lineaspropuestas->fechaDesde = $value["fechaDesde"];
                    $lineaspropuestas->fechaHasta = $value["fechaHasta"];
                    if(isset($value["codempresa"]))
                        $lineaspropuestas->codempresa = $value["codempresa"];
                    if(isset($value["prefijo"]))
                        $lineaspropuestas->prefijo = $value["prefijo"];
                    $lineaspropuestas->actividad = $value["actividad"];
                    $lineaspropuestas->clasificacion = $value["clasificacion"];
                    

                    if($aux != $lineaspropuestas->prefijo.$lineaspropuestas->id_propuesta){
                        if(isset($value["codempresa"]))
                            DB::select("DELETE FROM lineas_propuestas WHERE prefijo = '".$lineaspropuestas->prefijo."' AND id_propuesta = '".$lineaspropuestas->id_propuesta."' AND codempresa = '".$lineaspropuestas->codempresa."' ");
                        $aux = $lineaspropuestas->prefijo.$lineaspropuestas->id_propuesta;
                    }

                    if (!$lineaspropuestas->save()) {
                        $errores .= "No se pudo guardar línea propuesta " . $propuesta->reg;
                    }

                    
                }
            }

            if ($req["listbarriospropuestas"] != null) {

                $aux = "";

                foreach ($req["listbarriospropuestas"] as $value) {
                    $barriospropuestas = new BarriosPropuesta();
                    $barriospropuestas->reg = $value["id"];
                    $barriospropuestas->id_propuesta = $value["id_propuesta"];
                    $barriospropuestas->id_barrio = $value["id_barrio"];
                    $barriospropuestas->nombre = $value["nombre"];
                    $barriospropuestas->ultmod = $value["ultmod"];
                    $barriospropuestas->user_edit = $value["user_edit"];
                    $barriospropuestas->codestado = $value["codestado"];
                    if(isset($value["prefijo"]))
                        $barriospropuestas->prefijo = $value["prefijo"];
                    if(isset($value["codempresa"]))
                        $barriospropuestas->codempresa = $value["codempresa"];
                    
                        if($aux != $barriospropuestas->prefijo.$barriospropuestas->id_propuesta){
                            if(isset($value["codempresa"]))
                                DB::select("DELETE FROM barrios_propuestas WHERE prefijo = '".$barriospropuestas->prefijo."' AND id_propuesta = '".$barriospropuestas->id_propuesta."' AND codempresa = '".$barriospropuestas->codempresa."'  ");
                            $aux = $barriospropuestas->prefijo.$barriospropuestas->id_propuesta;
                        }

                    if (!$barriospropuestas->save()) {
                        $errores .= "No se pudo guardar línea propuesta " . $propuesta->reg;
                    }
                }
            }

        }catch(Exception $ex){
            $logs = new Logs();
            $logs->saveerror($ex->getMessage(), "", "", "113");
        }


        try{
            if ($req["listtomador"] != null) {
                foreach ($req["listtomador"] as $value) {
                    $cliente  = new cliente();
                    $cons = $cliente->where('id',$value["id"])->where('codempresa',$value["codempresa"])->get();
                    if(count($cons) > 0){

                        if(isset($value["codempresa"])){
                            $cliente->where('id',$value["id"])->update([
                                "nombres" => $value["nombres"],
                                "apellidos" => $value["apellidos"],
                                "telefono" => $value["telefono"],
                                "direccion" => $value["direccion"],
                                "email" => $value["email"],
                                "ciudad" => $value["ciudad"],
                                "codpostal" => $value["codpostal"],
                                "localidad" => $value["localidad"],
                                "fecha_nacimiento" => $value["fecha_nacimiento"],
                                "tipo_id" => $value["tipo_id"],
                                "sexo" => $value["sexo"],
                                "situacion" => $value["situacion"],
                                "ultmod" => $value["ultmod"],
                                "user_edit" =>  $value["user_edit"],
                                "codempresa" =>  $value["codempresa"],
                                "puntodeventa" =>  $req["prefpuntodeventa"],
                                "categoria" => $value["categoria"]
                            ]);
                        }else{
                            $cliente->where('id',$value["id"])->update([
                                "nombres" => $value["nombres"],
                                "apellidos" => $value["apellidos"],
                                "telefono" => $value["telefono"],
                                "direccion" => $value["direccion"],
                                "email" => $value["email"],
                                "ciudad" => $value["ciudad"],
                                "codpostal" => $value["codpostal"],
                                "localidad" => $value["localidad"],
                                "fecha_nacimiento" => $value["fecha_nacimiento"],
                                "tipo_id" => $value["tipo_id"],
                                "sexo" => $value["sexo"],
                                "situacion" => $value["situacion"],
                                "ultmod" => $value["ultmod"],
                                "user_edit" =>  $value["user_edit"],
                                "categoria" => $value["categoria"]
                            ]);
                        }
                        
                    }else{
                        $cliente->id = $value["id"];
                        $cliente->nombres = $value["nombres"];
                        $cliente->apellidos = $value["apellidos"];
                        $cliente->telefono = $value["telefono"];
                        $cliente->direccion = $value["direccion"];
                        $cliente->email = $value["email"];
                        $cliente->ciudad = $value["ciudad"];
                        $cliente->codpostal = $value["codpostal"];
                        $cliente->localidad = $value["localidad"];
                        $cliente->fecha_nacimiento = $value["fecha_nacimiento"];
                        $cliente->tipo_id = $value["tipo_id"];
                        $cliente->sexo = $value["sexo"];
                        $cliente->situacion = $value["situacion"];
                        $cliente->ultmod = $value["ultmod"];
                        $cliente->user_edit = $value["user_edit"];
                        if(isset($value["codempresa"])){
                            $cliente->codempresa = $value["codempresa"];
                            $cliente->puntodeventa = $req["prefpuntodeventa"];
                        }
                            
                        $cliente->categoria = $value["categoria"];
                        $cliente->save();
                    }
                }
            }
        }catch(Exception $ex){
            $logs = new Logs();
            $logs->saveerror($ex->getMessage(), "", "", "112");
        }

        try{
       
            if ($req["listbarrios"] != null) {
                foreach ($req["listbarrios"] as $value) {
                    $barrio = new barrio();
                    $cons = $data = DB::table('barrios')->where('id',$value['id'])->get();
                    if(count($cons) > 0){
                        $barrio->where('id',$value['id'])->update([
                            "id" => $value["id"],
                            "nombre" => $value["nombre"],
                            "telefono" => $value["telefono"],
                            "direccion" => $value["direccion"],
                            "email" => $value["email"],
                            "sub_barrio" => $value["sub_barrio"],
                            "clase_barrio" => $value["clase_barrio"],
                            "suma_muerte" => $value["suma_muerte"],
                            "suma_gm" => $value["suma_gm"],
                            "suma_rc" => $value["suma_rc"],
                            "exige" => $value["exige"],
                            "observaciones" => $value["observaciones"],
                            "ultmod" => $value["ultmod"],
                            "user_edit" => $value["user_edit"],
                            "codestado" => $value["codestado"]
                        ]);
                    }else{
                        $barrio->id = $value["id"];
                        $barrio->nombre = $value["nombre"];
                        $barrio->telefono = $value["telefono"];
                        $barrio->direccion = $value["direccion"];
                        $barrio->email = $value["email"];
                        $barrio->sub_barrio = $value["sub_barrio"];
                        $barrio->clase_barrio = $value["clase_barrio"];
                        $barrio->suma_muerte = $value["suma_muerte"];
                        $barrio->suma_gm = $value["suma_gm"];
                        $barrio->suma_rc = $value["suma_rc"];
                        $barrio->exige = $value["exige"];
                        $barrio->observaciones = $value["observaciones"];
                        $barrio->ultmod = $value["ultmod"];
                        $barrio->user_edit = $value["user_edit"];
                        $barrio->codestado = $value["codestado"];

                
                        if (!$barrio->save()) {
                            $errores .= "No se pudo guardar el barrio " . $barrio->id;
                        }
                    
                        
                    }
                }
            }

        }catch(Exception $ex){
            $logs = new Logs();
            $logs->saveerror($ex->getMessage(), "", "", "114");
        }

        

        if($req["rolpuntodeventa"] != "PRINCIPAL"){
            return $errores;
        }

        if(isset($req["apiversion"])){
            if($req["apiversion"] == "2"){
                return $errores;
            }
            
            /*if($req["apiversion"] == "3"){
                return $errores;
            }*/
        }

        


        if ($req["listactividades"] != null) {
            foreach ($req["listactividades"] as $value) {
                $actividad = new Actividade();
                $cons = $data = DB::table('actividades')->where('cod',$value['cod'])->get();
                if(count($cons) > 0){
                    $actividad->where('cod',$value['cod'])->update([
                        "reg" => $value["id"],
                        "cod" => $value["cod"],
                        "nombre" => $value["nombre"],
                        "ultmod" => $value["ultmod"],
                        "user_edit" => $value["user_edit"],
                        "codestado" => $value["codestado"]
                    ]);
                }else{
                    $actividad->reg = $value["id"];
                    $actividad->cod = $value["cod"];
                    $actividad->nombre = $value["nombre"];
                    $actividad->ultmod = $value["ultmod"];
                    $actividad->user_edit = $value["user_edit"];
                    $actividad->codestado = $value["codestado"];
                    if (!$actividad->save()) {
                        $errores .= "No se pudo guardar la actividad " . $actividad->id;
                    }
                }
            }
        }


        if ($req["listclasificaciones"] != null) {
            foreach ($req["listclasificaciones"] as $value) {
                $clasificacion = new Clasificacione();
                $cons = DB::table('clasificaciones')->where('reg',$value['id'])->get();
                if(count($cons) > 0){
                    $clasificacion->where('reg',$value['id'])->update([
                        "reg" => $value["id"],
                        "cod" => $value["cod"],
                        "nombre" => $value["nombre"],
                        "id_actividad" => $value["id_actividad"],
                        "ultmod" => $value["ultmod"],
                        "user_edit" => $value["user_edit"],
                        "codestado" => $value["codestado"]
                    ]);
                }else{
                    $clasificacion->reg = $value["id"];
                    $clasificacion->cod = $value["cod"];
                    $clasificacion->nombre = $value["nombre"];
                    $clasificacion->id_actividad = $value["id_actividad"];
                    $clasificacion->ultmod = $value["ultmod"];
                    $clasificacion->user_edit = $value["user_edit"];
                    $clasificacion->codestado = $value["codestado"];
                    if (!$clasificacion->save()) {
                        $errores .= "No se pudo guardar línea clasificación " . $clasificacion->id;
                    }
                }
            }
        }

        if ($req["listcoberturas"] != null) {
            foreach ($req["listcoberturas"] as $value) {
                $cobertura = new Cobertura();
                $cons = $data = DB::table('coberturas')->where('nombre',$value['nombre'])->get();
                if(count($cons) > 0){
                    $cobertura->where('nombre',$value['nombre'])->update([
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
                        "codestado" => $value["codestado"]
                    ]);
                }else{
                    $cobertura->id = $value["reg"];
                    $cobertura->nombre = $value["nombre"];
                    $cobertura->suma = $value["suma"];
                    $cobertura->gastos = $value["gastos"];
                    $cobertura->deducible = $value["deducible"];
                    $cobertura->vrMensual = $value["vrMensual"];
                    $cobertura->vrTrimestral = $value["vrTrimestral"];
                    $cobertura->vrSemestral = $value["vrSemestral"];
                    $cobertura->x21 = $value["x21"];
                    $cobertura->x32 = $value["x32"];
                    $cobertura->x64 = $value["x64"];
                    $cobertura->ultmod = $value["ultmod"];
                    $cobertura->user_edit = $value["user_edit"];
                    $cobertura->codestado = $value["codestado"];
                    if (!$cobertura->save()) {
                        $errores .= "No se pudo guardar línea cobertura " . $cobertura->reg;
                    }
                }
            }
        }




        return $errores;
    }
}
