<?php

namespace App\Http\Resources\Halls;

use Illuminate\Http\Resources\Json\JsonResource;

class Hall extends JsonResource
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
            'hall_name' => $this->hall_name,
            'price'=> number_format($this->price, 2),
            'created_at' => $this->created_at
        ];
    }
}
