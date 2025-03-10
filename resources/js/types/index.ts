import {AppStateType} from "../store/context/AppContext";

export type ActionType = {
    type: string
    payload?: any
}

export type DispatchType = (action: ActionType) => void

export type AppContextType = {
    state: AppStateType,
    dispatch: DispatchType
}

export type LoginPayloadType = {
    email: string;
    password: string
}

export type RegisterPayloadType = {
    name: string;
    email: string;
    password: string;
}

export type CheckoutFormDataType = {
    name: string;
    email: string;
    country: string;
    city: string;
}

//APP ENUMS
export type OrderStatusType = 'paid' | 'failed' | 'pending';
export type NotificationType = 'success' | 'error' | 'info';

export type OrderItemType = {
    id: number;
    product: string
}

export type OrderType = {
    id: number;
    items: OrderItemType[]
}

export type CheckoutType = {
    code: string;
    date_created: string;
    status: OrderStatusType;
    price: number;
    orders: OrderType[]
}

export type AttributeType = {
    id: number;
    name: string;
    value: string;
    slug: string
}

export type CartItemType = {
    product_id: number;
    quantity: number;
    price: number;
    title: string;
    image: string;
    slug: string;
}

export type UserType = {
    id: number,
    username: string,
    email: string
    token: string
}

export type CartType = {
    items: CartItemType[] | null
}

export type CategoryType = {
    id: number;
    name: string;
}

export type ProductImageType = {
    url: string;
}

export type ProductType = {
    id: number;
    title: string;
    slug: string;
    price: number;
    description: string;
    images?: ProductImageType[];
    categories?: CategoryType[];
    attributes?: AttributeType[];
}

// APP CONSTANTS
export const ADD_TO_CART = 'ADD_TO_CART';
export const UPDATE_CART_ITEM_QUANTITY = 'UPDATE_CART';
export const REMOVE_CART_ITEM = 'REMOVE_FROM_CART';
export const CLEAR_CART = 'CLEAR_CART';
export const SET_AUTH_USER = 'SET_AUTH_USER';
export const LOGOUT = 'LOGOUT';

export const REDUCE_CART_ITEM_QTY = '-';
export const INCREASE_CART_ITEM_QTY = '+';

export const PERSISTED_STATE_KEY = 'WORK_WIZE_DATA'
