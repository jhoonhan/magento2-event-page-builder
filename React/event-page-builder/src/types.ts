import React from "react";

export interface IEvent {
    id: number,
    created_at: Date,
    description: string,
    event_start_date: Date,
    is_published: boolean,
    name: string,
    timezone: string,
    schedules: ISchedules,
    updated_at: Date,
    url: string,
}

export interface ISchedules {
    [key: string]: ISchedule[],
}

export interface ISchedule {
    schedule_id: number,
    event_id: number,
    name: string,
    description: string,
    is_published: boolean,
    schedule_date: string,
    starts_at: string | null,
    ends_at: string | null,
    speakers: ISpeaker[],
}

export interface IState {
    fetched: boolean,
    event: IEvent,
    activeSchedule: number,
    modal: {
        type: string | null,
        data: ISpeaker | null,
        show: boolean
    },
    showSpeakerModal: boolean,
}

export interface ISpeaker {
    id: number,
    event_id: number,
    firstname: string,
    lastname: string,
    title: string,
    company: string,
    description: string,
    image: any,
}

export interface IStateContext {
    state: IState;
    setState: React.Dispatch<React.SetStateAction<IState>>;
}
