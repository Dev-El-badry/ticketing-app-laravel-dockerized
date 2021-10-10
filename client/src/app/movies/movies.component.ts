import { Component, OnInit } from '@angular/core';
import { Store } from '@ngrx/store';
import { AppService } from '../app.service';
import { Movie } from './Movie.model';
import * as fromRoot from '../app.reducer';
import { Observable } from 'rxjs';
@Component({
  selector: 'app-movies',
  templateUrl: './movies.component.html',
  styleUrls: ['./movies.component.scss']
})
export class MoviesComponent implements OnInit {
  movies: Movie[];
  isLoading$: Observable<boolean>;
  constructor(
    private appService: AppService,
    private store: Store<fromRoot.State>
  ) { }

  ngOnInit(): void {
    this.isLoading$ = this.store.select(fromRoot.getIsLoading);
    this.store.select(fromRoot.getMovies).subscribe(movies => {
      this.movies = movies;
    });
    this.appService.getMovies();
  }

}
