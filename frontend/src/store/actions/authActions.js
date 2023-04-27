import {
    loginFailure,
    loginRequest,
    loginSuccess,
    logoutFailure,
    logoutRequest,
    logoutSuccess
} from "../reducers/authSlice";
import * as api from "../../api/api";

export const login = (username, password) => async (dispatch) => {
    try {
        dispatch(loginRequest());
        const authData = await api.authenticate(username, password);
        const token = authData.data.token;
        const user = {};
        dispatch(loginSuccess({ user, token }));
    } catch (error) {
        dispatch(loginFailure(error.message));
    }
};
export const logout = () => async (dispatch) => {
    try {
        dispatch(logoutRequest());
        await api.logout();
        dispatch(logoutSuccess());
    } catch (error) {
        dispatch(logoutFailure());
    }
};
export const reauthenticate = (token) => async (dispatch) => {
    try {
        dispatch(loginRequest());
        const authData = await api.reauthenticate(token);
        const { user, token: newToken } = authData.data;
        dispatch(loginSuccess({ user, token: newToken }));
    } catch (error) {
        dispatch(loginFailure());
    }
};
