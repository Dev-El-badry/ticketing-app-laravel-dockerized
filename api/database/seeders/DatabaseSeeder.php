<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
use Illuminate\Support\Str;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
    	$baseDir = __DIR__.'/../constants/';
    	$tables = require($baseDir.'tables.php');

    	foreach ($tables as $key => $value) {
    		DB::table($value)->delete();
    	}

    	//create user
        \App\Models\User::create(
        	[
        		'name' => 'super user',
	            'email' => 'user@null.com',
	            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
	            'remember_token' => Str::random(10),
        	]
        );

        //create movies
        $movies = require($baseDir.'movies.php');
        DB::table('movies')->insert($movies);

        //create halls
        $halls = require($baseDir.'halls.php');
        DB::table('halls')->insert($halls);

        //create halls
        $seats = require($baseDir.'seats.php');
        DB::table('seats')->insert($seats);

        //create halls
        $show_times = require($baseDir.'showTimes.php');
        DB::table('show_times')->insert($show_times);
    }
}
