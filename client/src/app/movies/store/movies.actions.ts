import { Movie } from "../Movie.model";
import { Action } from "@ngrx/store";
import { ShowTime } from "../show-times/ShowTime.model";
import { Seat } from "../seats/seat.model";

export const SET_ALL_MOVIES = "[Movies] Set All Movies";
export const SET_ALL_SHOW_TIMES = "[Show Times] Set All Show Times";
export const SET_ONE_SHOW_TIME = "[Show Time] Set One Show Time";
export const SET_ALL_SEATS = "[Show Seats] Set all Seats";

export class SetAllMovies implements Action {
  readonly type = SET_ALL_MOVIES;
  constructor(public payload: Movie[]) {}
}

export class SetAllShowTimes implements Action {
  readonly type = SET_ALL_SHOW_TIMES;
  constructor(public payload: ShowTime[]) {}
}

export class SetOneShowTime implements Action {
  readonly type = SET_ONE_SHOW_TIME;
  constructor(public payload: ShowTime) {}
}

export class SetAllSeats implements Action {
  readonly type = SET_ALL_SEATS;
  constructor(public payload: Seat[]) {}
}

export type MovieActions = SetAllMovies | SetAllShowTimes | SetAllSeats | SetOneShowTime;
