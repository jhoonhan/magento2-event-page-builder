// @flow
import {IEvent, ISchedule, ISchedules, IState} from "types";
import moment from 'moment-timezone';
import {useEffect} from "react";

interface IDateTimeObject {
    date: string;
    time: string;
    oneLine: string;
    momentFormat: moment.Moment;
}


// Converts date object to the event's timezone
const convertToEventTimezone = (date: Date | string, timezone: string, time?: string): IDateTimeObject => {
    let momentDate: moment.Moment;

    if (time) {
        const dateTimeString = `${date} ${time}:00`;
        momentDate = moment.tz(dateTimeString, timezone);
    } else {
        momentDate = moment(date);
        momentDate.tz(timezone);
    }

    return {
        date: momentDate.format('YYYY-MM-DD'),
        time: momentDate.format('HH:mm'),
        oneLine: momentDate.format('YYYY-MM-DD HH:mm:ss'),
        momentFormat: momentDate
    };
}


const getScheduleDate = (date: string): string => {
    return date.split(" ")[0];
}

const useCurrentTimeSchedule = (schedules: ISchedules, timezone: string): number[] => {
    const currTime: IDateTimeObject = convertToEventTimezone(new Date(), timezone);

    const getCurrentSchedules = (): number[] => {
        let res: number[] = [];
        if (schedules[currTime.date]) {
            const todaysSchedules: ISchedule[] = schedules[currTime.date];
            todaysSchedules.forEach((schedule: ISchedule, i: number): void => {
                if (schedule.starts_at && schedule.ends_at) {
                    const scheduleDate = getScheduleDate(schedule.schedule_date);
                    const startsAt: IDateTimeObject = convertToEventTimezone(scheduleDate, timezone, schedule.starts_at);
                    const endsAt: IDateTimeObject = convertToEventTimezone(scheduleDate, timezone, schedule.ends_at);

                    if (currTime.momentFormat.isBetween(startsAt.momentFormat, endsAt.momentFormat)) {
                        res.push(schedule.schedule_id);
                    }
                }
            })
        }
        return res;
    }

    return getCurrentSchedules();
};

export default useCurrentTimeSchedule;

