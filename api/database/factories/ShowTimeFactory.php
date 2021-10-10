<?php

namespace Database\Factories;

use App\Models\ShowTime;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShowTimeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ShowTime::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $movie = \App\Models\Movie::factory()->create();
        $hall = \App\Models\Hall::factory()->create();
        return [
            'date' => now()->addDay()->toFormattedDateString(),
            'start_time' => '12:30',
            'end_time' => '15:30',
            'movie_id' => $movie->id,
            'hall_id' => $hall->id
        ];
    }
}
