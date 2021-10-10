<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Reservation extends Model
{
	use HasFactory;

	protected $table = 'reservations';
	protected $fillable = ['employee_id', 'show_time_id', 'num_of_seats', 'total_cost', 'status'];

	public function employee() {
		return $this->belongsTo('App\Models\User', 'employee_id');
	}

	public function showTime() {
		return $this->belongsTo('App\Models\ShowTime', 'show_time_id');
	}

	public function seatReserved() {
		return $this->hasMany('App\Models\SeatReserved', 'reservation_id');
	}
}
