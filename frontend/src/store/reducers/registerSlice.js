import { createSlice } from '@reduxjs/toolkit';

const initialState = {
    loading: false,
    error: null,
    success: false,
};

export const registerSlice = createSlice({
    name: 'register',
    initialState,
    reducers: {
        registerRequest: (state) => {
            state.loading = true;
            state.error = null;
            state.success = false;
        },
        registerSuccess: (state, action) => {
            state.loading = false;
            state.error = null;
            state.success = true;
        },
        registerFailure: (state, action) => {
            state.loading = false;
            state.error = action.payload;
            state.success = false;
        }
    },
});

export default registerSlice.reducer;
export const { registerRequest, registerSuccess, registerFailure } = registerSlice.actions;