import {Formik, Field, ErrorMessage, Form} from "formik";
import * as Yup from "yup";
import {ClipLoader} from "react-spinners";
import {useDispatch, useSelector} from "react-redux";
import {useEffect} from "react";
import toast from "react-hot-toast";
import {newPassword} from "../store/actions/newPasswordActions";
import {Link, useParams} from "react-router-dom";
import * as api from "../api/api";

function NewPasswordPage() {
    const dispatch = useDispatch();
    const { id } = useParams();

    const initialValues = {
        password: "",
        confirmPassword: "",
    };

    const validationSchema = Yup.object({
        password: Yup.string()
            .required("Please enter a new password")
            .min(8, "Password must be at least 8 characters long"),
        confirmPassword: Yup.string()
            .required("Please confirm your new password")
            .oneOf([Yup.ref("password")], "Passwords do not match"),
    });

    const handleSubmit = async (values, {setSubmitting}) => {
        setSubmitting(false);
        await dispatch(newPassword(values, id));
    };

    const error = useSelector((state) => {
        return state.newPassword.error;
    });

    const success = useSelector((state) => {
        return state.newPassword.isSuccess;
    });

    const isLoading = useSelector((state) => {
        return state.newPassword.isLoading;
    });


    useEffect( () => {
        if (id) {
            toast.promise(
                api.checkForgotToken(id),
                {
                    loading: "Loading...",
                    success: "Loaded...",
                    error: "The forgot password token is invalid or has expired.",
                    retry: "Retry",
                }
            );
        }
    }, [id])

    useEffect(() => {
        if (error) {
            toast.error('An error occurred. Please try again later.');
        }
    }, [error])

    useEffect(() => {
        if (success) {
            toast.custom((t) => (
                <div className="flex items-center justify-between bg-green-500 text-white py-2 px-4 rounded">
                    <div>Password successfully changed!</div>
                    <Link to="/login" className="underline ml-4">
                        Go to login
                    </Link>
                </div>
            ), { duration: Infinity });
        }
    }, [success]);

    return (
        <div className="w-full max-w-md">
            <Formik
                initialValues={initialValues}
                validationSchema={validationSchema}
                onSubmit={handleSubmit}
            >
                {({ isSubmitting, errors, touched }) => (
                    <Form className="bg-slate-100 shadow-sm rounded px-8 pt-6 pb-8 mb-4">
                        <h2 className="text-center text-2xl font-bold mb-4">
                            Create New Password
                        </h2>
                        <div className="mb-4">
                            <label
                                className="block text-gray-700 text-sm font-bold mb-2"
                                htmlFor="password"
                            >
                                New Password
                            </label>
                            <Field
                                type="password"
                                name="password"
                                id="password"
                                placeholder="New Password"
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
                        <div className="mb-4">
                            <label
                                className="block text-gray-700 text-sm font-bold mb-2"
                                htmlFor="confirmPassword"
                            >
                                Confirm New Password
                            </label>
                            <Field
                                type="password"
                                name="confirmPassword"
                                id="confirmPassword"
                                placeholder="Confirm New Password"
                                className={`shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline ${
                                    touched.confirmPassword && errors.confirmPassword
                                        ? "border-red-500"
                                        : ""
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
                                className={`${
                                    isLoading ? "bg-blue-500 opacity-50 cursor-not-allowed" : "bg-blue-500 hover:bg-blue-700"
                                } text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline`}
                                type="submit"
                                disabled={isLoading || isSubmitting}
                            >
                                {isLoading ? (
                                    <ClipLoader color="#FFFFFF" loading={isLoading} size={20}/>
                                ) : (
                                    "Create New Password"
                                )}
                            </button>
                            <Link to="/login" className="text-blue-500 hover:underline">Back to Login</Link>
                        </div>
                    </Form>
                )}
            </Formik>
        </div>
    );
};

export default NewPasswordPage;