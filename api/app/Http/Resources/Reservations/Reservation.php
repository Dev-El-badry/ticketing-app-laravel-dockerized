<?php

namespace App\Http\Resources\Reservations;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ShowTimes\ShowTime as ShowTimeResource;
class Reservation extends JsonResource
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
            'id' => $this->id,
            'receptionist' => $this->employee,
            'show_time' => new ShowTimeResource($this->showTime),
            'num_of_seats' => $this->num_of_seats,
            'total_cost' => $this->total_cost,
            'status' => $this->status ? true : false,
            'created_at' => (string)$this->created_at
        ];
    }
}
