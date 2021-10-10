import { Component, OnInit } from '@angular/core';
import { Store } from '@ngrx/store';
import { Observable } from 'rxjs';
import * as fromRoot from './app.reducer';
import { AuthService } from './auth/auth.service';
@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss']
})
export class AppComponent implements OnInit {
  title = 'client';
  isAuth$: Observable<boolean>;

  constructor(
    private store: Store<fromRoot.State>,
    private authService: AuthService
  ) {}

  ngOnInit() {
    this.isAuth$ = this.store.select(fromRoot.getIsAuthenticate);
    this.authService.autoLogin();
  }
}
