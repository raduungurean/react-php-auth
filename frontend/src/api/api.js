import axios from 'axios';
import {BASE_URL} from "../constants";

export const authenticate = async (username, password) => {
    const response = await axios.post(`${BASE_URL}/login`, { username, password });
    return response.data;
};

export const logout = async () => {
    const response = await axios.post(`${BASE_URL}/logout`);
    return response.data;
};

export const reauthenticate = async (token) => {
    const response = await axios.post(`${BASE_URL}/reauthenticate`, { token });
    return response.data;
};

export const register = async (user) => {
    const response = await axios.post(`${BASE_URL}/register`, user);
    return response.data;
};

export const activateAccount = async (activationCode) => {
    const response = await axios.post(`${BASE_URL}/activate`, { activationCode });
    return response.data;
};

export const forgotPassword = async (email) => {
    const response = await axios.post(`${BASE_URL}/forgot-password`, { email });
    return response.data;
};

export const newPassword = async (data) => {
    const response = await axios.post(`${BASE_URL}/new-password`, data);
    return response.data;
};

export const checkForgotToken = (id) => {
    return axios.post(`${BASE_URL}/check-forgot-token`, {token: id});
};