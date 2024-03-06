// @flow
import React, {ReactElement, useState, useEffect, useContext} from "react";
import ScheduleDetails from "Components/ScheduleDetails";
import {getSpeakerNameString} from "Components/getSpeakerNameString";
import {StateContext} from "Components/State/useStateContext";
import {ISchedule} from "types";
import useCurrentTimeSchedule from "Components/Animation/useCurrentTimeSchedule";


export interface Iprops {
    [key: string]: any
}

export default function Schedules(): ReactElement {
    const {state, setState} = useContext(StateContext);

    const schedules: Iprops = state.event.schedules;

    const currentSchedule: number[] = useCurrentTimeSchedule(state.event.schedules, state.event.timezone);

    const [activeSchedule, setActiveSchedule] =
        useState([-1]);

    useEffect(() => {
        setActiveSchedule([-1, ...currentSchedule]);
    }, []);


    const getDropdownHeight = (scheduleId: number): number => {
        if (!activeSchedule.includes(scheduleId)) {
            return 0;
        }
        const element = document.getElementById(`schedule__${scheduleId}`);
        if (element) {
            return element.scrollHeight;
        } else {
            return 0;
        }
    }

    const handleScheduleClick = (e: React.MouseEvent, schedule: Iprops): void => {
        if (!activeSchedule.includes(schedule.schedule_id)) {
            setActiveSchedule([...activeSchedule, schedule.schedule_id])
        } else {
            // When user click the same schedule, close the details
            setActiveSchedule(
                activeSchedule.filter(
                    (id: number) =>
                        id !== schedule.schedule_id
                )
            )
        }
    }

    const getSchedulesByDate = (date: string): Iprops => {
        return schedules[date];
    }

    const renderCurrentScheduleIndicator = (schedule_id: number): ReactElement => {
        return (
            <div className={"schedule__tab__indicator flex--v"}>
                <span
                    className={
                        currentSchedule.includes(schedule_id) ? "active" : ""
                    }
                />
            </div>
        )
    }

    const getBackgroundColor = (schedule: ISchedule): string => {
        const hasInfo = schedule.speakers.length > 0 || schedule.description.length > 0
        if (activeSchedule.includes(schedule.schedule_id) && hasInfo) {
            return "var(--c--white--dark)"
        } else {
            return "var(--c--white)"
        }
    }

    const getScheduleElementsByDate = (date: string): ReactElement => {
        return getSchedulesByDate(date).map((schedule: ISchedule, i: number): ReactElement => {
            const contentSection: ReactElement = (
                <div className={"schedule__tab animation__opacity-in"}
                     data-animation-delay={0}
                     data-animation-sequence={i}
                     onClick={(e) => handleScheduleClick(e, schedule)}>

                    {renderCurrentScheduleIndicator(schedule.schedule_id)}
                    <div className={"schedule__tab__time flex--v"}>
                        <p>{schedule.starts_at}</p>
                        <p>{schedule.ends_at}</p>
                    </div>
                    <div className={"schedule__tab__info flex--v"}>
                        <div className={"schedule__tab__info__name"}>
                            <h6>{schedule.name}</h6>
                        </div>
                        <div className="schedule__tab__info__speakers flex--h">
                            {getSpeakerNameString(schedule)}
                        </div>
                    </div>
                </div>
            );
            return (
                <div
                    key={schedule.schedule_id}
                    className="schedule"
                    style={{backgroundColor: getBackgroundColor(schedule)}}
                >
                    {contentSection}
                    <ScheduleDetails
                        id={`schedule__${schedule.schedule_id}`}
                        schedule={schedule}
                        isActive={activeSchedule.includes(schedule.schedule_id)}
                        dropdownHeight={getDropdownHeight(schedule.schedule_id)}
                    />
                </div>
            )
        });
    };

    const getDateString = (date: string): string => {
        const dateObj: Date = new Date(date);
        const formattedDate: Intl.DateTimeFormat = new Intl.DateTimeFormat('en-US', {
            weekday: "long",
            month: "long",
            day: '2-digit',
        })
        return formattedDate.format(dateObj);
    }

    const render = (): ReactElement => {
        // Get all the dates of the schedules
        const schedulesDates: string[] = Object.keys(schedules);
        const scheduleElements: ReactElement[] =
            schedulesDates.map((date: string, index: number): ReactElement => {
                const scheduleElementsByDate: ReactElement = getScheduleElementsByDate(date);
                return (
                    <div className="schedules" key={`schedule-${index}`}>
                        <h3 className={"schedules__date"}>{getDateString(date)}</h3>
                        {scheduleElementsByDate}
                    </div>
                );
            });

        return (
            <div className="schedules">
                {scheduleElements}
            </div>
        )
    }
    return render();
};
