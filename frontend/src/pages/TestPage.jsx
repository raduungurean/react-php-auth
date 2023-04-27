import React, { useState } from "react";
import { Formik, Form, Field, ErrorMessage } from "formik";
import * as Yup from "yup";

const validationSchema = Yup.object().shape({
    email: Yup.string().email("Invalid email").required("Email is required"),
    password: Yup.string().required("Password is required").min(6, "Password is too short"),
});

const TestForm = () => {
    const [formValues, setFormValues] = useState({ email: "", password: "" });

    const handleFormChange = (event) => {
        const { name, value } = event.target;
        setFormValues({ ...formValues, [name]: value });
    };

    return (
        <div className="max-w-md mx-auto">
            <h1 className="text-2xl font-bold mb-4">Login Form</h1>
            <Formik
                initialValues={{ email: "", password: "" }}
                validationSchema={validationSchema}
                onSubmit={(values, { setSubmitting }) => {
                    setTimeout(() => {
                        alert(JSON.stringify(values, null, 2));
                        setSubmitting(false);
                    }, 400);
                }}
            >
                {({ isSubmitting, touched, errors }) => (
                    <Form>
                        <div className="mb-4">
                            <label htmlFor="email" className="block text-gray-700 font-bold mb-2">
                                Email
                            </label>
                            <Field
                                type="text"
                                name="email"
                                id="email"
                                onBlur={(event) => {
                                    event.target.onfocus = () => {};
                                    event.target.onblur = () => {};
                                }}
                                className={`appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline ${
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
                            <label htmlFor="password" className="block text-gray-700 font-bold mb-2">
                                Password
                            </label>
                            <Field
                                type="password"
                                name="password"
                                id="password"
                                onBlur={(event) => {
                                    event.target.onfocus = () => {}
                                    event.target.onblur = () => {};
                                }}
                                className={`appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline ${
                                    touched.password && errors.password ? "border-red-500" : ""
                                }`}
                            />
                            <ErrorMessage name="password">
                                {(msg) => (
                                    <div className="text-red-500 text-sm mt-1">
                                        {touched.password && msg}
                                    </div>
                                )}
                            </ErrorMessage>
                        </div>

                        <button
                            type="submit"
                            disabled={isSubmitting}
                            className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                        >
                            Submit
                        </button>
                    </Form>
                )}
            </Formik>
        </div>
    );
};

export default TestForm;
