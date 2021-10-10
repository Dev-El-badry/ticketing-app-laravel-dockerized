import { Component, ElementRef, OnInit, ViewChild } from '@angular/core';
import { Store } from '@ngrx/store';
import { Observable } from 'rxjs';
import { AuthService } from './auth.service';

import * as fromRoot from '../app.reducer';
import { Router } from '@angular/router';
import { NgForm } from '@angular/forms';
@Component({
  selector: 'app-auth',
  templateUrl: './auth.component.html',
  styleUrls: ['./auth.component.scss']
})
export class AuthComponent implements OnInit {

  hide: boolean = true;
  isLoading$: Observable<boolean>;

  @ViewChild('field2') pwd: ElementRef;

  constructor(
    private auth: AuthService,
    private store: Store<fromRoot.State>,
    private router: Router
  ) { }

  ngOnInit(): void {
    this.isLoading$ = this.store.select(fromRoot.getIsLoading);

    const isAuth = this.auth.getIsAuth;
    if (isAuth) {
      let translatedPath = '/';
      this.router.navigateByUrl(translatedPath.toString());
    }
  }

  signin(form: NgForm) {
    if(form.invalid) {
      return;
    }
    this.auth.login(form.value).subscribe(loginState => {

      if(loginState) {
        
        this.router.navigate(['/']);
      }
    }, err => {
      console.log(err, 'LOGIN STATE ERROR');
    });
    this.pwd.nativeElement.value = '';
  }


}
