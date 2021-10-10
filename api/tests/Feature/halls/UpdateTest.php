<?php

namespace Tests\Feature\halls;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Hall;

class UpdateTest extends TestCase
{
    use RefreshDatabase;



    /**
     * if has a route handler listening to /api/halls for PATCH requests
     *
     * @test
     */
    public function if_listen_to_request()
    {
        $response = $this->json('PATCH', 'api/halls/-1', []);
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
        $response = $this->withHeaders(['Authorization'=> 'Bearer '.$this->userLogin()])->json('PUT', 'api/halls/-1', []);
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
        $response = $this->json('PUT', 'api/halls/1', []);
       
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
        $response = $this->withHeaders(['Authorization'=> 'Bearer '.$this->userLogin()])->json('PATCH', 'api/halls/-1', $data);
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
        $response = $this->withHeaders(['Authorization'=> 'Bearer '.$this->userLogin()])->json('PATCH', 'api/halls/-1', $data);
        $response->assertStatus(403);
    }

    /**
     * creates hall with valid inputs
     *
     * @test
     */
    public function updates_hall_with_valid_inputs()
    {
        //create hall
        $hallRes = $this->withHeaders(['Authorization'=> 'Bearer '.$this->userLogin()])->json('POST', 'api/halls', [
            'hall_name' => 'beauty hall',
            'price' => 100
        ]);

        $hall_id = $hallRes->decodeResponseJson()['data']['id'];

        // update hall
        $response = $this->withHeaders(['Authorization'=> 'Bearer '.$this->userLogin()])->json('PATCH', 'api/halls/'.$hall_id, [
            'hall_name' => 'hall test',
            'price' => 100
        ]);
        $response->assertStatus(201);
    }
}
