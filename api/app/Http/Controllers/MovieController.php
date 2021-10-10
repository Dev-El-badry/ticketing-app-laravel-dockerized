<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;
use App\Http\Resources\Movies\Movie as MovieResource;
use App\Http\Resources\Movies\MovieCollection;
use Illuminate\Support\Facades\Cache; 
use Validator;

class MovieController extends Controller
{
    /**
     * Create a new MovieController instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $movies = Cache::remember('movies', now()->addMinutes(120), function() {
            return Movie::get();
        });

        return response()->json(['status'=> 'success', 'data'=> new MovieCollection($movies)], 201);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'movie_name'=> 'required|min:3|unique:movies',
            'movie_duration' => 'required|numeric',
            'release_date' => 'required|numeric',
        ]);

        if($validator->fails()) {
            return response()->json(['status'=>'fail', 'errors'=>$validator->errors()], 403);
        }

        try {
            $movie = Movie::create($request->all());
            Cache::forget('movies');
        } catch(exception $e) {
            return response()->json(['status'=> 'fail', 'errors'=> [$e->getMessage()]], 403);
        }

        return response(['status'=> 'success', 'message'=> 'Successfully added movie', 'data'=> new MovieResource($movie)], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $movie = Movie::findOrFail($id);
        return response()->json(['status'=> 'success', 'data'=> new MovieResource($movie)], 201);
    }

   
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'movie_name'=> 'required|min:3|unique:movies,id',
            'movie_duration' => 'required|numeric',
            'release_date' => 'required|numeric',
        ]);

        if($validator->fails()) {
            return response()->json(['status'=>'fail', 'errors'=>$validator->errors()], 403);
        }

        try {
            Movie::findOrFail($id)->update($request->all());
            Cache::forget('movies');
        } catch(exception $e) {
            return response()->json(['status'=> 'fail', 'errors'=> [$e->getMessage()]], 403);
        }

        return response(['status'=> 'success', 'message'=> 'Successfully updated movie'], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $movie = Movie::findOrFail($id);
            $movie->delete();
            Cache::forget('movies');
        }catch(exception $e) {
            return response()->json(['status'=> 'fail', 'errors'=> [$e->getMessage()]], 403);
        }
        
        return response()->json(['status'=> 'success', 'message'=> 'successfully delete movie'], 204);
    }
}
