import React from 'react';

const LoginLayout = ({ children }) => {
    return (
        <div className="container mx-auto flex justify-center items-center h-screen">
            <div className="bg-white w-full max-w-md p-6 rounded-md">{children}</div>
        </div>
    );
};

export default LoginLayout;
