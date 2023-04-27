import {createSlice} from '@reduxjs/toolkit';

const forgotPasswordSlice = createSlice({
    name: 'forgotPassword',
    initialState: {
        isLoading: false,
        isSuccess: false,
        error: null,
    },
    reducers: {
        forgotPasswordRequest: (state) => {
            state.isLoading = true;
            state.isSuccess = false;
            state.error = null;
        },
        forgotPasswordSuccess: (state) => {
            state.isLoading = false;
            state.isSuccess = true;
            state.error = null;
        },
        forgotPasswordFailure: (state, action) => {
            state.isLoading = false;
            state.isSuccess = false;
            state.error = action.payload;
        },
    },
});

export const {
    forgotPasswordRequest,
    forgotPasswordSuccess,
    forgotPasswordFailure,
} = forgotPasswordSlice.actions;

export default forgotPasswordSlice.reducer;
