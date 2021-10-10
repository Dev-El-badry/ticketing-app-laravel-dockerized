<?php

namespace Tests\Feature\movies;

use Illuminate\Foundation\Testing\RefreshDatabase;

use Tests\TestCase;

class CreateTest extends TestCase
{
    use RefreshDatabase;
    private $targetUrl = 'api/movies';

    /**
     * if has a route handler listening to /api/movies for post requests
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
     * return a error if an invalid movie name provided
     *
     * @test
     */
    public function if_invalid_name_provided()
    {
        $data = [
            'movie_name' => 'mo',
            'movie_duration' => 100,
            'release_date' => 2000
        ];
        $response = $this->withHeaders(['Authorization'=> 'Bearer '.$this->userLogin()])->json('POST', $this->targetUrl, $data);
        $response->assertStatus(403);
    }

    /**
     * return a error if an invalid movie duration provided
     *
     * @test
     */
    public function if_invalid_duration_provided()
    {
        $data = [
            'movie_name' => 'movie test',
            'release_date' => 2000
        ];
        $response = $this->withHeaders(['Authorization'=> 'Bearer '.$this->userLogin()])->json('POST', $this->targetUrl, $data);
        $response->assertStatus(403);
    }

    /**
     * return a error if an invalid released date provided
     *
     * @test
     */
    public function if_invalid_release_date_provided()
    {
        $data = [
            'movie_name' => 'movie test',
            'movie_duration' => 100
        ];
        $response = $this->withHeaders(['Authorization'=> 'Bearer '.$this->userLogin()])->json('POST', $this->targetUrl, $data);
        $response->assertStatus(403);
    }

    /**
     * creates movie with valid inputs
     *
     * @test
     */
    public function creates_movie_with_valid_inputs()
    {
        $data = [
            'movie_name' => 'movie test',
            'release_date'=> 2000,
            'movie_duration' => 100
        ];
        $response = $this->withHeaders(['Authorization'=> 'Bearer '.$this->userLogin()])->json('POST', $this->targetUrl, $data);
        $response->assertStatus(201);
        
        
       $response->assertJsonStructure(
            [
                'data' => ['id', 'movie_name', 'movie_duration', 'release_date']
            ]   
        )
       ->assertJson([
            'data' => [
                'movie_name' => $data['movie_name'],
                'movie_duration' => $data['movie_duration'],
                'release_date' => $data['release_date']
            ]
       ]);

    }

}
