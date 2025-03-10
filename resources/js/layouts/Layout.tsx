import {Navbar} from "flowbite-react";
import React, {ReactNode} from "react";
import {Outlet, useNavigate} from "react-router-dom";
import AppNavLink from "../components/AppNavLink";
import Cart from "../components/cart/Cart";
import useAppContext from "../hooks/useAppContext";
import {LOGOUT} from "../types";

export default function Layout(props: {children?: ReactNode}) {
    const {appState: {user}, appDispatch} = useAppContext()

    const navigate = useNavigate()
    const logoutClickHandler = () => {
        appDispatch({
            type: LOGOUT
        })
        navigate('/', {replace: true})
    }
    return (
        <div className="container mx-auto px-4">
            <Navbar fluid rounded >
                <Navbar.Brand href="/">
                    <span
                        className="self-center whitespace-nowrap text-xl font-semibold dark:text-white">
                        Workwize Store
                    </span>
                </Navbar.Brand>
                <Navbar.Toggle/>
                <Navbar.Collapse>
                    <AppNavLink to='/' label='Shop' />
                    {!user ? <a href="/suppliers" >Suppliers</a> : null }
                    {user
                        ? <AppNavLink to='/my-account' label='My Account' />
                        : <AppNavLink to='/login' label='Login' />
                    }
                </Navbar.Collapse>
                <div className="flex">
                    <div className="mr-4">
                        <Cart/>
                    </div>
                    {user ? <div>
                        <button className="" onClick={logoutClickHandler}>Logout</button>
                    </div>: null }
                </div>
            </Navbar>
            {props.children}
            <Outlet/>
        </div>
    )
};
