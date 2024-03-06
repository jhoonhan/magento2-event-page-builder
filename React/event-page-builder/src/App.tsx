import React, {useState, useEffect, ReactElement, Suspense} from 'react';
import 'App.scss';
import {ROOT_DIV_ID} from 'config.js';

import {getEvent} from 'actions';
import Schedules from "Components/Schedules";
import SpeakerModal from "Components/SpeakerModal";

import {StateContext, useStateContext} from "Components/State/useStateContext";
import {useScrollDisabler} from "./hooks/useScrollDisabler";
import AnimationLibrary from "Components/Animation/AnimationLibrary";
import Spinner from "Components/Spinner";


function App(): ReactElement {

    const DEV_EVENT_ID: number = 2;
    const eventIdValue: number = Number(document.getElementById(ROOT_DIV_ID)!.dataset.eventId);
    const EVENT_ID: number =
        !Number.isNaN(eventIdValue) ? eventIdValue : DEV_EVENT_ID
    ;

    const {state, setState} = useStateContext();
    const [fetchFailed, setFetchFailed] = useState<boolean>(false);


    // When modal is activated, it sets <html> overflow to hidden
    useScrollDisabler(state);

    // This fetches data from Magento db and sets it to state
    useEffect((): void => {
        getEvent(EVENT_ID).then((res) => {
            if (res.success) {
                setState({...state, fetched: true, event: res.data});
            } else {
                setFetchFailed(true);
            }
        });
    }, []);

    // When data is loaded
    useEffect((): void => {
        if (state.fetched) {
            // Load animation library when data is fetched
            AnimationLibrary()
        }
    }, [state.fetched]);

    useEffect((): void => {
        // console.log(state.event);
    }, [state]);


    const conditionalRender = (): ReactElement => {
        if (state.fetched) {
            return (
                <div id="hanstudio-event-page-builder" className="hanstudio-event-page-builder__wrapper">
                    <StateContext.Provider value={{state, setState}}>
                        <Schedules/>
                        {state.modal.show && <SpeakerModal data={state.modal.data}/>}
                    </StateContext.Provider>
                </div>

            );
        } else if (fetchFailed) {
            return <h1>Failed to fetch data. Please check if there is an event with event_id {EVENT_ID}.</h1>;
        } else {
            return <Spinner/>;
        }
    }

    return (
        <>
            {conditionalRender()}
        </>
    );
}

export default App;
