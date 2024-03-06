// @flow
import React, {useState, useEffect, ReactElement, useContext} from "react";
import {StateContext} from "Components/State/useStateContext";
import SpeakerImage from "./SpeakerImage";
import {IMAGE_URL, DEFAULT_IMAGE_URL} from "config";

export default function SpeakerTabs({schedule, showTitle, showImage}: any): ReactElement {
    const {state, setState} = useContext(StateContext);

    const handleSpeakerClick = (speaker: any): void => {
        // This triggers modal to open
        setState({...state, modal: {type: 'speaker', data: speaker, show: true}});
    }

    const speakerElements: ReactElement[] = schedule.speakers.map((speaker: any, i: number): ReactElement => {
        const speakerName: string = `${speaker.firstname} ${speaker.lastname}`;
        const speakerImageData: { [key: string]: any } = JSON.parse(speaker.image);

        const speakerNameOnly: ReactElement = (
            <p className={"speaker__name"}>{speakerName}</p>
        )
        const speakerWithTitle: ReactElement = (
            <div className={"flex--v"}>
                <p className={"speaker__name"}>{speakerName}</p>
                <p className={"speaker__title"}>
                    {speaker.title}{speaker.company && ','} {speaker.company}
                </p>
            </div>
        )
        return (
            <div
                onClick={() => handleSpeakerClick(speaker)}
                key={speaker.speaker_id}
                className={
                    `schedule__speaker ${showImage ? "grid--col " : "flex--h "}` +
                    "animation__opacity-in"
                }
                data-animation-sequence={i}
                data-animation-delay={0}
            >

                {showImage && <SpeakerImage speakerData={speakerImageData} alt={speakerName}/>}
                {showTitle ? speakerWithTitle : speakerNameOnly}
            </div>
        )
    });

    return (<>{speakerElements}</>)
};
