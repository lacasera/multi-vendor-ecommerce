import { useContext } from "react";
import { AppContext } from "../store/context/AppContext";

const useAppContext = () => {
    const context = useContext(AppContext)

    if (context === undefined) {
        throw new Error('useAppContext must be used within an AppContextProvider')
    }

    return {appState: context.state, appDispatch: context.dispatch}
}

export default useAppContext;
