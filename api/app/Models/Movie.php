<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Movie extends Model {
	use HasFactory;
	protected $table = 'movies';
	protected $fillable = ['movie_name', 'movie_description', 'movie_duration', 'release_date'];

	public function showTimes() {
		return $this->hasMany('show_times', 'movie_id');
	}
}