import React, {useEffect} from 'react';
import {useLocation} from "react-router-dom";
import toast from "react-hot-toast";

const ToastRemover = () => {

    const location = useLocation();

    useEffect(() => {
        if (location) {
            console.log(`Route changed to: ${location.pathname}`);
            toast.dismiss();
        }
    }, [location]);

    return (
        <div>

        </div>
    );
};

export default ToastRemover;