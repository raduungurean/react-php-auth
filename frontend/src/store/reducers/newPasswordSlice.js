import {createSlice} from '@reduxjs/toolkit';

const newPasswordSlice = createSlice({
    name: 'newPassword',
    initialState: {
        isLoading: false,
        isSuccess: false,
        error: null,
    },
    reducers: {
        newPasswordRequest: (state) => {
            state.isLoading = true;
            state.isSuccess = false;
            state.error = null;
        },
        newPasswordSuccess: (state) => {
            state.isLoading = false;
            state.isSuccess = true;
            state.error = null;
        },
        newPasswordFailure: (state, action) => {
            state.isLoading = false;
            state.isSuccess = false;
            state.error = action.payload;
        }
    },
});

export const {
    newPasswordRequest,
    newPasswordSuccess,
    newPasswordFailure,
} = newPasswordSlice.actions;

export default newPasswordSlice.reducer;
