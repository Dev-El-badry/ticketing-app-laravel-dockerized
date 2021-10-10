import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { AppComponent } from './app.component';
import { AuthComponent } from './auth/auth.component';
import { AuthGuard } from './auth/auth.guard';
import { MoviesComponent } from './movies/movies.component';
import { SeatsComponent } from './movies/seats/seats.component';
import { ShowTimesComponent } from './movies/show-times/show-times.component';

const routes: Routes = [
  {
    path: '',
    redirectTo: '/',
    pathMatch: 'full'
  },
  {
    path: '',
    component: MoviesComponent,
    canActivate: [AuthGuard]
  },
  {
    path: 'auth',
    component: AuthComponent
  },
  {
    path: 'show-times/:movieId',
    component: ShowTimesComponent,
    canActivate: [AuthGuard]
  },
  {
    path: 'show-seats/:hallId/show/:showId',
    component: SeatsComponent,
    canActivate: [AuthGuard]
  },

  // { path: '**', redirectTo: '/' }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
