<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Hall extends Model {
	use HasFactory;
	protected $table = 'halls';
	protected $fillable = ['hall_name', 'price'];

	public function showTimes() {
		return $this->hasMany('App\Models\ShowTime', 'hall_id');
	}

	public function seats() {
		return $this->hasMany('App\Models\Seat', 'seat_id');
	}
}