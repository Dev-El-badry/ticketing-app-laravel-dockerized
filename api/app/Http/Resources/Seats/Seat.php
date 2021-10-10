<?php

namespace App\Http\Resources\Seats;

use Illuminate\Http\Resources\Json\JsonResource;

class Seat extends JsonResource
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
            'id'=>$this->id,
            'seat_code' => $this->seat_code,
            'hall' => $this->hall,
            'created_at' => $this->created_at
        ];
    }
}
