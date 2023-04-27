import {useEffect, useState} from "react";
import {login} from "../store/actions/authActions";
import {useDispatch, useSelector} from "react-redux";
import {Link, useHistory} from "react-router-dom";
import {setRemember} from "../store/reducers/authSlice";
import {ClipLoader} from "react-spinners";
import toast from "react-hot-toast";

function LoginPage() {
    const [email, setEmail] = useState("");
    const [password, setPassword] = useState("");
    const dispatch = useDispatch();
    const history = useHistory();
    const [remember, setRememberState] = useState(false);
    const isAuthenticated = useSelector((state) => {
        return state.auth.isLoggedIn;
    });
    const isLoggingIn = useSelector((state) => {
        return state.auth.isLoggingIn;
    });

    useEffect(() => {
        if (isAuthenticated) {
            history.push('/dashboard');
        }
    }, [isAuthenticated])

    const handleSubmit = (event) => {
        event.preventDefault();
        dispatch(login(email, password));
    };

    const handleRememberChange = (event) => {
        setRememberState(event.target.checked);
        dispatch(setRemember(event.target.checked));
    };

    const error = useSelector((state) => {
        return state.auth.error;
    });

    useEffect(() => {
        if (error) {
            toast.error('Invalid username or password.');
        }
    }, [error])

    return (
        <div className="w-full max-w-md">
            <form className="bg-slate-100 shadow-sm rounded px-8 pt-6 pb-8 mb-4" onSubmit={handleSubmit}>
                <h2 className="text-center text-2xl font-bold mb-4">Sign In</h2>
                <div className="mb-4">
                    <label className="block text-gray-700 text-sm font-bold mb-2" htmlFor="email">
                        Username
                    </label>
                    <input
                        className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="email"
                        type="text"
                        placeholder="Username"
                        value={email}
                        onChange={(e) => setEmail(e.target.value)}
                    />
                </div>
                <div className="mb-6">
                    <label className="block text-gray-700 text-sm font-bold mb-2" htmlFor="password">
                        Password
                    </label>
                    <input
                        className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="password"
                        type="password"
                        placeholder="Password"
                        value={password}
                        onChange={(e) => setPassword(e.target.value)}
                    />
                </div>
                <div className="mb-4 flex items-center justify-between">
                    <label className="text-gray-700 font-bold">
                        <input
                            className="mr-2 leading-tight"
                            type="checkbox"
                            checked={remember}
                            onChange={handleRememberChange}
                        />
                        <span className="text-sm">Remember me</span>
                    </label>
                    <Link to="/forgot-password" className="text-sm text-blue-500 hover:text-blue-800">
                        Forgot password?
                    </Link>
                </div>
                <div className="flex items-center justify-between">
                    {isLoggingIn ? (
                        <button
                            className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                            type="button"
                            disabled
                        >
                            <ClipLoader size={12} color={"#fff"} loading={true} />
                        </button>
                    ) : (
                        <button
                            className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                            type="submit"
                        >
                            Sign In
                        </button>
                    )}
                    <Link to="/register" className="text-sm text-blue-500 hover:text-blue-800">
                        Don't have an account?
                    </Link>
                </div>
            </form>
        </div>
    );
}

export default LoginPage;
