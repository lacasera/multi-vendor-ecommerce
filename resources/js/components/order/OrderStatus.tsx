import React from "react";
import OrderPaidStatus from "./OrderPaidStatus";
import OrderFailedStatus from "./OrderFailedStatus";
import OrderPendingStatus from "./OrderPendingStatus";
import {OrderStatusType} from "../../types";

type Props = {
    status: OrderStatusType
}
export default function OrderStatus({status} : Props) {

    const getStatusComponent = () => {
        switch (status) {
            case "paid":
                return <OrderPaidStatus />
            case "failed":
                return <OrderFailedStatus />
            default:
                return <OrderPendingStatus />
        }
    }

    return getStatusComponent()
}
