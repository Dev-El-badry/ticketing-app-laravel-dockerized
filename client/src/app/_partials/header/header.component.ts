import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { Store } from '@ngrx/store';
import { Observable } from 'rxjs';
import { AuthService } from 'src/app/auth/auth.service';
import * as fromRoot from '../../app.reducer';

@Component({
  selector: 'app-header',
  templateUrl: './header.component.html',
  styleUrls: ['./header.component.scss']
})
export class HeaderComponent implements OnInit {
  isAuth$: Observable<boolean>;
  list = [];
  constructor(
    private store: Store<fromRoot.State>,
    private router: Router,
    private authService: AuthService
  ) { }

  ngOnInit(): void {
    this.store.select(fromRoot.getIsAuthenticate).subscribe(currentUser => {
      this.renderList(currentUser);
    });
  }

  renderList(currentUser) {
    this.list = [
      !currentUser && {
        title: 'Sign in',
        action: () => {
          this.router.navigateByUrl('/auth')
        }
      },
      currentUser && {
        title: 'Movies',
        action: () => {
          this.router.navigateByUrl('/')
        }
      },
      currentUser && {
        title: 'Logout',
        action: () => {
          this.authService.logout()
        }
      },
      
    ];
  }

}
