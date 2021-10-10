<?php

namespace Tests\Feature\reservations;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateTest extends TestCase
{
    use RefreshDatabase;
    private $targetUrl = 'api/reservations';

    /**
     * if has a route handler listening to /api/reservations for post requests
     *
     * @test
     */
    public function if_listen_to_request()
    {
        $response = $this->json('POST', $this->targetUrl, []);
        $status = (int)$response->status() != 404 ? true : false;

        $this->assertTrue($status);
    }

    /**
     * can only be accessed it the user is signed in
     *
     * @test
     */
    public function must_user_signed_in()
    {
        $response = $this->withHeaders(['Authorization'=> 'Bearer '.$this->userLogin()])->json('POST', $this->targetUrl, []);
        $status = (int)$response->status() != 401 ? true : false;
        $this->assertTrue($status);
    }

    /**
     * return a status other than 401 if the user is signed in
     *
     * @test
     */
    public function chk_if_user_not_signed_in()
    {
        $response = $this->json('POST', $this->targetUrl, []);
       
        $response->assertStatus(401);
    }

    /**
     * return a error if user the provided invalid inputs
     *
     * @test
     */
    public function return_403_if_user_provided_invalid_inputs()
    {
       
        //invalid show_time_id
        $this
            ->withHeaders(['Authorization'=> 'Bearer '.$this->userLogin()])
            ->json('POST', $this->targetUrl, [
                'employee_id'=> 23,
                'show_time_id' => 'dsc',
                'seats' => [1,2],
                'total_cost' => 100
            ])
            ->assertStatus(403);

        //invalid seats
        $this
            ->withHeaders(['Authorization'=> 'Bearer '.$this->userLogin()])
            ->json('POST', $this->targetUrl, [
                 'employee_id'=> 23,
                'show_time_id' => 32,
                'seats' => 'sdc',
                'total_cost' => 100
            ])
            ->assertStatus(403);

        //invalid pass total_cost
        $this
            ->withHeaders(['Authorization'=> 'Bearer '.$this->userLogin()])
            ->json('POST', $this->targetUrl, [
                'employee_id'=> 23,
                'show_time_id' => 43,
                'seats' => [1,2],
                'total_cost' => 'd'
            ])
            ->assertStatus(403);
    }


    /**
     * creates reservation with valid inputs
     *
     * @test
     */
    public function creates_reservation_with_valid_inputs()
    {
        
        $showTime = \App\Models\ShowTime::factory()->create();

        $seat1 = \App\Models\Seat::factory()->create();
        $seat2 = \App\Models\Seat::factory()->create();

        $timeNow = now()->toFormattedDateString();
        $response = $this->withHeaders(['Authorization'=> 'Bearer '.$this->userLogin()])->json('POST', $this->targetUrl, [
                'show_time_id' => $showTime->id,
                'seats' => [$seat1->id, $seat2->id],
                'total_cost' => 200
            ]);
        $response->assertStatus(201);
        
        
       $response
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
            )
           ->assertJson([
                'data' => [ 
                    'show_time'=> [
                        'id'=> $showTime->id, 
                        'date'=> $showTime->date, 
                        'start_time'=> $showTime->start_time, 
                        'end_time'=> $showTime->end_time, 
                        // "created_at"=> $showTime->created_at,
                        'movie' => [
                            "id"=> $showTime->movie->id,
                            "movie_name"=> $showTime->movie->movie_name,
                            "movie_duration"=> $showTime->movie->movie_duration,
                            "release_date"=> $showTime->movie->release_date
                        ],
                        "hall" => [
                            "id"=> $showTime->hall->id,
                            "hall_name"=> $showTime->hall->hall_name,
                            "price"=> $showTime->hall->price
                        ]
                    ],
                    "num_of_seats"=>2,
                    "total_cost"=>200
                ]
           ]);

    }

}