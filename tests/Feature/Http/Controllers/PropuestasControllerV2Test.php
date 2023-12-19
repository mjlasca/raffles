<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\barrio;
use App\Models\gruposbarrio;
use App\Models\Propuesta;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PropuestasControllerV2Test extends TestCase
{
    use WithFaker;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_agregar_barrios()
    {
        $data = [
            'prefijo' => 'A',
            'idpropuesta' => $this->faker->numberBetween(40133,40150)
        ];

        $propuesta = Propuesta::where('prefijo', $data['prefijo'])->where('idpropuesta',$data['idpropuesta'])->get();

        $exclude = json_decode( $propuesta[0]->data_barrios );
        $excludedIdBarrios = collect($exclude->barrios)->pluck('id_barrio')->toArray();

        $valorComparacion = $propuesta[0]->cobertura_suma;
        $grupos = GruposBarrio::whereIn('idbarrio', function ($query) use ($valorComparacion) {
            $query->select('id')
                ->from('barrios')
                ->where('suma_muerte', '>=', $valorComparacion);
        })
        ->whereNotIn('idbarrio', $excludedIdBarrios)
        ->groupBy('id')
        ->first();
        

        $barrios = barrio::where('nombre', 'LIKE', "%%")->orWhere('id',null)->where('suma_muerte', '>=', $valorComparacion)->whereNotIn('id', $excludedIdBarrios)->orderBy('nombre','asc')->first();

        $this
            ->get("/propuesta/agregar-barrios/".$data['prefijo']."/".$data['idpropuesta'])
            ->assertStatus(200)
            ->assertSee($grupos->id)
            ->assertSee($barrios->id)
            ->assertSee($propuesta[0]->prefijo."-".$propuesta[0]->idpropuesta);
            
    }

    public function test_agregar_barrios_barrio()
    {
        $data = [];

        $this
            ->put('agregar_barrios_barrio', $data)
            ->assertStatus(302);
            
    }
}
