<?php



namespace App\Http\Controllers;

ini_set('memory_limit', '512M');

use App\Models\BarriosPropuesta;
use App\Models\cliente;
use App\Models\LineasPropuesta;
use App\Models\logs;
use App\Models\migracionespunto;
use App\Models\Propuesta;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Foreach_;
use Barryvdh\DomPDF\Facade as PDF;
use DateTime;
use Exception;
use Illuminate\Support\Facades\DB;

// SDK de Mercado Pago
use MercadoPago;

class PropuestaController extends Controller
{
    
    //
    public function __invoke()
    {
        //return response()->json( $get, 200);
    }

    public  function savepropuesta()
    {
        
        try
        {
            $request = request()->all();
            
            $datos["barriosagregados"] = json_decode( $request["barriosagregados"] );
            $datos["personasaseguradas"] = json_decode( $request["personasaseguradas"] );
            $datos["coberturavigen"] = json_decode( $request["coberturavigen"] );
            $datos["tomador"] = json_decode( $request["tomador"] );
            $datos["parametros"] = json_decode( $request["parametros"] );
        

            $propuesta = new Propuesta();
            $propuesta->prefijo = "O";
            $propuesta->documento = $datos["tomador"]->documento;
            
            $propuesta->nombre = $datos["tomador"]->nombres . " ".$datos["tomador"]->apellidos;
            $propuesta->num_polizas =  count($datos["personasaseguradas"]);
            $propuesta->meses = $datos["coberturavigen"]->meses;
            $propuesta->id_cobertura = $datos["coberturavigen"]->cobertura->nombre;
            $propuesta->fecha_nacimiento = $datos["tomador"]->fechanacimiento;
            $propuesta->nueva_poliza = $propuesta->num_polizas > 1 ? 1 : 2;
            $propuesta->premio = $datos["coberturavigen"]->premio;
            $propuesta->premio_total = $datos["coberturavigen"]->premiototal;
            $propuesta->fechaDesde = $datos["coberturavigen"]->vigenciadesde;
            $propuesta->fechaHasta = $datos["coberturavigen"]->vigenciahasta;
            $propuesta->clausula = count($datos["barriosagregados"]) > 0 ? 1 : 0;
            $propuesta->barrio_beneficiario = 0;
            $propuesta->ultmod = $datos["parametros"]->ultmod;
            $propuesta->useredit = "online";
            $propuesta->codestado = 0;
            $propuesta->cobertura_suma = $datos["coberturavigen"]->cobertura->suma;
            $propuesta->cobertura_deducible = $datos["coberturavigen"]->cobertura->deducible;
            $propuesta->cobertura_gastos = $datos["coberturavigen"]->cobertura->gastos;
            $propuesta->promocion = $datos["coberturavigen"]->promociones;
            $propuesta->paga = 0;
            $propuesta->fecha_paga = $datos["parametros"]->ultmod;
            $propuesta->referencia = null;
            $propuesta->prima = null;
            $propuesta->master = null;
            $propuesta->formadepago = null;
            $propuesta->organizador = null;
            $propuesta->productor = null;
            $propuesta->puntodeventa ="online";
            $propuesta->csrf = $datos["parametros"]->csrf;
            $propuesta->usuariopaga ="online";

            $cons = DB::table('propuestas')->where('csrf',$datos["parametros"]->csrf)->get();
            if(count($cons) > 0){
                $propuesta->reg = $cons[0]->reg;
                $propuesta->where('csrf',$datos["parametros"]->csrf)->update([

                    "prefijo" => "O",
                    "documento" => $datos["tomador"]->documento,
                    "nombre" => $datos["tomador"]->nombres . " ".$datos["tomador"]->apellidos,
                    "num_polizas" =>  count($datos["personasaseguradas"]),
                    "meses" => $datos["coberturavigen"]->meses,
                    "id_cobertura" => $datos["coberturavigen"]->cobertura->nombre,
                    "nueva_poliza" => $propuesta->num_polizas > 1 ? 1 : 2,
                    "premio" => $datos["coberturavigen"]->premio,
                    "premio_total" => $datos["coberturavigen"]->premiototal,
                    "fechaDesde" => $datos["coberturavigen"]->vigenciadesde,
                    "fechaHasta" => $datos["coberturavigen"]->vigenciahasta,
                    "clausula" => count($datos["barriosagregados"]) > 0 ? 1 : 0,
                    "barrio_beneficiario" => 0,
                    "ultmod" => $datos["parametros"]->ultmod,
                    "useredit" => "online",
                    "codestado" => 0,
                    "cobertura_suma" => $datos["coberturavigen"]->cobertura->suma,
                    "cobertura_deducible" => $datos["coberturavigen"]->cobertura->deducible,
                    "cobertura_gastos" => $datos["coberturavigen"]->cobertura->gastos,
                    "promocion" => $datos["coberturavigen"]->promociones,
                    "paga" => 0,
                    "fecha_paga" => $datos["parametros"]->ultmod,
                    "referencia" => null,
                    "prima" => null,
                    "master" => null,
                    "formadepago" => null,
                    "fecha_nacimiento" => $propuesta->fecha_nacimiento,
                    "organizador" => null,
                    "productor" => null,
                    "puntodeventa" =>"online",
                    "csrf" => $datos["parametros"]->csrf,
                    "usuariopaga" =>"online"

                ]);

            }else{
                $propuesta->reg = $propuesta->consecutivo();
                $propuesta->id_barrio = $propuesta->reg ;
                $propuesta->save();
            }

                $cliente  = new cliente();
                $cons = $cliente->where('id',$datos["tomador"]->documento)->get();
                if(count($cons) > 0){
                    $cliente->where('id',$datos["tomador"]->documento)->update([
                        "nombres" => $datos["tomador"]->nombres,
                        "apellidos" => $datos["tomador"]->apellidos,
                        "telefono" => $datos["tomador"]->telefono,
                        "direccion" => $datos["tomador"]->direccion,
                        "email" => $datos["tomador"]->email,
                        "ciudad" => $datos["tomador"]->ciudad,
                        "codpostal" => $datos["tomador"]->codpostal,
                        "localidad" => $datos["tomador"]->localidad,
                        "fecha_nacimiento" => $datos["tomador"]->fechanacimiento,
                        "tipo_id" => $datos["tomador"]->tipodocumento,
                        "sexo" => $datos["tomador"]->sexo,
                        "situacion" => $datos["tomador"]->situacionimpositiva,
                        "ultmod" => $datos["parametros"]->ultmod,
                        "user_edit" =>  "online",
                        "categoria" => "ONLINE"
                    ]);
                }else{
                    $cliente->id = $datos["tomador"]->documento;
                    $cliente->nombres = $datos["tomador"]->nombres;
                    $cliente->apellidos = $datos["tomador"]->apellidos;
                    $cliente->telefono = $datos["tomador"]->telefono;
                    $cliente->direccion = $datos["tomador"]->direccion;
                    $cliente->email = $datos["tomador"]->email;
                    $cliente->ciudad = $datos["tomador"]->ciudad;
                    $cliente->codpostal = $datos["tomador"]->codpostal;
                    $cliente->localidad = $datos["tomador"]->localidad;
                    $cliente->fecha_nacimiento = $datos["tomador"]->fechanacimiento;
                    $cliente->tipo_id = $datos["tomador"]->tipodocumento;
                    $cliente->sexo = $datos["tomador"]->sexo;
                    $cliente->situacion = $datos["tomador"]->situacionimpositiva;
                    $cliente->ultmod = $datos["parametros"]->ultmod;
                    $cliente->user_edit = "online";
                    $cliente->categoria = "ONLINE";
                    $cliente->save();
                }
                

                
                

                if (count($datos["personasaseguradas"])> 0) {
                    
                    DB::table('lineas_propuestas')->where('id_propuesta',$propuesta->reg)->where('prefijo',$propuesta->prefijo)->delete();

                    foreach ($datos["personasaseguradas"] as $value) {
                        $lineaspropuestas = new LineasPropuesta();
                        $lineaspropuestas->reg = $propuesta->reg ;
                        $lineaspropuestas->id_propuesta = $propuesta->reg ;
                        $lineaspropuestas->documento = $value->documento;
                        $lineaspropuestas->tipo_documento = $value->tipodocumento;
                        $lineaspropuestas->apellidos = $value->apellidos;
                        $lineaspropuestas->nombres = $value->nombres;
                        $lineaspropuestas->fecha_nacimiento = $value->fechanacimiento;
                        $lineaspropuestas->id_actividad = $value->actividad;
                        $lineaspropuestas->id_clasificacion = $value->clasificacion;
                        $lineaspropuestas->premio = $propuesta->premio;
                        $lineaspropuestas->ultmod = $propuesta->ultmod;
                        $lineaspropuestas->user_edit = $propuesta->useredit;
                        $lineaspropuestas->codestado = 0;
                        $lineaspropuestas->fechaDesde = $propuesta->fechaDesde;
                        $lineaspropuestas->fechaHasta = $propuesta->fechaHasta;
                        $lineaspropuestas->prefijo = $propuesta->prefijo;
                        $lineaspropuestas->actividad = $value->nomactividad;
                        $lineaspropuestas->clasificacion = $value->nomclasificacion;
                        
                        
                        $lineaspropuestas->save();
                    }
                }

        
                
                if ( count($datos["barriosagregados"]) > 0 ) {
                    
                    DB::table('barrios_propuestas')->where('id_propuesta',$propuesta->reg)->where('prefijo',$propuesta->prefijo)->delete();

                    foreach ($datos["barriosagregados"] as $value) {
                        $barriospropuestas = new BarriosPropuesta();
                        $barriospropuestas->reg = $propuesta->reg;
                        $barriospropuestas->id_propuesta = $propuesta->reg;
                        $barriospropuestas->id_barrio = $value->cuit;
                        $barriospropuestas->nombre = $value->nombre;
                        $barriospropuestas->ultmod = $propuesta->ultmod;
                        $barriospropuestas->user_edit = $propuesta->useredit;
                        $barriospropuestas->codestado = 1;
                        $barriospropuestas->prefijo = $propuesta->prefijo;
                        $barriospropuestas->save(); 
                        
                    }
                }

                // Agrega credenciales
                MercadoPago\SDK::setAccessToken(config('services.mercadopago.token'));
                // Crea un objeto de preferencia
                $preference = new MercadoPago\Preference();

                // Crea un ítem en la preferencia
                $item = new MercadoPago\Item();
                $item->title = "Póliza No. ".$propuesta->prefijo."-".$propuesta->reg." | "."Tomador : ".$propuesta->nombre;
                $item->quantity = 1;
                $item->unit_price = $propuesta->premio_total;

                $preference->back_urls = array(
                    "success" => url('/polizas')."?estado=success&idpropuesta=".$propuesta->reg."&prefijo=".$propuesta->prefijo,
                    "failure" => url('/cotizadoronline')."?estado=failure",
                    "pending" => url('/polizas')."?estado=pending&idpropuesta=".$propuesta->reg."&prefijo=".$propuesta->prefijo
                );

                $preference->items = array($item);
                $preference->save();
                
                //total=590&idpropuesta=2&prefijo=O&tomador=MARIO%20LASLUISA%20CASTAÑO
            
                return response()->json(
                    [
                        'res' => 'Se ha generado la propuesta con éxito', 'success'=>true, 
                        'idpropuesta' => $propuesta->reg,
                        'prefijo' => $propuesta->prefijo,
                        'total' => $propuesta->premio_total,
                        'tomador' => $propuesta->nombre,
                        'preference' => $preference->id

                    ], 202);

        }catch(Exception $ex){
            $logs = new logs();
            $logs->saveerror($ex->getMessage(), $propuesta->reg, $propuesta->prefijo, "101");
            return response()->json(['res' => 'No se ha podido guardar la propuesta error #101', 'success'=>false], 400);
        }

        
    }


    public function paypropuesta()
    {
        $data = request()->all();
        return view('pay.index', compact('data'));
    }


    public function descargapdfpoliza()
    {
        $data = request()->all();

        $data = DB::table('propuestas')->where('idpropuesta',$data['id'])->where('prefijo', $data['prefijo'])->get();

        if (count($data) > 0) {

            $lineasdata = DB::table('lineas_propuestas')->where('id_propuesta',$data[0]->idpropuesta)->where('prefijo',$data[0]->prefijo)->where('codempresa',$data[0]->codempresa)->groupBy('documento')->get();
            if(isset($data[0]->data_barrios) && $data[0]->data_barrios != ""){
                $barriospropuesta = json_decode( $data[0]->data_barrios);
                $barriospropuesta = $barriospropuesta->barrios;
            }
            else
                $barriospropuesta = DB::table('barrios_propuestas')->where('id_propuesta',$data[0]->reg)->where('prefijo',$data[0]->prefijo)->where('codempresa',$data[0]->codempresa)->get();

            $cliente = DB::table('clientes')->where('id',$data[0]->documento)->get();
        
            $pdf = PDF::loadView('pdf-propuesta.index', compact('cliente','data','lineasdata','barriospropuesta'));

            return $pdf->stream();
        } else {
            return response()->json(['res' => 'El documento solicitado no existe o no se ha generado en la nube'], 400);
        }
    }


    public function cotizadoronline()
    {
        $data["coberturas"] = DB::table('coberturas')->where('codestado','=', '1')->get();
        $data["actividades"] = DB::table('actividades')->where('codestado','=', '1')->get();
        $data["clasificaciones"] = DB::table('clasificaciones')->where('codestado','=', '1')->get();
        $data["provincias"] = DB::table('provincias')->where('codestado','=', '1')->get();
        $data["barrios"] = DB::table('barrios')->where('codestado','=', '1')->get();
        $data["gruposbarrios"] = DB::table('gruposbarrios')->get();
        $data["gruposbarriosnombres"] = DB::table('gruposbarrios')->select('nombre','id')->distinct()->get();

        /*MercadoPago\SDK::setAccessToken('PROD_ACCESS_TOKEN');
        $preference = new MercadoPago\Preference();
        // Create a preference item
        $item = new MercadoPago\Item();
        $item->title = 'My Item';
        $item->quantity = 1;
        $item->unit_price = 75;
        $preference->items = array($item);
        $preference->save();*/

        return view('cotizadoronline.index', compact('data'));
        
    }

    public function paypro(){

        $req = request()->all();

        if($req){
            $prop = new Propuesta();
            if($prop->pagarpropuesta(
                $req["idpropuesta"],
                $req["prefijopropuesta"],
                $req["tipopago"],
                $req["compformapago"],
                $req["usuariopaga"],
                $req["fecha_paga"],
                $req["codempresa"],
                $req["version"]
            )){
                return response()->json(['res' => 'Se ha hecho el pago de la propuesta con éxito'], 200);
            }else{
                return response()->json(['res' => 'No se pudo hacer el pago de la propuesta'], 400);
            }
        }
        
    }


    public function polizas()
    {
        $data = [];
        $success = true;
        $estado = "";
        $req = request()->all();
        if($req){
            $prop = new Propuesta();
            $estado = request()->get('estado');
        }
        return view('polizas.index', compact('data','success','estado'));
    }

    

    public function consultapoliza()
    {
        $data = request()->all();
        $estado = "";


        $sql = "SELECT lp.* 
                FROM propuestas p 
                INNER JOIN lineas_propuestas lp ON p.prefijo = lp.prefijo AND p.reg = lp.id_propuesta  AND (p.codempresa = lp.codempresa OR p.codempresa IS NULL)
                WHERE 
                ((lp.fechaHasta >= '".date("Y-m-d H:i:s")."' AND lp.fecha_nacimiento = '".$data['fechanacimiento']."' AND lp.documento = '".$data['documento']."')
                 OR
                (p.fechaHasta >= '".date("Y-m-d H:i:s")."' AND p.fecha_nacimiento = '".$data['fechanacimiento']."' AND p.documento = '".$data['documento']."'))
                AND p.codestado > 0 AND p.paga > 0
                GROUP BY lp.id_propuesta
                ";
        $data = DB::select($sql);

        $success = false;        

        if (count($data) > 0) {
            $success = true;
            // Almacenar una variable en la sesión
            session(['get_prop' => true]);

            return view('polizas.index', compact('data','success','estado'));
        } else {
            return view('polizas.index', compact('data','success','estado'));
        }
    }

    

    public function consultaparametros()
    {
        try{
            $req = request()->all();
            $datos = [];
    
    
            $sql = "SELECT reg,fecha FROM exportaciones WHERE fecha > '".$req["fecha_actualizacion_hasta"]."' ORDER BY reg DESC  LIMIT 1";
            $fechaexporta = DB::select($sql);
    
            if(count($fechaexporta) > 0)        
                $req["fecha_actualizacion_hasta"] = $fechaexporta[0]->fecha;
    
            //$datos["fechaimportacion"] = $req["fecha_actualizacion_hasta"];
    
    
            if(isset($req["apiversion"])){


                if($req["apiversion"] == "2"){
                    $sql = "SELECT MAX(updated_at) as fecha FROM migracionespuntos WHERE codempresa = '".$req["codempresa"]."' AND puntodeventa = '".$req["prefijositio"]."' AND tipo = 'SOLOPROPUESTAS' LIMIT 1";
                    $migpunto = DB::select($sql);
        
                    $fechamigracion = '2020-01-01 00:00:00';
        
                    
        
                    if(count($migpunto) > 0)  {
                        if($migpunto[0]->fecha != null && $migpunto[0]->fecha != "" ){
                            $fechamigracion = new DateTime($migpunto[0]->fecha);
                            $fechamigracion = $fechamigracion->modify('-30 minute')->format('Y-m-d H:i:s');
                        }
                            
                    }
        
                    if(isset($req["reset"])){
                        if($req["reset"] == "1" )
                            $fechamigracion = '2020-01-01 00:00:00';
                    }
        
                    
                        /*$datos["fechamigra"] = "SELECT MAX(updated_at) as fecha FROM migracionespuntos WHERE codempresa = '".$req["codempresa"]."' AND puntodeventa = '".$req["prefijositio"]."'  LIMIT 1"." <br>". $fechamigracion . " -- ".date('Y-m-d H:i:s');*/
              
        
                        $sql = "SELECT t1.* FROM propuestas t1 LEFT JOIN payregistries t2 
                        ON t1.prefijo = t2.prefijo AND t1.idpropuesta = t2.idpropuesta
                        WHERE t1.updated_at >= '".$fechamigracion."' AND t1.updated_at <= '".date('Y-m-d H:i:s')."' AND codempresa = '".$req["codempresa"]."' AND (t1.prefijo != '".$req["prefijositio"]."' OR (t1.prefijo = '".$req["prefijositio"]."' AND t1.prefijo = t2.prefijo AND t1.idpropuesta AND t2.idpropuesta) );";
                        //$datos["propuestas"] = DB::select($sql);
                        $datos["propuestas"] = [];
    
                        $sql = "SELECT t1.* FROM lineas_propuestas t1 LEFT JOIN payregistries t2 
                        ON t1.prefijo = t2.prefijo AND t1.id_propuesta = t2.idpropuesta
                        WHERE t1.updated_at >= '".$fechamigracion."' AND t1.updated_at <= '".date('Y-m-d H:i:s')."' AND codempresa = '".$req["codempresa"]."' AND (t1.prefijo != '".$req["prefijositio"]."' OR (t1.prefijo = '".$req["prefijositio"]."' AND t1.prefijo = t2.prefijo AND t1.id_propuesta AND t2.idpropuesta) )  GROUP BY t1.prefijo,t1.id_propuesta,t1.documento;";
                        //$datos["lineas_propuestas"] = DB::select($sql);
                        $datos["lineas_propuestas"] = [];
            
                        $sql = "SELECT t1.* FROM barrios_propuestas t1 LEFT JOIN payregistries t2 
                        ON t1.prefijo = t2.prefijo AND t1.id_propuesta = t2.idpropuesta
                        WHERE t1.updated_at >= '".$fechamigracion."' AND t1.updated_at <= '".date('Y-m-d H:i:s')."' AND codempresa = '".$req["codempresa"]."' AND (t1.prefijo != '".$req["prefijositio"]."' OR (t1.prefijo = '".$req["prefijositio"]."' AND t1.prefijo = t2.prefijo AND t1.id_propuesta AND t2.idpropuesta) )  ;";
                        //$datos["barrios_propuestas"] = DB::select($sql);
                        $datos["barrios_propuestas"] = [];
    
                        
                        $datos["clientes"] = DB::table('clientes')->where('fecha_nacimiento','!=','0000-00-00')->where('codempresa','=',$req["codempresa"])->where('updated_at','>=',$fechamigracion)->where('updated_at','<=',date('Y-m-d H:i:s'))->where('puntodeventa','!=',$req["prefijositio"])->get();
    
                        if(isset($req["reset"])){
                            if($req["reset"] == "1" )
                                {
                                    $datos["clientes"] = DB::table('clientes')->where('fecha_nacimiento','!=','0000-00-00')->where('codempresa','=',$req["codempresa"])->where('updated_at','>=',$fechamigracion)->where('updated_at','<=',date('Y-m-d H:i:s'))->get();
                                }
                        }
                        $datos["clientes"] = [];
    
                    
                    
                    if($req["solopropuestas"] != "1"){
    
                        $sql = "SELECT MAX(updated_at) as fecha FROM migracionespuntos WHERE codempresa = '".$req["codempresa"]."' AND puntodeventa = '".$req["prefijositio"]."' AND tipo = 'GENERAL'  LIMIT 1";
                        $migpunto = DB::select($sql);
            
                        $fechamigracion = '2020-01-01 00:00:00';
            
                        
            
                        if(count($migpunto) > 0)  {
                            if($migpunto[0]->fecha != null && $migpunto[0]->fecha != "" ){
                                $fechamigracion = new DateTime($migpunto[0]->fecha);
                                $fechamigracion = $fechamigracion->modify('-30 minute')->format('Y-m-d H:i:s');
                            }
                                
                        }
            
                        if(isset($req["reset"])){
                            if($req["reset"] == "1" )
                                $fechamigracion = '2020-01-01 00:00:00';
                        }
        
                        //$datos["usuarios"] = DB::table('usuarios')->where('codempresa','=',$req["codempresa"])->get();
                        $datos["usuarios"] = [];
                        //$datos["perfiles"] = DB::table('perfiles')->where('codempresa','=',$req["codempresa"])->get();
                        $datos["perfiles"] = [];
    
                        //$datos["arqueos"] = DB::table('arqueos')->where('updated_at','>=',$fechamigracion)->where('updated_at','<=',date('Y-m-d H:i:s'))->where('codempresa','=',$req["codempresa"])->where('puntodeventa','!=',$req["prefijositio"])->get();
                        $datos["arqueos"] = [];
    
                        //$datos["rendiciones"] = DB::table('rendiciones')->where('updated_at','>=',$fechamigracion)->where('updated_at','<=',date('Y-m-d H:i:s'))->where('codempresa','=',$req["codempresa"])->where('puntodeventa','!=',$req["prefijositio"])->get();
                        $datos["rendiciones"] = [];
    
                        //$datos["lineas_rendiciones"] = DB::table('lineas_rendiciones')->where('updated_at','>=',$fechamigracion)->where('updated_at','<=',date('Y-m-d H:i:s'))->where('codempresa','=',$req["codempresa"])->where('puntodeventa','!=',$req["prefijositio"])->get();
                        $datos["lineas_rendiciones"] = [];
    
                        if($req["rolpuntodeventa"] == "COLABORADOR" AND $req["apiversion"] == 2){
        
                            
    
                            //$datos["actividades"] = DB::table('actividades')->where('updated_at','>=',$fechamigracion)->where('updated_at','<=',date('Y-m-d H:i:s'))->get();
                            $datos["actividades"] = [];
                            
                            //$datos["coberturas"] = DB::table('coberturas')->where('ultmod','>=',$req["fecha_actualizacion_desde"])->where('ultmod','<=',$req["fecha_actualizacion_hasta"])->get();
                            //$datos["coberturas"] = DB::table('coberturas')->where('updated_at','>=',$fechamigracion)->where('updated_at','<=',date('Y-m-d H:i:s'))->get();
                            $datos["coberturas"] = [];
                            //$datos["clasificaciones"] = DB::table('clasificaciones')->where('updated_at','>=',$fechamigracion)->where('updated_at','<=',date('Y-m-d H:i:s'))->get();
                            $datos["clasificaciones"] = [];
                
                            //$datos["barrios"] = DB::table('barrios')->where('updated_at','>=',$fechamigracion)->where('updated_at','<=',date('Y-m-d H:i:s'))->get();
                            $datos["barrios"] = [];

                            //$datos["gruposbarrios"] = DB::table('gruposbarrios')->where('updated_at','>=',$fechamigracion)->where('updated_at','<=',date('Y-m-d H:i:s'))->get();
                            $datos["gruposbarrios"] = [];
        
                            //$datos["provincias"] = DB::table('provincias')->where('updated_at','>=',$fechamigracion)->where('updated_at','<=',date('Y-m-d H:i:s'))->get();
                            $datos["provincias"] = [];
                            
                        }
        
                    }
    
                    /*if($req["solopropuestas"] != "1"){
                        $migpunto = new migracionespunto();
                        $migpunto->puntodeventa = $req["prefijositio"];
                        $migpunto->codempresa = $req["codempresa"];
                        $migpunto->tipo = "GENERAL";
                        $migpunto->save();
                    }else{
                        $migpunto = new migracionespunto();
                        $migpunto->puntodeventa = $req["prefijositio"];
                        $migpunto->codempresa = $req["codempresa"];
                        $migpunto->tipo = "SOLOPROPUESTAS";
                        $migpunto->save();
                    }*/

                }

                if($req["apiversion"] == "3"){

                    
                    
                    
                    

                    if(isset($req["solicitud"])){

                        

                        if($req["solicitud"] == "solicitud_propuestas"){

                            $fechamigracion = $this->fechamigra($req["codempresa"],$req["prefijositio"], $req["reset"], "propuestas");
                            
                            if($req["reset"] == "1" ){
                                $fechamigracion = new DateTime();
                                $fechamigracion = $fechamigracion->modify('-30 day')->format('Y-m-d'.' 00:00:00');
                            }
                                

                            /*$sql = "SELECT t1.* FROM propuestas t1 LEFT JOIN payregistries t2 
                            ON t1.prefijo = t2.prefijo AND t1.idpropuesta = t2.idpropuesta
                            WHERE t1.updated_at >= '".$fechamigracion."' AND t1.updated_at <= '".date('Y-m-d H:i:s')."' AND codempresa = '".$req["codempresa"]."' AND (t1.prefijo != '".$req["prefijositio"]."' OR (t1.prefijo = '".$req["prefijositio"]."' AND t1.prefijo = t2.prefijo AND t1.idpropuesta AND t2.idpropuesta) );";*/
                            $sql = "SELECT t1.* FROM propuestas t1 
                            WHERE t1.updated_at >= '".$fechamigracion."' AND t1.updated_at <= '".date('Y-m-d H:i:s')."' AND codempresa = '".$req["codempresa"]."' AND (t1.prefijo != '".$req["prefijositio"]."' OR ( (SELECT COUNT(1) FROM payregistries t2 WHERE t1.prefijo = t2.prefijo AND t1.idpropuesta = t2.idpropuesta ) > 0 AND t1.prefijo = '".$req["prefijositio"]."' ) OR (t1.codestado = '0' AND t1.prefijo = '".$req["prefijositio"]."'))  ORDER BY t1.ultmod DESC;";

                            if(isset($req["get_prefix_own"])){
                                if($req["get_prefix_own"] == 1){
                                    $sql = "SELECT t1.* FROM propuestas t1 
                                    WHERE  codempresa = '".$req["codempresa"]."' AND (t1.prefijo = '".$req["prefijositio"]."')  LIMIT 200;";
                                }
                            }
                            

                            $datos["propuestas"] = DB::select($sql);

                            $migpunto = new migracionespunto();
                            $migpunto->puntodeventa = $req["prefijositio"];
                            $migpunto->codempresa = $req["codempresa"];
                            $migpunto->tipo = "propuestas";
                            $migpunto->save();
                        }

                        

                    }

                    if(isset($req["solicitud"])){

                        

                        if($req["solicitud"] == "solicitud_lineas_propuestas"){

                            $fechamigracion = $this->fechamigra($req["codempresa"],$req["prefijositio"], $req["reset"], "lineas_propuestas");
                            if($req["reset"] == "1" ){
                                $fechamigracion = new DateTime();
                                $fechamigracion = $fechamigracion->modify('-30 day')->format('Y-m-d'.' 00:00:00');
                            }

                            /*$sql = "SELECT t1.* FROM lineas_propuestas t1 LEFT JOIN payregistries t2 
                            ON t1.prefijo = t2.prefijo AND t1.id_propuesta = t2.idpropuesta
                            WHERE t1.updated_at >= '".$fechamigracion."' AND t1.updated_at <= '".date('Y-m-d H:i:s')."' AND codempresa = '".$req["codempresa"]."' AND (t1.prefijo != '".$req["prefijositio"]."' OR (t1.prefijo = '".$req["prefijositio"]."' AND t1.prefijo = t2.prefijo AND t1.id_propuesta AND t2.idpropuesta) )  GROUP BY t1.prefijo,t1.id_propuesta,t1.documento;";*/

                            $sql = "SELECT t1.* FROM lineas_propuestas t1 
                            WHERE t1.updated_at >= '".$fechamigracion."' AND t1.updated_at <= '".date('Y-m-d H:i:s')."' AND codempresa = '".$req["codempresa"]."' AND (t1.prefijo != '".$req["prefijositio"]."' OR ( (SELECT COUNT(1) FROM payregistries t2 WHERE t1.prefijo = t2.prefijo AND t1.id_propuesta = t2.idpropuesta ) > 0 AND t1.prefijo = '".$req["prefijositio"]."' ))  ;";

                            if(isset($req["get_prefix_own"])){
                                if($req["get_prefix_own"] == 1){

                                    $sql = "SELECT t1.* FROM lineas_propuestas t1 
                                    WHERE (SELECT MIN(t0.idpropuesta) FROM propuestas t0 
                                    WHERE  t0.codempresa = '".$req["codempresa"]."' AND (t0.prefijo = '".$req["prefijositio"]."')  LIMIT 200) <= t1.id_propuesta  AND t1.codempresa = '".$req["codempresa"]."' AND (t1.prefijo = '".$req["prefijositio"]."' )  ;";

                                }
                            }
                            

                            $datos["lineas_propuestas"] = DB::select($sql);

                            $migpunto = new migracionespunto();
                            $migpunto->puntodeventa = $req["prefijositio"];
                            $migpunto->codempresa = $req["codempresa"];
                            $migpunto->tipo = "lineas_propuestas";
                            $migpunto->save();
                        }

                        
                    }

                    if(isset($req["solicitud"])){

                        if($req["solicitud"] == "solicitud_barrios"){

                            $fechamigracion = $this->fechamigra($req["codempresa"],$req["prefijositio"], $req["reset"], "barrios");

                            $datos["barrios"] = DB::table('barrios')->where('updated_at','>=',$fechamigracion)->where('updated_at','<=',date('Y-m-d H:i:s'))->get();

                            $migpunto = new migracionespunto();
                            $migpunto->puntodeventa = $req["prefijositio"];
                            $migpunto->codempresa = $req["codempresa"];                                    
                            $migpunto->tipo = "barrios";
                            $migpunto->save();
                        }
                    }

                    if(isset($req["solicitud"])){

                        if($req["solicitud"] == "solicitud_barrios_propuestas"){

                            $fechamigracion = $this->fechamigra($req["codempresa"],$req["prefijositio"], $req["reset"], "barrios_propuestas");
                            $fechamigracion = new DateTime();
                            $fechamigracion = $fechamigracion->modify('-1 hour')->format('Y-m-d'.' 00:00:00');
                            if($req["reset"] == "1" ){
                                $fechamigracion = new DateTime();
                                $fechamigracion = $fechamigracion->modify('-30 day')->format('Y-m-d'.' HH:mm:ss');
                            }


                            
                            /*SELECT t1.* FROM barrios_propuestas t1 WHERE t1.updated_at >= '2022-03-22 00:00:00' AND t1.updated_at <= '2022-04-21 00:00:00' AND codempresa = 'BDPAPRAPIDO' AND (t1.prefijo != '".$req["prefijositio"]."' OR ( (SELECT COUNT(1) FROM payregistries t2 WHERE t1.prefijo = t2.prefijo AND t1.id_propuesta = t2.idpropuesta ) > 0 AND t1.prefijo = '".$req["prefijositio"]."' )) ;*/

                            $sql = "SELECT t1.* FROM barrios_propuestas t1 WHERE t1.updated_at >= '".$fechamigracion."' AND t1.updated_at <= '".date('Y-m-d H:i:s')."' AND codempresa = '".$req["codempresa"]."' AND (t1.prefijo != '".$req["prefijositio"]."' OR ( (SELECT COUNT(1) FROM payregistries t2 WHERE t1.prefijo = t2.prefijo AND t1.id_propuesta = t2.idpropuesta ) > 0 AND t1.prefijo = '".$req["prefijositio"]."' ))  ;";

                            if(isset($req["get_prefix_own"])){
                                if($req["get_prefix_own"] == 1){

                                    $sql = "SELECT t1.* FROM barrios_propuestas t1 
                                    WHERE (SELECT MIN(t0.idpropuesta) FROM propuestas t0 
                                    WHERE  t0.codempresa = '".$req["codempresa"]."' AND (t0.prefijo = '".$req["prefijositio"]."')  LIMIT 200) <= t1.id_propuesta  AND t1.codempresa = '".$req["codempresa"]."' AND (t1.prefijo = '".$req["prefijositio"]."' )  ;";

                                }
                            }


                            $datos["barrios_propuestas"] = DB::select($sql);

                            $migpunto = new migracionespunto();
                            $migpunto->puntodeventa = $req["prefijositio"];
                            $migpunto->codempresa = $req["codempresa"];
                            $migpunto->tipo = "barrios_propuestas";
                            $migpunto->save();
                        }
                        
                    }

                    if(isset($req["solicitud"])){
                        if($req["solicitud"] == "solicitud_clientes"){

                            $fechamigracion = $this->fechamigra($req["codempresa"],$req["prefijositio"], $req["reset"], "clientes");

                            if(isset($req["reset"])){
                                if($req["reset"] == "1" )
                                {
                                    $datos["clientes"] = DB::table('clientes')->where('fecha_nacimiento','!=','0000-00-00')->where('codempresa','=',$req["codempresa"])->where('updated_at','>=',$fechamigracion)->where('updated_at','<=',date('Y-m-d H:i:s'))->get();
                                }else{
                                    $datos["clientes"] = DB::table('clientes')->where('fecha_nacimiento','!=','0000-00-00')->where('codempresa','=',$req["codempresa"])->where('updated_at','>=',$fechamigracion)->where('updated_at','<=',date('Y-m-d H:i:s'))->where('puntodeventa','!=',$req["prefijositio"])->get();    
                                }
                            }else{
                                $datos["clientes"] = DB::table('clientes')->where('fecha_nacimiento','!=','0000-00-00')->where('codempresa','=',$req["codempresa"])->where('updated_at','>=',$fechamigracion)->where('updated_at','<=',date('Y-m-d H:i:s'))->where('puntodeventa','!=',$req["prefijositio"])->get();
                            }
                            $migpunto = new migracionespunto();
                            $migpunto->puntodeventa = $req["prefijositio"];
                            $migpunto->codempresa = $req["codempresa"];
                            $migpunto->tipo = "clientes";
                            $migpunto->save();
                            
                        }
                    }

                    if(isset($req["solicitud"])){

                        if($req["solicitud"] == "solicitud_usuarios"){
                            $datos["usuarios"] = DB::table('usuarios')->where('codempresa','=',$req["codempresa"])->get();
                        }

                    }

                    if(isset($req["solicitud"])){
                        if($req["solicitud"] == "solicitud_perfiles"){
                            $datos["perfiles"] = DB::table('perfiles')->where('codempresa','=',$req["codempresa"])->get();
                        }
                    }

                    if($req["solopropuestas"] != "1"){

                        if(isset($req["solicitud"])){

                            if($req["solicitud"] == "solicitud_arqueos"){

                                $fechamigracion = $this->fechamigra($req["codempresa"],$req["prefijositio"], $req["reset"], "arqueos");
                                if($req["reset"] == "1" ){
                                    $fechamigracion = new DateTime();
                                    $fechamigracion = $fechamigracion->modify('-30 day')->format('Y-m-d'.' 00:00:00');
                                }

                                $datos["arqueos"] = DB::table('arqueos')->where('updated_at','>=',$fechamigracion)->where('updated_at','<=',date('Y-m-d H:i:s'))->where('codempresa','=',$req["codempresa"])->where('puntodeventa','!=',$req["prefijositio"])->get();


                                $migpunto = new migracionespunto();
                                $migpunto->puntodeventa = $req["prefijositio"];
                                $migpunto->codempresa = $req["codempresa"];
                                $migpunto->tipo = "arqueos";
                                $migpunto->save();
                            }
                            
                        }


                        if(isset($req["solicitud"])){

                            if($req["solicitud"] == "solicitud_rendiciones"){

                                $fechamigracion = $this->fechamigra($req["codempresa"],$req["prefijositio"], $req["reset"], "rendiciones");
                                if($req["reset"] == "1" ){
                                    $fechamigracion = new DateTime();
                                    $fechamigracion = $fechamigracion->modify('-30 day')->format('Y-m-d'.' 00:00:00');
                                }

                                $datos["rendiciones"] = DB::table('rendiciones')->where('updated_at','>=',$fechamigracion)->where('updated_at','<=',date('Y-m-d H:i:s'))->where('codempresa','=',$req["codempresa"])->where('puntodeventa','!=',$req["prefijositio"])->get();

                                $migpunto = new migracionespunto();
                                $migpunto->puntodeventa = $req["prefijositio"];
                                $migpunto->codempresa = $req["codempresa"];
                                $migpunto->tipo = "rendiciones";
                                $migpunto->save();
                            }

                        }

                        if(isset($req["solicitud"])){
                            if($req["solicitud"] == "solicitud_lineas_rendiciones"){

                                $fechamigracion = $this->fechamigra($req["codempresa"],$req["prefijositio"], $req["reset"], "lineas_rendiciones");
                                if($req["reset"] == "1" ){
                                    $fechamigracion = new DateTime();
                                    $fechamigracion = $fechamigracion->modify('-30 day')->format('Y-m-d'.' 00:00:00');
                                }

                                $datos["lineas_rendiciones"] = DB::table('lineas_rendiciones')->where('updated_at','>=',$fechamigracion)->where('updated_at','<=',date('Y-m-d H:i:s'))->where('codempresa','=',$req["codempresa"])->where('puntodeventa','!=',$req["prefijositio"])->get();

                                $migpunto = new migracionespunto();
                                $migpunto->puntodeventa = $req["prefijositio"];
                                $migpunto->codempresa = $req["codempresa"];
                                $migpunto->tipo = "lineas_rendiciones";
                                $migpunto->save();
                            }
                        }
                        


                        if($req["rolpuntodeventa"] == "COLABORADOR" ){
        
                            if(isset($req["solicitud"])){

                                

                                if($req["solicitud"] == "solicitud_actividades"){

                                    $fechamigracion = $this->fechamigra($req["codempresa"],$req["prefijositio"], $req["reset"], "actividades");

                                    $datos["actividades"] = DB::table('actividades')->where('updated_at','>=',$fechamigracion)->where('updated_at','<=',date('Y-m-d H:i:s'))->get();

                                    $migpunto = new migracionespunto();
                                    $migpunto->puntodeventa = $req["prefijositio"];
                                    $migpunto->codempresa = $req["codempresa"];                                    
                                    $migpunto->tipo = "actividades";
                                    $migpunto->save();
                                }

                                
                            }


                            if(isset($req["solicitud"])){

                                

                                if($req["solicitud"] == "solicitud_coberturas"){

                                    $fechamigracion = $this->fechamigra($req["codempresa"],$req["prefijositio"], $req["reset"], "coberturas");

                                    $datos["coberturas"] = DB::table('coberturas')->where('updated_at','>=',$fechamigracion)->where('updated_at','<=',date('Y-m-d H:i:s'))->get();

                                    $migpunto = new migracionespunto();
                                    $migpunto->puntodeventa = $req["prefijositio"];
                                    $migpunto->codempresa = $req["codempresa"];                                    
                                    $migpunto->tipo = "coberturas";
                                    $migpunto->save();
                                }

                                
                            }

                            if(isset($req["solicitud"])){
                                if($req["solicitud"] == "solicitud_clasificaciones"){

                                    $fechamigracion = $this->fechamigra($req["codempresa"],$req["prefijositio"], $req["reset"], "clasificaciones");

                                    $datos["clasificaciones"] = DB::table('clasificaciones')->where('updated_at','>=',$fechamigracion)->where('updated_at','<=',date('Y-m-d H:i:s'))->get();

                                    $migpunto = new migracionespunto();
                                    $migpunto->puntodeventa = $req["prefijositio"];
                                    $migpunto->codempresa = $req["codempresa"];                                    
                                    $migpunto->tipo = "clasificaciones";
                                    $migpunto->save();
                                }
                            }
                            

                            

                            if(isset($req["solicitud"])){
                                if($req["solicitud"] == "solicitud_gruposbarrios"){

                                    $fechamigracion = $this->fechamigra($req["codempresa"],$req["prefijositio"], $req["reset"], "gruposbarrios");

                                    $datos["gruposbarrios"] = DB::table('gruposbarrios')->where('updated_at','>=',$fechamigracion)->where('updated_at','<=',date('Y-m-d H:i:s'))->get();

                                    $migpunto = new migracionespunto();
                                    $migpunto->puntodeventa = $req["prefijositio"];
                                    $migpunto->codempresa = $req["codempresa"];                                    
                                    $migpunto->tipo = "gruposbarrios";
                                    $migpunto->save();
                                }
                            }
                            
                
                            if(isset($req["solicitud"])){
                                if($req["solicitud"] == "solicitud_provincias"){

                                    $fechamigracion = $this->fechamigra($req["codempresa"],$req["prefijositio"], $req["reset"], "provincias");

                                    $datos["provincias"] = DB::table('provincias')->where('updated_at','>=',$fechamigracion)->where('updated_at','<=',date('Y-m-d H:i:s'))->get();  

                                    $migpunto = new migracionespunto();
                                    $migpunto->puntodeventa = $req["prefijositio"];
                                    $migpunto->codempresa = $req["codempresa"];                                    
                                    $migpunto->tipo = "provincias";
                                    $migpunto->save();
                                }
                            }
                        }
                    }

                    
                        
                    

                }
                
                
            }else{

                $datos["fechaimportacion"] = $req["fecha_actualizacion_hasta"];

                if($req["rolpuntodeventa"] == "COLABORADOR" ){

                    
    
                    //$datos["actividades"] = DB::table('actividades')->where('ultmod','>=',$req["fecha_actualizacion_desde"])->where('ultmod','<=',$req["fecha_actualizacion_hasta"])->get();
                    $datos["actividades"] = DB::table('actividades')->where('updated_at','<=',date('Y-m-d H:i:s'))->get();
                    
                    //$datos["coberturas"] = DB::table('coberturas')->where('ultmod','>=',$req["fecha_actualizacion_desde"])->where('ultmod','<=',$req["fecha_actualizacion_hasta"])->get();
                    //$datos["coberturas"] = DB::table('coberturas')->where('ultmod','<=',$req["fecha_actualizacion_hasta"])->get();
                    $datos["coberturas"] = DB::table('coberturas')->where('updated_at','<=',date('Y-m-d H:i:s'))->get();

                    //$datos["clasificaciones"] = DB::table('clasificaciones')->where('ultmod','>=',$req["fecha_actualizacion_desde"])->where('ultmod','<=',$req["fecha_actualizacion_hasta"])->get();
                    $datos["clasificaciones"] = DB::table('clasificaciones')->where('updated_at','<=',date('Y-m-d H:i:s'))->get();
        
                    //$datos["barrios"] = DB::table('barrios')->where('ultmod','>=',$req["fecha_actualizacion_desde"])->where('ultmod','<=',$req["fecha_actualizacion_hasta"])->get();
        
                    //$datos["gruposbarrios"] = DB::table('gruposbarrios')->where('updated_at','<=',$req["fecha_actualizacion_hasta"])->get();
        
                }
            }
    
            return response()->json($datos, 200);

            
        }catch (Exception $ex){

            $logs = new logs();
            $logs->saveerror("Error en importarción ".$ex->getMessage(),"", "", "IMP101");
            return response()->json("Error en importarción ".$ex->getMessage(), 404);

        }
        
        
    }

    public function fechamigra($codempresa , $prefijositio, $reset ,$tipo){

        $sql = "SELECT MAX(updated_at) as fecha FROM migracionespuntos WHERE codempresa = '".$codempresa."' AND puntodeventa = '".$prefijositio."' AND ( tipo = '".$tipo."' OR tipo = 'GENERAL' OR tipo = 'SOLOPROPUESTAS')  LIMIT 1";
        $migpunto = DB::select($sql);

        $fechamigracion = '2020-01-01 00:00:00';

        
        
        if(count($migpunto) > 0)  {
            if($migpunto[0]->fecha != null && $migpunto[0]->fecha != "" ){
                $fechamigracion = new DateTime($migpunto[0]->fecha);
                $fechamigracion = $fechamigracion->modify('-360 minute')->format('Y-m-d'.' 00:00:00');
            }
                
        }

        if($reset == "1" )
                $fechamigracion = '2020-01-01 00:00:00';

        return $fechamigracion;
    }


}
