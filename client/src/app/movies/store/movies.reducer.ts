import { Movie } from '../Movie.model';
import { Seat } from '../seats/seat.model';
import { ShowTime } from '../show-times/ShowTime.model';
import {MovieActions, SET_ALL_MOVIES, SET_ALL_SHOW_TIMES, SET_ALL_SEATS, SET_ONE_SHOW_TIME} from './movies.actions';

export interface State {
  movies: Movie[];
  showTimes: ShowTime[];
  seats: Seat[];
  showTime: ShowTime;
}

const initialState: State = {
  movies: [],
  showTimes: [],
  seats: [],
  showTime: null
}

export function movieReducer(state = initialState, action: MovieActions) {
  switch (action.type) {
    case SET_ALL_MOVIES:
      return {
        ...state,
        movies: action.payload
      };
    case SET_ALL_SHOW_TIMES:
      return {
        ...state,
        showTimes: action.payload
      };
    case SET_ALL_SEATS:
      return {
        ...state,
        seats: action.payload
      };
    case SET_ONE_SHOW_TIME:
      return {
        ...state,
        showTime: action.payload
      }
    default:
      return state;
  }
}
export const getShowTimes = (state: State) => state.showTimes;
export const getShowTime = (state: State) => state.showTime;

export const getMovies = (state: State) => state.movies;
export const getSeats = (state: State) => state.seats;