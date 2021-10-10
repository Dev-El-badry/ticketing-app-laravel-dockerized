import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Store } from '@ngrx/store';
import { environment } from 'src/environments/environment';
import * as UI from './ui/store/ui.actions';
import * as Movie from './movies/store/movies.actions';
import * as fromRoot from './app.reducer';
import { Movie as MovieModel } from './movies/Movie.model';
import { UiService } from './ui/ui.service';
import { ShowTime } from './movies/show-times/ShowTime.model';
import { Seat } from './movies/seats/seat.model';
@Injectable({
  providedIn: 'root'
})
export class AppService {
  private baseUrl = environment.baseUrl;
  constructor(
    private http: HttpClient,
    private store: Store<fromRoot.State>,
    private uiService: UiService
  ) { }

  getMovies() {
    this.store.dispatch(new UI.StartLoading());
    this.http.get<{data: MovieModel[]}>(`${this.baseUrl}movies`)
      .subscribe(movies => {
        this.store.dispatch(new UI.StopLoading());
        this.store.dispatch(new Movie.SetAllMovies(movies.data))
      }, err => {
        this.errorActions(err);
      })
  }

  getShowTimes(movieId: number) {
    this.store.dispatch(new UI.StartLoading());
    this.http.get<{data: ShowTime[]}>(`${this.baseUrl}showTimes/show/${movieId}`)
      .subscribe(times => {
        this.store.dispatch(new UI.StopLoading());
        this.store.dispatch(new Movie.SetAllShowTimes(times.data))
      }, err => {
        this.errorActions(err);
      })
  }

  getSeats(hallId: number, showId: number) {
    this.store.dispatch(new UI.StartLoading());
    this.http.get<{data: {seats: Seat[], show: ShowTime}}>(`${this.baseUrl}seats/get-seats/hall/${hallId}/show/${showId}`)
        .subscribe(seats => {
        this.store.dispatch(new UI.StopLoading());
        this.store.dispatch(new Movie.SetAllSeats(seats.data.seats))
        this.store.dispatch(new Movie.SetOneShowTime(seats.data.show))
      }, err => {
        this.errorActions(err);
      })
  }

  booking(showId: number, totalCost: number, seats: number[]) {
    const data = JSON.stringify({
      show_time_id: showId,
      total_cost: totalCost,
      seats
    });
    const header: HttpHeaders = new HttpHeaders()
    .set('Content-Type', 'application/json');
    return this.http.post(`${this.baseUrl}reservations`, data, {
      headers: header
    })
  }

  errorActions(error) {
    this.store.dispatch(new UI.StopLoading());
    this.uiService.snackbar('TRY AGAIN', 'OKAY', 2000);
  }

}
