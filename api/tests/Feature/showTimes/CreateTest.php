<?php

namespace Tests\Feature\showTimes;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateTest extends TestCase
{
    use RefreshDatabase;
    private $targetUrl = 'api/showTimes';

    /**
     * if has a route handler listening to /api/showTimes for post requests
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
        //invalid date
        $this
            ->withHeaders(['Authorization'=> 'Bearer '.$this->userLogin()])
            ->json('POST', $this->targetUrl, [
                'date' => now()->subDay()->toFormattedDAteString(),
                "start_time" => "14:30",
                "end_time" => "15:45",
                "movie_id" => 1,
                "hall_id" => 1
            ])
            ->assertStatus(403);

        //invalid start time
        $this
            ->withHeaders(['Authorization'=> 'Bearer '.$this->userLogin()])
            ->json('POST', $this->targetUrl, [
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
            ->json('POST', $this->targetUrl, [
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
            ->json('POST', $this->targetUrl, [
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
            ->json('POST', $this->targetUrl, [
                'date' => '2020-10-09',
                "start_time" => "15:45",
                "end_time" => "",
                "movie_id" => 1,
                "hall_id" => -1
            ])
            ->assertStatus(403);

    }


    /**
     * creates showTime with valid inputs
     *
     * @test
     */
    public function creates_showTime_with_valid_inputs()
    {
        $movie = \App\Models\Movie::factory()->create();
        $hall = \App\Models\Hall::factory()->create();

        $timeNow = now()->toFormattedDateString();
        $response = $this->withHeaders(['Authorization'=> 'Bearer '.$this->userLogin()])->json('POST', $this->targetUrl, [
                'date' => $timeNow,
                "start_time" => "14:30",
                "end_time" => "15:45",
                "movie_id" => $movie->id,
                "hall_id" => $hall->id
            ]);
        $response->assertStatus(201);
        
        
       $response
           ->assertJsonStructure(
                [
                    'data' => ['id', 'date', 'start_time', 'end_time', 'created_at', 'hall' => ['id', 'hall_name', 'created_at'], 'movie' => ['id', 'movie_name', 'movie_duration', 'release_date', 'created_at']]
                ]   
            )
           ->assertJson([
                'data' => [
                    'date' => $timeNow,
                    'start_time' => '14:30',
                    "end_time" => "15:45",
                    'hall' => ['id' => 1],
                    'movie' => ['id' => 1]
                ]
           ]);

    }

}
