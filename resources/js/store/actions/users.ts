import {useMutation} from "@tanstack/react-query";
import {loginRequest, registerRequest} from "../requests/users";
import useAppContext from "../../hooks/useAppContext";
import {SET_AUTH_USER} from "../../types";
import {useNavigate} from "react-router-dom";
import {notify} from "../../helpers";

export const useLoginAction = () => {

    const {appDispatch} = useAppContext();
    const navigate = useNavigate();

    const { mutate, isPending } = useMutation({
        mutationFn: loginRequest,
        onSuccess: async (response) => {
            const json =  response.data.data
            appDispatch({
                type: SET_AUTH_USER,
                payload: {
                    id: json.user.id,
                    username: json.user.name,
                    email: json.user.email,
                    token: json.token
                }
            })

            navigate('/', {replace: true})
        },
        onError: (error) => {
            notify("Username and password not found", "error")
        }
    })

    return {isLoginPending: isPending, loginCall: mutate}
}

export const useRegisterAction = () => {

    const { appDispatch } = useAppContext();
    const navigate = useNavigate();

    const { mutate, isPending } = useMutation({
        mutationFn: registerRequest,
        onSuccess: async (response) => {
            const json =  response.data.data
            appDispatch({
                type: SET_AUTH_USER,
                payload: {
                    id: json.user.id,
                    username: json.user.name,
                    email: json.user.email,
                    token: json.token
                }
            })

           navigate('/', {replace: true})
        },

        onError: (error) => {
            notify("Unable to sign up please check and try again", "error")
        }
    })

    return {isRegisterPending: isPending, registerUserCall: mutate}
}
