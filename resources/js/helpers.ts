import {NotificationType} from "./types";
import {toast} from "react-toastify";

export const formatCurrency = (amount: number, currency = 'USD', locale = 'en-US') => {
    return new Intl.NumberFormat(locale, {
        style: 'currency',
        currency,
    }).format(amount)
}

export const notify = (message: string, type: NotificationType) =>  {
   switch (type) {
       case "success":
           toast.success(message)
           break
       case "error":
           toast.error(message)
           break
       default:
           toast.info(message)
   }
}
