import * as api from "../../api/api";
import {
    forgotPasswordFailure,
    forgotPasswordRequest,
    forgotPasswordSuccess
} from "../reducers/forgotPasswordSlice";


export const forgotPassword = (user) => async (dispatch) => {
    try {
        dispatch(forgotPasswordRequest());
        const response = await api.forgotPassword(user);
        dispatch(forgotPasswordSuccess());
    } catch (error) {
        dispatch(forgotPasswordFailure(error.message));
    }
};