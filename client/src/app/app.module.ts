import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';

import { reducer } from './app.reducer';

//modules
import { MaterialModule } from './material.module';
import { HttpClientModule, HTTP_INTERCEPTORS } from '@angular/common/http';
import { AuthComponent } from './auth/auth.component';
import { StoreModule } from '@ngrx/store';
import { FormsModule } from '@angular/forms';
import { MoviesComponent } from './movies/movies.component';
import { AuthInterceptor } from './auth/auth.interceptor';
import { HeaderComponent } from './_partials/header/header.component';
import { ShowTimesComponent } from './movies/show-times/show-times.component';
import { SeatsComponent } from './movies/seats/seats.component';
import { DialogAlertComponent } from './ui/dialog-alert.component';
@NgModule({
  declarations: [
    AppComponent,
    AuthComponent,
    MoviesComponent,
    HeaderComponent,
    ShowTimesComponent,
    SeatsComponent,
    DialogAlertComponent
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    BrowserAnimationsModule,
    
    //modules
    MaterialModule,
    HttpClientModule,
    StoreModule.forRoot(reducer),
    FormsModule
  ],
  providers: [{provide: HTTP_INTERCEPTORS, useClass: AuthInterceptor, multi: true}],
  bootstrap: [AppComponent]
})
export class AppModule { }
