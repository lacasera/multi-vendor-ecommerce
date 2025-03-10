
import React, {MouseEvent } from 'react'
import { Dialog, DialogBackdrop, DialogPanel, DialogTitle } from '@headlessui/react'
import { XMarkIcon } from '@heroicons/react/24/outline'
import useAppContext from "../../hooks/useAppContext";
import {formatCurrency} from "../../helpers";
import {useNavigate} from "react-router-dom";
import {
    CLEAR_CART,
    INCREASE_CART_ITEM_QTY,
    REDUCE_CART_ITEM_QTY,
    REMOVE_CART_ITEM,
    UPDATE_CART_ITEM_QUANTITY
} from "../../types";

export default function CartItems({open, setOpen}) {

    const navigate = useNavigate();

    const {appState: {cart}, appDispatch} = useAppContext()

    const cartTotal = cart.items.reduce((acc, item) => (item.quantity * item.price) + acc ,0)

    const handleCheckoutClick = () => {
        setOpen(false)
        navigate('/checkout', {replace: true})
    }

    const handleRemoveCartItem = (product_id: number) => {
        appDispatch({
            type: REMOVE_CART_ITEM,
            payload: {
                product_id
            }
        })
    }

    const handleCartItemQtyChange = (product_id: number, operation: string) => {
        appDispatch({
            type: UPDATE_CART_ITEM_QUANTITY,
            payload: {
                product_id,
                operation
            }
        })
    }

    const handleClearCart = () => {
        appDispatch({
            type: CLEAR_CART
        })

        setOpen(false)
    }

    return (
        <Dialog open={open} onClose={setOpen} className="relative z-10">
            <DialogBackdrop
                transition
                className="fixed inset-0 bg-gray-500/75 transition-opacity duration-500 ease-in-out data-[closed]:opacity-0"
            />

            <div className="fixed inset-0 overflow-hidden">
                <div className="absolute inset-0 overflow-hidden">
                    <div className="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                        <DialogPanel
                            transition
                            className="pointer-events-auto w-screen max-w-md transform transition duration-500 ease-in-out data-[closed]:translate-x-full sm:duration-700"
                        >
                            <div className="flex h-full flex-col overflow-y-scroll bg-white shadow-xl">
                                <div className="flex-1 overflow-y-auto px-4 py-6 sm:px-6">
                                    <div className="flex items-start justify-between">
                                        <DialogTitle className="text-lg font-medium text-gray-900">Shopping cart</DialogTitle>
                                        <div className="ml-3 flex h-7 items-center">
                                            <button
                                                type="button"
                                                onClick={() => setOpen(false)}
                                                className="relative -m-2 p-2 text-gray-400 hover:text-gray-500"
                                            >
                                                <span className="absolute -inset-0.5" />
                                                <span className="sr-only">Close panel</span>
                                                <XMarkIcon aria-hidden="true" className="size-6" />
                                            </button>
                                        </div>
                                    </div>

                                    <div className="mt-8">
                                        <div className="flow-root">
                                            <ul role="list" className="-my-6 divide-y divide-gray-200">
                                                {cart.items.map((product) => (
                                                    <li key={product.product_id} className="flex py-6">
                                                        <div className="size-24 shrink-0 overflow-hidden rounded-md border border-gray-200">
                                                            <img alt={product.title} src={product.image} className="size-full object-cover" />
                                                        </div>

                                                        <div className="ml-4 flex flex-1 flex-col">
                                                            <div>
                                                                <div className="flex justify-between text-base font-medium text-gray-900">
                                                                    <h3>
                                                                        <a href={product.title}>{product.title}</a>
                                                                    </h3>
                                                                    <p className="ml-4">{formatCurrency(product.quantity * product.price)}</p>
                                                                </div>
                                                                {/*<p className="mt-1 text-sm text-gray-500">{product.color}</p>*/}
                                                            </div>
                                                            <div
                                                                className="flex flex-1 items-end justify-between text-sm">

                                                                <div className="flex items-center">
                                                                    <button
                                                                        disabled={product.quantity === 1}
                                                                        onClick={() => handleCartItemQtyChange(product.product_id, REDUCE_CART_ITEM_QTY)}
                                                                        type="button" id="decrement-button"
                                                                        data-input-counter-decrement="counter-input"
                                                                        className="inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-md border border-gray-300 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-100">
                                                                        <svg
                                                                            className="h-2.5 w-2.5 text-gray-900 "
                                                                            aria-hidden="true"
                                                                            xmlns="http://www.w3.org/2000/svg"
                                                                            fill="none" viewBox="0 0 18 2">
                                                                            <path stroke="currentColor"
                                                                                  strokeLinecap="round"
                                                                                  strokeLinejoin="round" strokeWidth={2}
                                                                                  d="M1 1h16"/>
                                                                        </svg>
                                                                    </button>
                                                                    <span className="w-10 shrink-0 border-0 bg-transparent text-center text-sm font-medium text-gray-900 focus:outline-none focus:ring-0">
                                                                        {product.quantity}
                                                                    </span>
                                                                    <button
                                                                        onClick={() => handleCartItemQtyChange(product.product_id, INCREASE_CART_ITEM_QTY)}
                                                                        type="button" id="increment-button"
                                                                            data-input-counter-increment="counter-input"
                                                                            className="inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-md border border-gray-300 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-100">
                                                                        <svg
                                                                            className="h-2.5 w-2.5 text-gray-900 "
                                                                            aria-hidden="true"
                                                                            xmlns="http://www.w3.org/2000/svg"
                                                                            fill="none" viewBox="0 0 18 18">
                                                                            <path stroke="currentColor"
                                                                                  strokeLinecap="round"
                                                                                  strokeLinejoin="round" strokeWidth={2}
                                                                                  d="M9 1v16M1 9h16"/>
                                                                        </svg>
                                                                    </button>
                                                                </div>


                                                                <div className="flex">
                                                                    <button
                                                                        onClick={() => handleRemoveCartItem(product.product_id)}
                                                                        type="button"
                                                                        className="font-medium text-indigo-600 hover:text-indigo-500">
                                                                        Remove
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                ))}
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div className="border-t border-gray-200 px-4 py-6 sm:px-6">
                                    <div className="flex justify-between text-base font-medium text-gray-900">
                                        <p>Subtotal</p>
                                        <p>{formatCurrency(cartTotal)}</p>
                                    </div>
                                    <p className="mt-0.5 text-sm text-gray-500">Shipping and taxes calculated at
                                        checkout.</p>
                                    <div className="mt-6">
                                        <button onClick={handleClearCart}
                                                className="flex items-center w-full justify-center rounded-md border border-transparent bg-red-400 px-6 py-3 text-base font-medium text-white shadow-sm hover:bg-red-700">
                                            Clear Cart
                                        </button>
                                    </div>
                                    <div className="mt-6">
                                        <button onClick={handleCheckoutClick}
                                                className="flex items-center w-full justify-center rounded-md border border-transparent bg-indigo-600 px-6 py-3 text-base font-medium text-white shadow-sm hover:bg-indigo-700">
                                        Checkout
                                        </button>
                                    </div>
                                    <div className="mt-6 flex justify-center text-center text-sm text-gray-500">
                                        <p>
                                            or{' '}
                                            <button
                                                type="button"
                                                onClick={() => setOpen(false)}
                                                className="font-medium text-indigo-600 hover:text-indigo-500"
                                            >
                                                Continue Shopping
                                                <span aria-hidden="true"> &rarr;</span>
                                            </button>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </DialogPanel>
                    </div>
                </div>
            </div>
        </Dialog>
    )
}
