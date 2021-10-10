import { ActionReducerMap, createFeatureSelector, createSelector } from '@ngrx/store';
import * as fromAuth from './auth/store/auth.reducer';
import * as fromUI from './ui/store/ui.reducer';
import * as fromMovies from './movies/store/movies.reducer';

export interface State {
  auth: fromAuth.State,
  ui: fromUI.State,
  movies: fromMovies.State
}

export const reducer: ActionReducerMap<State> = {
  auth: fromAuth.authReducer,
  ui: fromUI.uiReducer,
  movies: fromMovies.movieReducer
};

export const getUIState = createFeatureSelector<fromUI.State>('ui');
export const getIsLoading = createSelector(getUIState, fromUI.getIsLoading);

export const getAuthState = createFeatureSelector<fromAuth.State>('auth');
export const getIsAuthenticate = createSelector(getAuthState, fromAuth.getIsAuthenticate);

export const getMovieState = createFeatureSelector<fromMovies.State>('movies');
export const getMovies = createSelector(getMovieState, fromMovies.getMovies);
export const getShowTimes = createSelector(getMovieState, fromMovies.getShowTimes);
export const getShowTime = createSelector(getMovieState, fromMovies.getShowTime);
export const getSeats = createSelector(getMovieState, fromMovies.getSeats);