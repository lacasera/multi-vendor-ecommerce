import React from "react";
import {Link} from "react-router-dom";

export default function OrderConfirmed({order_code}: string) {
    return (
        <div>
            <section className="bg-white py-8 antialiased dark:bg-gray-900 md:py-16">
                <div className="mx-auto max-w-2xl px-4 2xl:px-0">
                    <h2 className="text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl mb-2">Thanks for your
                        order!</h2>
                    <p className="text-gray-500 dark:text-gray-400 mb-6 md:mb-8">
                        Your order <span className="font-medium text-gray-900 dark:text-white hover:underline"> #{order_code} </span>
                         will be processed within 24 hours during working days. We will notify you by email once your order
                        has been shipped.
                    </p>
                    <div className="flex items-center space-x-4">
                        <Link to="/"
                           className="py-2.5 px-5 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                            Return to shopping
                        </Link>
                    </div>
                </div>
            </section>
        </div>
    )
}
