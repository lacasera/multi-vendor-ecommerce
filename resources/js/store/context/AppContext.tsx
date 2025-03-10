import React from "react";
import {createContext, FC, useReducer} from "react";
import AppReducer from "../reducers/AppReducer";
import {AppContextType, CartType, PERSISTED_STATE_KEY, UserType} from "../../types";

export type AppStateType = {
    cart: CartType,
    user?: UserType,
}

const persistedState = localStorage.getItem(PERSISTED_STATE_KEY);

let appState = persistedState ? JSON.parse(persistedState) : {
    cart: {
        items: []
    },
    user: null
}

export  const AppContext = createContext<AppContextType | undefined>(undefined)

export const AppContextProvider = ({ children }) => {

    const [state, dispatch] = useReducer(AppReducer, appState);

    return (
        <AppContext.Provider value={{state , dispatch}}>
            {children}
        </AppContext.Provider>
    )
}
