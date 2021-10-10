<?php

namespace Tests\Feature\reservations;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;
    /**
     * can fetch a list of show times
     *
     * @test
     */
    
    public function fetch_list_of_show_times()
    {
        \App\Models\Reservation::factory()->count(3)->create();

        $response = $this->withHeaders(['Authorization'=> 'Bearer '.$this->userLogin()])->json('GET', 'api/reservations');
        $response->assertStatus(201);

        $length = count($response->decodeResponseJson()['data']);
        
        $this->assertTrue($length == 3);
    }
}
