import React, {useEffect, useState} from 'react';
import { Link } from 'react-router-dom';
import {useDispatch, useSelector} from "react-redux";
import {forgotPassword} from "../store/actions/forgotPasswordActions";
import toast from "react-hot-toast";
import {ClipLoader} from "react-spinners";

const ForgotPasswordPage = () => {
    const [email, setEmail] = useState('');
    const dispatch = useDispatch();
    const [errorMessage, setErrorMessage] = useState('');

    const handleBlur = () => {
        // Check if email format is valid
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            setErrorMessage('Invalid email format');
        } else {
            setErrorMessage('');
        }
    };

    const handleChange = (e) => {
        // Clear error message when email is valid
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (e.target.value === '') {
            setErrorMessage('Email is required');
        } else if (emailRegex.test(e.target.value)) {
            setErrorMessage('');
        } else {
            setErrorMessage('Invalid email format');
        }
        setEmail(e.target.value);
    };
    const handleSubmit = (e) => {
        e.preventDefault();
        dispatch(forgotPassword(email));
    };

    const success = useSelector((state) => {
        return state.forgotPassword.isSuccess;
    });

    const error = useSelector((state) => {
        return state.forgotPassword.error;
    });

    const isLoading = useSelector((state) => {
        return state.forgotPassword.isLoading;
    });

    useEffect(() => {
        if (error) {
            toast.error('Oops! We\'re sorry, but we encountered an error while processing your password reset request. Please try again later or contact our support team for assistance.', {
                duration: 10000,
            });
        }
    }, [error])

    useEffect(() => {
        if (success) {
            toast.success('Thank you for your password reset request. Please check your email ' + email + ' for further instructions. If you do not receive an email from us within the next few minutes, please check your spam folder. If you are still having trouble, please contact our support team for assistance.', {
                duration: 16000,
            });
        }
    }, [success]);

    return (
        <div className="w-full max-w-md">
            <form className="bg-slate-100 shadow-sm rounded px-8 pt-6 pb-8 mb-4" onSubmit={handleSubmit}>
                <h2 className="text-center text-2xl font-bold mb-4">Forgot Password</h2>
                <div className="mb-4">
                    <label className="block text-gray-700 text-sm font-bold mb-2" htmlFor="email">
                        Email
                    </label>
                    <input
                        className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="email"
                        type="text"
                        placeholder="Email"
                        value={email}
                        onBlur={handleBlur}
                        onChange={handleChange}
                    />
                    <div className="text-red-500 text-sm mt-1">
                        {errorMessage}
                    </div>
                </div>
                <div className="flex items-center justify-between">
                    <button
                        className={`${
                            isLoading ? "bg-blue-500 opacity-50 cursor-not-allowed" : "bg-blue-500 hover:bg-blue-700"
                        } text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline`}
                        type="submit"
                        disabled={isLoading}
                    >
                        {isLoading ? (
                            <ClipLoader color="#FFFFFF" loading={isLoading} size={20} />
                        ) : (
                            "Send Password Reset Link"
                        )}
                    </button>
                    <Link to="/login" className="text-sm text-blue-500 hover:text-blue-800">
                        Back to Login
                    </Link>
                </div>
            </form>
        </div>
    );
};

export default ForgotPasswordPage;
