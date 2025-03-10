import React from "react"
import ReactDOM from "react-dom/client"
import App from "./App"
import {AppContextProvider} from "./store/context/AppContext";
import {QueryClient, QueryClientProvider} from "@tanstack/react-query";
import {Bounce, ToastContainer} from "react-toastify";
const queryClient = new QueryClient()

ReactDOM.createRoot(document.getElementById("root") as HTMLElement).render(

    <React.StrictMode>
        <QueryClientProvider client={queryClient}>
            <AppContextProvider>
                <App />
                <ToastContainer
                    position="top-right"
                    autoClose={4000}
                    hideProgressBar={false}
                    newestOnTop={false}
                    closeOnClick={false}
                    rtl={false}
                    pauseOnFocusLoss
                    draggable
                    pauseOnHover
                    theme="colored"
                    transition={Bounce}
                />
            </AppContextProvider>
        </QueryClientProvider>

    </React.StrictMode>
)
