<?php

namespace Tests\Feature\showTimes;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;



    /**
     * if has a route handler listening to /api/showTimes for PATCH requests
     *
     * @test
     */
    public function if_listen_to_request()
    {
        $response = $this->json('PATCH', 'api/showTimes/-1', []);
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
        $response = $this->withHeaders(['Authorization'=> 'Bearer '.$this->userLogin()])->json('PUT', 'api/showTimes/-1', []);
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
        $response = $this->json('PUT', 'api/showTimes/1', []);
       
        $response->assertStatus(401);
    }

    
    /**
     * return a error if user the provided invalid inputs
     *
     * @test
     */
    public function return_403_if_user_provided_invalid_inputs()
    {
        //invalid date
        $this
            ->withHeaders(['Authorization'=> 'Bearer '.$this->userLogin()])
            ->json('PATCH', 'api/showTimes/1', [
                'date' => "dgf",
                "start_time" => "14:30",
                "end_time" => "15:45",
                "movie_id" => 1,
                "hall_id" => 1
            ])
            ->assertStatus(403);

        //invalid start time
        $this
            ->withHeaders(['Authorization'=> 'Bearer '.$this->userLogin()])
            ->json('PATCH', 'api/showTimes/1', [
                'date' => '2020-10-09',
                "start_time" => "0",
                "end_time" => "15:45",
                "movie_id" => 1,
                "hall_id" => 1
            ])
            ->assertStatus(403);

        //invalid end time
        $this
            ->withHeaders(['Authorization'=> 'Bearer '.$this->userLogin()])
            ->json('PATCH', 'api/showTimes/1', [
                'date' => '2020-10-09',
                "start_time" => "15:45",
                "end_time" => "",
                "movie_id" => 1,
                "hall_id" => 1
            ])
            ->assertStatus(403);

        //invalid pass movie id
        $this
            ->withHeaders(['Authorization'=> 'Bearer '.$this->userLogin()])
            ->json('PATCH', 'api/showTimes/1', [
                'date' => '2020-10-09',
                "start_time" => "15:45",
                "end_time" => "16:45",
                "movie_id" => -1,
                "hall_id" => 1
            ])
            ->assertStatus(403);

        //invalid pass movie id
        $this
            ->withHeaders(['Authorization'=> 'Bearer '.$this->userLogin()])
            ->json('PATCH', 'api/showTimes/1', [
                'date' => '2020-10-09',
                "start_time" => "15:45",
                "end_time" => "",
                "movie_id" => 1,
                "hall_id" => -1
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
        $showTime = \App\Models\ShowTime::factory()->create();

        // update movie
        $response = $this->withHeaders(['Authorization'=> 'Bearer '.$this->userLogin()])->json('PATCH', 'api/showTimes/'.$showTime->id, [
            'date' => now()->addDay()->toFormattedDAteString(),
            'start_time'=> '16:30',
            'end_time' => '17:00',
            'movie_id' => $showTime->movie->id,
            'hall_id' => $showTime->hall->id
        ]);
        $response->assertStatus(201);
    }
}
