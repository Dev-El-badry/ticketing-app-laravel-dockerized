<?php

namespace Tests\Feature\halls;

use Illuminate\Foundation\Testing\RefreshDatabase;

use Tests\TestCase;

class CreateTest extends TestCase
{
    use RefreshDatabase;
    private $targetUrl = 'api/halls';

    /**
     * if has a route handler listening to /api/halls for post requests
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
     * return a error if an invalid hall name provided
     *
     * @test
     */
    public function if_invalid_name_provided()
    {
        $data = [
            'hall_name' => 'm'
        ];
        $response = $this->withHeaders(['Authorization'=> 'Bearer '.$this->userLogin()])->json('POST', $this->targetUrl, $data);
        $response->assertStatus(403);
    }

    /**
     * return a error if an invalid price provided
     *
     * @test
     */
    public function if_invalid_price_provided()
    {
        $data = [
            'hall_name' => 'm1',
            'price' => 0
        ];
        $response = $this->withHeaders(['Authorization'=> 'Bearer '.$this->userLogin()])->json('POST', $this->targetUrl, $data);
        $response->assertStatus(403);
    }


    /**
     * creates hall with valid inputs
     *
     * @test
     */
    public function creates_hall_with_valid_inputs()
    {
        $data = [
            'hall_name' => 'hall test',
            'price' => 100
        ];
        $response = $this->withHeaders(['Authorization'=> 'Bearer '.$this->userLogin()])->json('POST', $this->targetUrl, $data);
        $response->assertStatus(201);
        
        
       $response->assertJsonStructure(
            [
                'data' => ['id', 'hall_name', 'price']
            ]   
        )
       ->assertJson([
            'data' => [
                'hall_name' => $data['hall_name'],
                'price' => $data['price']
            ]
       ]);

    }

}
