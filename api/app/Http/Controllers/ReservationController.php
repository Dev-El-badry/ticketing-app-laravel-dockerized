<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\SeatReserved;
use App\Http\Resources\Reservations\Reservation as ReservationResource;
use App\Http\Resources\Reservations\ReservationCollection;
use Illuminate\Support\Facades\Cache; 
use App\Jobs\SeatReserve;
use Validator;

class ReservationController extends Controller
{
    /**
     * Create a new ReservationController instance.
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
        $reservations = Cache::remember('reservations', now()->addMinutes(120), function() {
            return Reservation::get();
        });
        return response()->json(['status'=> 'success', 'data'=> new ReservationCollection($reservations)], 201);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $userId = auth('api')->user()->id;

        $validator = Validator::make($request->all(), [
            'show_time_id' => 'required|numeric|min:0|not_in:0',
            'total_cost' => 'required|numeric|min:0|not_in:0',
            'seats' => 'required|array'
        ]);

        if($validator->fails()) {
            return response()->json(['status'=>'fail', 'errors'=>$validator->errors()], 403);
        }
        
        try {
        	$data = $request->all();
        	$data['num_of_seats'] = count($request->input('seats'));
            $data['employee_id'] = $userId;
            $reservation = Reservation::create($data);
            Cache::forget('reservations');

            //add action
            SeatReserve::dispatch($request->input('seats'), $reservation);
        } catch(exception $e) {
            return response()->json(['status'=> 'fail', 'errors'=> [$e->getMessage()]], 403);
        }

        return response(['status'=> 'success', 'message'=> 'Successfully added reservation', 'data'=> new ReservationResource($reservation)], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $reservation = Reservation::findOrFail($id);
        return response()->json(['status'=> 'success', 'data'=> new ReservationResource($reservation)], 201);
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
        $userId = auth('api')->user()->id;
        $validator = Validator::make($request->all(), [
            'show_time_id' => 'required|numeric|min:0|not_in:0',
            'total_cost' => 'required|numeric|min:0|not_in:0',
            'seats' => 'required|array'
        ]);

        if($validator->fails()) {
            return response()->json(['status'=>'fail', 'errors'=>$validator->errors()], 403);
        }

        try {
            $data = $request->all();
            $data['num_of_seats'] = count($request->input('seats'));

            $reservation = Reservation::findOrFail($id);
            $reservation->employee_id =$userId;
            $reservation->show_time_id = $data['show_time_id'];
            $reservation->total_cost = $data['total_cost'];
            $reservation->num_of_seats = $data['num_of_seats'];
            $reservation->save();

            Reservation::findOrFail($id)->update($data);
            Cache::forget('reservations');

            //add action
            SeatReserve::dispatch($request->input('seats'), $reservation, true);
        } catch(exception $e) {
            return response()->json(['status'=> 'fail', 'errors'=> [$e->getMessage()]], 403);
        }

        return response(['status'=> 'success', 'message'=> 'Successfully updated reservation'], 201);
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
            SeatReserved::where('reservation_id', $this->reservation->id)->delete();
            $reservation = Reservation::findOrFail($id);
            $reservation->delete();
            Cache::forget('reservations');
        } catch(exception $e) {
            return response()->json(['status'=> 'fail', 'errors'=> [$e->getMessage()]], 403);
        }

        return response()->json(['status'=> 'success', 'message'=> 'successfully delete reservation'], 204);
    }
}
