import './App.css';
import LoginLayout from "./layouts/LoginLayout";
import LoginPage from "./pages/LoginPage";
import RegisterPage from "./pages/RegisterPage";
import DashboardPage from "./pages/DashboardPage";
import AppLayout from "./layouts/AppLayout";
import {Route, BrowserRouter as Router} from "react-router-dom";
import PrivateRoute from "./components/PrivateRoute";
import Home from "./pages/Home";
import {useEffect, useState} from "react";
import {useDispatch} from "react-redux";
import {reauthenticate} from "./store/actions/authActions";
import {ClipLoader} from "react-spinners";
import ForgotPasswordPage from "./pages/ForgotPasswordPage";
import TestPage from "./pages/TestPage";
import ActivateAccountPage from "./pages/ActivateAccountPage";
import { Toaster } from 'react-hot-toast';
import ToastRemover from "./components/ToastRemover";
import NewPasswordPage from "./pages/NewPasswordPage";

function App() {

    const dispatch = useDispatch();
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        const token = sessionStorage.getItem('token') || localStorage.getItem('token');
        if (token) {
            dispatch(reauthenticate(token)).finally(() => {
                setLoading(false);
            });
        } else {
            setLoading(false);
        }
    }, [dispatch]);

    if (loading) {
        return <div className="flex items-center justify-center h-screen">
            <ClipLoader size={50} color="#10B981" />
        </div>;
    }

    return (
        <>
            <Router>
                <Route path="/login" render={() => <LoginLayout><LoginPage /></LoginLayout>} />
                <Route path="/forgot-password" render={() => <LoginLayout><ForgotPasswordPage /></LoginLayout>} />
                <Route path="/register" render={() => <LoginLayout><RegisterPage /></LoginLayout>} />
                <Route path="/test" render={() => <LoginLayout><TestPage /></LoginLayout>} />
                <Route path="/activate/:id" render={() => <ActivateAccountPage />} />
                <Route path="/new-password/:id" render={() => <LoginLayout><NewPasswordPage /></LoginLayout>} />
                <PrivateRoute exact={true} path="/" component={Home} layout={AppLayout} />
                <PrivateRoute exact={true} path="/dashboard" component={DashboardPage} layout={AppLayout} />
                <Toaster />
                <ToastRemover />
            </Router>
        </>
    );
}

export default App;
