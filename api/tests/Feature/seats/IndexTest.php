<?php

namespace Tests\Feature\seats;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    /**
     * create seat
     * @return  void
     */
    private function createSeat(): void {
        $randomNum = random_int(1,100);
        $hall = \App\Models\Hall::factory()->create();
        $this->withHeaders(['Authorization'=> 'Bearer '.$this->userLogin()])->json('POST', 'api/seats', [
            'seat_code' => 'seat test '.$randomNum,
            'hall_id' => $hall->id
        ]);
    }


    /**
     * can fetch a list of seats
     *
     * @test
     */
    
    public function fetch_list_of_seats()
    {
        $this->createSeat();
        $this->createSeat();
        $this->createSeat();

        $response = $this->withHeaders(['Authorization'=> 'Bearer '.$this->userLogin()])->json('GET', 'api/seats');
        $response->assertStatus(201);

        $length = count($response->decodeResponseJson()['data']);
        $this->assertTrue($length == 3);
    }
}
