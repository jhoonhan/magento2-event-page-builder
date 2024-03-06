import React, {useState, useEffect} from "react";
import {IEvent, IState, ISpeaker, IStateContext} from "../../types";


const eventInitialState: IEvent = {
    id: -1,
    name: '',
    event_start_date: new Date(),
    timezone: "America/New_York",
    description: '',
    is_published: false,
    created_at: new Date(),
    updated_at: new Date(),
    url: '',
    schedules: {},
}

const speakerInitialState: ISpeaker = {
    id: -1,
    event_id: -1,
    firstname: '',
    lastname: '',
    title: '',
    company: '',
    description: '',
    image: null,
}

const initialState: IState = {
    fetched: false,
    event: eventInitialState,
    activeSchedule: 1,
    showSpeakerModal: false,
    modal: {type: null, data: null, show: false},
};
export const StateContext = React.createContext<IStateContext>({
    state: initialState,
    setState: () => {
    },
});

export const useStateContext = (): IStateContext => {
    const [state, setState] = useState<IState>(initialState);

    useEffect(() => {
    }, [state]);

    return {state, setState};
};
