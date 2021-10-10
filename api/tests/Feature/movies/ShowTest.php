<?php

namespace Tests\Feature\movies;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowTest extends TestCase
{
    use RefreshDatabase;

    /**
     * show 4040 is the movie is not found
     *
     * @test
     */
    public function if_movie_not_found()
    {
        $reponse = $this->withHeaders(['Authorization'=> 'Bearer '.$this->userLogin()])->json('GET', 'api/movies/-1');
        $reponse->assertStatus(404);
    }

    /**
     * returns the movie if the movie is found
     *
     * @test
     */
    public function if_movie_found()
    {
        $movieData = [
            'movie_name' => 'movie test',
            'movie_duration' => 140,
            'release_date' => 2007
        ];
        $movieRes = $this->withHeaders(['Authorization'=> 'Bearer '.$this->userLogin()])->json('POST', 'api/movies', $movieData);
        $movieRes->assertStatus(201);

        $movieId = $movieRes->decodeResponseJson()['data']['id'];

        $reponse = $this->withHeaders(['Authorization'=> 'Bearer '.$this->userLogin()])->json('GET', 'api/movies/'.$movieId);
        $reponse
            ->assertStatus(201)
            ->assertJsonStructure([
                'data' => ['id', 'movie_name', 'movie_duration', 'release_date']
            ])
            ->assertJson([
                'data' => $movieData
            ]);
    }
}
