<?php

namespace Tests\Feature\seats;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowTest extends TestCase
{
    use RefreshDatabase;

    /**
     * show 4040 is the seat is not found
     *
     * @test
     */
    public function if_seat_not_found()
    {
        $reponse = $this->withHeaders(['Authorization'=> 'Bearer '.$this->userLogin()])->json('GET', 'api/seats/-1');
        $reponse->assertStatus(404);
    }

    /**
     * returns the seat if the seat is found
     *
     * @test
     */
    public function if_seat_found()
    {
        $hall = \App\Models\Hall::factory()->create();
        $seatData = [
            'seat_code' => 'seat test',
            'hall_id' => $hall->id
        ];
        $seatRes = $this->withHeaders(['Authorization'=> 'Bearer '.$this->userLogin()])->json('POST', 'api/seats', $seatData);
        $seatRes->assertStatus(201);

        $seatId = $seatRes->decodeResponseJson()['data']['id'];

        $reponse = $this->withHeaders(['Authorization'=> 'Bearer '.$this->userLogin()])->json('GET', 'api/seats/'.$seatId);
        $reponse
           ->assertStatus(201)
           ->assertJsonStructure(
                [
                    'data' => ['id', 'seat_code', 'hall' => ['id', 'hall_name', 'price', 'created_at']]
                ]   
            )
           ->assertJson([
                'data' => [
                    'seat_code' => $seatData['seat_code'],
                    'hall' => [
                        'id' => $hall->id,
                        'hall_name' => $hall->hall_name,
                        'price' => $hall->price
                    ]
                ]
           ]);
    }
}
