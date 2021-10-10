<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\SeatReserved;

class SeatReserve implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $seatIds;
    public $reservation;
    public $editable;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($seatIds, $reservationModel, $editable = false)
    {
        $this->seatIds = $seatIds;
        $this->reservation = $reservationModel;
        $this->editable = $editable; 
    }

    
    public function handle()
    {
        
        if($this->editable === TRUE) {
            SeatReserved::where('reservation_id', $this->reservation->id)->delete();
        }

        $seats = $this->seatIds;
        foreach ($seats as $value) {
            $seatReserved = new SeatReserved();
            $seatReserved->seat_id = $value;
            $seatReserved->reservation_id = $this->reservation->id;
            $seatReserved->show_time_id = $this->reservation->show_time_id;
            $seatReserved->save();
        }
    }
}
