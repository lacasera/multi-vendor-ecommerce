import axios from "axios";
import {LoginPayloadType, RegisterPayloadType} from "../../types";

export const loginRequest = (data: LoginPayloadType) => {
    return axios.post('/api/auth/login', data)
}

export const registerRequest = (data: RegisterPayloadType) => {
    return axios.post('/api/auth/register', data)
}
