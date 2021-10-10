import { Component, OnInit } from '@angular/core';
import { MatDialog } from '@angular/material/dialog';
import { ActivatedRoute, Router } from '@angular/router';
import { Store } from '@ngrx/store';
import { Observable } from 'rxjs';
import { AppService } from 'src/app/app.service';
import { DialogAlertComponent } from 'src/app/ui/dialog-alert.component';

import * as fromRoot from '../../app.reducer';
import { ShowTime } from '../show-times/ShowTime.model';
import { Seat } from './seat.model';
@Component({
  selector: 'app-seats',
  templateUrl: './seats.component.html',
  styleUrls: ['./seats.component.scss']
})
export class SeatsComponent implements OnInit {
  isLoading$: Observable<boolean>;
  seats: Seat[];
  showTime: ShowTime;

  showId: number;
  hallId: number;

  seatReserved = [];

  isLoading: boolean;
  constructor(
    private store: Store<fromRoot.State>,
    private appService: AppService,
    private route: ActivatedRoute,
    private router: Router,
    private dialog: MatDialog
  ) { }

  ngOnInit(): void {
    this.isLoading$ = this.store.select(fromRoot.getIsLoading);
    this.route.paramMap.subscribe(paramMap => {
      this.showId = +paramMap.get('showId');
      this.hallId = +paramMap.get('hallId');

      if(isNaN(this.hallId) || isNaN(this.showId)) {
        this.router.navigateByUrl('/');
        return;
      }

      this.appService.getSeats(this.hallId, this.showId);
      this.store.select(fromRoot.getSeats).subscribe(seats => {
        this.seats = seats;
      });
      this.store.select(fromRoot.getShowTime).subscribe(show => {
        this.showTime = show;
      })
    });
  }

  selectSeat(seatId, available) {
    if(!available) return;

    if(this.seatReserved.includes(seatId)) {
      const list = this.seatReserved.filter(s => s != seatId);
      this.seatReserved.length = 0;
      this.seatReserved = list;
    } else {
      this.seatReserved.push(seatId);
    }

    this.getTotalCost();
  }

  getTotalCost() {
    const count = this.seatReserved.length;
    return +this.showTime.hall.price * count;
  }

  onClick() {
    this.isLoading = true;
    this.appService.booking(this.showId, this.getTotalCost(), this.seatReserved)
      .subscribe(res => {
        this.dialog.open(DialogAlertComponent);
        this.isLoading = false;
      }, err => {
        this.isLoading = false;
        console.log('ERROR!', err);
      });
  }

}
