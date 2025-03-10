import {axios} from ".";

export const fetchProductsRequest = () => {
    return axios.get('/api/products')
}
