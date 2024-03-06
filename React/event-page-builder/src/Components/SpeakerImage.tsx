// @flow
import React, {useState, useEffect, ReactElement} from "react";
import {DEFAULT_IMAGE_URL} from "../config";

type Props = { speakerData: { [key: string]: any }, alt: string }
const SpeakerImage = ({speakerData, alt}: Props): ReactElement => {
    return (
        <div className={"speaker__image flex--v flex-align--cc"}>
            <img src={speakerData?.url || DEFAULT_IMAGE_URL} alt={alt}/>
        </div>
    );
};

export default SpeakerImage;
