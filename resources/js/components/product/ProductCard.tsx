import React from "react";
import { Card } from "flowbite-react";
import { ProductType } from "../../types";
import useAppContext from "../../hooks/useAppContext";
import {ADD_TO_CART} from "../../types";
import {formatCurrency} from "../../helpers";

type Props = {
    product: ProductType
}
export default function ProductCard ({product}: Props) {

    const { appDispatch } = useAppContext()

    const addToCart = (product: ProductType) => {
        appDispatch({
            type: ADD_TO_CART,
            payload: {
                product_id: product.id,
                price: product.price,
                title: product.title,
                image: product.images?.length ? product.images[0].url : null,
                quantity: 1,
                slug: product.slug,
            }
        })
    }

    return (
        <Card className="max-w-sm" imgAlt={product.title} imgSrc={product.images ?  product.images[0].url : ''}>
            <a href="#">
                <h5 className="text-xl font-semibold tracking-tight text-gray-900 dark:text-white">
                    {product.title}
                </h5>
            </a>

            <div className="flex items-center justify-between">
                <span className="text-3xl font-bold text-gray-900 dark:text-white">{formatCurrency(product.price)}</span>
                <button
                    onClick={() => addToCart({...product})}
                    className="rounded-lg bg-cyan-700 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-cyan-800 focus:outline-none focus:ring-4 focus:ring-cyan-300 dark:bg-cyan-600 dark:hover:bg-cyan-700 dark:focus:ring-cyan-800"
                >
                    Add to cart
                </button>
            </div>
        </Card>
    )
}
