import { combineReducers } from 'redux';
import authReducer from './reducers/authSlice';
import registerReducer from './reducers/registerSlice';
import forgotPasswordReducer from './reducers/forgotPasswordSlice';
import newPassword from './reducers/newPasswordSlice';

const rootReducer = combineReducers({
    auth: authReducer,
    register: registerReducer,
    newPassword: newPassword,
    forgotPassword: forgotPasswordReducer,
});

export default rootReducer;