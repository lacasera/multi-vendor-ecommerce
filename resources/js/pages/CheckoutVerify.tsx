import React, {useEffect} from "react";
import {useParams} from "react-router-dom";
import OrderConfirmed from "../components/order/OrderConfirmed";
import OrderFailed from "../components/order/OrderFailed";
import {useVerifyCheckout} from "../store/actions/checkout";

export default function CheckoutVerify() {
    const params =  useParams()
    const checkoutCode = params.checkout_code;

    const {verifyCheckoutCall, isVerifyingCheckout, isError,isSuccess} = useVerifyCheckout()

    useEffect(() => {
        verifyCheckoutCall({
            checkout_code: checkoutCode
        })
    }, [checkoutCode])

    return (
        <div>
            {isVerifyingCheckout
                ? <p>loading</p>
                : <div>
                    {isSuccess ? <OrderConfirmed order_code={checkoutCode} /> : <OrderFailed  order_code={checkoutCode} />}
                </div> }

        </div>
    )
}
