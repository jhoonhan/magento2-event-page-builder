import {API_URL} from "config.js";
import getTimezones from "../timzones";


const convertStartEndTime = (time: string): string | null => {
    if (!time) return null;
    const splitted: string[] = time.split(":");
    return `${splitted[0]}:${splitted[1]}`;
}
export const getEvent = async (id: number): Promise<any> => {
    try {
        const response: any = await fetch(`${API_URL}${id}`);
        const data = await response.json();
        const jsonData = JSON.parse(data);
        jsonData.id = Number(id);
        jsonData.is_published = jsonData.is_published === "1";
        jsonData.timezone = getTimezones(+jsonData.timezone);
        jsonData.description =
            jsonData.description === null ? "" : jsonData.description;
        jsonData.created_at = new Date(jsonData.created_at);
        jsonData.updated_at = new Date(jsonData.updated_at);
        jsonData.event_start_date = new Date(jsonData.event_start_date);
        const scheduleDates: string[] = Object.keys(jsonData.schedules);
        scheduleDates.forEach((date: string) => {
            jsonData.schedules[date].forEach((schedule: any) => {
                schedule.schedule_id = Number(schedule.schedule_id);
                schedule.event_id = Number(schedule.event_id);
                schedule.starts_at = convertStartEndTime(schedule.starts_at);
                schedule.ends_at = convertStartEndTime(schedule.ends_at);
                schedule.is_published = schedule.is_published === "1";
                schedule.speakers.forEach((speaker: any) => {
                    speaker.speaker_id = Number(speaker.speaker_id);
                    speaker.event_id = Number(speaker.event_id);
                });
            });
        });

        if (!response.ok) {
            throw new Error(response.statusText);
        }
        return {success: true, data: jsonData};

        // return await response.json();
    } catch (error: any) {
        // console.error("Error fetching data:", error.message);
        // throw error;
        return {success: false, data: []};
    }
};
