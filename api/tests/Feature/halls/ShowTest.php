<?php

namespace Tests\Feature\halls;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowTest extends TestCase
{
    use RefreshDatabase;

    /**
     * show 4040 is the hall is not found
     *
     * @test
     */
    public function if_hall_not_found()
    {
        $reponse = $this->withHeaders(['Authorization'=> 'Bearer '.$this->userLogin()])->json('GET', 'api/halls/-1');
        $reponse->assertStatus(404);
    }

    /**
     * returns the hall if the hall is found
     *
     * @test
     */
    public function if_hall_found()
    {
        $hallData = [
            'hall_name' => 'hall test',
            'price' => 100
        ];
        $hallRes = $this->withHeaders(['Authorization'=> 'Bearer '.$this->userLogin()])->json('POST', 'api/halls', $hallData);
        $hallRes->assertStatus(201);

        $hallId = $hallRes->decodeResponseJson()['data']['id'];

        $reponse = $this->withHeaders(['Authorization'=> 'Bearer '.$this->userLogin()])->json('GET', 'api/halls/'.$hallId);
        $reponse
            ->assertStatus(201)
            ->assertJsonStructure([
                'data' => ['id', 'hall_name', 'price']
            ])
            ->assertJson([
                'data' => $hallData
            ]);
    }
}
