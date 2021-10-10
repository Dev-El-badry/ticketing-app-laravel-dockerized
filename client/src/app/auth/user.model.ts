export class User {
  constructor(public userId: number, public email: string, public name: string, private _token: string, private tokenExpirationDate: Date) {}

  get token() {
      if(!this.tokenExpirationDate || this.tokenExpirationDate <= new Date()) {
          return null;
      }
      return this._token;
  }

  get tokenDuration() {
      if(!this.token) {
          return 0;
      }

      return this.tokenExpirationDate.getTime() - new Date().getTime();
  }
}

export interface UserData {
  userId: number;
  email: string;
  name: string;
  token: string;
  tokenDuration: Date;
}
