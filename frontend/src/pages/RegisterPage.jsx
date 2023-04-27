import React, {useEffect} from 'react';
import {Link} from "react-router-dom";
import {useDispatch, useSelector} from "react-redux";
import {registerUser} from "../store/actions/registerActions";
import * as Yup from 'yup';
import {ErrorMessage, Field, Form, Formik} from "formik";
import {isEmpty} from "../utils";
import {disableRegisterClientSideValidation} from "../settings";
import toast from "react-hot-toast";
import {ClipLoader} from "react-spinners";

const RegisterPage = () => {
    const dispatch = useDispatch();

    const validationSchema = Yup.object().shape({
        firstName: Yup.string()
            .required('First name is required'),
        lastName: Yup.string()
            .required('Last name is required'),
        email: Yup.string()
            .email('Invalid email')
            .required('Email is required'),
        username: Yup.string()
            .required('Username is required'),
        password: Yup.string()
            .min(8, 'Password must be at least 8 characters')
            .required('Password is required'),
        confirmPassword: Yup.string()
            .oneOf([Yup.ref('password'), null], 'Passwords must match')
            .required('Confirm password is required')
    });

    const error = useSelector((state) => {
        return state.register.error;
    });

    const success = useSelector((state) => {
        return state.register.success;
    });

    useEffect(() => {
        if (error) {
            toast.error(error);
        }
    }, [error])

    useEffect(() => {
        if (success) {
            toast.success('Successfully registered.');
        }
    }, [success])

    return (
        <div className="w-full max-w-md">
            <Formik
                initialValues={{
                    email: '',
                    firstName: '',
                    lastName: '',
                    username: '',
                    password: '',
                    confirmPassword: '',
                }}
                validationSchema={disableRegisterClientSideValidation ? undefined : validationSchema}
                onSubmit={async (values, {setSubmitting}) => {
                    await dispatch(registerUser(values));
                    await setSubmitting(false);
                }}
            >
                {({isSubmitting, touched, errors, handleBlur}) => {
                    return (
                        <Form>
                            <div className="bg-slate-100 shadow-sm rounded px-8 pt-6 pb-8 mb-4">
                                <h2 className="text-center text-2xl font-bold mb-4">Register</h2>
                                <div className="mb-4">
                                    <label className="block text-gray-700 text-sm font-bold mb-2" htmlFor="email">
                                        Email
                                    </label>
                                    <Field
                                        type="text"
                                        name="email"
                                        id="email"
                                        placeholder="Email"
                                        onBlur={handleBlur}
                                        className={`shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline ${
                                            touched.email && errors.email ? "border-red-500" : ""
                                        }`}
                                    />
                                    <ErrorMessage name="email">
                                        {(msg) => {
                                            return (
                                                <div className="text-red-500 text-sm mt-1">
                                                    {touched.email && msg}
                                                </div>
                                            );
                                        }}
                                    </ErrorMessage>
                                </div>

                                <div className="mb-4">
                                    <label className="block text-gray-700 text-sm font-bold mb-2" htmlFor="firstName">
                                        First Name
                                    </label>
                                    <Field
                                        type="text"
                                        name="firstName"
                                        id="firstName"
                                        placeholder="First Name"
                                        onBlur={handleBlur}
                                        className={`shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline ${
                                            touched.firstName && errors.firstName ? "border-red-500" : ""
                                        }`}
                                    />
                                    <ErrorMessage name="firstName">
                                        {(msg) => {
                                            return (
                                                <div className="text-red-500 text-sm mt-1">
                                                    {touched.firstName && msg}
                                                </div>
                                            );
                                        }}
                                    </ErrorMessage>
                                </div>

                                <div className="mb-4">
                                    <label className="block text-gray-700 text-sm font-bold mb-2" htmlFor="lastName">
                                        Last Name
                                    </label>
                                    <Field
                                        type="text"
                                        name="lastName"
                                        id="lastName"
                                        placeholder="Last Name"
                                        onBlur={handleBlur}
                                        className={`shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline ${
                                            touched.lastName && errors.lastName ? "border-red-500" : ""
                                        }`}
                                    />
                                    <ErrorMessage name="lastName">
                                        {(msg) => {
                                            return (
                                                <div className="text-red-500 text-sm mt-1">
                                                    {touched.lastName && msg}
                                                </div>
                                            );
                                        }}
                                    </ErrorMessage>
                                </div>

                                <div className="mb-4">
                                    <label className="block text-gray-700 text-sm font-bold mb-2" htmlFor="username">
                                        Username
                                    </label>
                                    <Field
                                        type="text"
                                        name="username"
                                        id="username"
                                        placeholder="Username"
                                        onBlur={handleBlur}
                                        className={`shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline ${
                                            touched.username && errors.username ? "border-red-500" : ""
                                        }`}
                                    />
                                    <ErrorMessage name="username">
                                        {(msg) => {
                                            return (
                                                <div className="text-red-500 text-sm mt-1">
                                                    {touched.username && msg}
                                                </div>
                                            );
                                        }}
                                    </ErrorMessage>
                                </div>

                                <div className="mb-6">
                                    <label className="block text-gray-700 text-sm font-bold mb-2" htmlFor="password">
                                        Password
                                    </label>
                                    <Field
                                        type="password"
                                        name="password"
                                        id="password"
                                        placeholder="Password"
                                        onBlur={handleBlur}
                                        className={`shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline ${
                                            touched.password && errors.password ? "border-red-500" : ""
                                        }`}
                                    />
                                    <ErrorMessage name="password">
                                        {(msg) => {
                                            return (
                                                <div className="text-red-500 text-sm mt-1">
                                                    {touched.password && msg}
                                                </div>
                                            );
                                        }}
                                    </ErrorMessage>
                                </div>
                                <div className="mb-6">
                                    <label className="block text-gray-700 text-sm font-bold mb-2"
                                           htmlFor="confirmPassword">
                                        Confirm Password
                                    </label>
                                    <Field
                                        type="password"
                                        name="confirmPassword"
                                        id="confirmPassword"
                                        placeholder="Confirm Password"
                                        onBlur={handleBlur}
                                        className={`shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline ${
                                            touched.confirmPassword && errors.confirmPassword ? "border-red-500" : ""
                                        }`}
                                    />
                                    <ErrorMessage name="confirmPassword">
                                        {(msg) => {
                                            return (
                                                <div className="text-red-500 text-sm mt-1">
                                                    {touched.confirmPassword && msg}
                                                </div>
                                            );
                                        }}
                                    </ErrorMessage>
                                </div>
                                <div className="flex items-center justify-between">
                                    <button
                                        disabled={isSubmitting || !isEmpty(errors)}
                                        className={`${
                                            isSubmitting || !isEmpty(errors) ? "bg-blue-500 opacity-50 cursor-not-allowed" : "bg-blue-500 hover:bg-blue-700"
                                        } text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline disabled:bg-blue-200`}
                                        type="submit"
                                    >
                                        {isSubmitting ? (
                                            <ClipLoader color="#FFFFFF" loading={isSubmitting} size={20} />
                                        ) : (
                                            "Register"
                                        )}
                                    </button>
                                    <Link to="/login" className="text-sm text-blue-500 hover:text-blue-800">
                                        Already have an account?
                                    </Link>
                                </div>
                            </div>
                        </Form>
                    );
                }}
            </Formik>
        </div>
    );
};

export default RegisterPage;