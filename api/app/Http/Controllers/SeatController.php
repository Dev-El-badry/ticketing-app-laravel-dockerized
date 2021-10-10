<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Seat;
use App\Models\SeatReserved;
use App\Http\Resources\Seats\Seat as SeatResource;
use App\Http\Resources\Seats\SeatCollection;
use Illuminate\Support\Facades\Cache; 
use App\Services\Seats;
use App\Models\ShowTime;
use App\Http\Resources\ShowTimes\ShowTime as ShowTimeResource;
use Validator;

class SeatController extends Controller
{
    /**
     * Create a new SeatController instance.
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
        $seats = Cache::remember('seats', now()->addMinutes(120), function() {
            return Seat::get();
        });
        return response()->json(['status'=> 'success', 'data'=> new SeatCollection($seats)], 201);
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
            'seat_code'=> 'required|min:2|unique:seats',
            'hall_id' => 'required|numeric|min:0|not_in:0'
        ]);

        if($validator->fails()) {
            return response()->json(['status'=>'fail', 'errors'=>$validator->errors()], 403);
        }

        try {
            $seat = Seat::create($request->all());
            Cache::forget('seats');
        } catch(exception $e) {
            return response()->json(['status'=> 'fail', 'errors'=> [$e->getMessage()]], 403);
        }

        return response(['status'=> 'success', 'message'=> 'Successfully added seat', 'data'=> new SeatResource($seat)], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $seat = Seat::findOrFail($id);
        return response()->json(['status'=> 'success', 'data'=> new SeatResource($seat)], 201);
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
            'seat_code'=> 'required|min:2|unique:seats,id',
            'hall_id' => 'required|numeric|min:0|not_in:0'
        ]);

        if($validator->fails()) {
            return response()->json(['status'=>'fail', 'errors'=>$validator->errors()], 403);
        }

        try {
            Seat::findOrFail($id)->update($request->all());
            Cache::forget('seats');
        } catch(exception $e) {
            return response()->json(['status'=> 'fail', 'errors'=> [$e->getMessage()]], 403);
        }

        return response(['status'=> 'success', 'message'=> 'Successfully updated seat'], 201);
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
            $seat = Seat::findOrFail($id);
            $seat->delete();
            Cache::forget('seats');
        } catch(exception $e) {
            return response()->json(['status'=> 'fail', 'errors'=> [$e->getMessage()]], 403);
        }

        return response()->json(['status'=> 'success', 'message'=> 'successfully delete seat'], 204);
    }

     /**
     * get all seats of hall by status of seat is available OR not
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getSeatsByHallIdAndShowId($hallId, $showTimeId) {
        if(!is_numeric($hallId)) {
            return response()->json(['status'=> 'fail', 'errors'=> ['pass a valid hall id']], 403);
        }

        $seats = Seat::where('hall_id', $hallId)
            ->get();
        $seatReserved = SeatReserved::where('show_time_id', $showTimeId)->pluck('seat_id')->toArray();
        $result = (new Seats())->generate($seats, $seatReserved);
    
        $show = ShowTime::findOrFail($showTimeId);
        return response()->json([
            'status'=> 'success',
            'data'=> [
                'seats' => $seats,
                'show'=> new ShowTimeResource($show)
            ]
        ], 201);
    }
}
