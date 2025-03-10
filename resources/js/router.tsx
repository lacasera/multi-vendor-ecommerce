import React from 'react';
import { Route, createBrowserRouter, createRoutesFromElements } from 'react-router-dom';
import Shop from "./pages/Shop";
import Layout from "./layouts/Layout";
import NotFound from "./components/404";
import Checkout from "./pages/Checkout";
import Login from "./pages/Login";
import Register from "./pages/Rigester";
import MyAccount from "./pages/MyAccount";
import CheckoutVerify from "./pages/CheckoutVerify";
import Loader from "./components/Loader";

const router = createBrowserRouter(
    createRoutesFromElements(
        <Route path="/" element={<Layout />}>
            <Route index element={
                    <React.Suspense>
                        <Shop />
                    </React.Suspense>
                }
            />
            <Route
                path="/login"
                element={
                    <React.Suspense fallback={<Loader />}>
                        <Login />
                    </React.Suspense>
                }
            />

            <Route
                path="/sign-up"
                element={
                    <React.Suspense fallback={<Loader />}>
                        <Register />
                    </React.Suspense>
                }
            />

            <Route
                path="/checkout"
                element={
                    <React.Suspense fallback={<Loader />}>
                        <Checkout />
                    </React.Suspense>
                }
            />

            <Route
                path="/verify/:checkout_code"
                element={
                    <React.Suspense fallback={<Loader />}>
                        <CheckoutVerify />
                    </React.Suspense>
                }
            />

            <Route
                path="/my-account"
                element={
                    <React.Suspense fallback={<Loader />}>
                        <MyAccount />
                    </React.Suspense>
                }
            />

            <Route path="*" element={<NotFound />} />

        </Route>
    )
);

export default router;
