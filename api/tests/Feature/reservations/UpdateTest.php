<?php

namespace Tests\Feature\reservations;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;



    /**
     * if has a route handler listening to /api/reservations for PATCH requests
     *
     * @test
     */
    public function if_listen_to_request()
    {
        $response = $this->json('PATCH', 'api/reservations/-1', []);
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
        $response = $this->withHeaders(['Authorization'=> 'Bearer '.$this->userLogin()])->json('PUT', 'api/reservations/-1', []);
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
        $response = $this->json('PUT', 'api/reservations/1', []);
       
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
            ->json('PATCH', 'api/reservations/1', [
                'employee_id'=> 23,
                'show_time_id' => 'dsc',
                'seats' => [1,2],
                'total_cost' => 100
            ])
            ->assertStatus(403);

        //invalid seats
        $this
            ->withHeaders(['Authorization'=> 'Bearer '.$this->userLogin()])
            ->json('PATCH', 'api/reservations/1', [
                 'employee_id'=> 23,
                'show_time_id' => 32,
                'seats' => 'sdc',
                'total_cost' => 100
            ])
            ->assertStatus(403);

        //invalid pass total_cost
        $this
            ->withHeaders(['Authorization'=> 'Bearer '.$this->userLogin()])
            ->json('PATCH', 'api/reservations/1', [
                'employee_id'=> 23,
                'show_time_id' => 43,
                'seats' => [1,2],
                'total_cost' => 'd'
            ])
            ->assertStatus(403);

    }

    
    /**
     * creates movie with valid inputs
     *
     * @test
     */
    public function updates_movie_with_valid_inputs()
    {
        $reservation = \App\Models\Reservation::factory()->create();

        $seat1 = \App\Models\Seat::factory()->create();
        // update movie
        $response = $this->withHeaders(['Authorization'=> 'Bearer '.$this->userLogin()])->json('PATCH', 'api/reservations/'.$reservation->id, [
            'show_time_id' => $reservation->show_time_id,
            'seats' => [$seat1->id],
            'total_cost' => 205
        ]);
        $response->assertStatus(201);
    }
}
