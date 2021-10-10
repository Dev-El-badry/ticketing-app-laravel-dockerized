import { Injectable } from '@angular/core';
import { CanLoad, Route, UrlSegment, Router, ActivatedRouteSnapshot, RouterStateSnapshot } from '@angular/router';
import { Observable, of } from 'rxjs';
import { AuthService } from './auth.service';

@Injectable({
  providedIn: 'root'
})
export class AuthGuard implements CanLoad {
  constructor(private authService: AuthService, private router: Router) {}
  canLoad(
    route: Route,
    segments: UrlSegment[]): Observable<boolean> | Promise<boolean> | boolean {
      const isAuth = this.authService.getIsAuth;

      if(!isAuth) {
        this.router.navigateByUrl('/auth');
        
      }

      return isAuth;
    }

    canActivate(route: ActivatedRouteSnapshot, state: RouterStateSnapshot) {
      if(!this.authService.getIsAuth) {
        this.router.navigateByUrl('/auth');
      }
      return this.authService.getIsAuth;
    }

}
