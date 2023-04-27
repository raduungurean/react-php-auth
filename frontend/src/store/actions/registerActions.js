import * as api from "../../api/api";
import {registerFailure, registerRequest, registerSuccess} from "../reducers/registerSlice";

export const registerUser = (user) => async (dispatch) => {
    try {
        dispatch(registerRequest());
        const response = await api.register(user);
        dispatch(registerSuccess());
    } catch (error) {
        dispatch(registerFailure(error.response?.data?.data?.error ?? error.message));
    }
};