import React, {ChangeEvent, FormEvent, useEffect, useState} from "react";
import {Link} from "react-router-dom";
import useAppContext from "../hooks/useAppContext";
import {formatCurrency} from "../helpers";
import {CheckoutFormDataType} from "../types";
import {useCreateCheckout} from "../store/actions/checkout";

export default function Checkout() {
    const {appState: {cart, user}} = useAppContext()
    const cartTotal = cart.items.reduce((acc, item) => (item.quantity * item.price) + acc ,0)
    const {isCheckoutPending, createCheckoutCall} = useCreateCheckout();

    const [checkoutData, setCheckoutData] = useState<CheckoutFormDataType>({
        name: user?.username ?? '',
        email: user?.email ?? '',
        country:  'US',
        city:  'SF'
    });

    const handle = (field: string) => {
        return (event: ChangeEvent<HTMLInputElement | HTMLSelectElement>) => {
            setCheckoutData((prevState) => ({...prevState, [field]: event.target.value})  )
        }
    }

    const handleSubmit = async (e: FormEvent<HTMLFormElement>) => {
         e.preventDefault();

         if (!user) {
             alert("please login or sign  up to place order")
         } else {
             createCheckoutCall({...checkoutData, items: cart.items})
         }
    }

    return (
        <div>
            <section className="bg-white py-8 antialiased dark:bg-gray-900 md:py-16">
                <form onSubmit={handleSubmit} className="mx-auto max-w-screen-xl px-4 2xl:px-0">
                    <div className="mt-6 sm:mt-8 lg:flex lg:items-start lg:gap-12 xl:gap-16">
                        <div className="min-w-0 flex-1 space-y-8">
                            <div className="space-y-4">
                                <h2 className="text-xl font-semibold text-gray-900 dark:text-white">Delivery Details</h2>
                                <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                    <div>
                                        <label htmlFor="your_name" className="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                                            Your name
                                        </label>
                                        <input
                                            onChange={handle('name')}
                                            value={checkoutData.name} type="text" id="your_name" className="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                                            placeholder="Bonnie Green" required/>
                                    </div>
                                    <div>
                                        <label htmlFor="your_email" className="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                                            Your email*
                                        </label>
                                        <input
                                            value={checkoutData.email}
                                            onChange={handle('email')}
                                            type="email" id="your_email"
                                            className="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                                            placeholder="name@flowbite.com" required/>
                                    </div>
                                    <div>
                                        <div className="mb-2 flex items-center gap-2">
                                            <label htmlFor="select-country-input-3"
                                                   className="block text-sm font-medium text-gray-900 dark:text-white"> Country* </label>
                                        </div>
                                        <select
                                            onChange={handle('country')}
                                            value={checkoutData.country} id="select-country-input-3"
                                            className="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500">
                                            <option value="US">United States</option>
                                            <option value="AS">Australia</option>
                                            <option value="FR">France</option>
                                            <option value="ES">Spain</option>
                                            <option value="UK">United Kingdom</option>
                                        </select>
                                    </div>
                                    <div>
                                        <div className="mb-2 flex items-center gap-2">
                                            <label htmlFor="select-city-input-3"
                                                   className="block text-sm font-medium text-gray-900 dark:text-white"> City* </label>
                                        </div>
                                        <select
                                            onChange={handle('city')}
                                            value={checkoutData.city} id="select-city-input-3"
                                                className="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500">
                                            <option value="SF">San Francisco</option>
                                            <option value="NY">New York</option>
                                            <option value="LA">Los Angeles</option>
                                            <option value="CH">Chicago</option>
                                            <option value="HU">Houston</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div className="mt-6 w-full space-y-6 sm:mt-8 lg:mt-0 lg:max-w-xs xl:max-w-md">
                            <div className="flow-root">
                                <div className="-my-3 divide-y divide-gray-200 dark:divide-gray-800">
                                    <dl className="flex items-center justify-between gap-4 py-3">
                                        <dt className="text-base font-normal text-gray-500 dark:text-gray-400">Total</dt>
                                        <dd className="text-base font-medium text-gray-900 dark:text-white">
                                            {formatCurrency(cartTotal)}
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                            <div className="space-y-3">
                                <button disabled={isCheckoutPending} type="submit" className="flex w-full items-center justify-center rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-4  focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                                    {isCheckoutPending ? 'Please wait..' : 'Proceed to Payment' }
                                </button>
                                <p className="text-sm font-normal text-gray-500 dark:text-gray-400">One or more items in
                                    your cart require an account.
                                    <Link to="/login" className="font-medium text-primary-700 underline hover:no-underline dark:text-primary-500">
                                        Sign in or create an account now.
                                    </Link>.
                                </p>
                            </div>
                        </div>
                    </div>
                </form>
            </section>

        </div>
    )
}
