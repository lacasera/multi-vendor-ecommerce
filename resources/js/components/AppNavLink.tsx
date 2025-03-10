import React from "react";
import {useLinkClickHandler, useLocation} from "react-router-dom";
import {Navbar} from "flowbite-react";

type Props = {
    to: string;
    label?: string,
}
export default function AppNavLink({to, label, children }: Props) {

    const location = useLocation()
    const clickHandler = useLinkClickHandler(to)

    return (
        <span onClick={clickHandler}>
             <Navbar.Link href={to} active={location.pathname === to}>
                 {label ?? children}
             </Navbar.Link>
        </span>
    )
}
