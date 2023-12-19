<?php

namespace Database\Factories;

use App\Models\barrio;
use App\Models\gruposbarrio;
use Illuminate\Database\Eloquent\Factories\Factory;

class gruposbarrioFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = gruposbarrio::class;

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
            'idbarrio' => barrio::factory(),
            'nombrebarrio' => $this->faker->word,
        ];
    }
}
