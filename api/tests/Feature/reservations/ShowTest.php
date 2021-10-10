<?php

namespace Tests\Feature\reservations;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowTest extends TestCase
{
    use RefreshDatabase;

    /**
     * show 4040 is the reservation is not found
     *
     * @test
     */
    public function if_show_time_not_found()
    {
        $reponse = $this->withHeaders(['Authorization'=> 'Bearer '.$this->userLogin()])->json('GET', 'api/reservations/-1');
        $reponse->assertStatus(404);
    }

    /**
     * returns the reservation if the reservation is found
     *
     * @test
     */
    public function if_show_time_found()
    {
        $reservation = \App\Models\Reservation::factory()->create();

        $reponse = $this->withHeaders(['Authorization'=> 'Bearer '.$this->userLogin()])->json('GET', 'api/reservations/'.$reservation->id);
        $reponse
            ->assertStatus(201)
             ->assertJsonStructure(
                [
                    'data' => [
                        'id', 
                        'receptionist'=> ['name', 'email', 'created_at'], 
                        'show_time'=> [
                            'id', 
                            'date', 
                            'start_time', 
                            'end_time', 
                            "created_at",
                            'movie' => [
                                "id",
                                "movie_name",
                                "movie_duration",
                                "release_date",
                                "created_at"
                            ],
                            "hall" => [
                                "id",
                                "hall_name",
                                "price",
                                "created_at"
                            ]
                        ],
                        "num_of_seats",
                        "total_cost",
                        "created_at"
                    ]
                ]   
            );
    }
}
