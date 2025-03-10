import React from "react";
import useAppContext from "../hooks/useAppContext";
import {useGetOrderHistory} from "../store/actions/checkout";
import OrderHistoryItem from "../components/order/OrderHistoryItem";
import {CheckoutType} from "../types";

export default function MyAccount() {
    const {appState: {user}} = useAppContext()
    const {orders, isFetchingOrders} = useGetOrderHistory()

    return (
        <section className="bg-white py-8 antialiased dark:bg-gray-900 md:py-16">
            <div className="mx-auto max-w-screen-xl px-4 2xl:px-0">
                <div className="mx-auto max-w-5xl">
                    <div className="gap-4 sm:flex sm:items-center sm:justify-between">
                        <h2 className="text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl">My orders</h2>
                    </div>
                    {orders && orders.map((order: CheckoutType) => (
                        <OrderHistoryItem key={order.code} checkout={order} />
                        )
                    )}
                </div>
            </div>
        </section>
    );
}
