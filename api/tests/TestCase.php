<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\Models\User;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function userLogin() {
    	$user = User::factory()->create();
    	$credentials = [
    		'email' => $user->email,
    		'password' => 'password'
    	];
    	$token = auth()->attempt($credentials);
    	return $token;
    }
}
