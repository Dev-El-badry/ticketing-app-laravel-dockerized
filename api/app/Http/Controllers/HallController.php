<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hall;
use App\Http\Resources\Halls\Hall as HallResource;
use App\Http\Resources\Halls\HallCollection;
use Illuminate\Support\Facades\Cache; 
use Validator;

class HallController extends Controller
{
    /**
     * Create a new HallController instance.
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
        $halls = Cache::remember('halls', now()->addMinutes(120), function() {
            return Hall::get();
        });
        return response()->json(['status'=> 'success', 'data'=> new HallCollection($halls)], 201);
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
            'hall_name'=> 'required|min:2|unique:halls',
            'price' => 'required|numeric|min:0|not_in:0'
        ]);

        if($validator->fails()) {
            return response()->json(['status'=>'fail', 'errors'=>$validator->errors()], 403);
        }

        try {
            $hall = Hall::create($request->all());
            Cache::forget('halls');
        } catch(exception $e) {
            return response()->json(['status'=> 'fail', 'errors'=> [$e->getMessage()]], 403);
        }

        return response(['status'=> 'success', 'message'=> 'Successfully added hall', 'data'=> new HallResource($hall)], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $hall = Hall::findOrFail($id);
        return response()->json(['status'=> 'success', 'data'=> new HallResource($hall)], 201);
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
            'hall_name'=> 'required|min:2|unique:halls,id',
            'price' => 'required|numeric|min:0|not_in:0'
        ]);

        if($validator->fails()) {
            return response()->json(['status'=>'fail', 'errors'=>$validator->errors()], 403);
        }

        try {
            Hall::findOrFail($id)->update($request->all());
            Cache::forget('halls');
        } catch(exception $e) {
            return response()->json(['status'=> 'fail', 'errors'=> [$e->getMessage()]], 403);
        }

        return response(['status'=> 'success', 'message'=> 'Successfully updated hall'], 201);
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
            $hall = Hall::findOrFail($id);
            $hall->delete();
            Cache::forget('halls');
        } catch(exception $e) {
            return response()->json(['status'=> 'fail', 'errors'=> [$e->getMessage()]], 403);
        }

        return response()->json(['status'=> 'success', 'message'=> 'successfully delete hall'], 204);
    }
}
