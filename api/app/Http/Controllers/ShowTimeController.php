<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShowTime;
use App\Http\Resources\ShowTimes\ShowTime as ShowTimeResource;
use App\Http\Resources\ShowTimes\ShowTimeCollection;
use Illuminate\Support\Facades\Cache; 
use Validator;

class ShowTimeController extends Controller
{
    /**
     * Create a new ShowTimeController instance.
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
        $showTimes = Cache::remember('showTimes', now()->addMinutes(120), function() {
            return ShowTime::get();
        });

        return response()->json(['status'=> 'success', 'data'=> new ShowTimeCollection($showTimes)], 201);
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
            'date'=> 'required|date|after:'.now()->subDay(),
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'hall_id' => 'required|numeric',
            'movie_id' => 'required|numeric'
        ]);

        if($validator->fails()) {
            return response()->json(['status'=>'fail', 'errors'=>$validator->errors()], 403);
        }

        try {
            $showTime = ShowTime::create($request->all());
            Cache::forget('showTimes');
        } catch(exception $e) {
            return response()->json(['status'=> 'fail', 'errors'=> [$e->getMessage()]], 403);
        }

        return response(['status'=> 'success', 'message'=> 'Successfully added show time', 'data'=> new ShowTimeResource($showTime)], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $showTime = ShowTime::findOrFail($id);
        return response()->json(['status'=> 'success', 'data'=> new ShowTimeResource($showTime)], 201);
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
            'date'=> 'required|date|after:'.now()->subDay(),
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'hall_id' => 'required|numeric',
            'movie_id' => 'required|numeric'
        ]);

        if($validator->fails()) {
            return response()->json(['status'=>'fail', 'errors'=>$validator->errors()], 403);
        }

        try {
            ShowTime::findOrFail($id)->update($request->all());
            Cache::forget('showTimes');
        } catch(exception $e) {
            return response()->json(['status'=> 'fail', 'errors'=> [$e->getMessage()]], 403);
        }

        return response(['status'=> 'success', 'message'=> 'Successfully updated show time'], 201);
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
            $showTime = ShowTime::findOrFail($id);
            $showTime->delete();
            Cache::forget('showTimes');
        } catch(exception $e) {
            return response()->json(['status'=> 'fail', 'errors'=> [$e->getMessage()]], 403);
        }

        return response()->json(['status'=> 'success', 'message'=> 'successfully delete showTime'], 204);
    }

    /**
     * get show times based on movie_id
     *
     * @param  int  $movieId
     * @return \Illuminate\Http\Response
     */
    
    public function getShowTimes($movieId) {
        if(!is_numeric($movieId)) {
            return response()->json(['status'=> 'fail', 'errors'=> ['pass a valid movie id']], 403);
        }

        $result = ShowTime::where('movie_id', $movieId)
            ->selectRaw("*, DAY(DATE(show_times.date)) as day")
            ->whereRaw("DATE(date) BETWEEN CURRENT_DATE() AND DATE(CURRENT_DATE() + INTERVAL 1 WEEK)")
            ->orderBy('date')
            ->get();

        return response()->json(['status'=> 'success', 'data'=> new ShowTimeCollection($result)], 200);
    }
}
