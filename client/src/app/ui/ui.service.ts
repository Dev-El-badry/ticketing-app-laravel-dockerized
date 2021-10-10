import { Injectable } from '@angular/core';
import { MatSnackBar } from '@angular/material/snack-bar';

@Injectable({
  providedIn: 'root'
})
export class UiService {

  constructor(private snakbar: MatSnackBar) { }

  snackbar(msgError, action, durationTime) {
    this.snakbar.open(msgError, action, {
      duration: durationTime
    });
  }
}
