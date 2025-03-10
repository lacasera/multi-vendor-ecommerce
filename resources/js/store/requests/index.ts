import Axios, {CreateAxiosDefaults} from 'axios';
import {PERSISTED_STATE_KEY} from "../../types";
import {AppStateType} from "../context/AppContext";

export const axios = Axios.create({
    headers: {
        'Accept': 'application/json',
    }
} as CreateAxiosDefaults);

/**
 * Intercept each request and set the bearer token for user
 */
axios.interceptors.request.use(async (config) => {

    let state : AppStateType | null | string = localStorage.getItem(PERSISTED_STATE_KEY);
    state = state ? JSON.parse(state) as AppStateType : null

    if (state?.user && !config.headers.Authorization) {
        config.headers.Authorization = ` Bearer ${state.user.token}`;
    }

    return config;
});
