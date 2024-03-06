import React, {useEffect} from "react";
import {IState} from "types";

export type TuseScrollDisabler = { state: IState };

// when modal is activated, it sets <html> overflow to hidden
export const useScrollDisabler = (state: IState): void => {
    useEffect(() => {
        if (state.modal.show) {
            document.documentElement.style.overflow = "hidden";
        } else {
            document.documentElement.style.overflow = "auto";
        }
    }, [state.modal.show]);
};
