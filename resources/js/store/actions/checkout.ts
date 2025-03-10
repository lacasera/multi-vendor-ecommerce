import useAppContext from "../../hooks/useAppContext";
import {useNavigate} from "react-router-dom";
import {useMutation, useQuery} from "@tanstack/react-query";
import {createCheckout, getOrderHistory, verifyCheckout} from "../requests/checkout";
import {CheckoutType, CLEAR_CART} from "../../types";
import {notify} from "../../helpers";

export const useCreateCheckout = () => {
    const { appDispatch } = useAppContext();
    const navigate = useNavigate();

    const { mutate, isPending } = useMutation({
        mutationFn: createCheckout,
        onSuccess: async (response) => {
            const json =  response.data.data
            window.location.replace(json.payment_url)
        },

        onError: (error) => {
            notify("Unable to create checkout", "error")
        }
    })

    return {isCheckoutPending: isPending, createCheckoutCall: mutate}
}

export const useVerifyCheckout = () => {
    const { appDispatch } = useAppContext();
    const navigate = useNavigate();

    const { mutate, isPending, isSuccess, isError } = useMutation({
        mutationFn: verifyCheckout,
        onSuccess: async (response) => {
            appDispatch({type: CLEAR_CART})
        },
        onError: (error) => {
            notify("unable to verify checkout", "error")
        }
    })

    return {
        isVerifyingCheckout: isPending,
        verifyCheckoutCall: mutate,
        isSuccess,
        isError
    }
}

export const useGetOrderHistory = () => {

    const {data, isPending} = useQuery({
        queryKey: ['orderHistory'],
        queryFn: getOrderHistory
    })

    return {orders: data?.data?.data as CheckoutType[] , isFetchingOrders: isPending}
}
