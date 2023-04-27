import React from 'react';
import { Route, Redirect } from 'react-router-dom';
import { useSelector } from 'react-redux';

const PrivateRoute = ({ component: Component, layout: Layout, ...rest }) => {
    const isLoggedIn = useSelector(state => state.auth.isLoggedIn);

    return (
        <Route {...rest} render={props => {
            if (isLoggedIn) {
                return (
                    <Layout>
                        <Component {...props} />
                    </Layout>
                );
            } else {
                return <Redirect to="/login" />;
            }
        }} />
    );
};

export default PrivateRoute;