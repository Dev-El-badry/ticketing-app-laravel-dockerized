<?php

namespace Database\Factories;

use App\Models\Reservation;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReservationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Reservation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user = \App\Models\User::factory()->create();
        $showTime = \App\Models\ShowTime::factory()->create();

        return [
            'employee_id' => $user->id,
            'show_time_id' => $showTime->id,
            'num_of_seats' => random_int(0,9),
            'total_cost' => 100
        ];
    }
}
