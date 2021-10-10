<?php

namespace Tests\Feature\movies;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    /**
     * create movie
     * @return  void
     */
    private function createMovie(): void {
        $randomNum = random_int(1,100);
        $this->withHeaders(['Authorization'=> 'Bearer '.$this->userLogin()])->json('POST', 'api/movies', [
            'movie_name' => 'movie test '.$randomNum,
            'movie_duration' => 120,
            'release_date' => 2020
        ]);
    }


    /**
     * can fetch a list of movies
     *
     * @test
     */
    
    public function fetch_list_of_movies()
    {
        $this->createMovie();
        $this->createMovie();
        $this->createMovie();

        $response = $this->withHeaders(['Authorization'=> 'Bearer '.$this->userLogin()])->json('GET', 'api/movies');
        $response->assertStatus(201);

        $length = count($response->decodeResponseJson()['data']);
        
        $this->assertTrue($length == 3);
    }
}
