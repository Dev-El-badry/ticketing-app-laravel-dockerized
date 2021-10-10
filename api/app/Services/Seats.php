<?php

namespace App\Services;

class Seats {

  /**
   * merge seats
   *
   * @param [Seat] $seats
   * @param array $seatReservedIds
   * @return array
   */
  public function generate($seats, $seatReservedIds) {
    foreach($seats as $seat) {
      if(in_array($seat['id'], $seatReservedIds)) {
        $seat['available'] = false;
      } else {
        $seat['available'] = true;
      }
    }

    return $seats;
  }

}