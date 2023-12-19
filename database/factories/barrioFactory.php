<?php

namespace Database\Factories;

use App\Models\barrio;
use Illuminate\Database\Eloquent\Factories\Factory;

class barrioFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = barrio::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => $this->faker->phoneNumber,
            'nombre' => $this->faker->word,
            'telefono' => $this->faker->numberBetween(00000000000,999999999),
            'direccion' => $this->faker->text(60),
            'email' => $this->faker->email,
            'sub_barrio' => $this->faker->numberBetween(0,10000000),
            'clase_barrio' => $this->faker->numberBetween(0,10000000),
            'suma_muerte' => $this->faker->numberBetween(0,10000000),
            'suma_gm' => $this->faker->numberBetween(0,10000000),
            'suma_rc' => $this->faker->numberBetween(0,10000000),
            'exige' => $this->faker->numberBetween(0,10000000),
            'observaciones' => $this->faker->text(200),
            'ultmod' => $this->faker->date,

        ];
    }
}
