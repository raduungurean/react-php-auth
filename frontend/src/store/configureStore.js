import {configureStore} from '@reduxjs/toolkit';
import rootReducer from './index'

const store = configureStore({
    reducer: rootReducer,
    middleware: (m) => m()
});

export default store;
