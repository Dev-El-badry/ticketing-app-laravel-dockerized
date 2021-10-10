<?php

namespace Tests\Feature\movies;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Movie;

class UpdateTest extends TestCase
{
    use RefreshDatabase;



    /**
     * if has a route handler listening to /api/movies for PATCH requests
     *
     * @test
     */
    public function if_listen_to_request()
    {
        $response = $this->json('PATCH', 'api/movies/-1', []);
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
        $response = $this->withHeaders(['Authorization'=> 'Bearer '.$this->userLogin()])->json('PUT', 'api/movies/-1', []);
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
        $response = $this->json('PUT', 'api/movies/1', []);
       
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
        $response = $this->withHeaders(['Authorization'=> 'Bearer '.$this->userLogin()])->json('PATCH', 'api/movies/-1', $data);
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
        $response = $this->withHeaders(['Authorization'=> 'Bearer '.$this->userLogin()])->json('PATCH', 'api/movies/-1', $data);
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
        $response = $this->withHeaders(['Authorization'=> 'Bearer '.$this->userLogin()])->json('PATCH', 'api/movies/-1', $data);
        $response->assertStatus(403);
    }

    /**
     * creates movie with valid inputs
     *
     * @test
     */
    public function updates_movie_with_valid_inputs()
    {
        //create movie
        $movieRes = $this->withHeaders(['Authorization'=> 'Bearer '.$this->userLogin()])->json('POST', 'api/movies', [
            'movie_name' => 'beauty movie',
            'movie_duration' => 120,
            'release_date' => 2002
        ]);

        $movie_id = $movieRes->decodeResponseJson()['data']['id'];

        // update movie
        $response = $this->withHeaders(['Authorization'=> 'Bearer '.$this->userLogin()])->json('PATCH', 'api/movies/'.$movie_id, [
            'movie_name' => 'movie test',
            'release_date'=> 2000,
            'movie_duration' => 100
        ]);
        $response->assertStatus(201);
    }
}
