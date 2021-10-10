import { Component, OnInit } from '@angular/core';
import { MatTableDataSource } from '@angular/material/table';
import { ActivatedRoute, Router } from '@angular/router';
import { Store } from '@ngrx/store';
import { Observable } from 'rxjs';
import { AppService } from 'src/app/app.service';
import * as fromRoot from '../../app.reducer';
import { ShowTime } from './ShowTime.model';

@Component({
  selector: 'app-show-times',
  templateUrl: './show-times.component.html',
  styleUrls: ['./show-times.component.scss']
})
export class ShowTimesComponent implements OnInit {
  isLoading$: Observable<boolean>;
  movieId: number;

  displayedColumns: string[] = [
    "hall",
    "date",
    "start_time",
    "end_time",
    "price",
    "actions",
  ];
  dataSource = new MatTableDataSource<ShowTime>();
  constructor(
    private store: Store<fromRoot.State>,
    private appService: AppService,
    private route: ActivatedRoute,
    private router: Router
  ) { }

  ngOnInit(): void {
    this.route.paramMap.subscribe(paramMap => {
      this.movieId = +paramMap.get('movieId');
      if(isNaN(this.movieId)) {
        this.router.navigateByUrl('/');
        return;
      }

      this.appService.getShowTimes(this.movieId);
      this.isLoading$ = this.store.select(fromRoot.getIsLoading);
      this.store.select(fromRoot.getShowTimes).subscribe(times => {
        this.dataSource.data = times;
      });
    });

  }

}
