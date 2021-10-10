<?php

namespace Database\Factories;

use App\Models\Hall;
use Illuminate\Database\Eloquent\Factories\Factory;

class HallFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Hall::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'hall_name' => $this->faker->name()[0] . random_int(1, 20),
            'price' => random_int(1, 1000)
        ];
    }
}
