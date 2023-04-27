import * as api from "../../api/api";
import {
    newPasswordFailure,
    newPasswordRequest,
    newPasswordSuccess
} from "../reducers/newPasswordSlice";
export const newPassword = (data, id) => async (dispatch) => {
    try {
        dispatch(newPasswordRequest());
        const response = await api.newPassword({
            ...data,
            token: id,
        });
        dispatch(newPasswordSuccess());
    } catch (error) {
        dispatch(newPasswordFailure(error.message));
    }
};