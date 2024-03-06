import {Iprops} from "Components/Schedules";

export const getSpeakerNameString = (schedule: Iprops): string => {
    let speakerNameString: string = '';

    schedule.speakers.forEach((speaker: any, i: number) => {
        // Decides whether to add a comma or not by the ternary operator at the end
        speakerNameString += `${speaker.firstname} ${speaker.lastname}${i !== schedule.speakers.length - 1 ? ", " : ""}`;
    });
    return speakerNameString;
}
