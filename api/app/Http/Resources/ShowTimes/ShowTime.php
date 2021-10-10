<?php

namespace App\Http\Resources\ShowTimes;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Halls\Hall;
use App\Http\Resources\Movies\Movie;
class ShowTime extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'=> $this->id,
            'date' => (string) $this->date,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'created_at' => (string) $this->created_at,
            'hall' => new Hall($this->hall),
            'movie' => new Movie($this->movie)
        ];
    }
}
