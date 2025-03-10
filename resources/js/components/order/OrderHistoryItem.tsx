import React from "react";
import {CheckoutType} from "../../types";
import {formatCurrency} from "../../helpers";
import OrderStatus from "./OrderStatus";

type Props = {
    checkout: CheckoutType
}
export default function OrderHistoryItem({checkout}: Props) {
    return (
        <div className="mt-6 flow-root sm:mt-8">
            <div className="divide-y divide-gray-200 dark:divide-gray-700">
                <div className="flex flex-wrap items-center gap-y-4 py-6">
                    <dl className="w-1/2 sm:w-1/4 lg:w-auto lg:flex-1 mr-2">
                        <dt className="text-base font-medium text-gray-500 dark:text-gray-400">Order ID:
                        </dt>
                        <dd className="mt-1.5 text-base font-semibold text-gray-900 dark:text-white">
                            <a href="#" className="hover:underline">#{checkout.code} </a>
                        </dd>
                    </dl>
                    <dl className="w-1/2 sm:w-1/4 lg:w-auto lg:flex-1">
                        <dt className="text-base font-medium text-gray-500 dark:text-gray-400">Date:</dt>
                        <dd className="mt-1.5 mr-1 text-base font-semibold text-gray-900 dark:text-white">
                            {checkout.date_created}
                        </dd>
                    </dl>
                    <dl className="w-1/2 sm:w-1/4 lg:w-auto lg:flex-1">
                        <dt className="text-base font-medium text-gray-500 dark:text-gray-400">Price:</dt>
                        <dd className="mt-1.5 text-base font-semibold text-gray-900 dark:text-white">
                            {formatCurrency(checkout.price)}
                        </dd>
                    </dl>
                    <dl className="w-1/2 sm:w-1/4 lg:w-auto lg:flex-1">
                        <dl className="w-1/2 sm:w-1/4 lg:w-auto lg:flex-1">
                            <dt className="text-base font-medium text-gray-500 dark:text-gray-400">Status:</dt>
                            <OrderStatus status={checkout.status} />
                        </dl>
                    </dl>
                    <div
                        className="w-full grid sm:grid-cols-2 lg:flex lg:w-64 lg:items-center lg:justify-end gap-4">
                        <a href="#"
                           className="w-full inline-flex justify-center rounded-lg  border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:outline-none focus:ring-4 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700 lg:w-auto">View
                            details
                        </a>
                    </div>
                </div>
            </div>
        </div>
    )
}
