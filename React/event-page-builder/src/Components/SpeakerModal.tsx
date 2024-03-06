// @flow
import React, {useState, useEffect, ReactElement, useContext} from "react";
import {ISpeaker} from "types";
import {StateContext} from "Components/State/useStateContext";
import SpeakerImage from "Components/SpeakerImage";

interface ISpeakerModal {
    data: ISpeaker | null;
}

const SpeakerModal = ({data}: ISpeakerModal): ReactElement => {
    const {state, setState} = useContext(StateContext);

    useEffect(() => {
        // console.log(data);
    }, []);


    const handleCloseButton = (): void => {
        setState({...state, modal: {type: null, data: null, show: false}});
    }
    const getFormattedTitle = (data: ISpeaker | null): string => {
        if (data!.title && data!.company) {
            return `${data!.title}, ${data!.company}`;
        } else if (data!.title) {
            return data!.title;
        } else if (data!.company) {
            return data!.company;
        }
        return '';
    }


    const render = (): ReactElement => {
        return (
            <div className={"modal-wrapper speaker-modal flex--v flex-gap--xl"}>
                <button onClick={handleCloseButton} className={"btn__close"}/>
                <SpeakerImage speakerData={JSON.parse(data!.image)} alt={`${data!.firstname} ${data!.lastname}`}/>
                <div className={"flex--v"}>
                    <h2 className={"speaker-modal__name"}>{data!.firstname} {data!.lastname}</h2>
                    <h4 className={"speaker-modal__title"}>{getFormattedTitle(data)}</h4>
                </div>
                <p className={"speaker-modal__description"}>{data!.description}</p>
            </div>
        );
    }
    return render();
};

export default SpeakerModal;
