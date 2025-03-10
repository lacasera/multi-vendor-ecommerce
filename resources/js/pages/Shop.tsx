import React from "react";
import ProductCard from "../components/product/ProductCard";
import {useGetProducts} from "../store/actions/products";
import {ProductType} from "../types";

export default function Shop() {
    const {products, isFetchingProducts, error} = useGetProducts()
    return (
        <div className="flex flex-wrap justify-start">
            {isFetchingProducts ? <p>loading....</p> :
                products?.map((product: ProductType) => (
                    <div key={product.id} className="px-4 py-2 m-2">
                        <ProductCard product={product} />
                    </div>
                ))
            }
        </div>
    );
}
