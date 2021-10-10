import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Store } from '@ngrx/store';
import { tap, map } from 'rxjs/operators';
import { from } from 'rxjs';

import * as fromRoot from '../app.reducer';

import * as UI from '../ui/store/ui.actions';
import * as Auth from './store/auth.actions';
import { environment } from '../../environments/environment';
import { UiService } from '../ui/ui.service';
import { User } from './user.model';
import { Router } from '@angular/router';

export interface AuthData {
  email: string;
  password: string;
}

interface AuthResponseData {
  access_token: string;
  expires_in: number;
  payload: {
    id: number;
    name: string;
    email: string;
  };
}

@Injectable({
  providedIn: "root",
})
export class AuthService {
  _baseUrl: string = environment.baseUrl;
  private isAuth: boolean;
  private tokenTimer: any;
  private token: string;
  
  constructor(
    private store: Store<fromRoot.State>,
    private _http: HttpClient,
    private uiService: UiService,
    private router: Router
  ) {}

  getToken() {
      return this.token;
  }


  get getIsAuth() {
    return this.isAuth;
  }

  login(authData: AuthData) {
    const targetUrl = this._baseUrl + 'auth/login';
    const obj = JSON.stringify({
      email: authData.email,
      password: authData.password,
    });
    this.store.dispatch(new UI.StartLoading());

    const header: HttpHeaders = new HttpHeaders()
    .set('Content-Type', 'application/json');

    return this._http
      .post<AuthResponseData>(targetUrl, obj, {
        headers: header,
      })
      .pipe(
        tap(
          (resData) => {
            if (resData) {
              this.store.dispatch(new UI.StopLoading());
              this.store.dispatch(new Auth.SetAuthentication());
              this.isAuth = true;
              this.token = resData.access_token;
              this.setAuthTimer(+resData.expires_in);
              this.setUserData(resData);
             
            }
          },
          (error) => {
            this.store.dispatch(new UI.StopLoading());
            this.uiService.snackbar('Invalid Login', 'TRY AGAIN', 2000);
          }
        )
      )
      
  }

  autoLogin() {
    const authInformation = this.getAuthData();
    if(!authInformation) {
      return;
    }

    const parseData = JSON.parse(authInformation) as {
      userID: number,
      token: string,
      tokenExpirationDate: string,
      email: string,
      username: string
    };
   
    if (!parseData) {
      return;
    }
    const expirationDate = new Date(parseData.tokenExpirationDate);
    if(expirationDate <= new Date()) {
      return;
    } 
    this.token = parseData.token;
    this.isAuth = true;
  
    
    this.setAuthTimer(+expirationDate);
    this.store.dispatch(new Auth.SetAuthentication());
  }

  private setAuthTimer(duration: number) {
    this.tokenTimer = setTimeout(() => {
      this.logout();
    }, duration * 1000);
  }

  logout() {
    this.store.dispatch(new Auth.SetUnauthentication());
    this.isAuth = false;
    clearTimeout(this.tokenTimer);
    this.router.navigateByUrl('/auth');
    localStorage.removeItem('authDate');
  }


  setUserData(userData: AuthResponseData) {
    
      const expirationTime = new Date(
        new Date().getTime() + +userData.expires_in * 1000
      );

      const user = new User(
        userData.payload.id,
        userData.payload.email,
        userData.payload.name,
        userData.access_token,
        expirationTime
      );
     
     
      this.storeAuthData(
        userData.payload.id,
        userData.access_token,
        expirationTime.toISOString(),
        userData.payload.email,
        userData.payload.name,
      );
  }

  private storeAuthData(
    userId: number,
    token: string,
    tokenExpirationDate: string,
    email: string,
    username: string
  ) {
    const data = JSON.stringify({
      userId: userId,
      token: token,
      tokenExpirationDate,
      email: email,
      username: username
    });

    localStorage.setItem('authDate', data);
  }

  private getAuthData() {
    const authData = localStorage.getItem('authDate');
    if(!authData) {
      return null;
    }

    return authData;
  }

  getAuthInfo() {
    const authInformation = this.getAuthData();
    if(!authInformation) {
      return;
    }

    const parseData = JSON.parse(authInformation) as {
      userId: number,
      token: string,
      tokenExpirationDate: Date,
      email: string,
      username: string
    };
    
    return parseData;
  }
}
