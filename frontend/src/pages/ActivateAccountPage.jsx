import React, {useEffect} from 'react';
import {useHistory, useParams} from "react-router-dom";
import {activateAccount} from "../api/api";
import toast from "react-hot-toast";

const ActivateAccountPage = () => {

    const { id } = useParams();
    const history = useHistory();

    useEffect(() => {
        activateAccount(id)
            .then(() => {
                toast.success('Account activated successfully!');
            })
            .catch((error) => {
                toast.error('Activation failed. Please try again later.');
                console.error(error);
            }).finally(() => history.push('/'));
    }, [id, history, toast]);

    return null;
};

export default ActivateAccountPage;