<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeatReserved extends Model
{
	protected $table = 'seat_reserved';
	protected $fillable = ['seat_id', 'reservation_id', 'show_time_id'];

	public function reservation() {
		return $this->belongsTo('App\Models\Reservation', 'reservation_id');
	}
}
