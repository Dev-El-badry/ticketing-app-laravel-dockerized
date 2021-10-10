<?php

namespace Tests\Feature\showTimes;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowTest extends TestCase
{
    use RefreshDatabase;

    /**
     * show 4040 is the showTime is not found
     *
     * @test
     */
    public function if_show_time_not_found()
    {
        $reponse = $this->withHeaders(['Authorization'=> 'Bearer '.$this->userLogin()])->json('GET', 'api/showTimes/-1');
        $reponse->assertStatus(404);
    }

    /**
     * returns the showTime if the showTime is found
     *
     * @test
     */
    public function if_show_time_found()
    {
        $showTime = \App\Models\ShowTime::factory()->create();

        $reponse = $this->withHeaders(['Authorization'=> 'Bearer '.$this->userLogin()])->json('GET', 'api/showTimes/'.$showTime->id);
        $reponse
            ->assertStatus(201)
            ->assertJsonStructure(
                [
                    'data' => ['id', 'date', 'start_time', 'end_time', 'created_at', 'hall' => ['id', 'hall_name', 'created_at'], 'movie' => ['id', 'movie_name', 'movie_duration', 'release_date', 'created_at']]
                ]   
            )
           ->assertJson([
                'data' => [
                    'date' => $showTime->date,
                    'start_time' => $showTime->start_time,
                    "end_time" => $showTime->end_time,
                    'hall' => ['id' => $showTime->hall->id],
                    'movie' => ['id' => $showTime->movie->id]
                ]
           ]);
    }
}
