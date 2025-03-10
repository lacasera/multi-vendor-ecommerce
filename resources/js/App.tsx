import React, {createContext} from "react";
import {RouterProvider} from "react-router-dom";
import router from "./router";

function App() {
    const CartContext = createContext(null);

    return (
        <RouterProvider router={ router} />
    )
}

export default App
