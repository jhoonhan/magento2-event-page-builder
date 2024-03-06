// @flow
import React, {useState, useEffect, ReactElement, useContext} from "react";
import SpeakerTabs from "./SpeakerTabs";
import {Iprops} from "./Schedules";


export default function ScheduleDetails({
                                            id,
                                            schedule,
                                            isActive,
                                            dropdownHeight,
                                        }: Iprops): ReactElement {

    useEffect(() => {

        // console.log(dropdownHeight);

    }, [dropdownHeight]);

    const getSpeakerSection = (): ReactElement => {
        return (
            <div className={
                "schedule__details__speakers flex--v"
            }>
                <p className={"schedule__details__speakers__label"}><strong>Speakers:</strong></p>
                <div className={"schedule__details__speakers__tabs"}>
                    <SpeakerTabs
                        schedule={schedule}
                        showTitle={true}
                        showImage={true}
                    />
                </div>
            </div>
        )
    }
    const descriptionSection: ReactElement = (
        <div className={"schedule__details__description flex--v"}>
            <p>{schedule.description}</p>
        </div>
    )

    if (schedule.speakers[0] || schedule.description) {
        const maxHeight = isActive && dropdownHeight ? `${dropdownHeight}px` : "0px";
        return (
            <div
                id={id}
                className={`
                    schedule__details
                    dropdown__target
                    flex--v
                `}
                style={
                    {
                        maxHeight
                    }
                }
            >
                {schedule.description ? descriptionSection : <></>}
                {schedule.speakers[0] ? getSpeakerSection() : <></>}
            </div>
        )
    } else {
        return <></>
    }
};
