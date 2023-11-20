import React, { useEffect, useState } from "react";
import { useSelector } from "react-redux";

import Modal from "../Modal";
import {
    general,
    errorModal as strings,
} from "../../../../constants/strings/fa";
import InputTextAreaColumn from "../../Input/InputTextAreaColumn";
import utils from "../../../../utils/Utils";

function ErrorModal() {
    const layoutState = useSelector((state) => state.layoutReducer);
    const [title, setTitle] = useState(strings._title);

    useEffect(() => {
        if (layoutState?.shownModal?.props?.error) {
            const { date, time } = utils.getTimezoneDate(
                layoutState?.shownModal?.props?.error.createdAt,
                general.locale
            );
            setTitle(`${strings._title} - ${date}, ${time}`);
        }
    }, [layoutState?.shownModal?.props?.error]);

    return (
        <Modal id="errorModal" title={title} fullWidth={true}>
            <InputTextAreaColumn
                field="messageErrorModal"
                readOnly={true}
                strings={strings}
                showLabel
                value={layoutState?.shownModal?.props?.error?.message}
                containerStyle={{ minHeight: "60vh" }}
                inputStyle={{
                    textAlign: "left",
                    direction: "ltr",
                    minHeight: "60vh",
                }}
            />
        </Modal>
    );
}

export default ErrorModal;
