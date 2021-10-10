import {AuthActions, SET_AUTHENTICATION, SET_UNAUTHENTICATION} from './auth.actions';

export interface State {
  isAuthentication: boolean;
}

const initialState: State = {
  isAuthentication: false,
}

export function authReducer(state = initialState, action: AuthActions) {
  switch (action.type) {
    case SET_UNAUTHENTICATION:
      return {
        ...state,
        isAuthentication: false
      };
    case SET_AUTHENTICATION:
      return {
        ...state,
        isAuthentication: true
      }
    default:
      return state;
  }
}

export const getIsAuthenticate = (state: State) => state.isAuthentication;