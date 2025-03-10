import React from "react";
import {Link} from "react-router-dom";

export default function OrderConfirmed({order_code}: string) {
    return (
        <div>
            <section className="bg-white py-8 antialiased dark:bg-gray-900 md:py-16">
                <div className="mx-auto max-w-2xl px-4 2xl:px-0">
                    <h2 className="text-xl font-semibold text-red-600 dark:text-red-400 sm:text-2xl mb-2">
                        Oops! Your order failed.
                    </h2>
                    <p className="text-gray-500 dark:text-gray-400 mb-6 md:mb-8">
                        Unfortunately, your order
                        <span
                            className="font-medium text-gray-900 dark:text-white hover:underline"> #{order_code} </span>
                        could not be processed. Please check your payment details and try again.
                        If the issue persists, contact our support team for assistance.
                    </p>
                    <div className="flex items-center space-x-4">
                        <Link to="/checkout"
                              className="py-2.5 px-5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-4 focus:ring-red-300 dark:focus:ring-red-800">
                            Try Again
                        </Link>
                    </div>
                </div>
            </section>
        </div>
    )
}
