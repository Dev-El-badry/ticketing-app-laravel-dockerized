<?php

namespace App\Http\Resources\Movies;

use Illuminate\Http\Resources\Json\JsonResource;

class Movie extends JsonResource
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
            'movie_name' => $this->movie_name,
            'movie_description' => $this->movie_description,
            'movie_duration' => $this->movie_duration,
            'release_date' => $this->release_date,
            'created_at' => (string) $this->created_at
        ];
    }
}
