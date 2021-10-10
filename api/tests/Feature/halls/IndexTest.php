<?php

namespace Tests\Feature\halls;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    /**
     * create hall
     * @return  void
     */
    private function createHall(): void {
        $randomNum = random_int(1,100);
        $this->withHeaders(['Authorization'=> 'Bearer '.$this->userLogin()])->json('POST', 'api/halls', [
            'hall_name' => 'hall test '.$randomNum,
            'price' => 100
        ]);
    }


    /**
     * can fetch a list of halls
     *
     * @test
     */
    
    public function fetch_list_of_halls()
    {
        $this->createHall();
        $this->createHall();
        $this->createHall();

        $response = $this->withHeaders(['Authorization'=> 'Bearer '.$this->userLogin()])->json('GET', 'api/halls');
        $response->assertStatus(201);

        $length = count($response->decodeResponseJson()['data']);
        $this->assertTrue($length == 3);
    }
}
