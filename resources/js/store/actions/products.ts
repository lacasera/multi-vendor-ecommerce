import {useQuery} from "@tanstack/react-query";
import {fetchProductsRequest} from "../requests/products";
import {ProductType} from "../../types";

export const useGetProducts = () => {
    const {data, isPending} = useQuery({
        queryKey: ['products'],
        queryFn: fetchProductsRequest
    })

    return {products: data?.data?.data as ProductType[] , isFetchingProducts: isPending}
}
