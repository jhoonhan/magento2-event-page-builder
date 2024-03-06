import React, {ReactElement} from "react";

const Spinner = (): ReactElement => {
    return (
        <section className="section__spinner flex--v flex-align--cc">
            <div className="lds-roller">
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
            </div>
        </section>
    );
};

export default Spinner;
