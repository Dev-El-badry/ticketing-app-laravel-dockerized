<?php

namespace Tests\Feature\seats;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Seat;

class UpdateTest extends TestCase
{
    use RefreshDatabase;



    /**
     * if has a route handler listening to /api/seats for PATCH requests
     *
     * @test
     */
    public function if_listen_to_request()
    {
        $response = $this->json('PATCH', 'api/seats/-1', []);
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
        $response = $this->withHeaders(['Authorization'=> 'Bearer '.$this->userLogin()])->json('PUT', 'api/seats/-1', []);
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
        $response = $this->json('PUT', 'api/seats/1', []);
       
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
        $response = $this->withHeaders(['Authorization'=> 'Bearer '.$this->userLogin()])->json('PATCH', 'api/seats/-1', $data);
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
        $response = $this->withHeaders(['Authorization'=> 'Bearer '.$this->userLogin()])->json('PATCH', 'api/seats/-1', $data);
        $response->assertStatus(403);
    }

    /**
     * creates seat with valid inputs
     *
     * @test
     */
    public function updates_seat_with_valid_inputs()
    {
        $hall = \App\Models\Hall::factory()->create();
        //create seat
        $seatRes = $this->withHeaders(['Authorization'=> 'Bearer '.$this->userLogin()])->json('POST', 'api/seats', [
            'seat_code' => 'beauty seat',
            'hall_id' => $hall->id
        ]);

        $seat_id = $seatRes->decodeResponseJson()['data']['id'];

        // update seat
        $response = $this->withHeaders(['Authorization'=> 'Bearer '.$this->userLogin()])->json('PATCH', 'api/seats/'.$seat_id, [
            'seat_code' => 'seat test',
            'hall_id' => $hall->id
        ]);
        $response->assertStatus(201);
    }
}
