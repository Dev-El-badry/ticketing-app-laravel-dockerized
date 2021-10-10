<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class ShowTime extends Model
{
	use HasFactory;

	protected $table = 'show_times';
	protected $fillable = ['date', 'start_time', 'end_time', 'movie_id', 'hall_id'];

	public function hall() {
		return $this->belongsTo('App\Models\Hall', 'hall_id');
	}

	public function movie() {
		return $this->belongsTo('App\Models\Movie', 'movie_id');
	}

	public function reservations() {
		return $this->hasMany('App\Models\Reservation', 'show_time_id');
	}
}
