import { Component } from "@angular/core";
import { Router } from "@angular/router";

@Component({
  template: `
  <h1 mat-dialog-title>Booked</h1>
  <div mat-dialog-content>Tickets have been booked.. Thank you.</div>
  <div mat-dialog-actions>
    <button mat-button mat-dialog-close (click)="onClick()">OKAY</button>
  </div>
  `,
  selector: 'app-dialog-alert'
})
export class DialogAlertComponent {
  constructor(
    private router: Router
  ) {}

  onClick() {
    this.router.navigateByUrl('/');
  }
}