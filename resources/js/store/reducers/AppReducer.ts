import {AppStateType} from "../context/AppContext";
import {
    ActionType,
    ADD_TO_CART, CartType,
    CLEAR_CART, INCREASE_CART_ITEM_QTY, LOGOUT, PERSISTED_STATE_KEY,
    REMOVE_CART_ITEM,
    SET_AUTH_USER,
    UPDATE_CART_ITEM_QUANTITY
} from "../../types";

const AppReducer = (state: AppStateType, action: ActionType) => {
    switch (action.type) {
        case ADD_TO_CART:
            return persistedState({...state, cart: getTransformedCart(state.cart, action.payload)})
        case UPDATE_CART_ITEM_QUANTITY:
            return persistedState({...state, cart: getCartWithTransformedQty(state.cart, action.payload)})
        case REMOVE_CART_ITEM:
            return persistedState({ ...state, cart: {...state.cart, items: state.cart.items.filter((item) => item.product_id !== action.payload.product_id) }});
        case CLEAR_CART:
            return persistedState({...state, cart: {...state.cart, items: []}})
        case SET_AUTH_USER:
            return persistedState({...state, user: {...action.payload}})
        case LOGOUT:
            return persistedState({...state, user: null})
        default: {
            throw new Error(`Unhandled action type in App Reducer: ${action.type}`)
        }
    }
}

const getTransformedCart = (cart: CartType, payload: any): CartType => {
    const item_exists = cart.items.find((item) => item.product_id === payload.product_id);

    if (!item_exists) {
        cart = {...cart, items:[...cart.items, {...payload}]}
    } else {
        cart = {
            ...cart,
            items: cart.items.map((item) =>
                item.product_id === payload.product_id ? {...item, quantity: item.quantity + 1} : item
            ),
        };
    }

    return cart
}

const getCartWithTransformedQty = (cart: CartType, payload): CartType => {
    return  {
        ...cart,
        items : cart.items.map((item)=> item.product_id === payload.product_id ?
            {
                ...item, quantity: payload.operation === INCREASE_CART_ITEM_QTY
                    ? item.quantity + 1 : item.quantity - 1
            } : item
        )
    }
 }

const persistedState = (state: AppStateType): AppStateType => {
    localStorage.setItem(PERSISTED_STATE_KEY, JSON.stringify(state))

    return state
}
export default AppReducer;
