<?php

namespace Database\Factories;

use App\Models\Seat;
use Illuminate\Database\Eloquent\Factories\Factory;

class SeatFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Seat::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $hall = \App\Models\Hall::factory()->create();
        return [
            'seat_code' => $this->faker->name()[0] . random_int(1,100),
            'hall_id'=> $hall->id
        ];
    }
}
