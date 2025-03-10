import { axios } from ".";

export const createCheckout = (data: any) => {
    return axios.post('/api/checkouts', data)
}

export const verifyCheckout = (data: any) => {
    return axios.post('/api/checkouts/verify', data)
}

export const getOrderHistory = (data: any) => {
    return axios.get('/api/checkouts')
}
