<?php

namespace Tests\Feature\seats;

use Illuminate\Foundation\Testing\RefreshDatabase;

use Tests\TestCase;

class CreateTest extends TestCase
{
    use RefreshDatabase;
    private $targetUrl = 'api/seats';

    /**
     * if has a route handler listening to /api/seats for post requests
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
     * return a error if an invalid seat name provided
     *
     * @test
     */
    public function if_invalid_name_provided()
    {
        $data = [
            'seat_code' => 'm'
        ];
        $response = $this->withHeaders(['Authorization'=> 'Bearer '.$this->userLogin()])->json('POST', $this->targetUrl, $data);
        $response->assertStatus(403);
    }

    /**
     * return a error if an invalid hall_id provided
     *
     * @test
     */
    public function if_invalid_hall_id_provided()
    {
        $data = [
            'seat_code' => 'm1',
            'hall_id' => 0
        ];
        $response = $this->withHeaders(['Authorization'=> 'Bearer '.$this->userLogin()])->json('POST', $this->targetUrl, $data);
        $response->assertStatus(403);
    }


    /**
     * creates seat with valid inputs
     *
     * @test
     */
    public function creates_seat_with_valid_inputs()
    {
        $hall = \App\Models\Hall::factory()->create();

        $data = [
            'seat_code' => 'seat test',
            'hall_id' => $hall->id
        ];
        $response = $this->withHeaders(['Authorization'=> 'Bearer '.$this->userLogin()])->json('POST', $this->targetUrl, $data);
        $response->assertStatus(201);
        
        
       $response
           ->assertJsonStructure(
                [
                    'data' => ['id', 'seat_code', 'hall' => ['id', 'hall_name', 'price', 'created_at']]
                ]   
            )
           ->assertJson([
                'data' => [
                    'seat_code' => $data['seat_code'],
                    'hall' => [
                        'id' => $hall->id,
                        'hall_name' => $hall->hall_name,
                        'price' => $hall->price
                    ]
                ]
           ]);

    }

}
