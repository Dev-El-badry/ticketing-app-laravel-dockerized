<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Seat extends Model
{
	use HasFactory;
    protected $table = 'seats';
    protected $fillable = ['seat_code', 'hall_id'];

    public function hall() {
    	return $this->belongsTo('App\Models\Hall');
    }
}
