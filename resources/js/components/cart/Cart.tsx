import React, {useState} from "react";
import useAppContext from "../../hooks/useAppContext";
import CartItems from "./CartItems";
export default function Cart() {
    const {appState: {cart}} = useAppContext()
    const [showCartItems, setShowCartItems] = useState(false)

    return (
        <div>
            <button className="relative flex items-center justify-center text-dark dark:text-white"
                    onClick={() => setShowCartItems(!showCartItems)}>
                <svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg"
                     className="fill-current">
                    <path
                        d="M22.9125 7.96252H21.9375L19.2969 1.30002C19.0938 0.812515 18.5656 0.60939 18.1188 0.77189C17.6313 0.975015 17.4281 1.50314 17.5906 1.95002L19.9469 7.96252H6.05314L8.40939 1.99064C8.61251 1.50314 8.36876 0.975015 7.88126 0.812515C7.43439 0.60939 6.90626 0.812515 6.70314 1.30002L4.06251 7.96252H3.08751C2.35626 7.96252 1.74689 8.57189 1.74689 9.30314V12.5938C1.74689 13.2844 2.23439 13.8125 2.92501 13.8938L3.94064 22.75C4.10314 24.2125 5.32189 25.3094 6.78439 25.3094H19.2156C20.6781 25.3094 21.8969 24.2125 22.0594 22.75L23.075 13.8938C23.725 13.8125 24.2531 13.2438 24.2531 12.5938V9.26251C24.2531 8.53126 23.6438 7.96252 22.9125 7.96252ZM3.57501 9.79064H22.425V12.1063H3.57501V9.79064ZM19.2156 23.4813H6.78439C6.25626 23.4813 5.80939 23.075 5.72814 22.5469L4.75314 13.9344H21.2469L20.2719 22.5469C20.1906 23.075 19.7438 23.4813 19.2156 23.4813Z"></path>
                    <path
                        d="M8.85625 15.7626C8.36875 15.7626 7.92188 16.1688 7.92188 16.6969V19.7438C7.92188 20.2313 8.32812 20.6782 8.85625 20.6782C9.38437 20.6782 9.79062 20.2719 9.79062 19.7438V16.6563C9.79062 16.1688 9.38437 15.7626 8.85625 15.7626Z"></path>
                    <path
                        d="M17.1438 15.7626C16.6563 15.7626 16.2094 16.1688 16.2094 16.6969V19.7438C16.2094 20.2313 16.6156 20.6782 17.1438 20.6782C17.6719 20.6782 18.0781 20.2719 18.0781 19.7438V16.6563C18.0375 16.1688 17.6313 15.7626 17.1438 15.7626Z"></path>
                </svg>
                <span
                    className="absolute -right-2 -top-2 h-4 w-4 rounded-full bg-blue-600 text-[10px] font-normal text-white">
                {cart.items.reduce((acc, item) => acc + item.quantity, 0)}
            </span>
            </button>
            <CartItems open={showCartItems} setOpen={setShowCartItems}/>
        </div>

    )
}
