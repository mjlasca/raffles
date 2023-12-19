<?php

namespace App\Http\Controllers;

use App\Models\cliente;
use Illuminate\Http\Request;

class clienteController extends Controller
{
    //
    public function consultaparametros()
    {
        $req = request()->all();


        if ($req["id"] != null) {
            
            $cliente = new cliente();
            
            $cliente->id = $req["documentotomador"];
            $cliente->nombres = $req["nombrestomador"];
            $cliente->apellidos = $req["apellidostomador"];
            $cliente->tipo_id = $req["tipodocumentotomador"];
            $cliente->telefono = $req["telefonotomador"];
            $cliente->direccion = $req["direcciontomador"];
            $cliente->email = $req["emailtomador"];
            $cliente->codpostal = $req["codpostaltomador"];
            $cliente->localidad = $req["localidadtomador"];
            $cliente->ciudad = $req["ciudadtomador"];
            $cliente->sexo = $req["sexotomador"];
            $cliente->fecha_nacimiento = $req["fechanacimientotomador"];
            $cliente->situacion = $req["situaciontomador"];
            $cliente->ultmod = date("Y-m-d H:i:s");
            $cliente->user_edit = "NUBE";
            $cliente->codestado = 1;
            
            if ($cliente->save()) {
                return response()->json("Se ha creado el cliente con éxito", 202);
            } else {
                return response()->json("Hubo un error al crear el cliente", 404);
            }

        }
    }
}
