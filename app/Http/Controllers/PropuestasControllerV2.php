<?php

namespace App\Http\Controllers;

use App\Models\barrio;
use App\Models\gruposbarrio;
use App\Models\logs;
use App\Models\PendingDuplicate;
use App\Models\Propuesta;
use Barryvdh\DomPDF\Facade as PDF;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;




class PropuestasControllerV2 extends Controller
{
    //
    public function getReference($codempresa){

        $req = request()->all();
        
        $resultados = Propuesta::query()
                    ->whereIn('idpropuesta', collect($req['registros'])->pluck('idpropuesta'))
                    ->whereIn('prefijo', collect($req['registros'])->pluck('prefijo'))
                    ->where('codempresa', $codempresa)
                    ->whereNotNull('referencia')
                    ->select('prefijo', 'idpropuesta', 'ultmod', 'referencia', 'nota', 'prima')
                    ->get();


        return response()->json($resultados);
    }

   

    
    public function setReference($codempresa){

        $req = request()->all();
    
        try{
            foreach ($req["registros"] as $registro) {
                Propuesta::where('idpropuesta', $registro['idpropuesta'])
                            ->where('prefijo', $registro['prefijo'])
                            ->where('codempresa', $codempresa)
                            ->whereNull('referencia')
                            ->update([
                                'referencia' => $registro['referencia'],
                                'nota' => $registro['nota'],
                                'prima' => $registro['prima'],
                                'version' => DB::raw('version + 1'),
                            ]);
            }
    
            return response()->json(["success" => TRUE]);
    
        }catch(Exception $ex){
    
            return response()->json(["success" => FALSE, "msg" => $ex->getMessage()]);
        }   
    }
    

    function construirSentenciaActualizacion($registros, $campo) {
        $sentencia = "";
        foreach ($registros as $registro) {
            $sentencia .= "WHEN " . $registro['idpropuesta'] . " THEN '" . $registro[$campo] . "' ";
        }
        return $sentencia;
    }


    public function duplicatePendingProposal(Request $req){
        
        try{

            $proposal = new Propuesta();
            $data = $proposal->validateProposal($req);

            if(count($data) > 0){
                
                $resp = $proposal->calculateProposedTotal($data[0]->prefijo, $data[0]->idpropuesta, $req['monthly']);
                
                if(count( $resp ) > 0){

                    $duplicate = PendingDuplicate::updateOrCreate(
                        ['prefijo' => $data[0]->prefijo, 'idpropuesta' => $data[0]->idpropuesta, 'status' => 0],
                        [
                            'idpropuesta' => $data[0]->idpropuesta,
                            'prefijo' => $data[0]->prefijo,
                            'meses' => $req['monthly'],
                            'premio' => $resp['vrunit'],
                            'premio_total' => $resp['total'],
                        ]
                    );
    
                    return response()->json(["success" => TRUE, "data" => $resp]);
                }
                
            }

            return response()->json(["success" => FALSE, 'msg' => 'No hay coincidencia con la póliza']);
            

        }catch(Exception $ex){
    
            return response()->json(["success" => FALSE, "msg" => $ex->getMessage()]);
        }   
        
    }


    public function duplicateProposal(Request $req){
        try{

            $regpending = PendingDuplicate::where('prefijo', strtoupper($req['pref']))->where('idpropuesta', $req['id'])->where('status',0)->first();
            
                if( $regpending ){
                    
                    $prop = new Propuesta();
                    
                    $data= [
                        'meses' => $regpending->meses,
                        'premio' => $regpending->premio,
                        'premio_total' => $regpending->premio_total,
                        'pref' => strtoupper($req['pref']),
                        'id' => $req['id'],
                        'forma_pago' => $req['forma_pago'],
                        'nro_comprobante' => $req['nro_comprobante'],
                    ];
                    
                    $resDuplicate = $prop->duplicate('O', $data);
                    
                    if($resDuplicate){
                        $regpending->status = 1; 
                        $regpending->save();

                        return redirect()->route('descargapdfpoliza', [
                            'id' => $resDuplicate->idpropuesta,
                            'prefijo' => $resDuplicate->prefijo
                        ]);

                    }

                     //response()->json(["success" => TRUE, "data" => $resp]);
                }
                
                return response()->json(["success" => FALSE, 'msg' => 'No hay coincidencia con la póliza']);

        }catch(Exception $ex){

            return response()->json(["success" => FALSE, "msg" => $ex->getMessage()]);
            
        }   
    }

    public function descargarPdfLibreDeuda($id,$prefijo){
        if(!empty($id) && !empty($prefijo)){
            $data = DB::table('propuestas')->where('idpropuesta',$id)->where('prefijo', $prefijo)->get();

            if (count($data) > 0) {

                $lineasdata = DB::table('lineas_propuestas')->where('id_propuesta',$data[0]->idpropuesta)->where('prefijo',$data[0]->prefijo)->where('codempresa',$data[0]->codempresa)->groupBy('documento')->get();
                if(isset($data[0]->data_barrios) && $data[0]->data_barrios != ""){
                    $barriospropuesta = json_decode( $data[0]->data_barrios);
                    $barriospropuesta = $barriospropuesta->barrios;
                }
                else
                    $barriospropuesta = DB::table('barrios_propuestas')->where('id_propuesta',$data[0]->reg)->where('prefijo',$data[0]->prefijo)->where('codempresa',$data[0]->codempresa)->get();

            
                $pdf = PDF::loadView('pdf-libre-deuda.index', compact('data','lineasdata','barriospropuesta'));

                return $pdf->stream();
            }
        }

        return response()->json(['res' => 'El documento solicitado no existe o no se ha generado'], 404);
        
    }

    public function agregar_barrios(Request $request, gruposbarrio $gruposbarrios, $prefijo, $idpropuesta){
        
        $propuesta = Propuesta::where('prefijo', $prefijo)->where('idpropuesta',$idpropuesta)->get();

        if(count($propuesta) > 0){
            
            $exclude = json_decode( $propuesta[0]->data_barrios );
            $excludedIdBarrios = collect($exclude->barrios)->pluck('id_barrio')->toArray();

            
            $arr_group = session('arr_group');
            if(empty($arr_group))
                $arr_group = [];
            //dump($excludedIdBarrios);
            $valorComparacion = $propuesta[0]->cobertura_suma;
            $barrios_ = barrio::select('id') 
                ->whereNotNull('suma_muerte')
                ->where('suma_muerte', '<=', $valorComparacion)
                ->whereNotIn('id', $excludedIdBarrios)
                ->get();

            //dump($barrios_);
            $barr = [];
            foreach ($barrios_ as $key => $value) {
                $barr[] = $value->id;
            }
            
            //dump( implode(",", $barr ));
            $grupos = GruposBarrio::
            whereIn('idbarrio', $barr)
            ->groupBy('id')
            ->orderBy('nombre','asc')
            ->whereNotIn('id',$arr_group)
            ->get();
            
            $barrios = barrio::where('nombre', 'LIKE', "%$request->search%")->orWhere('id',$request->search)->where('suma_muerte', '>=', $valorComparacion)->whereNotIn('id', $excludedIdBarrios)->orderBy('nombre','asc')->latest()->paginate();
            
            return view('propuestas.agregar-barrios', ['gruposbarrios' => $grupos, 'propuesta' => $propuesta[0], 'barrios' => $barrios]);  
        }

        return view('propuestas.agregar-barrios', ['gruposbarrios' => [], 'propuesta' => $propuesta = new Propuesta, 'barrios' => $barrios = [] ]);  

        
    }

    public function agregar_barrios_barrio(Request $request){

        $request->validate([
            'id' => 'required',
            'prefijo' => 'required'
        ]);

        try{
            $data_barrios = Propuesta::where('prefijo', $request->prefijo)->where('idpropuesta',$request->id)->value('data_barrios');
            $suma = Propuesta::where('prefijo', $request->prefijo)->where('idpropuesta',$request->id)->value('cobertura_suma');

            if($data_barrios){

                $savetrue = false;
                $data_barrios =  json_decode($data_barrios);
                $coleccionBarrios = collect($data_barrios->barrios);

                if(isset($request->grupo)){

                    $grupoBarrios = gruposbarrio::where('id',$request->grupo)->groupBy('idbarrio')->get();
//dump($grupoBarrios);                    
                    foreach ($grupoBarrios as $key => $barrio) {
                        $id = $barrio->idbarrio;
                        //dump($id);
                        $estaEnBarrios = $coleccionBarrios->contains(function ($barrio) use ($id) {
                            return $barrio->id_barrio == $id;
                        });    
                        //dump($estaEnBarrios);
                        if($estaEnBarrios == false){
                            
                            $nuevobarrio = $this->validateBarrio((double)$suma,$id);
                            //dump($nuevobarrio);
                            if( is_array( $nuevobarrio )){
                                $data_barrios->barrios[] = (object)$nuevobarrio;    
                                
                                $savetrue = true;
                            }
                            
                            $arr_group = session('arr_group');
                            $arr_group[] = $request->grupo;
                            session(['arr_group' => $arr_group ]);
                            
                        }
                    }

                    
                }
                //dd($savetrue);
                
                if(isset($request->cuit)){
                    $id = $request->cuit;
                    $estaEnBarrios = $coleccionBarrios->contains(function ($barrio) use ($id) {
                        return $barrio->id_barrio == $id;
                    });
                    
                    if($estaEnBarrios == false){
                        
                        $nuevobarrio = $this->validateBarrio((double)$suma,$id);
                        
                        if( is_array( $nuevobarrio )){
                            $data_barrios->barrios[] = (object)$nuevobarrio;    
                            $savetrue = true;
                        }else if($nuevobarrio == 2){
                            return redirect()->route("agregar_barrios", ['prefijo' => $request->prefijo , 'idpropuesta' => $request->id, 'error_cuit' => 2, 'cuit' => $request->cuit]);            
                        }else{
                            return redirect()->route("agregar_barrios", ['prefijo' => $request->prefijo , 'idpropuesta' => $request->id, 'error_cuit' => "El CUIT $request->cuit no existe", 'cuit' => $request->cuit]);            
                        }
                        
                    }else{
                        return redirect()->route("agregar_barrios", ['prefijo' => $request->prefijo , 'idpropuesta' => $request->id, 'error_cuit' => "El CUIT $request->cuit ya se encuentra entre las claúsulas de tu propuesta", 'cuit' => $request->cuit]);        
                    }
                }
                
                if($savetrue){
                    Propuesta::where('prefijo', $request->prefijo)
                    ->where('idpropuesta', $request->id)
                    ->update(['data_barrios' => json_encode($data_barrios)]);

                    if(isset($request->grupo)){
                        return redirect()->route("agregar_barrios", ['prefijo' => $request->prefijo , 'idpropuesta' => $request->id, 'success_grupo' => $request->grupo]);        
                    }
                    if(isset($request->cuit))
                        return redirect()->route("agregar_barrios", ['prefijo' => $request->prefijo , 'idpropuesta' => $request->id, 'success_barrio' => $request->cuit]);        

                }else{
                    return redirect()->route("agregar_barrios", ['prefijo' => $request->prefijo , 'idpropuesta' => $request->id, 'error_grupo' => 'No se ha agregado ningún barrio con el grupo seleccionado']);        
                }
                
            }
            
            return redirect()->route("agregar_barrios", ['prefijo' => $request->prefijo , 'idpropuesta' => $request->id]);  

        }catch (Exception $ex){
            $error = new logs();
            $error->saveerror($ex->getMessage(), "", "", "ADDBARRIO 101");
            return redirect()->route("polizas");
        }
        

    }


    public function validateBarrio($suma, $idBarrio){
        $aplica = barrio::where('id',$idBarrio)->where('suma_muerte','<=',$suma)->first();
        
        if($aplica){
            $nuevobarrio = [
                "id" => null,
                "id_propuesta" => null,
                "id_barrio" => $aplica->id."",
                "nombre" => $aplica->nombre,
                "ultmod" => null,
                "user_edit" => null,
                "codestado" => null,
                "prefijo" => null,
                "idprefijo" => null,
                "codempresa" => null,
                "sumamuerte" => $aplica->suma_muerte,
                "sumagm" => $aplica->suma_gm,
                "email" => $aplica->email
            ];
            return $nuevobarrio;
        }

        $aplica = barrio::where('id',$idBarrio)->where('suma_muerte','>',$suma)->first();
        if($aplica){
            return 2;
        }

        return FALSE;
    }

    
}
